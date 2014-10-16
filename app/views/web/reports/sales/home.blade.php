@extends('web._templates.template')
@section('body')

<h3>Sales Summary Report</h3>
{{Form::open()}} 

{{Form::label('route_id', null )}}
{{Form::select('route_id',$routes, $routesId )}}
<br>
{{Form::label('customer_id', null )}}
{{Form::select('customer_id',$customers, $customerId )}}
<br>
{{Form::label('rep_id', null )}}
{{Form::select('rep_id',$reps, $repId )}} 
<br>
{{Form::label('date_from',null)}}
{{Form::input('date','date_from', $dateFrom )}}
<br>
{{Form::label('date_to',null)}}
{{Form::input('date','date_to',$dateTo )}}
<br>
{{Form::label('invoice_number', null )}}
{{Form::text('invoice_number',$invoiceNum, null )}} 
<br>
{{Form::submit('Submit' )}}

{{Form::close()}} 

<table class="table table-striped">
	<tr>
		<th>Route Name</th>
		<th>Customer Name</th>
		<th>Invoice Number</th>
		<th>By Cash</th>
		<th>By Cheque</th>
		<th>By Credit</th>
		<th>Gross Amount</th>
		<th>Cash Discount</th>
		<th>Net Amount</th>
		<th>Late Credit Invoice</th>
		<th>Late Credit Amount</th>
		<th>Total Collection</th>
	</tr>
	@foreach($sellingInvoices as $sellingInvoice)
	<tr>
		<td>{{$sellingInvoice->customer->route->name}}</td>
		<td>{{$sellingInvoice->customer->name}}</td>
		<td>{{HTML::link(URL::action('processes.sales.edit', [$sellingInvoice->id]),$sellingInvoice->id)}}</td>
		<td align="right">{{number_format($sellingInvoice->getPaymentValueByCash (), 2) }}</td>
		<td align="right">{{number_format($sellingInvoice->getPaymentValueByCheque () , 2)}}</td>
		<td align="right">{{number_format($sellingInvoice->getInvoiceCredit () , 2)}}</td>
		<td align="right">{{number_format($sellingInvoice->getGrossAmount()+$sellingInvoice->discount, 2)}}</td>
		<td align="right">{{number_format($sellingInvoice->discount, 2)}}</td>
		<td align="right">{{number_format($sellingInvoice->getInvoiceTotal(), 2)}}</td>
		<td></td>
		<td></td>
		<td align="right">{{number_format($sellingInvoice->getInvoiceTotal(), 2)}}</td>
	</tr>
	@endforeach
    <tr>
		<td colspan="3"><b>Total</b></td>
		<td class="text-right"><b>{{number_format($invoiceByCashTotalSum,2)}}</b></td>
		<td class="text-right"><b>{{number_format($invoiceByChequeTotalSum,2)}}</b></td>
		<td class="text-right"><b>{{number_format($invoiceByCreditTotalSum,2)}}</b></td>
		<td class="text-right"><b>{{number_format($invoiceGrossAmountTotal,2)}}</b></td>
		<td class="text-right"><b>{{number_format($totalOfDiscountSum,2)}}</b></td>
		<td class="text-right"><b>{{number_format($totalOfInvoiceSum,2)}}</b></td>
		<td class="text-right"><b></b></td>
		<td class="text-right"><b></b></td>
		<td class="text-right"><b>{{number_format($totalOfInvoiceSum,2)}}</b></td>
	</tr>
</table>
@stop

@section('file-footer')
<script src="/js/reports/sales/home.js"></script>
<script>
	setOldCustomerId({{Input::old('customer_id')}});
	loadCustomersForRout("{{URL::action('entities.customers.ajax.forRouteId')}}", "{{csrf_token()}}");</script> 
<script>
@stop