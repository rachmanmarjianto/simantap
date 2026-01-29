		<div class="deznav">
			<div class="deznav-scroll">
				<ul class="metismenu" id="menu">
					<li class="nav-label first pt-0">
						<table>
							<tr>
								<td><b>{{ session()->get('userdata')['gelar_depan'] }} {{ session()->get('userdata')['nama'] }} {{ session()->get('userdata')['gelar_belakang'] }}</b></td>
							</tr>
							<tr>
								<td>< {{ session()->get('userdata')['nama_role'] }} ></td>
							</tr>
							@if(session()->get('userdata')['idrole'] != 1)
							<tr>
								<td>< {{ session()->get('userdata')['nama_unit_kerja'] }} ></td>
							</tr>
							@endif
						</table>
					</li>
					<li class="nav-label">Main Menu</li>
					<li class="mega-menu mega-menu-lg @if($menu == 'home') mm-active @endif">
						<a class=" ai-icon" href="{{ route('home') }}" aria-expanded="false">
							<svg id="icon-home" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" style="stroke-dasharray: 66px, 86px; stroke-dashoffset: 0px;"></path><path d="M9,22L9,12L15,12L15,22" style="stroke-dasharray: 26px, 46px; stroke-dashoffset: 0px;"></path></svg>
							<span class="nav-text">Home</span>
						</a>
					</li>
					{{-- <li class="mega-menu mega-menu-lg">
						<a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
							<svg id="icon-setting" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
							<span class="nav-text">Setting</span>
						</a>
						<ul aria-expanded="false">
							<li><a href = "{{ route('ubah_password') }}">Ubah Password</a></li>
							<li><a href = "{{ route('ubah_role') }}">Pindah Role</a></li>
							<li><a href = "{{ route('logout') }}">Log Out</a></li>
						</ul>
					</li> --}}

					{{--
						Bisa menggunakan icon SVG apapun, jangan lupa tambahkan id="icon-manajemen-alat" !!!
					--}}
					{{-- @if( session()->get('userdata')['idrole'] == '2')
					<li class="mega-menu mega-menu-lg">
						<a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
							<svg id="icon-manajemen-alat" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
							<span class="nav-text">Manajemen Alat</span>
						</a>
						<ul aria-expanded="false">
							<li><a href = "{{ route('pemakaian_aset_index') }}">Pemakaian Aset</a></li>
						</ul>
					</li>
					@endif --}}
					@if( session()->get('userdata')['idrole'] == '1' || session()->get('userdata')['idrole'] == '2' )
					<li class="mega-menu mega-menu-lg @if($menu == 'master') mm-active @endif">
						<a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
							<svg id="icon-master" xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-file-stack"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M5 12v-7a2 2 0 0 1 2 -2h7l5 5v4" /><path d="M5 21h14" /><path d="M5 18h14" /><path d="M5 15h14" /></svg>
							<span class="nav-text">Master</span>
						</a>
						<ul aria-expanded="false" @if($menu == 'master') class="mm-collapse mm-show" @endif>
							{{-- <li><a href="{{ route('role_index') }}">Role</a></li>	 --}}
							<li @if($submenu == 'user') class="mm-active" @endif><a @if($submenu == 'user') class="mm-active" @endif href="{{ route('user_index') }}">User</a></li>
							@if(session()->get('userdata')['idrole'] == '1')
								<li @if($submenu == 'unitkerja') class="mm-active" @endif><a @if($submenu == 'unitkerja') class="mm-active" @endif href="{{ route('unitkerja_index') }}">Unit Kerja</a></li>
							@endif
							{{-- <li><a href="{{ route('role_user_index') }}">Role User</a></li>
							<li><a href="{{ route('fakultas_index') }}">Fakultas</a></li>
							<li><a href="{{ route('jenjang_index') }}">Jenjang</a></li>
							<li><a href="{{ route('program_studi_index') }}">Program Studi</a></li> --}}
							
							<li @if($submenu == 'alatlab') class="mm-active" @endif><a @if($submenu == 'alatlab') class="mm-active" @endif href="{{ route('aset_index') }}">Alat Lab</a></li>
							<li @if($submenu == 'maintenance_alat') class="mm-active" @endif><a @if($submenu == 'maintenance_alat') class="mm-active" @endif href="{{ route('maintenance_alat_index') }}">Maintenance Alat</a></li>
							<li @if($submenu == 'form_maintenance') class="mm-active" @endif><a @if($submenu == 'form_maintenance') class="mm-active" @endif href="{{ route('form_maintenance_index') }}">Form Maintenance</a></li>
							@if(session('userdata')['layanan'] == true || session()->get('userdata')['idrole'] == '1' )
							<li @if($submenu == 'layanan') class="mm-active" @endif><a @if($submenu == 'layanan') class="mm-active" @endif href="{{ route('layanan_index') }}">Layanan</a></li>
							<li @if($submenu == 'layanan_aset') class="mm-active" @endif><a @if($submenu == 'layanan_aset') class="mm-active" @endif href="{{ route('layanan_aset_index') }}">Layanan-Alat</a></li>
							<li @if($submenu == 'layanan_operator') class="mm-active" @endif><a @if($submenu == 'layanan_operator') class="mm-active" @endif href="{{ route('layanan_operator_index') }}">Layanan-Operator</a></li>
							@endif
							<li @if($submenu == 'api_aplikasi') class="mm-active" @endif><a @if($submenu == 'api_aplikasi') class="mm-active" @endif href="{{ route('api_aplikasi_index') }}">API Aplikasi</a></li>
							{{-- <li><a href="{{ route('unit_kerja_index') }}">Unit Kerja</a></li>
							<li><a href="{{ route('ruang_index') }}">Ruang</a></li>
							<li><a href="{{ route('permintaan_layanan_index') }}">Permintaan Layanan</a></li> --}}
							
						</ul>
					</li>
					@endif

					@if( session()->get('userdata')['idrole'] == '4')
					<li class="mega-menu mega-menu-lg @if($menu == 'master') mm-active @endif">
						<a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
							<svg id="icon-master" xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-file-stack"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M5 12v-7a2 2 0 0 1 2 -2h7l5 5v4" /><path d="M5 21h14" /><path d="M5 18h14" /><path d="M5 15h14" /></svg>
							<span class="nav-text">Master</span>
						</a>
						<ul aria-expanded="false" @if($menu == 'master') class="mm-collapse mm-show" @endif>
							<li @if($submenu == 'ruanganpj') class="mm-active" @endif><a @if($submenu == 'ruanganpj') class="mm-active" @endif href="{{ route('pj_ruang_ruanganpj') }}">PJ Ruang</a></li>
							<li @if($submenu == 'alatlab') class="mm-active" @endif><a @if($submenu == 'alatlab') class="mm-active" @endif href="{{ route('aset_index') }}">Alat Lab</a></li>
							<li @if($submenu == 'maintenance_alat') class="mm-active" @endif><a @if($submenu == 'maintenance_alat') class="mm-active" @endif href="{{ route('maintenance_alat_index') }}">Maintenance Alat</a></li>
													
						</ul>
					</li>
					@endif

					@if( session()->get('userdata')['idrole'] == '2' || session()->get('userdata')['idrole'] == '3' || session()->get('userdata')['idrole'] == '4' )
						
						@if(session('userdata')['layanan'] == true )
						<li class="mega-menu mega-menu-lg @if($menu == 'transaksi') mm-active @endif">
							<a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
								<svg id="icon-forms" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" style="stroke-dasharray: 66, 86; stroke-dashoffset: 0;"></path><path d="M14,2L14,8L20,8" style="stroke-dasharray: 12, 32; stroke-dashoffset: 0;"></path><path d="M16,13L8,13" style="stroke-dasharray: 8, 28; stroke-dashoffset: 0;"></path><path d="M16,17L8,17" style="stroke-dasharray: 8, 28; stroke-dashoffset: 0;"></path><path d="M10,9L9,9L8,9" style="stroke-dasharray: 2, 22; stroke-dashoffset: 0;"></path></svg>
								<span class="nav-text">Layanan</span>
							</a>
							<ul aria-expanded="false" @if($menu == 'transaksi') class="mm-collapse mm-show" @endif>
								<li @if($submenu == 'permintaan_layanan') class="mm-active" @endif><a @if($submenu == 'permintaan_layanan') class="mm-active" @endif href="{{ route('permintaan_layanan_index_admin') }}">Permintaan Layanan</a></li>
															
							</ul>
						</li>
						@endif

						@if(session('userdata')['penelitian'] == true )
						<li class="mega-menu mega-menu-lg @if($menu == 'penelitian') mm-active @endif">
							<a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
								<svg id="icon-bookmark" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bookmark"><path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/></svg>
								<span class="nav-text">Penelitian</span>
							</a>
							<ul aria-expanded="false" @if($menu == 'penelitian') class="mm-collapse mm-show" @endif>
								<li @if($submenu == 'sub_penelitian') class="mm-active" @endif><a @if($submenu == 'sub_penelitian') class="mm-active" @endif href="{{ route('penelitian_index') }}">Penelitian</a></li>
															
							</ul>
						</li>
						@endif

						@if(session('userdata')['praktikum'] == true )
						<li class="mega-menu mega-menu-lg @if($menu == 'praktikum') mm-active @endif">
							<a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
								<svg id="icon-edit-3" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-3"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
								<span class="nav-text">Praktikum</span>
							</a>
							<ul aria-expanded="false" @if($menu == 'praktikum') class="mm-collapse mm-show" @endif>
								<li @if($submenu == 'sub_praktikum') class="mm-active" @endif><a @if($submenu == 'sub_praktikum') class="mm-active" @endif href="{{ route('praktikum_index') }}">Praktikum</a></li>
															
							</ul>
						</li>
						@endif

						

						<li class="mega-menu mega-menu-lg @if($menu == 'proses_maintenance') mm-active @endif">
							<a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
								<svg id="icon-plugins" viewBox="0 0 24 24" width="64" height="64" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><path width="16" height="16" d="M4 4 L20 4 L20 20 L4 20 Z" style="stroke-dasharray: 64px, 84px; stroke-dashoffset: 0px;"></path><path width="6" height="6" d="M9 9 L15 9 L15 15 L9 15 Z" style="stroke-dasharray: 24px, 44px; stroke-dashoffset: 0px;"></path><path d="M9,1L9,4" style="stroke-dasharray: 3px, 23px; stroke-dashoffset: 0px;"></path><path d="M15,1L15,4" style="stroke-dasharray: 3px, 23px; stroke-dashoffset: 0px;"></path><path d="M9,20L9,23" style="stroke-dasharray: 3px, 23px; stroke-dashoffset: 0px;"></path><path d="M15,20L15,23" style="stroke-dasharray: 3px, 23px; stroke-dashoffset: 0px;"></path><path d="M20,9L23,9" style="stroke-dasharray: 3px, 23px; stroke-dashoffset: 0px;"></path><path d="M20,14L23,14" style="stroke-dasharray: 3px, 23px; stroke-dashoffset: 0px;"></path><path d="M1,9L4,9" style="stroke-dasharray: 3px, 23px; stroke-dashoffset: 0px;"></path><path d="M1,14L4,14" style="stroke-dasharray: 3px, 23px; stroke-dashoffset: 0px;"></path></svg>
								<span class="nav-text">Maintenance</span>
							</a>
							<ul aria-expanded="false" @if($menu == 'proses_maintenance') class="mm-collapse mm-show" @endif>
								<li @if($submenu == 'maintenance_aset') class="mm-active" @endif><a @if($submenu == 'maintenance_aset') class="mm-active" @endif href="{{ route('proses_maintenance_index') }}">Maintenance Aset</a></li>
															
							</ul>
						</li>
					@endif

					@if( session()->get('userdata')['idrole'] == '1' || session()->get('userdata')['idrole'] == '2' )
					<li class="mega-menu mega-menu-lg @if($menu == 'report') mm-active @endif">
						<a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
							<svg id="icon-charts" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bar-chart"><path d="M12,20L12,10" style="stroke-dasharray: 10px, 30px; stroke-dashoffset: 0px;"></path><path d="M18,20L18,4" style="stroke-dasharray: 16px, 36px; stroke-dashoffset: 0px;"></path><path d="M6,20L6,16" style="stroke-dasharray: 4px, 24px; stroke-dashoffset: 0px;"></path></svg>
							<span class="nav-text">Report</span>
						</a>
						<ul aria-expanded="false" @if($menu == 'report') class="mm-collapse mm-show" @endif>
							<li @if($submenu == 'report_alat') class="mm-active" @endif><a @if($submenu == 'report_alat') class="mm-active" @endif href="{{ route('report_penggunaan_alat') }}">Penggunaan Alat</a></li>
							<li @if($submenu == 'report_operator') class="mm-active" @endif><a @if($submenu == 'report_operator') class="mm-active" @endif href="{{ route('report_operator') }}">Operator</a></li>						
						</ul>
					</li>
					@endif

					
				</ul>
			</div>
		</div>