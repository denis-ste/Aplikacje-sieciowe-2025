<?php

namespace app\controllers;

use core\App;
use core\Message;
use core\ParamUtils;

class ServicesAdminCtrl extends BaseCtrl {

    public function action_admin_services(): void {
        $this->prepareLayout();

        $q = trim((string)ParamUtils::getFromRequest('q'));

        $where = ['ORDER' => ['name' => 'ASC']];
        if (!empty($q)) {
            $where['name[~]'] = $q;
        }

        $services = App::getDB()->select('services', ['id','name','price','is_active'], $where);

        App::getSmarty()->assign('q', $q);
        App::getSmarty()->assign('services', $services);
        App::getSmarty()->assign('pageTitle', 'Usługi (CRUD)');
        App::getSmarty()->display('AdminServices.tpl');
    }

    public function action_service_new(): void {
        $this->prepareLayout();

        if (!isset($_POST['name'])) {
            App::getSmarty()->assign('mode', 'new');
            App::getSmarty()->assign('service', null);
            App::getSmarty()->assign('pageTitle', 'Dodaj usługę');
            App::getSmarty()->display('ServiceForm.tpl');
            return;
        }

        $name = trim((string)ParamUtils::getFromRequest('name', true));
        $price = (float)ParamUtils::getFromRequest('price', true);

        if ($price < 0) {
            App::getMessages()->addMessage(new Message('Cena nie może być ujemna', Message::ERROR));
            App::getRouter()->redirectTo('service_new');
            return;
        }

        $exists = App::getDB()->has('services', ['name' => $name]);
        if ($exists) {
            App::getMessages()->addMessage(new Message('Usługa o takiej nazwie już istnieje', Message::ERROR));
            App::getRouter()->redirectTo('service_new');
            return;
        }

        App::getDB()->insert('services', ['name' => $name, 'price' => $price, 'is_active' => 'Y']);
        App::getMessages()->addMessage(new Message('Usługa dodana', Message::INFO));
        App::getRouter()->redirectTo('admin_services');
    }

    public function action_service_edit(): void {
        $this->prepareLayout();

        $id = (int)ParamUtils::getFromRequest('id', true);

        if (!isset($_POST['name'])) {
            $service = App::getDB()->get('services', ['id','name','price','is_active'], ['id' => $id]);
            if (!$service) {
                App::getMessages()->addMessage(new Message('Nie znaleziono usługi', Message::ERROR));
                App::getRouter()->redirectTo('admin_services');
                return;
            }

            App::getSmarty()->assign('mode', 'edit');
            App::getSmarty()->assign('service', $service);
            App::getSmarty()->assign('pageTitle', 'Edytuj usługę');
            App::getSmarty()->display('ServiceForm.tpl');
            return;
        }

        $name = trim((string)ParamUtils::getFromRequest('name', true));
        $price = (float)ParamUtils::getFromRequest('price', true);
        $isActive = ParamUtils::getFromRequest('is_active') === 'Y' ? 'Y' : 'N';

        if ($price < 0) {
            App::getMessages()->addMessage(new Message('Cena nie może być ujemna', Message::ERROR));
            App::getRouter()->redirectTo('service_edit', ['id' => $id]);
            return;
        }

        // unikalnosc nazwy (poza sobą)
        $exists = App::getDB()->has('services', ['AND' => ['name' => $name, 'id[!]' => $id]]);
        if ($exists) {
            App::getMessages()->addMessage(new Message('Inna usługa ma już taką nazwę', Message::ERROR));
            App::getRouter()->redirectTo('service_edit', ['id' => $id]);
            return;
        }

        App::getDB()->update('services', ['name' => $name, 'price' => $price, 'is_active' => $isActive], ['id' => $id]);
        App::getMessages()->addMessage(new Message('Zapisano', Message::INFO));
        App::getRouter()->redirectTo('admin_services');
    }

    public function action_service_toggle(): void {
        $id = (int)ParamUtils::getFromRequest('id', true);
        $service = App::getDB()->get('services', ['id','is_active'], ['id' => $id]);
        if (!$service) {
            App::getMessages()->addMessage(new Message('Nie znaleziono usługi', Message::ERROR));
            App::getRouter()->redirectTo('admin_services');
            return;
        }
        $new = ($service['is_active'] === 'Y') ? 'N' : 'Y';
        App::getDB()->update('services', ['is_active' => $new], ['id' => $id]);
        App::getMessages()->addMessage(new Message('Zmieniono aktywność usługi', Message::INFO));
        App::getRouter()->redirectTo('admin_services');
    }
}
