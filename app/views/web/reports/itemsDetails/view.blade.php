@extends('web._templates.template')

@section('body')
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Item Sales Details</h3>
	</div>
	<div class="panel-body">
		<div class="panel panel-default">
			<div class="panel-body">
				{{Form::open(['class'=>'form-inline', 'role'=>'form'])}}
				<div class="form-group bottom-space">
					{{Form::label('item',null,array('class' => 'control-label'))}} 
					{{Form::select('item',$items,$item, array('class' => 'form-control','required'=>true))}}
				</div> 
				<div class="form-group bottom-space">
					{{Form::label('rep',null,array('class' => 'control-label'))}} 
					{{Form::select('rep',$reps,$rep, array('class' => 'form-control'))}}
				</div> 
				<div class="form-group bottom-space">
					{{Form::label('route',null,array('class' => 'control-label'))}} 
					{{Form::select('route',$routes,$route, array('class' => 'form-control'))}}
				</div> 
				<div class="form-group bottom-space">
					{{Form::label('customer',null,array('class' => 'control-label'))}} 
					{{Form::select('customer',$customers,$customer, array('class' => 'form-control'))}}
				</div> 
				<div class="form-group bottom-space">
					{{Form::label('from_date',null,array('class' => 'control-label'))}}
					{{Form::input('date', 'from_date', $from_date,array('class' => 'form-control'))}}
				</div>
				<div class="form-group bottom-space">
					{{Form::label('to_date',null,array('class' => 'control-label'))}}
					{{Form::input('date', 'to_date', $to_date,array('class' => 'form-control'))}}
				</div> 
				<div class="form-group bottom-space">
					{{Form::submit('Submit',array('class' => 'btn btn-default pull-right'))}}
				</div>
				{{Form::close()}}
			</div> 
		</div>
		<br/>
		@if($view_data)
		<?php
		$totalPaid_quantity	 = 0 ;
		$totalFree_quantity	 = 0 ;
		$totalValue			 = 0 ;
		?>  
		<h4>Selected Item : {{$selectedItem->name}}</h4>
		<table class="table table-striped">
			<tr>
				<th>Date</th>
				<th>Rep</th>
				<th>Route</th>
				<th>Customer</th>
				<th>Paid Items</th>
				<th> Free Items</th>
				<th class="text-right">Price</th> 
				<th class="text-right">Value</th>
			</tr>
			@foreach($itemDetails as $itemDetail)
			<tr>
				<td>{{$itemDetail->sellingInvoice->date_time}}</th>
				<td>{{$itemDetail->sellingInvoice->rep->username}}</td>
				<td>{{$itemDetail->sellingInvoice->customer->route->name}}</td>
				<td>{{$itemDetail->sellingInvoice->customer->name}}</td>
				<td class="text-right">{{$itemDetail->paid_quantity}}</td>
				<td class="text-right">{{$itemDetail->free_quantity}}</td>
				<td class="text-right">{{number_format($itemDetail->item->current_selling_price, 2)}}</td>   
				<?php $value				 = $itemDetail -> paid_quantity * $itemDetail -> item -> current_selling_price ?> 
				<td class="text-right">{{number_format($value, 2)}}</td>

				<?php
				$totalPaid_quantity += $itemDetail -> paid_quantity ;
				$totalFree_quantity += $itemDetail -> free_quantity ;
				$totalValue += $itemDetail -> paid_quantity * $itemDetail -> item -> current_selling_price ;
				?> 

			</tr>
			@endforeach 
			<tr>
				<th> </th>
				<th> </th>
				<th> </th>
				<th>Total : </th>
				<th class="text-right">{{$totalPaid_quantity}} </th>
				<th class="text-right">{{$totalFree_quantity}}</th>
				<th class="text-right"> </th> 
				<th class="text-right">{{number_format($totalValue,2)}}</th>
			</tr>
		</table>
		@else
		<h4 class="text-center">Please define a criteria and press "Submit".</h4>
		@endif
	</div>
</div>
@stop


@section('file-footer')
<script src="/js/reports/items/view.js"></script>
<script>
loadCustomers("{{URL::action('entities.customers.ajax.forRouteId')}}", "{{csrf_token()}}");
validateDates();
</script>
@stop