<?php

use core\App;
use core\Utils;

App::getRouter()->setDefaultRoute('main');
App::getRouter()->setLoginRoute('accessdenied');

// public
Utils::addRoute('accessdenied', 'AccessDeniedCtrl');
Utils::addRoute('main', 'MainCtrl');
Utils::addRoute('login', 'AuthCtrl');
Utils::addRoute('logout', 'AuthCtrl');
Utils::addRoute('register', 'AuthCtrl');

// client
Utils::addRoute('client_dashboard', 'ClientCtrl', ['CLIENT']);
Utils::addRoute('order_new', 'ClientCtrl', ['CLIENT']);
Utils::addRoute('order_create', 'ClientCtrl', ['CLIENT']);
Utils::addRoute('product_order_create', 'ClientCtrl', ['CLIENT']);
Utils::addRoute('my_orders', 'ClientCtrl', ['CLIENT']);
Utils::addRoute('order_pickup', 'ClientCtrl', ['CLIENT']);

// worker
Utils::addRoute('worker_orders', 'WorkerCtrl', ['WORKER']);
Utils::addRoute('worker_status', 'WorkerCtrl', ['WORKER']);
Utils::addRoute('worker_pickup_set', 'WorkerCtrl', ['WORKER']);

// admin
Utils::addRoute('admin_services', 'ServicesAdminCtrl', ['ADMIN']);
Utils::addRoute('service_new', 'ServicesAdminCtrl', ['ADMIN']);
Utils::addRoute('service_edit', 'ServicesAdminCtrl', ['ADMIN']);
Utils::addRoute('service_toggle', 'ServicesAdminCtrl', ['ADMIN']);
