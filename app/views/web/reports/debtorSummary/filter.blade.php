@extends('web._templates.template')

@section('body')
<div class="panel panel-default well">
	<div class="panel-heading">
		<h3 class="panel-title">Debtor Summary Report</h3>
	</div>
	<div class="panel-body">
		<div class="panel panel-default">
			<div class="panel-body">
				{{Form::open(['class'=>'form-inline', 'role'=>'form'])}}
				<div class="form-group inline-form">
					{{Form::label('from_date',null,array('class' => 'control-label'))}}
					{{Form::input('date', 'from_date', $fromDate,array('class' => ''))}}
				</div>
				<div class="form-group inline-form">
					{{Form::label('to_date',null,array('class' => 'control-label'))}}
					{{Form::input('date', 'to_date', $toDate,array('class' => ''))}}
				</div>
				<div class="form-group inline-form">
					{{Form::label('route_id','Route',array('class' => 'control-label'))}}
					{{Form::select('route_id', $routes,$routeId,array('class' => ''))}}
				</div>
				<div class="form-group inline-form">
					{{Form::label('customer_id','Customer',array('class' => 'control-label'))}}
					{{Form::select('customer_id', $customers,null,array('class' => ''))}}
				</div>
				<div class="form-group inline-form">
					{{Form::submit('Submit',array('class' => 'btn btn-primary pull-right'))}}
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
		<table class="table table-striped" style="width:100%;">
			<tr>
				<th>Type</th>
				<th>Date</th>
				<th>Invoice Number</th>
				<th>DR</th>
				<th>CR</th>
				<th>Invoice Status</th>
			</tr>
			<tr>
				<td colspan="6">Balance Before: {{ViewButler::formatCurrency($balanceBefore)}}</td>
			</tr>
			@foreach($sellingInvoices as $sellingInvoice)
			<tr style="background-color: #ddd;">
				<td>Invoice</td>
				<td>{{$sellingInvoice->date_time}}</td>
				<td>{{$sellingInvoice->printed_invoice_number}}</td>
				<td></td>
				<td>{{$sellingInvoice->getInvoiceTotal()}}</td>
				<td>
					@if($sellingInvoice->isInvoiceBalanceZero())
					M*
					@else
					UM**
					@endif
				</td>
			</tr>
			@foreach($sellingInvoice->financeTransfers as $financeTransfer)
			<tr>
				<td>Payment</td>
				<td>{{$financeTransfer->date_time}}</td>
				<td>{{$sellingInvoice->printed_invoice_number}}</td>
				<td></td>
				<td>{{$financeTransfer->amount}}</td>
				<td></td>
			</tr>
			@endforeach
			@endforeach
			<tr>
				<td colspan="6">Ending Balance: {{ViewButler::formatCurrency($endingBalance)}}</td>
			</tr>
		</table>
		@endif
	</div>
</div>
@stop

@section('file-footer')
<script>
	$(document).on('change', '#route_id', function () {
	var routeId = $(this).val();
		$('#customer_id').find('option').remove();
		$('#customer_id').append(
		$('<option></option>')
		.text('Select')
		);
		$.post(
			"{{URL::action('entities.customers.ajax.forRouteId')}}",
		{
		_token: "{{csrf_token()}}",
			routeId: routeId
		},
			function (data) {
			$.each(data, function (index, customer) {
			$('#customer_id').append(
				$('<option></option>')
				.attr('value', customer.id)
				.text(customer.name)
				);
			});
			}
		);
	});</script>

<script>
		$(document).ready(function () {
	$('#route_id').change();
		setTimeout(function() {
		$("#customer_id").val({{$customerId}});
		}, 2000);
	});
</script>
@stop