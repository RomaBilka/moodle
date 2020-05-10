@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Users</div>

                <div class="panel-body">
                    @if ($users)
						<table class="table">
						  <thead>
							<tr>
							  <td>Id</td>
							  <td>Username</td>
							  <td>First Name</td>
							  <td>Last Name</td>
							  <td>Email</td>

							</tr>
						  </thead>
						  <tbody>
							@foreach($users as $user)
								<tr>
								  <td>{{$user['id']}}</td>
								  <td>{{$user['username']}}</td>
								  <td>{{$user['firstname']}}</td>
								  <td>{{$user['lastname']}}</td>
								  <td>{{$user['email']}}</td>
								</tr>
							@endforeach
						  </tbody>
						</table>
                        
					@else
						No users
                    @endif
					
					@include('layouts.users_form');
					
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
