<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Homepage::getIndex');
$routes->get('produk/(:num)', 'Product::detail/$1');

service('auth')->routes($routes);
