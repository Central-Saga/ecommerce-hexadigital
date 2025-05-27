<?php

namespace App\Models;

use CodeIgniter\Model;

class Pelanggan extends Model
{
    protected $table            = 'pelanggans';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id',
        'alamat',
        'created_at',
        'status',
        'no_telepon',
        'jenis_kelamin',
        'umur',
        'updated_at'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'user_id' => 'required|numeric|is_not_unique[users.id]',
        'alamat' => 'required',
        'status' => 'required',
        'no_telepon' => 'required|numeric|min_length[10]|max_length[15]',
        'jenis_kelamin' => 'required|in_list[L,P]',
        'umur' => 'required|numeric|greater_than[0]'
    ];

    protected $validationMessages   = [
        'user_id' => [
            'required' => 'User ID harus diisi',
            'numeric' => 'User ID harus berupa angka',
            'is_not_unique' => 'User ID tidak ditemukan'
        ],
        'alamat' => [
            'required' => 'Alamat harus diisi'
        ],
        'status' => [
            'required' => 'Status harus diisi'
        ],
        'no_telepon' => [
            'required' => 'Nomor telepon harus diisi',
            'numeric' => 'Nomor telepon harus berupa angka',
            'min_length' => 'Nomor telepon minimal 10 digit',
            'max_length' => 'Nomor telepon maksimal 15 digit'
        ],
        'jenis_kelamin' => [
            'required' => 'Jenis kelamin harus diisi',
            'in_list' => 'Jenis kelamin harus L atau P'
        ],
        'umur' => [
            'required' => 'Umur harus diisi',
            'numeric' => 'Umur harus berupa angka',
            'greater_than' => 'Umur harus lebih dari 0'
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
     * Get pelanggan with user relation
     */
    public function withUser()
    {
        return $this->select('pelanggans.*, users.username, auth_identities.secret as email')
            ->join('users', 'users.id = pelanggans.user_id', 'left')
            ->join('auth_identities', 'auth_identities.user_id = users.id', 'left');
    }
}
