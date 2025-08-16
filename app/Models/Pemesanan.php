<?php

namespace App\Models;

use CodeIgniter\Model;

class Pemesanan extends Model
{
    protected $table            = 'pemesanan';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'pelanggan_id',
        'tanggal_pemesanan',
        'total_harga',
        'status_pemesanan',
        'catatan'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'pelanggan_id' => 'integer',
        // 'total_harga' => 'float'
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
        'pelanggan_id' => 'required|integer', // Hapus is_not_unique untuk mengurangi ketergantungan
        'tanggal_pemesanan' => 'required|valid_date',
        'total_harga' => 'required|numeric',
        'status_pemesanan' => 'required|in_list[menunggu,diproses,selesai,dibatalkan]'
    ];

    protected $validationMessages   = [
        'pelanggan_id' => [
            'required' => 'Pelanggan harus dipilih',
            'integer' => 'ID Pelanggan harus berupa angka',
            'is_not_unique' => 'Pelanggan tidak ditemukan'
        ],
        'tanggal_pemesanan' => [
            'required' => 'Tanggal pemesanan harus diisi',
            'valid_date' => 'Tanggal pemesanan harus berupa tanggal yang valid'
        ],
        'total_harga' => [
            'required' => 'Total harga harus diisi',
            'numeric' => 'Total harga harus berupa angka'
        ],
        'status_pemesanan' => [
            'required' => 'Status pemesanan harus diisi',
            'in_list' => 'Status pemesanan tidak valid'
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
     * Get pemesanan with pelanggan relation
     */
    public function withPelanggan()
    {
        return $this->select('
            pemesanan.id,
            pemesanan.pelanggan_id,
            pemesanan.tanggal_pemesanan,
            pemesanan.total_harga,
            pemesanan.status_pemesanan,
            pemesanan.catatan,
            pemesanan.created_at,
            pemesanan.updated_at,
            COALESCE(users.username, "Unknown") as nama_pelanggan,
            COALESCE(auth_identities.secret, "") as email_pelanggan
        ')
            ->join('pelanggans', 'pelanggans.id = pemesanan.pelanggan_id', 'left')
            ->join('users', 'users.id = pelanggans.user_id', 'left')
            ->join('auth_identities', 'auth_identities.user_id = users.id AND auth_identities.type = "email_password"', 'left');
    }

    /**
     * Get laporan penjualan per bulan
     */
    public function getLaporanPenjualanPerBulan($tahun = null, $bulan = null)
    {
        if ($tahun === null) {
            $tahun = date('Y');
        }

        if ($bulan === null) {
            $bulan = date('n');
        }

        return $this->select('
            pemesanan.id,
            pemesanan.pelanggan_id,
            pemesanan.tanggal_pemesanan,
            pemesanan.total_harga,
            pemesanan.status_pemesanan,
            pemesanan.catatan,
            pemesanan.created_at,
            pemesanan.updated_at,
            COALESCE(users.username, "Unknown") as nama_pelanggan,
            COALESCE(auth_identities.secret, "") as email_pelanggan
        ')
            ->join('pelanggans', 'pelanggans.id = pemesanan.pelanggan_id', 'left')
            ->join('users', 'users.id = pelanggans.user_id', 'left')
            ->join('auth_identities', 'auth_identities.user_id = users.id AND auth_identities.type = "email_password"', 'left')
            ->where('YEAR(pemesanan.tanggal_pemesanan)', $tahun)
            ->where('MONTH(pemesanan.tanggal_pemesanan)', $bulan)
            ->where('pemesanan.status_pemesanan !=', 'dibatalkan')
            ->orderBy('pemesanan.tanggal_pemesanan', 'DESC');
    }

    /**
     * Get statistik penjualan per bulan
     */
    public function getStatistikPenjualanPerBulan($tahun = null)
    {
        if ($tahun === null) {
            $tahun = date('Y');
        }

        return $this->select('
            MONTH(tanggal_pemesanan) as bulan,
            COUNT(*) as total_pesanan,
            SUM(total_harga) as total_penjualan,
            AVG(total_harga) as rata_rata_penjualan
        ')
            ->where('YEAR(tanggal_pemesanan)', $tahun)
            ->where('status_pemesanan !=', 'dibatalkan')
            ->groupBy('MONTH(tanggal_pemesanan)')
            ->orderBy('bulan', 'ASC');
    }

    /**
     * Get total penjualan per bulan tertentu
     */
    public function getTotalPenjualanPerBulan($tahun, $bulan)
    {
        return $this->select('
            COUNT(*) as total_pesanan,
            SUM(total_harga) as total_penjualan,
            AVG(total_harga) as rata_rata_penjualan
        ')
            ->where('YEAR(tanggal_pemesanan)', $tahun)
            ->where('MONTH(tanggal_pemesanan)', $bulan)
            ->where('status_pemesanan !=', 'dibatalkan')
            ->first();
    }
}
