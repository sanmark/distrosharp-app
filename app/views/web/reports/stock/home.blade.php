@extends('web._templates.template')
@section('body')

<div class="panel panel-default well">
	<div class="panel-heading">
		<h3 class="panel-title">Stock Report</h3>
	</div>
	<div class="panel-body">

		<div class="panel panel-default">
			<div class="panel-body">
				{{Form::open(['class'=>'form-inline', 'role'=>'form'])}}
				<div class="form-group inline-form">
					{{Form::label('select_stock',null,array('class' => 'control-label'))}} 
					{{Form::select('stock_id', $stockSelect,$stockId,array('tabindex' => '1', 'class'=>''));}}
				</div>
				<div class="form-group inline-form">
					{{Form::submit('submit', array('tabindex' => '2', 'class' => 'btn btn-primary pull-right'))}}</div>
				{{Form::close()}}
			</div>
		</div>
		@if( $viewData)
		<br/>
		{{Form::open(['class'=>'form-inline', 'role'=>'form','action'=>'reports.stocks.confirm'])}}
		{{Form::hidden('stock_id',$stockId)}}
		<table class="table table-striped">
			<thead>
				<tr>
					<th>Item Code</th>
					<th>Item Name</th>
					<th class="text-right">Good Qnt</th>
					<th class="text-right">Return Qnt</th>
					<th class='text-right'>Good Qnt. Weight(Kg)</th>
					<th class="text-right">Buying Value of Good Qnt</th>
					<th class="text-right">Selling Value of Good Qnt</th>
					<th class="text-right">Buying Value of Return Qnt</th>
					<th class="text-right">Selling Value of Return Qnt</th>
					<th class="text-right">Total Buying Value (G+R)</th>
					<th class="text-right">Total Selling Value (G+R)</th>
				</tr>
			</thead>
			<tbody> 
				@foreach ($stockDetails as $stock)
				<tr>
					<td>{{$stock['item']['code']}}</td>
					<td>{{$stock['item']['name']}}</td>
					<td class="text-right">{{number_format($stock['good_quantity'],2)}}</td>
					<td class="text-right">{{number_format($stock['return_quantity'],2)}}</td> 
					<td class="text-right">{{number_format($stock['total_weight'],2)}}</td>
					<td class="text-right">{{number_format($stock['good_quantity_buying_value'],2)}}</td>
					<td class="text-right">{{number_format($stock['good_quantity_selling_value'],2)}}</td>
					<td class="text-right">{{number_format($stock['return_quantity_buying_value'],2)}}</td>
					<td class="text-right">{{number_format($stock['return_quantity_selling_value'],2)}}</td>
					<td class="text-right">{{number_format($stock['total_buying_value'],2)}}</td>
					<td class="text-right">{{number_format($stock['total_selling_value'],2)}}</td>
				</tr>
				@endforeach 
				<tr> 
					<td colspan="4"><b>Total</b></td>
					<td class="text-right"><b>{{number_format($totals[ 'totalWeight' ],2)}}</b></td>
					<td class="text-right"><b>{{number_format($totals[ 'good_quantity_buying_value_total' ],2)}}</b></td>
					<td class="text-right"><b>{{number_format($totals[ 'good_quantity_selling_value_total' ],2)}}</b></td>
					<td class="text-right"><b>{{number_format($totals[ 'return_quantity_buying_value_total' ],2)}}</b></td>
					<td class="text-right"><b>{{number_format($totals[ 'return_quantity_selling_value_total' ],2)}}</b></td>

					<td class="text-right"><b>{{number_format($totals[ 'grandBuyingTotal' ],2)}}</b></td>
					<td class="text-right"><b>{{number_format($totals[ 'grandSellingTotal' ],2)}}</b></td>

				</tr>
				@if($confirmStock)
				<tr>
					<td colspan="11" class="text-right">{{Form::submit('Confirm Stock',['class'=>'btn btn-danger pull-right'])}}</td>
				</tr>
				@endif
			</tbody>
		</table>
		@else
		<h4 class="text-center">Please define a criteria and press "Submit".</h4>
		@endif
	</div>
</div>

@stop