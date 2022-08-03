<div class="vertical-menu">
	<div data-simplebar class="h-100">
		<div id="sidebar-menu">
			<ul class="nav flex-column">
				<li class="nav-item">
					<hr/>
				</li>
				<li class="nav-item">
					<a href="{{ route('dashboard') }}" class="nav-link">
						<i class="dripicons-meter"></i>
						<span> Dashboard </span>
					</a>
				</li>
				<li class="nav-item">
					<hr/>
				</li>
				<li class="nav-item">
					<a href="javascript: void(0);" class="has-arrow nav-link">
						<i class="dripicons-user-group"></i>
						<span> Users </span>
					</a>
					<ul class="sub-menu" aria-expanded="false">
{{--						<li><a href="{{ route('users') }}" class="nav-link">View Users</a></li>--}}
{{--						<li><a href="{{ route('register_user') }}" class="nav-link">Register User</a></li>--}}

						{{-- <li><a href="{{ route('add_asset_variants') }}">Add Asset Variant</a></li>
						<li><a href="{{ route('asset_variants') }}">Asset Variants</a></li> --}}
					</ul>
				</li>
				<li class="nav-item">
					<hr/>
				</li>
			</ul>

		</div>
	</div>
</div>
