@extends('web._templates.template')

@section('body')

<div class="panel panel-default well">
	<div class="panel-heading">
		<h3 class="panel-title">View Age Credit Report</h3>
	</div>
	<div class="panel-body">

		<div class="panel panel-default">
			<div class="panel-body">

				{{Form::open(['class'=>'form-inline', 'role'=>'form'])}}
				<div class="form-group inline-form">
					{{Form::label('rep',null, array('class' => 'control-label'))}}
					{{Form::select('rep',$repSelectBox,$repId, array('class' => ''))}}
				</div>
				<div class="form-group inline-form">
					{{Form::label('route_id',null, array('class' => 'control-label'))}}
					{{Form::select('route_id',$routeSelectBox,$routeId, array('class' => ''))}}
				</div>
				<div class="form-group inline-form">
					{{Form::label('customer',null, array('class' => 'control-label'))}}
					{{Form::select('customer',$customerSelectBox,null, array('class' => ''))}}
				</div>
				<div class="form-group inline-form">
					{{Form::label('age_by_days','Days More Than', array('class' => 'control-label'))}}
					{{Form::text('age_by_days',$ageDays, array('class' => ''))}}
				</div>
				<div class="form-group inline-form">
					{{Form::submit('Submit', array('tabindex' => '10', 'class' => 'btn btn-primary pull-right'))}}
				</div>
				{{Form::close()}}

			</div>
		</div>

		@if(count($sellingData)==0)
		<br>
		<h4 class="text-center">There are no records to display...</h4>
		<br>
		@else
		<table class="table table-striped" style="width:80%;">
			<tr>
				<th>Date</th>
				<th>Rep</th>
				<th>Customer</th>
				<th>Printed Invoice Number</th>
				<th>Age(Days)</th>
				<th class="text-right">Amount</th>
			</tr>
			@foreach($sellingData as $sellingRow)
			<tr>
				<td>{{$sellingRow->date_time}}</td>
				<td>{{$sellingRow->rep['username']}}</td>
				<td>{{$sellingRow->customer['name']}}</td>
				<td>{{HTML::link (URL::action ( 'processes.sales.edit',[$sellingRow->id] ), $sellingRow->printed_invoice_number )}}</td>
				<td>{{number_format((strtotime(date('Y-m-d H:i:s'))-strtotime($sellingRow->date_time))/86400,0)}}</td>
				<td class="text-right">{{$invoiceBalanceTotal[$sellingRow->id]}}</td>
			</tr>
			@endforeach
			@if(count($sellingData)==0)
			<?php $customerId	 = 0 ; ?>
			@else
			<?php $customerId	 = $sellingRow -> customer_id ; ?>
			@endif
		</table>
		@endif

	</div>
</div>

@stop
@section('file-footer')
<script>
	$(document).ready(function() {
	$('#route_id').change();
		setTimeout(function() {
		$("#customer").val({{$customerId}});
		}, 1000);
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
