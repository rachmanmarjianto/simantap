<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiwayatPemakaianAset extends Model
{
	protected $table = 'riwayat_pemakaian_aset';
	protected $primaryKey = 'idriwayat_pemakaian_aset';

	protected $fillable = [
		'timestamp_mulai',
		'dimulai_oleh',
		'timestamp_akhir',
		'diakhiri_oleh',
		'keterangan',
		'idpermintaan_layanan',
		'kode_barang_aset',
	];

	public function permintaan_layanan(): BelongsTo
	{
		return $this->belongsTo(PermintaanLayanan::class, 'idpermintaan_layanan', 'idpermintaan_layanan');
	}

	public function aset(): BelongsTo
	{
		return $this->belongsTo(Aset::class, 'kode_barang_aset', 'kode_barang_aset');
	}

	public function user_dimulai(): BelongsTo
	{
		return $this->belongsTo(User::class, 'dimulai_oleh', 'iduser');
	}

	public function user_diakhiri(): BelongsTo
	{
		return $this->belongsTo(User::class, 'diakhiri_oleh', 'iduser');
	}
	
}
