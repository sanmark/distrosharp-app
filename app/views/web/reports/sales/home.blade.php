@extends('web._templates.template')
@section('body')

<div class="panel panel-default well"> 
	<div class="panel-heading">
		<h3 class="panel-title">Sales Summary Report</h3>
	</div>
	<div class="panel-body" id="panel-body">

		<div class="panel panel-default">
			<div class="panel-body">
				{{Form::open(['class'=>'form-inline', 'role'=>'form'])}} 

				<div class="form-group bottom-space">
					{{Form::label('route_id', null, array('class' => 'control-label'))}}
					{{Form::select('route_id',$routes, null, array('class' => ''))}}
				</div>
				<div class="form-group bottom-space">
					{{Form::label('customer_id', null, array('class' => 'control-label'))}}
					{{Form::select('customer_id',$customers, null, array('class' => ''))}}
				</div>
				<div class="form-group bottom-space">
					{{Form::label('rep_id', null, array('class' => 'control-label'))}}
					{{Form::select('rep_id',$reps, null, array('class' => ''))}} 
				</div>
				<div class="form-group bottom-space">
					{{Form::label('date_from',null, array('class' => 'control-label'))}}
					{{Form::input('date','date_from', $dateFrom, array('class' => '','id' => 'date_from'))}}
				</div>
				<div class="form-group bottom-space">
					{{Form::label('date_to',null, array('class' => 'control-label'))}}
					{{Form::input('date','date_to',$dateTo, array('class' => '','id' => 'date_to'))}}
				</div>
				<div class="form-group bottom-space">
					{{Form::label('invoice_number', null, array('class' => 'control-label'))}}
					{{Form::text('invoice_number',null, array('class' => ''))}} 
				</div>
				<div class="form-group bottom-space"> 
					<button id="btn-sub" class="btn btn-primary pull-right">Submit</button>
					<img src="../images/loading_small.gif" style=" position: absolute; margin: 15px 0px 0px 45px;" id="imgLoader"> 
				</div>

				{{Form::close()}} 
			</div>
		</div>
		<br/>
		<h4 class="text-center" style="display: none" id="emptyMessage">There are no records to display...</h4>
		<table class="table table-striped" id="tableReports">
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

			<tbody id="tableBody"></tbody>

			<tfoot>
				<tr>
					<td colspan="3"><b>Total</b></td>
					<td class="text-right">
						<b class="total-line-bottom">
							<span id="invoiceSubAmountTotal"></span>
						</b>
					</td>
					<td class="text-right">
						<b class="total-line-bottom">
							<span id="totalOfDiscountSum"></span>
						</b>
					</td>
					<td class="text-right">
						<b class="total-line-bottom">
							<span id="totalAmount"></span>
						</b>
					</td>
					<td class="text-right">
						<b class="total-line-bottom">
							<span id="invoiceByCashTotalSum"></span>
						</b>
					</td>
					<td class="text-right">
						<b class="total-line-bottom">
							<span id="invoiceByChequeTotalSum"></span>
						</b>
					</td>
					<td class="text-right">
						<b class="total-line-bottom">
							<span id="invoiceByCreditTotalSum"></span>
						</b>
					</td>
					<td class="text-right"><b>&nbsp;</b></td>
					<td class="text-right">
						<b class="total-line-bottom">
							<span id="creditPaymentsByCash"></span>
						</b>
					</td>
					<td class="text-right">
						<b class="total-line-bottom">
							<span id="creditPaymentsByCheque"></span>
						</b>
					</td>
					<td class="text-right">
						<b class="total-line-bottom">
							<span id="totalOfInvoiceSum"></span>
						</b>
					</td>
				</tr>
			</tfoot>
		</table>

	</div>
</div>

@stop

@section('file-footer')
<script src="/js/reports/sales/home.js"></script>
<script src="/js/reports/sales/report.js"></script>
<script>
	setOldCustomerId({{Input::old('customer_id')}});
	loadCustomersForRout("{{URL::action('entities.customers.ajax.forRouteId')}}", "{{csrf_token()}}");
	createReport("{{URL::action('reports.ajax.createSalesSummaryReport')}}", "{{csrf_token()}}");
</script> 
@stop 