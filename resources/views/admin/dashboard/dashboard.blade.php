@extends('layouts.admin.layout')
@section('title','Dashboard')
@section('content')
	<div class="row clearfix">
		<div class="col-md-12 mb-4">
			<div class="card">
				<div class="card-body">
					<h2 class="card-title">Dashboard</h2>
				</div>
			</div>
		</div>
	</div>

	<div class="row clearfix mb-4 text-center">
		<div class="col-md-3">
			<div class="card">
				<div class="card-body">
					<h5>Total Users</h5>
					<hr/>
					{{ $User_Count }}
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="card">
				<div class="card-body">
					<h5>Total Settleit Created</h5>
					<hr/>
					{{$Settleit_Count}}
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="card">
				<div class="card-body">
					<h5>Total Settleit Completed</h5>
					<hr/>
					{{$Settleit_Competed_Successfully}}
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="card">
				<div class="card-body">
					<h5>App Downloads</h5>
					<hr/>
					Coming Soon
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-12">
			<div class="card">
				<div class="card-body">
					<h4 class="mt-0 header-title">Latest Settleit Created</h4>
					<div class="table-responsive mt-4">
						<table class="table table-striped mb-0">
							<thead>
							<tr>
								<th scope="col">#</th>
								<th scope="col">ID</th>
								<th scope="col">Status</th>
								<th scope="col">Steps Completed</th>
								<th scope="col">Case Number or Details</th>
								<th scope="col">Creators Role</th>
								<th scope="col">Plaintiff ID</th>
								<th scope="col">Defendant ID</th>
							</tr>
							</thead>
							<tbody>
							@foreach ($Settleit as $Settleit_Data)
								<tr>
									<th scope="row">{{ $loop->index+1 }}</th>
									<td>{{ $Settleit_Data->id }}</td>
									<td>{{ $Settleit_Data->status }}</td>
									<td>{{ $Settleit_Data->step }}</td>
									<td class="text-center">
										@if ($Settleit_Data->case_number)
											{{ $Settleit_Data->case_number }}
										@else
											{{ $Settleit_Data->dispute_details }}
										@endif
									</td>
									<td>@if ($Settleit_Data->creator_role)
											{{ $Settleit_Data->creator_role }}
										@endif
									</td>
									<td>@if ($Settleit_Data->plaintiff)
											{{ $Settleit_Data->plaintiff }}
										@endif
									</td>
									<td>@if ($Settleit_Data->defendant)
											{{ $Settleit_Data->defendant }}
										@endif
									</td>
								</tr>
							@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-12">
			<div class="card">
				<div class="card-body">
					<h4 class="mt-0 header-title">Latest Users</h4>
					<div class="table-responsive mt-4">
						<table class="table table-striped mb-0">
							<thead>
							<tr>
								<th scope="col">#</th>
								<th scope="col">ID</th>
								<th scope="col">Name</th>
								<th scope="col">Email Address</th>
								<th scope="col" class="text-center">Account Status</th>
							</tr>
							</thead>
							<tbody>
							@foreach ($Users as $User)
								<tr>
									<th scope="row">{{ $loop->index+1 }}</th>
									<td>{{ $User->id }}</td>
									<td>{{ $User->full_name }}</td>
									<td>{{ $User->email }}</td>
									<td class="text-center">
										@if ($User->active == 1)
											<span class="badge bg-success">Active</span>
										@else
											<span class="badge bg-danger">In-Active</span>
										@endif
									</td>
								</tr>
							@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
