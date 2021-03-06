@extends('web._templates.template')

@section('body')

<div class="panel panel-default well">
	<div class="panel-heading">
		<div class="panel-title">
			<span>View Transfers</span>
			{{HTML::link ( URL::action ( 'processes.transfers.selectStocksInvolved') ,'Add New Transfer',['class' => 'panel-title-btn btn btn-success btn-sm pull-right'] )}}
		</div>
	</div>
	<div class="panel-body">

		<div class="panel panel-default">
			<div class="panel-body">

				{{Form::open(['class'=>'form-inline', 'role'=>'form'])}}
				<div class="form-group inline-form">
					{{Form::label('from_stock_id', null, array('class' => 'control-label'))}}
					{{Form::select('from_stock_id', $stocks, $fromStockId, array('tabindex' => '1', 'class' => ''))}}
				</div>
				<div class="form-group inline-form">
					{{Form::label('to_stock_id', null, array('class' => 'control-label'))}}
					{{Form::select('to_stock_id', $stocks, $toStockId, array('tabindex' => '2', 'class' => ''))}}
				</div>
				<div class="form-group inline-form">
					{{Form::label('date_time_from', null, array('class' => 'control-label'))}}
					{{Form::input('datetime-local','date_time_from', $dateTimeFrom, array('tabindex' => '3', 'class' => ''))}}
				</div>
				<div class="form-group inline-form">
					{{Form::label('date_time_to', null, array('class' => 'control-label'))}}
					{{Form::input('datetime-local','date_time_to', $dateTimeTo, array('tabindex' => '4', 'class' => ''))}}
				</div>
				<div class="form-group inline-form">
					{{Form::submit('Submit', array('tabindex' => '5', 'class' => 'btn btn-primary pull-right'))}}
				</div>
				{{Form::close()}}

			</div>
		</div>

		<br/>
		@if(count($transfers)==0)
		<br>
		<div class="no-records-message text-center">
			There are no records to display
		</div>
		<br>
		@else
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
					<td>{{HTML::link ( URL::action ('processes.transfers.view',[$transfer->id] ),$transfer->id )}}</td>
					<td>{{$transfer->fromStock->name}}</td>
					<td>{{$transfer->toStock->name}}</td>
					<td>{{$transfer->date_time}}</td>
					<td>{{$transfer->description}}</td>
				</tr>
				@endforeach
			</tbody>
		</table>
		@endif
	</div>
</div>

@stop