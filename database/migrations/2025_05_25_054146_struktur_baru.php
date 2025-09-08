<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('user', function (Blueprint $table)
		{
			$table->id('iduser');
			$table->string('nipnik')->unique();
			$table->string('password', length:255);
			$table->string('nama')->nullable();
			$table->string('gelar_depan')->nullable();
			$table->string('gelar_belakang')->nullable();
			$table->char('join_table', length:1)->default('0')->comment('1=tendik, 2=dosen');
			$table->char('status', length:1)->default('0')->comment('0/1');
			$table->unsignedInteger('idunit_kerja')->nullable();

			$table->timestamps();
			$table->unsignedBigInteger('created_by')->nullable();
			$table->unsignedBigInteger('updated_by')->nullable();
			$table->string('created_ip', length:50)->nullable();
			$table->string('updated_ip', length:50)->nullable();
		} );

		DB::table('user')->insert([
			['nipnik' => '198310302010121003', 'password' => Hash::make('123456'), 'nama' => 'Bambang', 'gelar_depan' => null, 'gelar_belakang' => null, 'join_table' => '1', 'status' => '1', 'idunit_kerja' => null, 'created_at' => now(), 'updated_at' => now()],
		]);

		Schema::create('jenjang', function (Blueprint $table)
		{
			$table->id('idjenjang');
			$table->string('nama_jenjang')->nullable();
			$table->char('status', length:1)->default('0')->comment('0/1');

			$table->timestamps();
			$table->unsignedBigInteger('created_by')->nullable();
			$table->unsignedBigInteger('updated_by')->nullable();
			$table->string('created_ip', length:50)->nullable();
			$table->string('updated_ip', length:50)->nullable();
		} );

		Schema::create('fakultas', function (Blueprint $table)
		{
			$table->id('idfakultas');
			$table->string('nama_fakultas')->nullable();
			$table->char('status', length:1)->default('0')->comment('0/1');

			$table->timestamps();
			$table->unsignedBigInteger('created_by')->nullable();
			$table->unsignedBigInteger('updated_by')->nullable();
			$table->string('created_ip', length:50)->nullable();
			$table->string('updated_ip', length:50)->nullable();
		} );

		Schema::create('program_studi', function (Blueprint $table)
		{
			$table->id('idprogram_studi');
			$table->string('nama_program_studi')->nullable();
			$table->char('status', length:1)->default('0')->comment('0/1');
			$table->unsignedBigInteger('idfakultas')->nullable();
			$table->unsignedBigInteger('idjenjang')->nullable();

			$table->timestamps();
			$table->unsignedBigInteger('created_by')->nullable();
			$table->unsignedBigInteger('updated_by')->nullable();
			$table->string('created_ip', length:50)->nullable();
			$table->string('updated_ip', length:50)->nullable();
		} );

		Schema::create('unit_kerja', function (Blueprint $table)
		{
			$table->id('idunit_kerja');
			$table->string('nama_unit_kerja')->nullable();
			$table->string('type_unit_kerja')->nullable();
			$table->char('status', length:1)->default('0')->comment('0/1');
			$table->unsignedBigInteger('idfakultas')->nullable();
			$table->unsignedBigInteger('idprogram_studi')->nullable();

			$table->timestamps();
			$table->unsignedBigInteger('created_by')->nullable();
			$table->unsignedBigInteger('updated_by')->nullable();
			$table->string('created_ip', length:50)->nullable();
			$table->string('updated_ip', length:50)->nullable();
		} );

		Schema::create('ruang', function (Blueprint $table)
		{
			$table->id('idruang');
			$table->string('nama_ruang');
			$table->unsignedBigInteger('idtipe_ruang')->nullable();
			$table->string('tipe_ruang');
			$table->unsignedBigInteger('idunit_kerja')->nullable();
			$table->unsignedBigInteger('idgedung')->nullable();
			$table->string('nama_gedung');
			$table->unsignedBigInteger('idkampus')->nullable();
			$table->string('nama_kampus');

			$table->timestamps();
			$table->unsignedBigInteger('created_by')->nullable();
			$table->unsignedBigInteger('updated_by')->nullable();
			$table->string('created_ip', length:50)->nullable();
			$table->string('updated_ip', length:50)->nullable();
		} );

		Schema::create('role', function (Blueprint $table)
		{
			$table->id('idrole');
			$table->string('nama_role')->nullable();

			$table->timestamps();
			$table->unsignedBigInteger('created_by')->nullable();
			$table->unsignedBigInteger('updated_by')->nullable();
			$table->string('created_ip', length:50)->nullable();
			$table->string('updated_ip', length:50)->nullable();
		} );

		DB::table('role')->insert([
			['nama_role' => 'admin', 'created_at' => now(), 'updated_at' => now()],
			['nama_role' => 'operator', 'created_at' => now(), 'updated_at' => now()],
		]);

		Schema::create('role_user', function (Blueprint $table)
		{
			$table->id('idrole_user');
			$table->unsignedBigInteger('iduser')->nullable();
			$table->unsignedBigInteger('idrole')->nullable();
			$table->char('status', length:1)->default('0')->comment('0/1');

			$table->timestamps();
			$table->unsignedBigInteger('created_by')->nullable();
			$table->unsignedBigInteger('updated_by')->nullable();
			$table->string('created_ip', length:50)->nullable();
			$table->string('updated_ip', length:50)->nullable();
		} );

		DB::table('role_user')->insert([
			['iduser' => '1', 'idrole' => '1', 'status' => '1', 'created_at' => now(), 'updated_at' => now()],
			['iduser' => '1', 'idrole' => '2', 'status' => '0', 'created_at' => now(), 'updated_at' => now()],
		]);

		Schema::create('layanan', function (Blueprint $table)
		{
			$table->id('idlayanan');
			$table->string('nama_layanan');
			$table->unsignedBigInteger('idunit_kerja')->nullable();
			$table->char('status', length:1)->default('0')->comment('0/1');
			$table->unsignedBigInteger('idlayanan_unit_kerja')->nullable();

			$table->timestamps();
			$table->unsignedBigInteger('created_by')->nullable();
			$table->unsignedBigInteger('updated_by')->nullable();
			$table->string('created_ip', length:50)->nullable();
			$table->string('updated_ip', length:50)->nullable();
		} );

		Schema::create('aset', function (Blueprint $table)
		{
			$table->id('kode_barang_aset');
			$table->string('nama_barang');
			$table->string('merk_barang');
			$table->unsignedSmallInteger('tahun_aset');
			$table->unsignedBigInteger('idruang')->nullable();

			$table->timestamps();
			$table->unsignedBigInteger('created_by')->nullable();
			$table->unsignedBigInteger('updated_by')->nullable();
			$table->string('created_ip', length:50)->nullable();
			$table->string('updated_ip', length:50)->nullable();
		} );

		Schema::create('layanan_aset', function (Blueprint $table)
		{
			$table->id('idlayanan_aset');
			$table->string('kode_barang_aset');
			$table->unsignedBigInteger('idlayanan');
			$table->unsignedSmallInteger('waktu_penggunaan_ideal_min');

			$table->timestamps();
			$table->unsignedBigInteger('created_by')->nullable();
			$table->unsignedBigInteger('updated_by')->nullable();
			$table->string('created_ip', length:50)->nullable();
			$table->string('updated_ip', length:50)->nullable();
		} );

		Schema::create('permintaan_layanan', function (Blueprint $table)
		{
			$table->id('idpermintaan_layanan');
			$table->unsignedBigInteger('idlayanan');
			$table->char('status', length:1)->default('0')->comment('0/1');

			$table->timestamps();
			$table->unsignedBigInteger('created_by')->nullable();
			$table->unsignedBigInteger('updated_by')->nullable();
			$table->string('created_ip', length:50)->nullable();
			$table->string('updated_ip', length:50)->nullable();
		} );

		Schema::create('riwayat_pemakaian_aset', function (Blueprint $table)
		{
			$table->id('idriwayat_pemakaian_aset');
			$table->dateTime('timestamp_mulai');
			$table->unsignedBigInteger('dimulai_oleh');
			$table->dateTime('timestamp_akhir');
			$table->unsignedBigInteger('diakhiri_oleh');
			$table->string('keterangan');
			$table->unsignedBigInteger('idpermintaan_layanan');
			$table->string('kode_barang_aset');

			$table->timestamps();
			$table->unsignedBigInteger('created_by')->nullable();
			$table->unsignedBigInteger('updated_by')->nullable();
			$table->string('created_ip', length:50)->nullable();
			$table->string('updated_ip', length:50)->nullable();
		} );

	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('user');
		Schema::dropIfExists('jenjang');
		Schema::dropIfExists('fakultas');
		Schema::dropIfExists('program_studi');
		Schema::dropIfExists('unit_kerja');
		Schema::dropIfExists('ruang');
		Schema::dropIfExists('role');
		Schema::dropIfExists('role_user');
		Schema::dropIfExists('layanan');
		Schema::dropIfExists('aset');
		Schema::dropIfExists('layanan_aset');
		Schema::dropIfExists('permintaan_layanan');
		Schema::dropIfExists('riwayat_pemakaian_aset');
	}
};
