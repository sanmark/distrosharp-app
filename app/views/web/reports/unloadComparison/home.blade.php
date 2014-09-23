@extends('web._templates.template')

@section('body')

<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">View Unload Comparison</h3>
	</div>
	<div class="panel-body">
		<div class="panel panel-default">
		{{Form::open(['class'=>'form-inline', 'role'=>'form'])}}
		<br />
		<div class="form-group inline-form">
			{{Form::label('from_date', null, array('class' => 'control-label'))}}
			{{Form::input('datetime-local','from_date_time',$fromDate, array('class' => 'form-control'))}}
		</div>
		<div class="form-group inline-form">
			{{Form::label('to_date', null, array('class' => 'control-label'))}}
			{{Form::input('datetime-local','to_date_time',$toDate, array('class' => 'form-control'))}}
		</div>
		<div class="form-group inline-form">
			{{Form::label('from_stock', null, array('class' => 'control-label'))}}
			{{Form::select('from_stock',$fromVehicleSelect,$fromStock, array('class' => 'form-control'))}}
		</div>
		<div class="form-group inline-form">
			{{Form::label('to_stock', null, array('class' => 'control-label'))}}
			{{Form::select('to_stock',$toVehicleSelect,$toStock, array('class' => 'form-control'))}}
		</div>
		<div class="form-group inline-form">
			{{Form::submit('Submit', array('class' => 'btn btn-default pull-right'))}}
		</div>
		{{Form::close()}}
		<br/>
		</div>
		<table class="table table-bordered" style="width:40%;">
			<tr>
				<th>Id</th>
				<th>Date/Time</th>
				<th>From</th>
				<th>To</th>
			</tr>
			{{Form::open()}}
			@foreach($transferRows as $unloadDetail)
			<tr>
				<td>{{$unloadDetail->id}}</td>
				<td>{{HTML::link(URL::action('reports.unloadComparison.view',[$unloadDetail->id]),$unloadDetail->date_time)}}</td>
				<td>{{$unloadDetail->fromStock->name}}</td>
				<td>{{$unloadDetail->toStock->name}}</td>
			</tr>
			@endforeach
			{{Form::close()}}
		</table>
	</div>
</div>
@stop