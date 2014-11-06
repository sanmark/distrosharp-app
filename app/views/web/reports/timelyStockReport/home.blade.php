@extends('web._templates.template')

@section('body')

<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Timely Stock Report</h3>
	</div>
	<div class="panel-body">

		<div class="panel panel-default">
			<div class="panel-body">
				{{Form::open(['class'=>'form-inline', 'role'=>'form'])}}
				<div class="form-group inline-form">
					{{Form::label('from_date',null,array('class' => 'control-label'))}}
					{{Form::input('datetime-local','fromDate',$fromDate,['step'=>'1'])}}
				</div>
				<div class="form-group inline-form">
					{{Form::label('to_date',null,array('class' => 'control-label'))}}
					{{Form::input('datetime-local','toDate',$toDate,['step'=>'1'])}}
				</div>
				<div class="form-group inline-form">
					{{Form::submit('Submit',array('class' => 'btn btn-primary'))}}
				</div>
				{{Form::close()}}
			</div>
		</div>

		<br/>
		@if(count($items)==0)
		<p>There are no records to display</p>
		@else
		<table class="table table-striped">
			<tr>
				<th rowspan="2" class="text-center">Item Name</th>
				<th colspan="2" class="text-center">Opening Balance</th>
				<th colspan="2" class="text-center">Sold Total</th>
				<th colspan="2" class="text-center">Purchased Total</th>
				<th colspan="2" class="text-center">Good return quantity</th>
				<th colspan="2" class="text-center">Ending Balance</th>
			</tr>
			<tr>
				<th class="text-right">Qty</th>
				<th class="text-right">Cost</th>
				<th class="text-right">Qty</th>
				<th class="text-right">Cost</th>
				<th class="text-right">Qty</th>
				<th class="text-right">Cost</th>
				<th class="text-right">Qty</th>
				<th class="text-right">Cost</th>
				<th class="text-right">Qty</th>
				<th class="text-right">Cost</th>
			</tr>
			@foreach($items as $item)
			<tr>
				<td>{{$item->item->name}}</td>
				<td>
					@if(!isset($openingQuantities[$item->item_id]))
					None
					@else
					{{$openingQuantities[$item->item_id]}}
					@endif
				</td>
				<td class="text-right">
					@if(!isset($openingCost[$item->item_id]))
					None
					@else
					{{$openingCost[$item->item_id]}}
					@endif
				</td>
				<td class="text-right">
					@if(!isset($soldQuantities[$item->item_id]))
					None
					@else
					{{$soldQuantities[$item->item_id]}}
					@endif
				</td>
				<td class="text-right">
					@if(!isset($soldCost[$item->item_id]))
					None
					@else
					{{$soldCost[$item->item_id]}}
					@endif
				</td>
				<td class="text-right">
					@if(!isset($purchasedQuantities[$item->item_id]))
					None
					@else
					{{$purchasedQuantities[$item->item_id]}}
					@endif
				</td>
				<td class="text-right">
					@if(!isset($purchasedCost[$item->item_id]))
					None
					@else
					{{$purchasedCost[$item->item_id]}}
					@endif
				</td>
				<td class="text-right">
					@if(!isset($goodReturnQuantities[$item->item_id]))
					None
					@else
					{{$goodReturnQuantities[$item->item_id]}}
					@endif
				</td>
				<td class="text-right">
					@if(!isset($goodReturnQuantities[$item->item_id]))
					None
					@else
					{{$goodReturnCost[$item->item_id]}}
					@endif
				</td>
				<td class="text-right">
					@if(!isset($endingQuantities[$item->item_id]))
					None
					@else
					{{$endingQuantities[$item->item_id]}}
					@endif
				</td>
				<td class="text-right">
					@if(!isset($endingCost[$item->item_id]))
					None
					@else
					{{$endingCost[$item->item_id]}}
					@endif
				</td>
			</tr>
			@endforeach
			<tr>
				<th>Grand Total</th>
				<th colspan="2">{{$openingTotal}}</th>
				<th colspan="2" class="text-right">{{$soldTotal}}</th>
				<th colspan="2" class="text-right">{{$purchasedTotal}}</th>
				<th colspan="2" class="text-right">{{$goodReturnTotal}}</th>
				<th colspan="2" class="text-right">{{$endingTotal}}</th>
			</tr>
		</table>
		@endif
	</div>
</div>

@stop