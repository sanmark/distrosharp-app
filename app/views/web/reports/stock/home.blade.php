@extends('web._templates.template')
@section('body')

<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Stock Report</h3>
	</div>
	<div class="panel-body">

		<div class="row">
			{{Form::open()}}
			<div class="col-md-2">Select Stock</div>
			<div class="col-md-2">{{Form::select('stock_id', $stockSelect,$stockId,array('class'=>'form-control'));}}</div>
			<div class="col-md-1">{{Form::submit('submit', array('class' => 'btn btn-default pull-right'))}}</div>
			{{Form::close()}}
		</div>
		<div class="row">
			<br>
			<div class="col-md-12">
				<table class="table table-bordered">
					<tr>
						<th class="text-center">Item</th>
						<th class="text-center">Good Quantity</th>
						<th class="text-center">Return Quantity</th>
						<th class="text-center">Good Quantity Value</th>
						<th class="text-center">Return Quantity Value</th>
						<th class="text-center">Total Value</th>
					</tr>
					@if(isset($calculatedStockValues))
					@foreach ($calculatedStockValues as $item)
					<tr>
						<td>{{$item->item->name}}</td>
						<td>{{$item->good_quantity}}</td>
						<td>{{$item->return_quantity}}</td>
						<td class="text-right">{{$item->good_quantity_value}}</td>
						<td class="text-right">{{$item->return_quantity_value}}</td>
						<td class="text-right">{{$item->total_value}}</td>
					</tr>
					@endforeach
					@endif
					<tr>
						@if(isset($good_quantity_value_total))
						<td colspan="3"></td>
						<td class="text-right"><b>{{$good_quantity_value_total}}</b></td>
						<td class="text-right"><b>{{$return_quantity_value_total}}</b></td>
						<td class="text-right"><b>{{$grandTotal}}</b></td>
						@endif
					</tr>
				</table>
			</div>
		</div>
	</div>
</div>

@stop