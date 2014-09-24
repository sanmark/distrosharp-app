@extends('web._templates.template')

@section('body')

<h2>View Age Credit Report</h2>
{{Form::open()}}
<table border="1">
	<tr>
		<td>{{Form::label('rep',null)}}</td>
		<td>{{Form::select('rep',$repSelectBox,$repId)}}</td>
	</tr>
	<tr>
		<td>{{Form::label('route_id',null)}}</td>
		<td>{{Form::select('route_id',$routeSelectBox,$routeId)}}</td>
	</tr>
	<tr>
		<td>{{Form::label('customer',null)}}</td>
		<td>{{Form::select('customer',$customerSelectBox,null)}}</td>
	</tr>
	<tr>
		<td>{{Form::label('age_by_days','Days More Than')}}</td>
		<td>{{Form::text('age_by_days',$ageDays)}}</td>
	</tr>
	<tr>
		<td colspan="2">{{Form::submit('Submit')}}</td>
	</tr>
</table>
{{Form::close()}}

@if(count($sellingData)==0)
<br>
<div class="no-records-message text-center">
	There are no records to display
</div>
<br>
@else
<table class="table table-striped">
	<tr>
		<th>Date</th>
		<th>Rep</th>
		<th>Customer</th>
		<th>Printed Invoice Number</th>
		<th>Age(Days)</th>
		<th>Amount</th>
	</tr>
	@foreach($sellingData as $sellingRow)
	<tr>
		<td>{{$sellingRow->date_time}}</td>
		<td>{{$sellingRow->rep['username']}}</td>
		<td>{{$sellingRow->customer['name']}}</td>
		<td>{{HTML::link (URL::action ( 'processes.sales.edit',[$sellingRow->id] ), $sellingRow->printed_invoice_number )}}</td>
		<td>{{number_format((strtotime(date('Y-m-d H:i:s'))-strtotime($sellingRow->date_time))/86400,0)}}</td>
		<td>{{$invoiceBalanceTotal[$sellingRow->id]}}</td>
	</tr>
	@endforeach
	@if(count($sellingData)==0)
	<?php $customerId = 0; ?>
	@else
	<?php $customerId = $sellingRow -> customer_id ; ?>
	@endif
</table>
@endif
@stop
@section('file-footer')
<script>
	$(document).ready(function() {
	$('#route_id').change();
			setTimeout(function() {
			$("#customer").val({{$customerId}});
			},1000);
	});</script>

<script>
			$(document).on('change', '#route_id', function() {
	routeId = $('#route_id').val();
			$('#customer').find('option').remove();
			$('#customer').append(
			$('<option></option>').
			text('Select')
			);
			$.post(
					"{{URL::action('entities.customers.ajax.forRouteId')}}",
			{
			_token: "{{csrf_token()}}",
					routeId: routeId
			},
					function(data) {
					$.each(data, function(index, customer) {
					$('#customer').append(
							$('<option></option>')
							.attr('value', customer.id)
							.text(customer.name)
							);
					});
					}
			);
	});</script>
@stop
