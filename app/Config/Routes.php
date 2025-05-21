<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Homepage Routes
$routes->get('/', 'Homepage::index');

// Tambahkan route Shield
defined('SHIELD_ROUTE') || service('auth')->routes($routes);
