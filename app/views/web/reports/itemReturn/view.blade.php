@extends('web._templates.template')

@section('body')
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Return Items Details Report</h3>
	</div>
	<div class="panel-body">
		<div class="panel panel-default">
			{{Form::open(['class'=>'form-inline', 'role'=>'form'])}}
			<div class="form-group inline-form">
				{{Form::label('item',null,array('class' => 'control-label'))}} 
				{{Form::select('item',$items,$item, array('class' => 'form-control'))}}
			</div> 
			<div class="form-group inline-form">
				{{Form::label('rep',null,array('class' => 'control-label'))}} 
				{{Form::select('rep',$reps,$rep, array('class' => 'form-control'))}}
			</div> 
			<div class="form-group inline-form">
				{{Form::label('route',null,array('class' => 'control-label'))}} 
				{{Form::select('route',$routes,$route, array('class' => 'form-control'))}}
			</div> 
			<div class="form-group inline-form">
				{{Form::label('customer',null,array('class' => 'control-label'))}} 
				{{Form::select('customer',$customers,$customer, array('class' => 'form-control'))}}
			</div> 
			<div class="form-group inline-form">
				{{Form::label('from_date',null,array('class' => 'control-label'))}}
				{{Form::input('date', 'from_date', $from_date,array('class' => 'form-control'))}}
			</div>
			<div class="form-group inline-form">
				{{Form::label('to_date',null,array('class' => 'control-label'))}}
				{{Form::input('date', 'to_date', $to_date,array('class' => 'form-control'))}}
			</div> 
			<div class="form-group inline-form">
				{{Form::submit('Submit',array('class' => 'btn btn-default pull-right'))}}
			</div>
			{{Form::close()}}
			<br/>
		</div> 
		@if($view_data)   

		@if($itemDetails->isEmpty())
		<div class="no-records-message text-center">
			There are no records to display
		</div>
		@else 
		<table class="table table-striped">
			<tr>
				<th>Date</th>
				<th>Invoice Number</th>
				<th>Sales Rep</th>
				<th>Customer</th>
				<th>Items</th>
				<th class="text-right">Good Return</th> 
				<th class="text-right">Company Return</th> 
			</tr>
			@foreach($itemDetails as $itemDetail)
			<tr>
				<td>{{$itemDetail->sellingInvoice->date_time}}</th>
				<td>{{$itemDetail->sellingInvoice->id}}</td>
				<td>{{$itemDetail->sellingInvoice->rep->username}}</td>
				<td>{{$itemDetail->sellingInvoice->customer->name}}</td>
				<td>{{$itemDetail->item->name}}</td>
				<td class="text-right">{{$itemDetail->good_return_quantity}}</td>
				<td class="text-right">{{$itemDetail->company_return_quantity}}</td> 
			</tr>
			@endforeach  
		</table>
		@endif
		
		@else
		Please define a criteria and press "Submit".
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
