<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgramStudi extends Model
{
	protected $table = 'aucc.program_studi';
	protected $primaryKey = 'idprogram_studi';

	// protected $fillable = [
	// 	'nama_program_studi',
	// 	'status',
	// 	'idfakultas',
	// 	'idjenjang',
	// ];

	public function fakultas(): BelongsTo
	{
		return $this->belongsTo(Fakultas::class, 'idfakultas', 'idfakultas');
	}

	public function jenjang(): BelongsTo
	{
		return $this->belongsTo(Jenjang::class, 'idjenjang', 'idjenjang');
	}
	
}
