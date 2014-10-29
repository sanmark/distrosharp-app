@extends('web._templates.template')

@section('body')

<div class="panel panel-default well">
	<div class="panel-heading">
		<h3 class="panel-title">Credit Summary of customer <b>{{$customer->name}}</b> at <b>{{$currentDate}}</b></h3>
	</div>
	<div class="panel-body">
		<table class="table table-striped" style="width:30%;">
			<tr>
				<th>Customer Name</th>
				<th class="text-right">Outstanding</th>
			</tr>			
			<tr>
				<td>{{$customer->name}}</td>
				<td class="text-right">{{$creditBalanceForCustomer}}</td>
			</tr>		
		</table>
		<table class="table table-striped" style="width:80%;">
			<tr>
				<th>Customer Name</th>
				<th class="text-right">Rep</th>
				<th class="text-right">Invoice Print Number</th>
				<th class="text-right">Aging</th>
				<th class="text-right">Outstanding Amount</th>
			</tr>
			@if(count($customerCreditInvoices)==0)
			<tr>
				<td colspan="5" class="text-left">No records for selected customer</td>
			</tr>
			@endif
			@foreach($customerCreditInvoices as $invoice)
			<tr>
				<td>{{$customer->name}}</td>
				<td class="text-right">{{$invoice->rep->username}}</td>
				<td class="text-right">{{$invoice->printed_invoice_number}}</td>
				<td class="text-right">{{$creditAge[$invoice->id]}} Days</td>
				<td class="text-right">{{$invoice->getInvoiceCredit()}}</td>
			</tr>
			@endforeach
		</table>
	</div>
</div>
@stop