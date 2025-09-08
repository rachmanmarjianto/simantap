<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PermintaanLayanan extends Model
{
	protected $table = 'permintaan_layanan';
	protected $primaryKey = 'idpermintaan_layanan';

	protected $fillable = [
		'idlayanan',
		'status',
	];

	public function layanan(): BelongsTo
	{
		return $this->belongsTo(Layanan::class, 'idlayanan', 'idlayanan');
	}
	
}
