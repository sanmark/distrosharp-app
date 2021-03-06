@extends('web._templates.template')

@section('body')

<div class="panel panel-default well">
	<div class="panel-heading">
		<h3 class="panel-title">View Unload Comparison</h3>
	</div>
	<div class="panel-body">
		<div class="panel panel-default">
			<div class="panel-body">
				{{Form::open(['class'=>'form-inline', 'role'=>'form'])}}
				<div class="form-group inline-form">
					{{Form::label('from_date', null, array('class' => 'control-label'))}}
					{{Form::input('datetime-local','from_date_time',$fromDate, array('class' => ''))}}
				</div>
				<div class="form-group inline-form">
					{{Form::label('to_date', null, array('class' => 'control-label'))}}
					{{Form::input('datetime-local','to_date_time',$toDate, array('class' => ''))}}
				</div>
				<div class="form-group inline-form">
					{{Form::label('from_stock', null, array('class' => 'control-label'))}}
					{{Form::select('from_stock',$fromVehicleSelect,$fromStock, array('class' => ''))}}
				</div>
				<div class="form-group inline-form">
					{{Form::label('to_stock', null, array('class' => 'control-label'))}}
					{{Form::select('to_stock',$toVehicleSelect,$toStock, array('class' => ''))}}
				</div>
				<div class="form-group inline-form">
					{{Form::submit('Submit', array('class' => 'btn btn-primary pull-right'))}}
				</div>
				{{Form::close()}}
			</div>
		</div>
		<br/>
		@if(count($transferRows)==0)
		<h4 class="text-center">There are no records to display...</h4>
		@else
		<table class="table table-striped" style="width:80%;">
			<tr>
				<th>Id</th>
				<th>Date/Time</th>
				<th>From</th>
				<th>To</th>
				<th>Description</th>
			</tr>
			@foreach($transferRows as $unloadDetail)
			<tr>
				<td>{{$unloadDetail->id}}</td>
				<td>{{HTML::link(URL::action('reports.unloadComparison.view',[$unloadDetail->id]),$unloadDetail->date_time)}}</td>
				<td>{{$unloadDetail->fromStock->name}}</td>
				<td>{{$unloadDetail->toStock->name}}</td>
				<td>{{$unloadDetail->description}}</td>
			</tr>
			@endforeach
		</table>
		@endif
	</div>
</div>
@stop