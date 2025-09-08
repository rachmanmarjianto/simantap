<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ruang extends Model
{
	protected $table = 'simba.ruang';
	protected $primaryKey = 'idruang';

	// protected $fillable = [
	// 	'nama_ruang',
	// 	'idtipe_ruang',
	// 	'tipe_ruang',
	// 	'idunit_kerja',
	// 	'idgedung',
	// 	'nama_gedung',
	// 	'idkampus',
	// 	'nama_kampus',
	// ];

	public function unit_kerja(): BelongsTo
	{
		return $this->belongsTo(UnitKerja::class, 'idunit_kerja', 'idunit_kerja');
	}
	
}
