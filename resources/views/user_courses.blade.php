@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">User courses</div>

                <div class="panel-body">
                    @if ($user_courses)
						<table class="table">
						  <thead>
							<tr>
							  <td>Username</td>
							  <td>Data</td>


							</tr>
						  </thead>
						  <tbody>
							@foreach($user_courses as $user)
								<tr>
								  <td>{{$user['user_name']}}</td>
								  <td>
									<table class="table">
									  <thead>
										<tr>
										  <td>Course</td>
										  <td>Data</td>
										</tr>
									  </thead>
									  <tbody>
										@foreach($user['course'] as $course)
											<tr>
											  <td>{{$course['course_name']}}</td>
											  <td>
												<table class="table">
												  <thead>
													<tr>
													  <td>Item</td>
													  <td>Grade</td>
													</tr>
												  </thead>
												  <tbody>
													@foreach($course['item'] as $item)
														<tr>
														  <td>{{$item['item_name']}}</td>
														  <td>{{$item['grade']}}</td>
														</tr>
													@endforeach
												  </tbody>
												</table>
											  </td>
											  
											</tr>
										@endforeach
									  </tbody>
									</table>
								  </td>
								  
								</tr>
							@endforeach
						  </tbody>
						</table>
                        
					@else
						No user courses
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
