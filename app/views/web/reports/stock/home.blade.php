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
		<br/>

		<table class="table table-striped" style="width: 80%;">
			<thead>
				<tr>
					<th>Item</th>
					<th>Good Quantity</th>
					<th>Return Quantity</th>
					<th class="text-right">Good Quantity Value</th>
					<th class="text-right">Return Quantity Value</th>
					<th class="text-right">Total Value</th>
				</tr>
			</thead>
			<tbody>
				@if(isset($calculatedStockValues))
				@foreach ($calculatedStockValues as $item)
				<tr>
					<td>{{$item->item->name}}</td>
					<td>{{$item->good_quantity}}</td>
					<td>{{$item->return_quantity}}</td>
					<td class="text-right">{{number_format($item->good_quantity_value,2)}}</td>
					<td class="text-right">{{number_format($item->return_quantity_value,2)}}</td>
					<td class="text-right">{{number_format($item->total_value,2)}}</td>
				</tr>
				@endforeach
				@endif
				<tr>
					@if(isset($good_quantity_value_total))
					<td colspan="3">&nbsp;</td>
					<td class="text-right"><b>{{number_format($good_quantity_value_total,2)}}</b></td>
					<td class="text-right"><b>{{number_format($return_quantity_value_total,2)}}</b></td>
					<td class="text-right"><b>{{number_format($grandTotal,2)}}</b></td>
					@endif
				</tr>
			</tbody>
		</table>
	</div>
</div>

@stop