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
    public string $defaultGroup = 'pengunjung';

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
            'description' => 'Administrator sistem dengan akses penuh.',
        ],
        'pegawai' => [
            'title'       => 'Pegawai',
            'description' => 'Pegawai dengan akses terbatas untuk operasional.',
        ],
        'pelanggan' => [
            'title'       => 'Pelanggan',
            'description' => 'Pengguna terdaftar yang dapat melakukan transaksi.',
        ],
        'pengunjung' => [
            'title'       => 'Pengunjung',
            'description' => 'Pengguna yang belum terdaftar.',
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
        'users.manage'    => 'Mengizinkan pengelolaan data pengguna',
        'users.view'      => 'Mengizinkan melihat data pengguna',
        'users.create'    => 'Mengizinkan membuat pengguna baru',
        'users.edit'      => 'Mengizinkan mengedit data pengguna',
        'users.delete'    => 'Mengizinkan menghapus pengguna',

        // Product Management
        'products.manage' => 'Mengizinkan pengelolaan produk',
        'products.view'   => 'Mengizinkan melihat produk',
        'products.create' => 'Mengizinkan membuat produk baru',
        'products.edit'   => 'Mengizinkan mengedit produk',
        'products.delete' => 'Mengizinkan menghapus produk',

        // Customer Management
        'customers.manage' => 'Mengizinkan pengelolaan data pelanggan',
        'customers.view'   => 'Mengizinkan melihat data pelanggan',
        'customers.create' => 'Mengizinkan membuat data pelanggan baru',
        'customers.edit'   => 'Mengizinkan mengedit data pelanggan',
        'customers.delete' => 'Mengizinkan menghapus data pelanggan',

        // Category Management
        'categories.manage' => 'Mengizinkan pengelolaan kategori',
        'categories.view'   => 'Mengizinkan melihat kategori',
        'categories.create' => 'Mengizinkan membuat kategori baru',
        'categories.edit'   => 'Mengizinkan mengedit kategori',
        'categories.delete' => 'Mengizinkan menghapus kategori',

        // Order Management
        'orders.manage'    => 'Mengizinkan pengelolaan pesanan',
        'orders.view'      => 'Mengizinkan melihat pesanan',
        'orders.create'    => 'Mengizinkan membuat pesanan baru',
        'orders.edit'      => 'Mengizinkan mengedit pesanan',
        'orders.delete'    => 'Mengizinkan menghapus pesanan',

        // Transaction Management
        'transactions.manage' => 'Mengizinkan pengelolaan transaksi',
        'transactions.view'   => 'Mengizinkan melihat transaksi',
        'transactions.create' => 'Mengizinkan membuat transaksi baru',
        'transactions.edit'   => 'Mengizinkan mengedit transaksi',
        'transactions.delete' => 'Mengizinkan menghapus transaksi',

        // Shipment Management
        'shipments.manage' => 'Mengizinkan pengelolaan pengiriman',
        'shipments.view'   => 'Mengizinkan melihat pengiriman',
        'shipments.create' => 'Mengizinkan membuat pengiriman baru',
        'shipments.edit'   => 'Mengizinkan mengedit pengiriman',
        'shipments.delete' => 'Mengizinkan menghapus pengiriman',
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
            'shipments.*',
        ],
        'pegawai' => [
            'products.view',
            'products.create',
            'products.edit',
            'customers.view',
            'customers.create',
            'customers.edit',
            'categories.view',
            'orders.view',
            'orders.create',
            'orders.edit',
            'transactions.view',
            'transactions.create',
            'transactions.edit',
            'shipments.view',
            'shipments.create',
            'shipments.edit',
        ],
        'pelanggan' => [
            'products.view',
            'categories.view',
            'orders.view',
            'orders.create',
            'transactions.view',
            'transactions.create',
            'shipments.view',
        ],
        'pengunjung' => [
            'products.view',
            'categories.view',
        ],
    ];
}
