@extends('web._templates.template')
@section('body')

<div class="panel panel-default well">
	<div class="panel-heading">
		<h3 class="panel-title">Sales Summary Report</h3>
	</div>
	<div class="panel-body">

		<div class="panel panel-default">
			<div class="panel-body">
				{{Form::open(['class'=>'form-inline', 'role'=>'form'])}} 

				<div class="form-group bottom-space">
					{{Form::label('route_id', null, array('class' => 'control-label'))}}
					{{Form::select('route_id',$routes, $routesId, array('class' => ''))}}
				</div>
				<div class="form-group bottom-space">
					{{Form::label('customer_id', null, array('class' => 'control-label'))}}
					{{Form::select('customer_id',$customers, $customerId, array('class' => ''))}}
				</div>
				<div class="form-group bottom-space">
					{{Form::label('rep_id', null, array('class' => 'control-label'))}}
					{{Form::select('rep_id',$reps, $repId, array('class' => ''))}} 
				</div>
				<div class="form-group bottom-space">
					{{Form::label('date_from',null, array('class' => 'control-label'))}}
					{{Form::input('date','date_from', $dateFrom, array('class' => ''))}}
				</div>
				<div class="form-group bottom-space">
					{{Form::label('date_to',null, array('class' => 'control-label'))}}
					{{Form::input('date','date_to',$dateTo, array('class' => ''))}}
				</div>
				<div class="form-group bottom-space">
					{{Form::label('invoice_number', null, array('class' => 'control-label'))}}
					{{Form::text('invoice_number',$invoiceNum, array('class' => ''))}} 
				</div>
				<div class="form-group bottom-space">
					{{Form::submit('Submit', array('class' => 'btn btn-primary pull-right'))}}
				</div>

				{{Form::close()}} 
			</div>
		</div>
		<br/>
		<table class="table table-striped">
			<thead>
				<tr>
					<th>Route</th>
					<th>Customer</th>
					<th>Invoice<br> Number</th>
					<th class="text-right">Sub Total</th>
					<th class="text-right">Discount</th>
					<th class="text-right">Total</th>
					<th class="text-right">Cash Payment</th>
					<th class="text-right">Cheque Payment </th>
					<th class="text-right">Credit</th>
					<th>Late Credit <br>  Invoices</th>
					<th class="text-right"> Late Credit Payments <br> By Cash </th>
					<th class="text-right"> Late Credit Payments <br> By  Cheque </th> 
					<th class="text-right">Total Collection</th>
				</tr>
			</thead>
			<tbody>
				@foreach($sellingInvoices as $sellingInvoice)
				<tr>
					<td>{{$sellingInvoice->customer->route->name}}</td>
					<td>{{$sellingInvoice->customer->name}}</td>
					<td>{{HTML::link(URL::action('processes.sales.edit', [$sellingInvoice->id]),$sellingInvoice->id)}}</td>
					<td class="text-right">{{number_format($sellingInvoice->getSubTotal(), 2)}}</td>
					<td class="text-right">{{number_format($sellingInvoice->discount, 2)}}</td>
					<td class="text-right">{{number_format($sellingInvoice->getTotal(), 2)}}</td>
					<td class="text-right">{{number_format($sellingInvoice->getPaymentValueByCash (), 2) }}</td>
					<td class="text-right">{{number_format($sellingInvoice->getPaymentValueByCheque () , 2)}}</td>
					<td class="text-right">{{number_format($sellingInvoice->getInvoiceCredit () , 2)}}</td>
					<td >  
						@foreach($sellingInvoice -> getLateCreditInvoices () as $key => $invoice)  
						@if($key !== 0)
						,
						@endif 
						{{HTML::link(URL::action('processes.sales.edit', [$invoice]),$invoice)}} 
						@endforeach 
					</td>
					<td class="text-right">   
						{{number_format($sellingInvoice -> getLateCreditPayments()['amount_cash'],2)}}  
					</td> 
					<td class="text-right">     
						{{number_format($sellingInvoice -> getLateCreditPayments()['amount_cheque'],2)}} 
					</td>
					<td class="text-right">{{number_format($sellingInvoice->getTotalCollection(), 2)}}</td>
				</tr>
				@endforeach
				<tr>
					<td colspan="3"><b>Total</b></td>
					<td class="text-right"><b class="total-line-bottom">{{number_format($invoiceSubAmountTotal,2)}}</b></td>
					<td class="text-right"><b class="total-line-bottom">{{number_format($totalOfDiscountSum,2)}}</b></td>
					<td class="text-right"><b class="total-line-bottom">{{number_format($totalAmount,2)}}</b></td>
					<td class="text-right"><b class="total-line-bottom">{{number_format($invoiceByCashTotalSum,2)}}</b></td>
					<td class="text-right"><b class="total-line-bottom">{{number_format($invoiceByChequeTotalSum,2)}}</b></td>
					<td class="text-right"><b class="total-line-bottom">{{number_format($invoiceByCreditTotalSum,2)}}</b></td>
					<td class="text-right"><b>&nbsp;</b></td>
					<td class="text-right"><b class="total-line-bottom">{{number_format($creditPaymentsByCash,2)}}</b></td>
					<td class="text-right"><b class="total-line-bottom">{{number_format($creditPaymentsByCheque,2)}}</b></td>
					<td class="text-right"><b class="total-line-bottom">{{number_format($totalOfInvoiceSum,2)}}</b></td>
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