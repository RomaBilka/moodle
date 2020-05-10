@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Courses</div>

                <div class="panel-body">
                    @if ($courses)
						<table class="table">
						  <thead>
							<tr>
							  <td>Id</td>
							  <td>Name</td>

							</tr>
						  </thead>
						  <tbody>
							@foreach($courses as $cours)
								<tr>
								  <td>{{$cours['id']}}</td>
								  <td>{{$cours['fullname']}}</td>
								</tr>
							@endforeach
						  </tbody>
						</table>
                        
					@else
						No courses
                    @endif
					

					@include('layouts.courses_form');

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
