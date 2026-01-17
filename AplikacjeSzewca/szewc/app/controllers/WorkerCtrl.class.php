<?php

namespace app\controllers;

use core\App;
use core\Message;
use core\ParamUtils;
use core\OrderStatus;

class WorkerCtrl extends BaseCtrl {

    public function action_worker_pickup_set(): void {
        $workerId = (int)($_SESSION['user_id'] ?? 0);
        $orderId = (int)ParamUtils::getFromRequest('id', true);
        $pickupDate = trim((string)ParamUtils::getFromRequest('pickup_date', true));

        // walidacja: data nie wstecz
        $today = date('Y-m-d');
        if ($pickupDate < $today) {
            App::getMessages()->addMessage(new Message('Termin odbioru nie może być wstecz', Message::ERROR));
            App::getRouter()->redirectTo('worker_orders');
            return;
        }

        $order = App::getDB()->get('orders', ['id','assigned_user_id'], ['id' => $orderId]);
        if (!$order) {
            App::getMessages()->addMessage(new Message('Nie znaleziono zlecenia', Message::ERROR));
            App::getRouter()->redirectTo('worker_orders');
            return;
        }

        // Jeśli zlecenie nie ma przypisanego pracownika, przypisz tego ustawiającego termin.
        $update = ['pickup_date' => $pickupDate];
        if (empty($order['assigned_user_id'])) {
            $update['assigned_user_id'] = $workerId;
        }

        App::getDB()->update('orders', $update, ['id' => $orderId]);
        App::getMessages()->addMessage(new Message('Termin odbioru został ustawiony', Message::INFO));
        App::getRouter()->redirectTo('worker_orders');
    }

    public function action_worker_orders(): void {
        $this->prepareLayout();

        $q = trim((string)ParamUtils::getFromRequest('q'));
        $statusId = ParamUtils::getFromRequest('status_id');
        $dateFrom = ParamUtils::getFromRequest('date_from');
        $dateTo = ParamUtils::getFromRequest('date_to');

        $statuses = App::getDB()->select('order_statuses', ['id','name'], ['is_active' => 'Y', 'ORDER' => ['sort_order' => 'ASC']]);

        $where = ['ORDER' => ['orders.created_at' => 'DESC']];

        // domyślnie pokazuj aktywne (bez odebranych)
        if ($statusId === null || $statusId === '') {
            $where['orders.status_id[!]' ] = OrderStatus::PICKED_UP;
        } else {
            $where['orders.status_id'] = (int)$statusId;
        }

        if (!empty($dateFrom) && !empty($dateTo)) {
            $where['orders.pickup_date[<>]'] = [$dateFrom, $dateTo];
        } elseif (!empty($dateFrom)) {
            $where['orders.pickup_date[>=]'] = $dateFrom;
        } elseif (!empty($dateTo)) {
            $where['orders.pickup_date[<=]'] = $dateTo;
        }

        if (!empty($q)) {
            // Szukamy też po produktach (zamówienia PRODUCT) - minimalnie: osobna kwerenda na order_id
            $productOrderIds = App::getDB()->select(
                'order_items',
                ['[>]products' => ['product_id' => 'id']],
                ['order_items.order_id'],
                ['products.name[~]' => $q]
            );
            $productOrderIds = array_values(array_unique(array_map('intval', $productOrderIds)));

            $where['OR'] = [
                'client.username[~]' => $q,
                'services.name[~]' => $q,
                'orders.note[~]' => $q,
            ];

            if (!empty($productOrderIds)) {
                $where['OR']['orders.id'] = $productOrderIds;
            }
        }

        $orders = App::getDB()->select(
            'orders',
            [
                '[>]users(client)' => ['client_user_id' => 'id'],
                '[>]users(worker)' => ['assigned_user_id' => 'id'],
                '[>]services' => ['service_id' => 'id'],
                '[>]order_statuses' => ['status_id' => 'id'],
            ],
            [
                'orders.id',
                'orders.order_type',
                'orders.pickup_date',
                'orders.total_price',
                'orders.note',
                'orders.created_at',
                'client.username(client_username)',
                'worker.username(worker_username)',
                'services.name(service_name)',
                'order_statuses.name(status_name)',
                'order_statuses.code(status_code)',
                'orders.status_id',
            ],
            $where
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

        App::getSmarty()->assign('q', $q);
        App::getSmarty()->assign('statusId', $statusId);
        App::getSmarty()->assign('dateFrom', $dateFrom);
        App::getSmarty()->assign('dateTo', $dateTo);
        App::getSmarty()->assign('statuses', $statuses);
        App::getSmarty()->assign('orders', $orders);
        App::getSmarty()->assign('pageTitle', 'Zlecenia (pracownik)');
        App::getSmarty()->display('WorkerOrders.tpl');
    }

    public function action_worker_status(): void {
        $workerId = (int)($_SESSION['user_id'] ?? 0);
        $orderId = (int)ParamUtils::getFromRequest('id', true);
        $to = strtoupper(trim((string)ParamUtils::getFromRequest('to', true)));

        $order = App::getDB()->get('orders', ['id','status_id','assigned_user_id'], ['id' => $orderId]);
        if (!$order) {
            App::getMessages()->addMessage(new Message('Nie znaleziono zlecenia', Message::ERROR));
            App::getRouter()->redirectTo('worker_orders');
            return;
        }

        $currentStatus = (int)$order['status_id'];

        if ($to === 'ACCEPTED') {
            if ($currentStatus !== OrderStatus::NEW) {
                App::getMessages()->addMessage(new Message('Można przyjąć tylko zlecenie ze statusem NOWE', Message::ERROR));
                App::getRouter()->redirectTo('worker_orders');
                return;
            }
            App::getDB()->update('orders', [
                'status_id' => OrderStatus::ACCEPTED,
                'assigned_user_id' => $workerId
            ], ['id' => $orderId]);

            App::getMessages()->addMessage(new Message('Zlecenie przyjęte', Message::INFO));
            App::getRouter()->redirectTo('worker_orders');
            return;
        }

        if ($to === 'READY') {
            if ($currentStatus !== OrderStatus::ACCEPTED) {
                App::getMessages()->addMessage(new Message('Można oznaczyć jako GOTOWE tylko zlecenie PRZYJĘTE', Message::ERROR));
                App::getRouter()->redirectTo('worker_orders');
                return;
            }

            // opcjonalnie: tylko ten pracownik, który przyjął
            if (!empty($order['assigned_user_id']) && (int)$order['assigned_user_id'] !== $workerId) {
                App::getMessages()->addMessage(new Message('To zlecenie jest przypisane do innego pracownika', Message::ERROR));
                App::getRouter()->redirectTo('worker_orders');
                return;
            }

            App::getDB()->update('orders', ['status_id' => OrderStatus::READY], ['id' => $orderId]);
            App::getMessages()->addMessage(new Message('Zlecenie oznaczone jako GOTOWE', Message::INFO));
            App::getRouter()->redirectTo('worker_orders');
            return;
        }

        App::getMessages()->addMessage(new Message('Nieznana zmiana statusu', Message::ERROR));
        App::getRouter()->redirectTo('worker_orders');
    }
}
