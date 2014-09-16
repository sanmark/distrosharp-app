@extends('web._templates.template')

@section('body')

<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">View Transfers</h3>
	</div>
	<div class="panel-body">

		<div class="panel panel-default">
			<div class="panel-body">

				{{Form::open(['class'=>'form-inline', 'role'=>'form'])}}
				<div class="form-group inline-form">
					{{Form::label('from_stock_id', null, array('class' => 'control-label'))}}
					{{Form::select('from_stock_id', $stocks, $fromStockId, array('class' => 'form-control'))}}
				</div>
				<div class="form-group inline-form">
					{{Form::label('to_stock_id', null, array('class' => 'control-label'))}}
					{{Form::select('to_stock_id', $stocks, $toStockId, array('class' => 'form-control'))}}
				</div>
				<div class="form-group inline-form">
					{{Form::label('date_time_from', null, array('class' => 'control-label'))}}
					{{Form::input('datetime-local','date_time_from', $dateTimeFrom, array('class' => 'form-control'))}}
				</div>
				<div class="form-group inline-form">
					{{Form::label('date_time_to', null, array('class' => 'control-label'))}}
					{{Form::input('datetime-local','date_time_to', $dateTimeTo, array('class' => 'form-control'))}}
				</div>
				<div class="form-group inline-form">
					{{Form::submit('Submit', array('class' => 'btn btn-default pull-right'))}}
				</div>
				{{Form::close()}}

			</div>
		</div>

		<br/>

		<table class="table table-striped">
			<tr>
				<th>ID</th>
				<th>From Stock</th>
				<th>To Stock</th>
				<th>Datetime</th>
				<th>Description</th>
			</tr>
			<tbody>
				@foreach($transfers as $transfer)
				<tr>
					<td>{{$transfer->id}}</td>
					<td>{{$transfer->fromStock->name}}</td>
					<td>{{$transfer->toStock->name}}</td>
					<td>{{$transfer->date_time}}</td>
					<td>{{$transfer->description}}</td>
				</tr>
				@endforeach
			</tbody>
		</table>

	</div>
</div>

@stop