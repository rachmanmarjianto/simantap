<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UnitKerja extends Model
{
	protected $table = 'aucc.unit_kerja';
	protected $primaryKey = 'idunit_kerja';

	// protected $fillable = [
	// 	'nama_unit_kerja',
	// 	'type_unit_kerja',
	// 	'status',
	// 	'idfakultas',
	// 	'idprogram_studi',
	// ];

	public function fakultas(): BelongsTo
	{
		return $this->belongsTo(Fakultas::class, 'idfakultas', 'idfakultas');
	}

	public function program_studi(): BelongsTo
	{
		return $this->belongsTo(ProgramStudi::class, 'idprogram_studi', 'idprogram_studi');
	}
	
}
