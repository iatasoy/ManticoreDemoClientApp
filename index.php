<?php


require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/src/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

$f3 = \Base::instance();

$appController = new ApplicationController();

$f3->route('GET /', [$appController, 'showHomePage']);
$f3->route('GET /customers/@customerid', [$appController, 'showCustomerByIdPage']);
$f3->route('GET /customers/search/@param', [$appController, 'showSearchCustomerPage']);

$f3->set('ONERROR', function ($f3) use ($appController) {$appController->showNotFoundPage();});

$f3->run();
