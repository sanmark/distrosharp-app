@extends('web._templates.template')

@section('body')

<div class="panel panel-default well">
	<div class="panel-heading">
		<h3 class="panel-title">View Stock &nbsp;<b>{{$stock->name}}</b></h3>
	</div>
	<div class="panel-body">
		{{Form::open(['class'=>'form-horizontal', 'role'=>'form'])}}
		<p>In-charge: <b>{{$stock['incharge']['username']}}.</b> &nbsp; Stock Type: <b>{{$stock->stockType->label}}.</b> &nbsp;{{HTML::link(URL::action('stocks.edit', [$stock->id]), 'Edit...')}}</p>
		<p>Last Confirmed Date :<b>{{$lastConfirmedDate}}</b></p><br/>

		<table class="table table-striped" style="width: 50%;">
			<tr>
				<th>Item</th>
				<th class="text-right">Good Qnt</th>
				<th class="text-right">Return Qnt</th>
				<th class="text-right">Good Qnt. Weight (Kg)</th>
			</tr>
			<tbody>
				@foreach($stockDetails as $stockDetail)
				<tr>
					<td>{{$stockDetail->item->name}}</td>
					<td class="text-right">{{$stockDetail->good_quantity}}</td>
					<td class="text-right">{{$stockDetail->return_quantity}}</td>
					<?php $total_weight = ($stockDetail->item->weight*$stockDetail->good_quantity)/1000; ?>
					<td class="text-right">{{number_format($total_weight,2)}}</td>
				</tr>
				@endforeach
				<tr>
					<td colspan="4" class="text-right">{{Form::submit('Confirm Stock',['class'=>'btn btn-danger pull-right'])}}</td>
				</tr>
			</tbody>
		</table>
		{{Form::close()}}
	</div>
</div>

@stop