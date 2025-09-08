<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoleUser extends Model
{
	protected $table = 'role_user';
	protected $primaryKey = 'idrole_user';

	protected $fillable = [
		'iduser',
		'idrole',
		'status',
	];

	public function role(): BelongsTo
	{
		return $this->belongsTo(Role::class, 'idrole', 'idrole');
	}

	public function user(): BelongsTo
	{
		return $this->belongsTo(User::class, 'iduser', 'iduser');
	}

	public function unitkerja(): BelongsTo
	{
		return $this->belongsTo(UnitKerja::class, 'idunit_kerja', 'idunit_kerja');
	}
	
}
