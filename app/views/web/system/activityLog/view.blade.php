@extends('web._templates.template')

@section('body')
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Activity Log</h3>
	</div>
	<div class="panel-body">
		<div class="panel panel-default">
			<div class="panel-body">
				{{Form::open(['class'=>'form-inline', 'role'=>'form'])}} 
				<div class="form-group bottom-space">
					{{Form::label('user',null,array('class' => 'control-label'))}} 
					{{Form::select('user',$users,$user, array('class' => 'form-control'))}}
				</div>     
				<div class="form-group bottom-space">
					{{Form::label('from_time',null,array('class' => 'control-label'))}}
					{{Form::input('datetime-local', 'from_time', $from_time,array('class' => 'form-control','required'=>true))}}
				</div>
				<div class="form-group bottom-space">
					{{Form::label('to_time',null,array('class' => 'control-label'))}}
					{{Form::input('datetime-local', 'to_time', $to_time,array('class' => 'form-control','required'=>true))}}
				</div> 
				<div class="form-group bottom-space">
					{{Form::submit('Submit',array('class' => 'btn btn-default pull-right'))}}
				</div>
				{{Form::close()}}
			</div> 
		</div>
		<br/>  
		@if($activityLogs->isEmpty())

		<h4 class="text-center">There are no records to display...</h4>

		@else 
		<table class="table table-striped">
			<tr>
				<th>Time</th>
				<th>User Name</th>
				<th>Message</th>
				<th>Url</th> 
			</tr> 
			@foreach($activityLogs as $activityLog)
			<tr>
				<td>{{$activityLog->date_time}}</td>
				<td>{{$activityLog->user->username}}</td>
				<td>{{$activityLog->message}}</td>
				<td>{{$activityLog->url}}</td>
			</tr>
			@endforeach
		</table> 

	@endif
	</div>
</div>
@stop

@section('file-footer')

@stop