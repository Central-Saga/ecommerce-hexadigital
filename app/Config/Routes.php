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

// Tambahkan route Shield, exclude register
service('auth')->routes($routes, ['except' => ['register']]);
