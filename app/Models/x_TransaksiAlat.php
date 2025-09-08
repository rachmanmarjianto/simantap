<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransaksiAlat extends Model
{
	protected $table = 'transaksi_alat';
	protected $primaryKey = 'id';

	protected $fillable = [
		'unit_id',
		'layanan_id',
		'alat_id',
		'waktu_pakai_alat_mulai',
		'waktu_pakai_alat_selesai',
		'biaya_pakai_alat',
		'user_operator_alat_id',
	];

	public function unit(): BelongsTo
	{
		return $this->belongsTo(Unit::class, 'unit_id', 'id');
	}

	public function layanan(): BelongsTo
	{
		return $this->belongsTo(Layanan::class, 'layanan_id', 'id');
	}

	public function alat(): BelongsTo
	{
		return $this->belongsTo(Alat::class, 'alat_id', 'id');
	}

	public function operator(): BelongsTo
	{
		return $this->belongsTo(User::class, 'user_operator_alat_id', 'id');
	}
	
}
