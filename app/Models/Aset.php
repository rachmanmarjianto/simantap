<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Aset extends Model
{
	protected $table = 'aset';
	protected $primaryKey = 'kode_barang_aset';

	protected $fillable = [
		'nama_barang',
		'merk_barang',
		'tahun_aset',
		'idruang',
	];

	public function ruang(): BelongsTo
	{
		return $this->belongsTo(Ruang::class, 'idruang', 'idruang');
	}
	
}
