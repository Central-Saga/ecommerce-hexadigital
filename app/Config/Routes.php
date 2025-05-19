<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Homepage Routes
$routes->add('/', ['Homepage::index']);

// Product Routes
$routes->add('produk', ['Product::index']);
$routes->add('produk/(:segment)', ['Product::detail/$1']);
$routes->add('kategori/(:num)', ['Product::category/$1']);
$routes->add('search', ['Product::search']);

// Cart Routes
$routes->add('keranjang', ['Cart::index']);
$routes->add('keranjang/tambah', ['Cart::add'], ['post']);
$routes->add('keranjang/update', ['Cart::update'], ['post']);
$routes->add('keranjang/hapus', ['Cart::remove'], ['post']);

// Auth Routes
$routes->add('login', ['Auth::login']);
$routes->add('register', ['Auth::register']);
$routes->add('auth/login', ['Auth::attemptLogin'], ['post']);
$routes->add('auth/register', ['Auth::attemptRegister'], ['post']);
$routes->add('logout', ['Auth::logout']);

// Profile Routes with Auth Filter
$routes->add('profil', ['Profile::index'], ['filter' => 'auth']);
$routes->add('profil/update', ['Profile::update'], ['filter' => 'auth', 'post']);

// Admin Routes
$routes->group('godmode', ['filter' => 'role:admin'], static function($routes) {
    $routes->add('/', ['Godmode\Dashboard::index']);
    $routes->resource('products', ['controller' => 'Godmode\Products']);
    $routes->resource('categories', ['controller' => 'Godmode\Categories']);
    $routes->resource('orders', ['controller' => 'Godmode\Orders']);
    $routes->resource('users', ['controller' => 'Godmode\Users']);
});
