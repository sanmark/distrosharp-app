@extends('web._templates.template')
@section('body')

<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Sales Summary Report</h3>
	</div>
	<div class="panel-body">

		<div class="panel panel-default">
			<div class="panel-body">
				{{Form::open(['class'=>'form-inline', 'role'=>'form'])}} 

				<div class="form-group bottom-space">
					{{Form::label('route_id', null, array('class' => 'control-label'))}}
					{{Form::select('route_id',$routes, $routesId, array('class' => 'form-control'))}}
				</div>
				<div class="form-group bottom-space">
					{{Form::label('customer_id', null, array('class' => 'control-label'))}}
					{{Form::select('customer_id',$customers, $customerId, array('class' => 'form-control'))}}
				</div>
				<div class="form-group bottom-space">
					{{Form::label('rep_id', null, array('class' => 'control-label'))}}
					{{Form::select('rep_id',$reps, $repId, array('class' => 'form-control'))}} 
				</div>
				<div class="form-group bottom-space">
					{{Form::label('date_from',null, array('class' => 'control-label'))}}
					{{Form::input('date','date_from', $dateFrom, array('class' => 'form-control'))}}
				</div>
				<div class="form-group bottom-space">
					{{Form::label('date_to',null, array('class' => 'control-label'))}}
					{{Form::input('date','date_to',$dateTo, array('class' => 'form-control'))}}
				</div>
				<div class="form-group bottom-space">
					{{Form::label('invoice_number', null, array('class' => 'control-label'))}}
					{{Form::text('invoice_number',$invoiceNum, array('class' => 'form-control'))}} 
				</div>
				<div class="form-group bottom-space">
					{{Form::submit('Submit', array('class' => 'btn btn-default pull-right'))}}
				</div>

				{{Form::close()}} 
			</div>
		</div>
		<br/>
		<table class="table table-striped">
			<thead>
				<tr>
					<th>Route Name</th>
					<th>Customer Name</th>
					<th class="text-center">Invoice Number</th>
					<th class="text-right">By Cash</th>
					<th class="text-right">By Cheque</th>
					<th class="text-right">By Credit</th>
					<th class="text-right">Gross Amount</th>
					<th class="text-right">Cash Discount</th>
					<th class="text-right">Net Amount</th>
					<th class="text-right">Late Credit Invoice</th>
					<th class="text-right">Late Credit Amount</th>
					<th class="text-right">Total Collection</th>
				</tr>
			</thead>
			<tbody>
				@foreach($sellingInvoices as $sellingInvoice)
				<tr>
					<td>{{$sellingInvoice->customer->route->name}}</td>
					<td>{{$sellingInvoice->customer->name}}</td>
					<td class="text-center">{{HTML::link(URL::action('processes.sales.edit', [$sellingInvoice->id]),$sellingInvoice->id)}}</td>
					<td class="text-right">{{number_format($sellingInvoice->getPaymentValueByCash (), 2) }}</td>
					<td class="text-right">{{number_format($sellingInvoice->getPaymentValueByCheque () , 2)}}</td>
					<td class="text-right">{{number_format($sellingInvoice->getInvoiceCredit () , 2)}}</td>
					<td class="text-right">{{number_format($sellingInvoice->getGrossAmount()+$sellingInvoice->discount, 2)}}</td>
					<td class="text-right">{{number_format($sellingInvoice->discount, 2)}}</td>
					<td class="text-right">{{number_format($sellingInvoice->getInvoiceTotal(), 2)}}</td>
					<td class="text-right">&nbsp;</td>
					<td class="text-right">&nbsp;</td>
					<td class="text-right">{{number_format($sellingInvoice->getInvoiceTotal(), 2)}}</td>
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
					<td class="text-right"><b>&nbsp;</b></td>
					<td class="text-right"><b>&nbsp;</b></td>
					<td class="text-right"><b>{{number_format($totalOfInvoiceSum,2)}}</b></td>
				</tr>
			</tbody>
		</table>
	</div>
</div>

@stop

@section('file-footer')
<script src="/js/reports/sales/home.js"></script>
<script>
	setOldCustomerId({{Input::old('customer_id')}});
	loadCustomersForRout("{{URL::action('entities.customers.ajax.forRouteId')}}", "{{csrf_token()}}");
</script> 
@stop