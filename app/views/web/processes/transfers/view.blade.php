@extends('web._templates.template')

@section('body')
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">View Transfer</h3>
	</div>
	<div class="panel-body">
		<table class="table table-bordered" style="width:40%">
			<tr><td>Date/Time</td><td>{{$basicStockDetails->date_time}}</td></tr>
			<tr><td>From </td><td>{{$basicStockDetails->fromStock->name}}</td></tr>
			<tr><td>To</td><td>{{$basicStockDetails->toStock->name}}</td></tr>
			<tr><td>Description</td><td>{{$basicStockDetails->description}}</td></tr>
		</table>
		<br/>
		<table class="table table-striped" style="width:40%;">
			<tr>
				<th>Item</th>
				<th>Quantity</th>
			</tr>
			@foreach($transferData as $transferRow)
			<tr>
				<td>{{ $transferRow->item->name}}</td>
				<td>{{$transferRow->quantity}}</td>
			</tr>
			@endforeach
		</table>
	</div>
</div>
@stop