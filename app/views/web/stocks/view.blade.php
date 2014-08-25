@extends('web._templates.template')

@section('body')
<h2>View Stock "{{$stock->name}}"</h2>
<p><b>In-charge:</b> {{$stock->incharge->username}}. <b>Stock Type:</b> {{$stock->stockType->label}}. {{HTML::link(URL::action('stocks.edit', [$stock->id]), 'Edit...')}}</p>

<table border="1">
	<tr>
		<th>Item</th>
		<th>Good Quantity</th>
		<th>Return Quantity</th>
	</tr>
	@foreach($stock->stockDetails as $stockDetail)
	<tr>
		<td>{{$stockDetail->item->name}}</td>
		<td>{{$stockDetail->good_quantity}}</td>
		<td>{{$stockDetail->return_quantity}}</td>
	</tr>
	@endforeach
</table>
@stop