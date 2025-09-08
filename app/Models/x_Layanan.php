<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Layanan extends Model
{
	protected $table = 'layanan';
	protected $primaryKey = 'id';

	protected $fillable = [
		'kode',
		'nama',
		'unit_id',
		'is_aktif',
	];

	public function unit(): BelongsTo
	{
		return $this->belongsTo(Unit::class, 'unit_id', 'id');
	}
	
}
