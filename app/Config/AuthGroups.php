<?php

declare(strict_types=1);

/**
 * This file is part of CodeIgniter Shield.
 *
 * (c) CodeIgniter Foundation <admin@codeigniter.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Config;

use CodeIgniter\Shield\Config\AuthGroups as ShieldAuthGroups;

class AuthGroups extends ShieldAuthGroups
{
    /**
     * --------------------------------------------------------------------
     * Default Group
     * --------------------------------------------------------------------
     * The group that a newly registered user is added to.
     */
    public string $defaultGroup = 'user';

    /**
     * --------------------------------------------------------------------
     * Groups
     * --------------------------------------------------------------------
     * An associative array of the available groups in the system, where the keys
     * are the group names and the values are arrays of the group info.
     *
     * Whatever value you assign as the key will be used to refer to the group
     * when using functions such as:
     *      $user->addGroup('superadmin');
     *
     * @var array<string, array<string, string>>
     *
     * @see https://codeigniter4.github.io/shield/quick_start_guide/using_authorization/#change-available-groups for more info
     */
    public array $groups = [
        'admin' => [
            'title'       => 'Admin',
            'description' => 'Administrator dengan akses penuh ke sistem.',
        ],
        'pegawai' => [
            'title'       => 'Pegawai',
            'description' => 'Pegawai dengan akses terbatas untuk operasional.',
        ],
        'pelanggan' => [
            'title'       => 'Pelanggan',
            'description' => 'Pengguna umum yang dapat melakukan pembelian.',
        ],
    ];

    /**
     * --------------------------------------------------------------------
     * Permissions
     * --------------------------------------------------------------------
     * The available permissions in the system.
     *
     * If a permission is not listed here it cannot be used.
     */
    public array $permissions = [
        // User Management
        'users.view'         => 'Dapat melihat daftar user',
        'users.create'       => 'Dapat membuat user baru',
        'users.edit'         => 'Dapat mengedit user',
        'users.delete'       => 'Dapat menghapus user',

        // Product Management
        'products.view'      => 'Dapat melihat daftar produk',
        'products.create'    => 'Dapat membuat produk baru',
        'products.edit'      => 'Dapat mengedit produk',
        'products.delete'    => 'Dapat menghapus produk',

        // Customer Management
        'customers.view'     => 'Dapat melihat daftar customer',
        'customers.create'   => 'Dapat membuat customer baru',
        'customers.edit'     => 'Dapat mengedit customer',
        'customers.delete'   => 'Dapat menghapus customer',

        // Category Management
        'categories.view'    => 'Dapat melihat daftar kategori',
        'categories.create'  => 'Dapat membuat kategori baru',
        'categories.edit'    => 'Dapat mengedit kategori',
        'categories.delete'  => 'Dapat menghapus kategori',

        // Order Management
        'orders.view'        => 'Dapat melihat daftar pesanan',
        'orders.create'      => 'Dapat membuat pesanan baru',
        'orders.edit'        => 'Dapat mengedit pesanan',
        'orders.delete'      => 'Dapat menghapus pesanan',
        'orders.process'     => 'Dapat memproses pesanan',

        // Transaction Management
        'transactions.view'  => 'Dapat melihat daftar transaksi',
        'transactions.create' => 'Dapat membuat transaksi',
        'transactions.edit'  => 'Dapat mengedit transaksi',
        'transactions.void'  => 'Dapat membatalkan transaksi',

        // Shipping Management
        'shipping.view'      => 'Dapat melihat daftar pengiriman',
        'shipping.create'    => 'Dapat membuat pengiriman baru',
        'shipping.edit'      => 'Dapat mengedit pengiriman',
        'shipping.track'     => 'Dapat melacak pengiriman',

        // Stock Management
        'stock.view'         => 'Dapat melihat stok',
        'stock.update'       => 'Dapat mengupdate stok',
        'stock.history'      => 'Dapat melihat history stok',
        'stock.adjust'       => 'Dapat melakukan penyesuaian stok',

        // Additional permissions
        // 'admin.access'        => 'Can access the sites admin area',
        // 'admin.settings'      => 'Can access the main site settings',
        // 'users.manage-admins' => 'Can manage other admins',
        // 'beta.access'         => 'Can access beta-level features',
        // 'roles.permissions'   => 'Can manage role permissions',
    ];

    /**
     * --------------------------------------------------------------------
     * Permissions Matrix
     * --------------------------------------------------------------------
     * Maps permissions to groups.
     *
     * This defines group-level permissions.
     */
    public array $matrix = [
        'admin' => [
            'users.*',
            'products.*',
            'customers.*',
            'categories.*',
            'orders.*',
            'transactions.*',
            'shipping.*',
            'stock.*',
        ],
        'pegawai' => [
            'products.view',
            'customers.view',
            'orders.view',
            'orders.process',
            'shipping.view',
            'shipping.track',
            'stock.view',
        ],
        'pelanggan' => [
            'products.view',
            'orders.view',
            'transactions.view',
        ],
    ];
}
