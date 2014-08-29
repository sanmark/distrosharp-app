@extends('web._templates.template')

@section('body')
<table border="1">
	{{Form::open()}}
	<tr>
		<td>{{Form::label('id')}}</td>
		<td>{{Form::text('id', $id)}}</td>
	</tr>
	<tr>
		<td>{{Form::label('printed_invoice_number')}}</td>
		<td>{{Form::text('printed_invoice_number', $printedInvoiceNumber)}}</td>
	</tr>
	<tr>
		<td>{{Form::label('date_time_from')}}</td>
		<td>{{Form::input('datetime-local','date_time_from', $dateTimeFrom)}}</td>
	</tr>
	<tr>
		<td>{{Form::label('date_time_to')}}</td>
		<td>{{Form::input('datetime-local','date_time_to', $dateTimeTo)}}</td>
	</tr>
	<tr>
		<td>{{Form::label('customer_id')}}</td>
		<td>{{Form::select('customer_id', $customerSelectBox, $customerId)}}</td>
	</tr>
	<tr>
		<td>{{Form::label('rep_id')}}</td>
		<td>{{Form::select('rep_id', $repSelectBox, $repId)}}</td>
	</tr>
	<tr>
		<td>{{Form::label('is_completely_paid')}}</td>
		<td>{{Form::select('is_completely_paid', $isActiveSelectBox, $isCompletelyPaid)}}</td>
	</tr>
	<tr style="text-align: right;">
		<td colspan="2">{{Form::submit('Submit')}}</td>
	</tr>
	{{Form::close()}}
</table>

<table border="1">
	<tr>
		<th>ID</th>
		<th>Printed Invoice Number</th>
		<th>Date/Time</th>
		<th>Customer</th>
		<th>Rep</th>
		<th>Is Completely Paid</th>
	</tr>
	@foreach($sellingInvoices as $sellingInvoice)
	<tr>
		<td>{{$sellingInvoice->id}}</td>
		<td>{{$sellingInvoice->printed_invoice_number}}</td>
		<td>{{$sellingInvoice->date_time}}</td>
		<td>{{$sellingInvoice->customer->name}}</td>
		<td>{{$sellingInvoice->rep->username}}</td>
		<td>{{ViewButler::getYesNoFromBoolean($sellingInvoice->is_completely_paid)}}</td>
	</tr>
	@endforeach
</table>
@stop