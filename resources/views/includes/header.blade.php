<header id="page-topbar">
	<div class="d-flex">
		<div class="navbar-brand-box text-center">
			<a href="{{route('dashboard')}}" class="logo logo-light">
                <span class="logo-sm">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="" height="22">
                </span>
				<span class="logo-lg">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="" height="50">
                </span>
			</a>
		</div>
		<div class="navbar-header">
{{--			<button type="button" class="button-menu-mobile waves-effect" id="vertical-menu-btn">--}}
{{--				<i class="mdi mdi-menu"></i>--}}
{{--			</button>--}}
			<div class="d-flex ms-auto">
				<div class="dropdown d-inline-block">
					<button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<span class="d-none d-md-inline-block ms-1">{{auth()->user()->name}} <i class="mdi mdi-chevron-down"></i> </span>
					</button>
					<div class="dropdown-menu dropdown-menu-end">
						<ul class="p-0 m-0">
							<li class="dropdown-item p-0 m-0">
								<form id="logout-form" action="{{ route('logout') }}" method="POST">
									@csrf
									<button class="btn btn-soft-light">Logout</button>
								</form>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</header>

