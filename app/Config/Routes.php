<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Homepage Routes
$routes->get('/', 'Homepage::index');

// Custom route untuk register
$routes->get('register', 'Auth\RegisterController::registerView');
$routes->post('register', 'Auth\RegisterController::registerAction');

// testing
$routes->get('godmode/laporan', 'Godmode\Laporan::index');

// Tambahkan route Shield, exclude register
service('auth')->routes($routes, ['except' => ['register']]);
