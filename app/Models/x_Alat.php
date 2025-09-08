<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Alat extends Model
{
	protected $table = 'alat';
	protected $primaryKey = 'id';

	protected $fillable = [
		'kode',
		'nama',
		'unit_id',
		'lokasi_ruangan',
		'keterangan',
		'is_aktif',
	];

	public function unit(): BelongsTo
	{
		return $this->belongsTo(Unit::class, 'unit_id', 'id');
	}
	
}
