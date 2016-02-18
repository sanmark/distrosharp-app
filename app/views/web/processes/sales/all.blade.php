@extends('web._templates.template')

@section('body')

<div class="panel panel-default well">
	<div class="panel-heading">
		<div class="panel-title">
			<span>View Sales</span>
			{{HTML::link ( URL::action ( 'processes.sales.add') ,'Add New Sale',['class' => 'panel-title-btn btn btn-success btn-sm pull-right'] )}}
		</div>
	</div>
	<div class="panel-body">

		<div class="panel panel-default">
			<div class="panel-body">
				{{Form::open(['class'=>'form-inline', 'role'=>'form'])}}
				<div class="form-group bottom-space">
					{{Form::label('id', 'System Invoice Number', array('class' => 'control-label'))}}
					{{Form::text('id', $id, array('tabindex' => '1', 'class' => ''))}}
				</div>
				<div class="form-group bottom-space">
					{{Form::label('printed_invoice_number', null, array('class' => 'control-label'))}}
					{{Form::text('printed_invoice_number', $printedInvoiceNumber, array('tabindex' => '2', 'class' => ''))}}
				</div>
				<div class="form-group bottom-space">
					{{Form::label('date_time_from','From', array('class' => 'control-label'))}}
					{{Form::input('datetime-local','date_time_from', $dateTimeFrom, array('tabindex' => '3', 'class' => ''))}}
				</div>
				<div class="form-group bottom-space">
					{{Form::label('date_time_to','To', array('class' => 'control-label'))}}
					{{Form::input('datetime-local','date_time_to', $dateTimeTo, array('tabindex' => '4', 'class' => ''))}}
				</div>
				<div class="form-group bottom-space">
					{{Form::label('route_id','Route', array('class' => 'control-label'))}}
					{{Form::select('route_id', $routeSelectBox, $routeId, array('tabindex' => '5', 'class' => ''))}}
				</div>
				<div class="form-group bottom-space">
					{{Form::label('customer_id','Customer', array('class' => 'control-label'))}}
					{{Form::select('customer_id', $customerSelectBox, null, array('tabindex' => '5', 'class' => ''))}}
				</div>
				<div class="form-group bottom-space">
					{{Form::label('rep_id','Rep', array('class' => 'control-label'))}}
					{{Form::select('rep_id', $repSelectBox, $repId, array('tabindex' => '6', 'class' => ''))}}
				</div>
				<div class="form-group bottom-space">
					{{Form::label('is_completely_paid', null, array('class' => 'control-label'))}}
					{{Form::select('is_completely_paid', $isActiveSelectBox, $isCompletelyPaid, array('tabindex' => '7', 'class' => ''))}}
				</div>
				<div class="form-group bottom-space">
					{{Form::submit('Submit', array('tabindex' => '8', 'class' => 'btn btn-primary pull-right'))}}
				</div>
				{{Form::close()}}
			</div>
		</div>

		<br/>
		@if(count($sellingInvoices)==0)
		<br>
		<div class="no-records-message text-center">
			There are no records to display
		</div>
		<br>
		@else
		<table class="table table-striped" style="width: 100%;">
			<tr>
				<th>Date/Time</th>
				<th>System Invoice Number</th>
				<th>Printed Invoice Number</th>
				<th>Customer</th>
				<th>Rep</th>
				<th class="text-center">Is Completely Paid</th> 
			</tr>
			<tbody>
				@foreach($sellingInvoices as $sellingInvoice)
				<tr>
					<td>{{$sellingInvoice->date_time}}</td>
					<td>{{HTML::link(URL::action('processes.sales.view', [$sellingInvoice->id]),$sellingInvoice->id)}}</td>
					<td>{{$sellingInvoice->printed_invoice_number}}</td>
					<td>{{$sellingInvoice->customer->name}}</td>
					<td>{{$sellingInvoice->rep->username}}</td>
					<td class="text-center">{{ViewButler::getYesNoFromBoolean($sellingInvoice->is_completely_paid)}}</td> 
				</tr>
				@endforeach
				 
			</tbody>
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
		$("#customer_id").val({{$customerId}});
		}, 1500);
	});</script>

<script>
		$(document).on('change', '#route_id', function() {
	routeId = $('#route_id').val();
		$('#customer_id').find('option').remove();
		$('#customer_id').append(
		$('<option value=""></option>').
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
			$('#customer_id').append(
				$('<option></option>')
				.attr('value', customer.id)
				.text(customer.name)
				);
			});
			}
		);
	});</script>

@stop