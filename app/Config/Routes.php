<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Homepage Routes
$routes->get('/', 'Homepage::index');

// Product Routes
$routes->get('produk', 'Product::index');
$routes->get('produk/(:segment)', 'Product::detail/$1');
$routes->get('kategori', 'Product::kategori');
$routes->get('kategori/(:num)', 'Product::category/$1');
$routes->get('search', 'Product::search');

// Cart Routes
$routes->get('cart', 'Cart::index');
$routes->post('cart/add', 'Cart::add');
$routes->post('cart/update', 'Cart::update');
$routes->post('cart/remove', 'Cart::remove');

// (Tetap biarkan juga route keranjang untuk dukungan bahasa Indonesia)
$routes->get('keranjang', 'Cart::index');
$routes->post('keranjang/tambah', 'Cart::add');
$routes->post('keranjang/update', 'Cart::update');
$routes->post('keranjang/hapus', 'Cart::remove');

// Auth Routes
$routes->get('login', 'Auth::login');
$routes->get('register', 'Auth::register');
$routes->post('auth/login', 'Auth::attemptLogin');
$routes->post('auth/register', 'Auth::attemptRegister');
$routes->get('logout', 'Auth::logout');

// Profile Routes with Auth Filter
$routes->get('profil', 'Profile::index', ['filter' => 'auth']);
$routes->post('profil/update', 'Profile::update', ['filter' => 'auth']);

// Admin Routes
$routes->group('godmode', ['filter' => 'role:admin'], static function($routes) {
    $routes->get('/', 'Godmode\Dashboard::index');
    $routes->resource('products', ['controller' => 'Godmode\Products']);
    $routes->resource('categories', ['controller' => 'Godmode\Categories']);
    $routes->resource('orders', ['controller' => 'Godmode\Orders']);
    $routes->resource('users', ['controller' => 'Godmode\Users']);
});
