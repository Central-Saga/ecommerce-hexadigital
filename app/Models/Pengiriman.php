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
        'pesanan_id',
        'tanggal_kirim',
        'tanggal_terima',
        'deskripsi',
        'status'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'pesanan_id' => 'integer',
        'tanggal_kirim' => 'date',
        'tanggal_terima' => 'date'
    ];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'pesanan_id' => 'permit_empty|integer',
        'status' => 'required|in_list[diproses,dikirim,diterima,dibatalkan]'
    ];
    protected $validationMessages   = [];
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
     * Get pengiriman with pesanan relation
     */
    public function withPesanan()
    {
        return $this->select('pengiriman.*, pesanan.nomor_pesanan')
                    ->join('pesanan', 'pesanan.id = pengiriman.pesanan_id', 'left');
    }
}
