@extends('web._templates.template')

@section('body')
<div class="panel panel-default well">
	<div class="panel-heading">
		<h3 class="panel-title">Return Items Details Report</h3>
	</div>
	<div class="panel-body">

		<div class="panel panel-default">
			<div class="panel-body">
				{{Form::open(['class'=>'form-inline', 'role'=>'form'])}}
				<div class="form-group bottom-space">
					{{Form::label('item',null,array('class' => 'control-label'))}}
					{{Form::select('item',$items,$item, array('class' => ''))}}
				</div> 
				<div class="form-group bottom-space">
					{{Form::label('rep',null,array('class' => 'control-label'))}} 
					{{Form::select('rep',$reps,$rep, array('class' => ''))}}
				</div> 
				<div class="form-group bottom-space">
					{{Form::label('route',null,array('class' => 'control-label'))}} 
					{{Form::select('route',$routes,$route, array('class' => ''))}}
				</div> 
				<div class="form-group bottom-space">
					{{Form::label('customer',null,array('class' => 'control-label'))}} 
					{{Form::select('customer',$customers,$customer, array('class' => ''))}}
				</div> 
				<div class="form-group bottom-space">
					{{Form::label('from_date',null,array('class' => 'control-label'))}}
					{{Form::input('date', 'from_date', $from_date,array('class' => ''))}}
				</div>
				<div class="form-group bottom-space">
					{{Form::label('to_date',null,array('class' => 'control-label'))}}
					{{Form::input('date', 'to_date', $to_date,array('class' => ''))}}
				</div> 
				<div class="form-group bottom-space">
					{{Form::submit('Submit',array('class' => 'btn btn-primary pull-right'))}}
				</div>
				{{Form::close()}}
			</div>
		</div>

		<br/>
		@if($view_data)   

		@if($itemDetails->isEmpty())

		<h4 class="text-center">There are no records to display...</h4>

		@else 
		<table class="table table-striped">
			<tr>
				<th>Date</th>
				<th>Invoice Number</th>
				<th>Sales Rep</th>
				<th>Customer</th>
				<th>Items</th>
				<th class="text-right">Good Return</th> 
				<th class="text-right">Good Return Value</th> 
				<th class="text-right">Company Return</th> 
				<th class="text-right">Company Return Value</th> 
			</tr>
			@foreach($itemDetails as $itemDetail)
			<tr>
				<td>{{$itemDetail->sellingInvoice->date_time}}</th>
				<td>{{$itemDetail->sellingInvoice->id}}</td>
				<td>{{$itemDetail->sellingInvoice->rep->username}}</td>
				<td>{{$itemDetail->sellingInvoice->customer->name}}</td>
				<td>{{$itemDetail->item->name}}</td>
				<td class="text-right">{{$itemDetail->good_return_quantity}}</td>

				<td class="text-right">
					{{number_format($itemDetail->item->current_selling_price * $itemDetail->good_return_quantity,2)}}
				</td>

				<td class="text-right">{{$itemDetail->company_return_quantity}}</td> 

				<td class="text-right">
					{{number_format($itemDetail->item->current_selling_price * $itemDetail->company_return_quantity,2)}}
				</td> 

			</tr>
			@endforeach  
		</table>
		@endif

		@else
		<h4 class="text-center">Please define a criteria and press "Submit".</h4>
		@endif
	</div>
</div>
@stop

@section('file-footer')
<script src="/js/reports/itemReturn/view.js"></script>
<script>
loadCustomers("{{URL::action('entities.customers.ajax.forRouteId')}}", "{{csrf_token()}}");
validateDates();
</script>
@stop
