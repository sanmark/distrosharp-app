@extends('web._templates.template')

@section('body')
<h2>Debtor Summary Report</h2>
{{Form::open()}}
<table>
	<tr>
		<td>{{Form::label('from_date')}}</td>
		<td>{{Form::input('date', 'from_date', $fromDate)}}</td>
	</tr>
	<tr>
		<td>{{Form::label('to_date')}}</td>
		<td>{{Form::input('date', 'to_date', $toDate)}}</td>
	</tr>
	<tr>
		<td>{{Form::label('route_id')}}</td>
		<td>{{Form::select('route_id', $routes, $routeId)}}</td>
	</tr>
	<tr>
		<td>{{Form::label('customer_id')}}</td>
		<td>{{Form::select('customer_id', $customers)}}</td>
	</tr>
	<tr>
		<td colspan="2">
			{{Form::submit()}}
		</td>
	</tr>
</table>
{{Form::close()}}

<table border="1">
	<tr>
		<th>Type</th>
		<th>Date</th>
		<th>Narration</th>
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
		<td colspan="6">Ending Before: {{ViewButler::formatCurrency($endingBalance)}}</td>
	</tr>
</table>
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