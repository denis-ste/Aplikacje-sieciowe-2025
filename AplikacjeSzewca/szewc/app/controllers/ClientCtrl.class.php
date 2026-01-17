<?php

namespace app\controllers;

use core\App;
use core\Message;
use core\ParamUtils;
use core\OrderStatus;

class ClientCtrl extends BaseCtrl {

    public function action_client_dashboard(): void {
        $this->prepareLayout();

        $services = App::getDB()->select('services', ['id', 'name', 'price'], ['is_active' => 'Y', 'ORDER' => ['name' => 'ASC']]);
        $products = App::getDB()->select('products', ['id', 'name', 'price', 'stock_qty'], ['is_active' => 'Y', 'ORDER' => ['name' => 'ASC']]);

        App::getSmarty()->assign('services', $services);
        App::getSmarty()->assign('products', $products);
        App::getSmarty()->assign('pageTitle', 'Panel klienta');
        App::getSmarty()->display('ClientDashboard.tpl');
    }

    public function action_order_new(): void {
        $this->prepareLayout();

        $services = App::getDB()->select('services', ['id', 'name', 'price'], ['is_active' => 'Y', 'ORDER' => ['name' => 'ASC']]);
        $serviceId = ParamUtils::getFromRequest('service_id');

        App::getSmarty()->assign('services', $services);
        App::getSmarty()->assign('serviceId', $serviceId);
        App::getSmarty()->assign('pageTitle', 'Nowe zlecenie');
        App::getSmarty()->display('OrderNew.tpl');
    }

    public function action_order_create(): void {
        $userId = (int)($_SESSION['user_id'] ?? 0);

        $serviceId = (int)ParamUtils::getFromRequest('service_id', true);
        $note = trim((string)ParamUtils::getFromRequest('note'));

        // Termin odbioru ustala WORKER (CLIENT nie wybiera terminu).
        $pickupDate = null;

        $service = App::getDB()->get('services', ['id','price','is_active'], ['id' => $serviceId]);
        if (!$service || $service['is_active'] !== 'Y') {
            App::getMessages()->addMessage(new Message('Wybrana usługa jest niedostępna', Message::ERROR));
            App::getRouter()->redirectTo('order_new');
            return;
        }

        if (mb_strlen($note) > 500) {
            App::getMessages()->addMessage(new Message('Opis jest zbyt długi (max 500 znaków)', Message::ERROR));
            App::getRouter()->redirectTo('order_new');
            return;
        }

        App::getDB()->insert('orders', [
            'client_user_id' => $userId,
            'assigned_user_id' => null,
            'order_type' => 'SERVICE',
            'service_id' => $serviceId,
            'pickup_date' => $pickupDate,
            'status_id' => OrderStatus::NEW,
            'total_price' => $service['price'],
            'note' => $note,
        ]);

        App::getMessages()->addMessage(new Message('Zlecenie zostało dodane', Message::INFO));
        App::getRouter()->redirectTo('my_orders');
    }

    /**
     * Minimalny zakup produktu: tworzy zamówienie typu PRODUCT + 1 pozycję w order_items.
     * (bez koszyka, żeby nie rozbudowywać projektu)
     */
    public function action_product_order_create(): void {
        $userId = (int)($_SESSION['user_id'] ?? 0);

        $productId = (int)ParamUtils::getFromRequest('product_id', true);
        $qtyRaw = ParamUtils::getFromRequest('qty', true);
        $qty = (int)$qtyRaw;

        if ($qty < 1) {
            App::getMessages()->addMessage(new Message('Ilość musi być liczbą całkowitą >= 1', Message::ERROR));
            App::getRouter()->redirectTo('client_dashboard');
            return;
        }

        $product = App::getDB()->get('products', ['id','name','price','stock_qty','is_active'], ['id' => $productId]);
        if (!$product || $product['is_active'] !== 'Y') {
            App::getMessages()->addMessage(new Message('Wybrany produkt jest niedostępny', Message::ERROR));
            App::getRouter()->redirectTo('client_dashboard');
            return;
        }

        $stock = (int)$product['stock_qty'];
        if ($stock < $qty) {
            App::getMessages()->addMessage(new Message('Brak wystarczającego stanu magazynowego', Message::ERROR));
            App::getRouter()->redirectTo('client_dashboard');
            return;
        }

        $unitPrice = (float)$product['price'];
        if ($unitPrice < 0) {
            App::getMessages()->addMessage(new Message('Błędna cena produktu', Message::ERROR));
            App::getRouter()->redirectTo('client_dashboard');
            return;
        }

        $lineTotal = round($qty * $unitPrice, 2);

        // transakcja: order + order_items + stock
        $db = App::getDB();
        $db->pdo->beginTransaction();
        try {
            $db->insert('orders', [
                'client_user_id' => $userId,
                'assigned_user_id' => null,
                'order_type' => 'PRODUCT',
                'service_id' => null,
                'pickup_date' => date('Y-m-d'),
                'status_id' => OrderStatus::NEW,
                'total_price' => $lineTotal,
                'note' => 'Zakup produktu: ' . $product['name'],
            ]);

            $orderId = (int)$db->id();

            $db->insert('order_items', [
                'order_id' => $orderId,
                'product_id' => $productId,
                'qty' => $qty,
                'unit_price' => $unitPrice,
                'line_total' => $lineTotal,
            ]);

            // minimalnie aktualizujemy stan
            $db->update('products', [
                'stock_qty' => $stock - $qty
            ], ['id' => $productId]);

            $db->pdo->commit();

            App::getMessages()->addMessage(new Message('Zamówienie produktowe zostało złożone', Message::INFO));
            App::getRouter()->redirectTo('my_orders');
        } catch (\Throwable $e) {
            $db->pdo->rollBack();
            App::getMessages()->addMessage(new Message('Nie udało się złożyć zamówienia produktu', Message::ERROR));
            App::getRouter()->redirectTo('client_dashboard');
        }
    }

    public function action_my_orders(): void {
        $this->prepareLayout();
        $userId = (int)($_SESSION['user_id'] ?? 0);

        $orders = App::getDB()->select(
            'orders',
            [
                '[>]services' => ['service_id' => 'id'],
                '[>]order_statuses' => ['status_id' => 'id'],
                '[>]users(worker)' => ['assigned_user_id' => 'id'],
            ],
            [
                'orders.id',
                'orders.order_type',
                'orders.pickup_date',
                'orders.total_price',
                'orders.note',
                'orders.created_at',
                'services.name(service_name)',
                'order_statuses.name(status_name)',
                'order_statuses.code(status_code)',
                'worker.username(worker_username)',
            ],
            [
                'orders.client_user_id' => $userId,
                'ORDER' => ['orders.created_at' => 'DESC']
            ]
        );

        // dla zamówień PRODUCT dorzucamy skrót pozycji (minimalnie)
        $itemsByOrder = [];
        if (!empty($orders)) {
            foreach ($orders as $o) {
                if (($o['order_type'] ?? 'SERVICE') === 'PRODUCT') {
                    $items = App::getDB()->select(
                        'order_items',
                        ['[>]products' => ['product_id' => 'id']],
                        ['products.name', 'order_items.qty'],
                        ['order_items.order_id' => (int)$o['id']]
                    );
                    $parts = [];
                    foreach ($items as $it) {
                        $parts[] = $it['name'] . ' x' . $it['qty'];
                    }
                    $itemsByOrder[(int)$o['id']] = !empty($parts) ? implode(', ', $parts) : '-';
                }
            }
        }

        App::getSmarty()->assign('itemsByOrder', $itemsByOrder);

        App::getSmarty()->assign('orders', $orders);
        App::getSmarty()->assign('pageTitle', 'Moje zlecenia');
        App::getSmarty()->display('MyOrders.tpl');
    }

    public function action_order_pickup(): void {
        $userId = (int)($_SESSION['user_id'] ?? 0);
        $orderId = (int)ParamUtils::getFromRequest('id', true);

        $order = App::getDB()->get('orders', ['id','status_id'], ['id' => $orderId, 'client_user_id' => $userId]);
        if (!$order) {
            App::getMessages()->addMessage(new Message('Nie znaleziono zlecenia', Message::ERROR));
            App::getRouter()->redirectTo('my_orders');
            return;
        }

        if ((int)$order['status_id'] !== OrderStatus::READY) {
            App::getMessages()->addMessage(new Message('Odbiór możliwy tylko dla statusu GOTOWE', Message::ERROR));
            App::getRouter()->redirectTo('my_orders');
            return;
        }

        App::getDB()->update('orders', ['status_id' => OrderStatus::PICKED_UP], ['id' => $orderId]);
        App::getMessages()->addMessage(new Message('Zlecenie odebrane – przeniesione do historii', Message::INFO));
        App::getRouter()->redirectTo('my_orders');
    }
}
