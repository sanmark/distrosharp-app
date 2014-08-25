@extends('web._templates.template')

@section('body')
<h2>Stocks</h2>
<table border="1">
	<tr>
		<th>Name</th>
		<th>In-charge</th>
		<th>Stock Type</th>
		<th></th>
	</tr>
	@foreach($stocks as $stock)
	<tr>
		<td>{{HTML::link ( URL::action ( 'stocks.view', [$stock->id]), $stock->name)}}</td>
		<td>{{$stock->incharge->username}}</td>
		<td>{{$stock->stockType->label}}</td>
		<td>{{HTML::link ( URL::action ( 'stocks.edit', [$stock->id]), 'Edit...')}}</td>
	</tr>
	@endforeach
</table>
@stop