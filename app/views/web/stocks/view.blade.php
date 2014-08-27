@extends('web._templates.template')

@section('body')

<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">View Stock &nbsp;<b>{{$stock->name}}</b></h3>
	</div>
	<div class="panel-body">

		<p>In-charge: <b>{{$stock->incharge->username}}.</b> &nbsp; Stock Type: <b>{{$stock->stockType->label}}.</b> &nbsp;{{HTML::link(URL::action('stocks.edit', [$stock->id]), 'Edit...')}}</p><br/>

		<table class="table table-striped" style="width: 50%;">
			<tr>
				<th>Item</th>
				<th>Good Quantity</th>
				<th>Return Quantity</th>
			</tr>
			<tbody>
				@foreach($stock->stockDetails as $stockDetail)
				<tr>
					<td>{{$stockDetail->item->name}}</td>
					<td>{{$stockDetail->good_quantity}}</td>
					<td>{{$stockDetail->return_quantity}}</td>
				</tr>
				@endforeach
			</tbody>
		</table>

	</div>
</div>

@stop