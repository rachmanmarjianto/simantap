<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LayananAset extends Model
{
	protected $table = 'layanan_aset';
	protected $primaryKey = 'idlayanan_aset';

	protected $fillable = [
		'kode_barang_aset',
		'idlayanan',
		'waktu_penggunaan_ideal_min',
	];

	public function layanan(): BelongsTo
	{
		return $this->belongsTo(Layanan::class, 'idlayanan', 'idlayanan');
	}
	
}
