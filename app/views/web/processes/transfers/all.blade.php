@extends('web._templates.template')

@section('body')
<h2>View Transfers</h2>
{{Form::open()}}
<table>
	<tr>
		<td>{{Form::label('from_stock_id')}}</td>
		<td>{{Form::select('from_stock_id', $stocks, $fromStockId)}}</td>
	</tr>
	<tr>
		<td>{{Form::label('to_stock_id')}}</td>
		<td>{{Form::select('to_stock_id', $stocks, $toStockId)}}</td>
	</tr>
	<tr>
		<td>{{Form::label('date_time_from')}}</td>
		<td>{{Form::input('datetime-local','date_time_from', $dateTimeFrom)}}</td>
	</tr>
	<tr>
		<td>{{Form::label('date_time_to')}}</td>
		<td>{{Form::input('datetime-local','date_time_to', $dateTimeTo)}}</td>
	</tr>
	<tr>
		<td colspan="2">
			{{Form::submit('Submit')}}
		</td>
	</tr>
</table>
{{Form::close()}}

<table border="1">
	<tr>
		<th>ID</th>
		<th>From Stock</th>
		<th>To Stock</th>
		<th>Datetime</th>
		<th>Description</th>
	</tr>
	@foreach($transfers as $transfer)
	<tr>
		<td>{{$transfer->id}}</td>
		<td>{{$transfer->fromStock->name}}</td>
		<td>{{$transfer->toStock->name}}</td>
		<td>{{$transfer->date_time}}</td>
		<td>{{$transfer->description}}</td>
	</tr>
	@endforeach
</table>
@stop