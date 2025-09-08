<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
	/** @use HasFactory<\Database\Factories\UserFactory> */
	use HasFactory, Notifiable;

	protected $table = 'user';
	protected $primaryKey = 'iduser';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var list<string>
	 */
	protected $fillable = [
		'nipnik',
		'password',
		'nama',
		'gelar_depan',
		'gelar_belakang',
		'join_table',
		'status',
		'idunit_kerja',
	];

	/**
	 * The attributes that should be hidden for serialization.
	 *
	 * @var list<string>
	 */
	protected $hidden = [
		'password',
	];

	/**
	 * Get the attributes that should be cast.
	 *
	 * @return array<string, string>
	 */
	protected function casts(): array
	{
		return [
			//'email_verified_at' => 'datetime',
			//'password' => 'hashed',
		];
	}

	public function unit_kerja(): BelongsTo
	{
		return $this->belongsTo(UnitKerja::class, 'idunit_kerja', 'idunit_kerja');
	}

	public function role_user(): HasMany
	{
		return $this->hasMany(RoleUser::class, 'iduser', 'iduser');
	}

	public function ambilRoleAktif()
	{
		return $this->role_user()
			->join('aucc.unit_kerja as uk', 'uk.id_unit_kerja', '=', 'role_user.idunit_kerja')
			->join('role as r', 'r.idrole', '=', 'role_user.idrole')
			->where('role_user.status', '1')
			->select([
				'role_user.idrole',
				'r.nama_role',
				'role_user.idunit_kerja',
				'uk.nm_unit_kerja'
			])
			->first();
	}
}
