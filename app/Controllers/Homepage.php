<?php

namespace App\Controllers;

use App\Models\Kategori;
use App\Models\Produk;

class Homepage extends BaseController
{
    protected $kategoriModel;
    protected $produkModel;

    public function __construct()
    {
        $this->kategoriModel = new Kategori();
        $this->produkModel = new Produk();
    }

    public function getIndex()
    {
        // Fetch all categories
        $categories = $this->kategoriModel->findAll();

        // Format categories with proper icon mapping
        $formattedCategories = [];
        foreach ($categories as $category) {
            // Define icon mapping (you can expand this list)
            $iconMap = [
                'software' => 'bi-laptop',
                'ebook' => 'bi-book',
                'course' => 'bi-play-circle',
                'design' => 'bi-brush',
                'music' => 'bi-music-note',
                'video' => 'bi-camera-video',
                'game' => 'bi-controller',
                'template' => 'bi-file-earmark-code'
            ];

            // Get icon based on category name or use default
            $icon = 'bi-collection'; // default icon
            foreach ($iconMap as $keyword => $iconClass) {
                if (stripos($category['nama_kategori'], $keyword) !== false) {
                    $icon = $iconClass;
                    break;
                }
            }

            $formattedCategories[] = [
                'id' => $category['id'],
                'nama_kategori' => $category['nama_kategori'],
                'deskripsi' => $category['deskripsi_kategori'],
                'icon' => $icon
            ];
        }

        // Fetch featured products with category information
        $featuredProducts = $this->produkModel->withKategori()
            ->orderBy('created_at', 'DESC')
            ->limit(8) // Show latest 8 products
            ->find();

        // Format products data
        $formattedProducts = [];
        foreach ($featuredProducts as $product) {
            // Calculate discount if original price exists
            $discount = null;
            if (isset($product['harga_asli']) && $product['harga_asli'] > $product['harga']) {
                $discount = round(($product['harga_asli'] - $product['harga']) / $product['harga_asli'] * 100);
            }

            $formattedProducts[] = [
                'id' => $product['id'],
                'nama_produk' => $product['nama_produk'],
                'harga' => $product['harga'],
                'harga_asli' => $product['harga_asli'] ?? null,
                'deskripsi_singkat' => substr($product['deskripsi'], 0, 150) . '...',
                'kategori' => $product['kategori_nama'] ?? 'Uncategorized',
                'gambar' => $product['gambar'],
                'discount' => $discount
            ];
        }

        return view('pages/homepage', [
            'categories' => $formattedCategories,
            'featured_products' => $formattedProducts
        ]);
    }
}
