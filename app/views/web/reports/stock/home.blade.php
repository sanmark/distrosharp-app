@extends('web._templates.template')
@section('body')

<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Stock Report</h3>
	</div>
	<div class="panel-body">

		<div class="panel panel-default">
			<div class="panel-body">
				{{Form::open(['class'=>'form-inline', 'role'=>'form'])}}
				<div class="form-group inline-form">
					{{Form::label('select_stock',null,array('class' => 'control-label'))}} 
					{{Form::select('stock_id', $stockSelect,$stockId,array('tabindex' => '1', 'class'=>'form-control'));}}
				</div>
				<div class="form-group inline-form">
					{{Form::submit('submit', array('tabindex' => '2', 'class' => 'btn btn-default pull-right'))}}</div>
				{{Form::close()}}
			</div>
		</div>
		@if( $viewData)
		<br/>
		<table class="table table-striped">
			<thead>
				<tr>
					<th>Item</th>
					<th class="text-right">Good Qnt</th>
					<th class="text-right">Return Qnt</th>
					<th class='text-right'>Good Qnt. Weight(Kg)</th>
					<th class="text-right">Good Qnt Value</th>
					<th class="text-right">Return Qnt Value</th>
					<th class="text-right">Total Value</th>
				</tr>
			</thead>
			<tbody> 
				@foreach ($stockDetails as $stock)
				<tr>
					<td>{{$stock['item']['name']}}</td>
					<td class="text-right">{{number_format($stock['good_quantity'],2)}}</td>
					<td class="text-right">{{number_format($stock['return_quantity'],2)}}</td> 
					<td class="text-right">{{number_format($stock['total_weight'],2)}}</td>
					<td class="text-right">{{number_format($stock['good_quantity_value'],2)}}</td>
					<td class="text-right">{{number_format($stock['return_quantity_value'],2)}}</td>
					<td class="text-right">{{number_format($stock['total_value'],2)}}</td>
				</tr>
				@endforeach 
				<tr> 
					<td colspan="3"><b>Total</b></td>
					<td class="text-right"><b>{{number_format($totals[ 'totalWeight' ],2)}}</b></td>
					<td class="text-right"><b>{{number_format($totals[ 'good_quantity_value_total' ],2)}}</b></td>
					<td class="text-right"><b>{{number_format($totals[ 'return_quantity_value_total' ],2)}}</b></td>

					<td class="text-right"><b>{{number_format($totals[ 'grandTotal' ],2)}}</b></td>

				</tr>
			</tbody>
		</table>
		@else
		<h4 class="text-center">Please define a criteria and press "Submit".</h4>
		@endif
	</div>
</div>

@stop