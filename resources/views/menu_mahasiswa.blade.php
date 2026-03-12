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

					@if(session('userdata')['penelitian'] == true )
						<li class="mega-menu mega-menu-lg @if($menu == 'penelitian') mm-active @endif">
							<a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
								<svg id="icon-bookmark" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bookmark"><path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/></svg>
								<span class="nav-text">Penelitian</span>
							</a>
							<ul aria-expanded="false" @if($menu == 'penelitian') class="mm-collapse mm-show" @endif>
								<li @if($submenu == 'sub_penelitian') class="mm-active" @endif><a @if($submenu == 'sub_penelitian') class="mm-active" @endif href="{{ route('penelitian_mhs_index') }}">Penelitian</a></li>
															
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
								<li @if($submenu == 'sub_praktikum') class="mm-active" @endif><a @if($submenu == 'sub_praktikum') class="mm-active" @endif href="{{ route('praktikum_mhs_index') }}">Praktikum</a></li>
															
							</ul>
						</li>
					@endif

					
				</ul>
			</div>
		</div>