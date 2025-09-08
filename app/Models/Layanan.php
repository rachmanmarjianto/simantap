<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Layanan extends Model
{
	protected $table = 'layanan';
	protected $primaryKey = 'idlayanan';

	protected $fillable = [
		'nama_layanan',
		'idunit_kerja',
		'status',
		'idlayanan_unit_kerja',
	];

	public function unit_kerja(): BelongsTo
	{
		return $this->belongsTo(UnitKerja::class, 'idunit_kerja', 'idunit_kerja');
	}
	
}
