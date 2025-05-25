<?php

namespace App\Models;

use CodeIgniter\Model;

class Pengiriman extends Model
{
    protected $table            = 'pengiriman';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'pemesanan_id',
        'tanggal_kirim',
        'tanggal_terima',
        'status',
        'deskripsi',
        'created_at',
        'updated_at'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'pemesanan_id' => 'integer',
    ];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = '';

    // Validation
    protected $validationRules      = [
        'pemesanan_id' => 'required|integer',
        'tanggal_kirim' => 'permit_empty|valid_date',
        'tanggal_terima' => 'permit_empty|valid_date',
        'status' => 'required|in_list[menunggu,dikirim,diterima,dibatalkan]'
    ];

    protected $validationMessages   = [
        'pemesanan_id' => [
            'required' => 'Pemesanan harus dipilih',
            'integer' => 'ID Pemesanan harus berupa angka',
        ],
        'tanggal_kirim' => [
            'valid_date' => 'Tanggal kirim harus berupa tanggal yang valid'
        ],
        'tanggal_terima' => [
            'valid_date' => 'Tanggal terima harus berupa tanggal yang valid'
        ],
        'status' => [
            'required' => 'Status pengiriman harus diisi',
            'in_list' => 'Status pengiriman tidak valid'
        ]
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Get pengiriman with pemesanan relation
     */
    public function withPemesanan()
    {
        return $this->select('pengiriman.*, pemesanan.id as pemesanan_id, pemesanan.tanggal_pemesanan, pemesanan.total_harga, pemesanan.status_pemesanan, pemesanan.catatan')
            ->join('pemesanan', 'pemesanan.id = pengiriman.pemesanan_id', 'left');
    }
}
