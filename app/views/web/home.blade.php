@extends('web._templates.template')

@section('body')
<div class="row">

	<div class="col-sm-4 home-panels">
		<div class="panel panel-default home-panel-height">
			<div class="panel-heading">
				<h3 class="panel-title">Last 30-days Purchases</h3>
			</div>
			<div class="panel-body">
				<table class="table table-bordered">
					<tr>
						<td>Total Amount</td>
						<td class="text-right">{{number_format($lastThirtyDaysPurchase['totalCost'],2)}}</td>
					</tr>
					<tr>
						<td>Total Paid</td>
						<td class="text-right">{{number_format($lastThirtyDaysPurchase['totalPaid'],2)}}</td>
					</tr>
					<tr>
						<td><b>Balance to Pay</b></td>
						<td class="text-right"><b>{{number_format($lastThirtyDaysPurchase['balanceToBePay'],2)}}</b></td>
					</tr>
				</table>
			</div>
		</div>
	</div>

	<div class="col-sm-4 home-panels">
		<div class="panel panel-default home-panel-height">
			<div class="panel-heading">
				<h3 class="panel-title">Stock Summery(All Stocks)</h3>
			</div>
			<div class="panel-body">
				<table class="table table-bordered">
					<tr>
						<td>Good Quantity Value</td>
						<td class="text-right">{{number_format($stockSummery['goodQntVal'], 2)}}</td>
					</tr>
					<tr>
						<td>Return Quantity Value</td>
						<td class="text-right">{{number_format($stockSummery['returnQntVal'], 2)}}</td>
					</tr>
					<tr>
						<td><b>Total Stock Value</b></td>
						<td class="text-right"><b>{{number_format($stockSummery['totalStockVal'], 2)}}</b></td>
					</tr>
				</table>
			</div>
		</div>
	</div>

	<div class="col-sm-4 home-panels">
		<div class="panel panel-default home-panel-height">
			<div class="panel-heading">
				<h3 class="panel-title">Last 30-days Sales</h3>
			</div>
			<div class="panel-body">
				<table class="table table-bordered">
					<tr>
						<td>Invoiced total</td>
						<td class="text-right">{{number_format($lastThirtyDaysSales['totalOfInvoiceSum'], 2)}}</td>
					</tr>
					<tr>
						<td>Total Discount</td>
						<td class="text-right">{{number_format($lastThirtyDaysSales['totalOfDiscountSum'], 2)}}</td>
					</tr>
					<tr>
						<td>Total Paid</td>
						<td class="text-right">{{number_format($lastThirtyDaysSales['totalOfTotalPaid'], 2)}}</td>
					</tr>
					<tr>
						<td>Credit</td>
						<td class="text-right">{{number_format($lastThirtyDaysSales['totalOfTotalCredit'], 2)}}</td>
					</tr>
				</table>
			</div>
		</div>
	</div>

	<div class="col-sm-4 home-panels">
		<div class="panel panel-default home-panel-height home-panel-overflow">
			<div class="panel-heading">
				<h3 class="panel-title">Daily Workflow</h3>
			</div>
			<div class="panel-body">
				<div class="row">
					{{ Form::open() }}
					{{Form::hidden('submitedForm','dailyWorkFlow')}}
					<div class="form-group">
						{{Form::label('the_date','Select Date', array('class' => 'col-sm-1 col-md-3 control-label'))}}
						<div class="col-sm-6">
							{{Form::input('date','the_date',$dailyWorkflow['today'], array('tabindex' => '1', 'class' => 'col-sm-1 col-md-3 form-control'))}}

						</div>
					</div>

					<div class="form-group">
						<div class="col-sm-3">
							{{Form::submit('Submit', array('tabindex' => '2', 'class' => 'btn btn-default pull-right'))}}</td>
						</div>
					</div>

					{{ Form::close() }}
				</div>
				<br>

				<table class="table table-bordered">
					<tr>
						<th>Process</th>
						<th>Description</th>
					</tr>
					<tr>
						<td>Purchases</td>
						<td>{{HTML::link(URL::action('processes.purchases.view'), $dailyWorkflow['buyingInvoiceCount'])}}</td>
					</tr>
					<tr>
						<td>Stock Transfers</td>
						<td>{{HTML::link(URL::action('processes.transfers.all'), $dailyWorkflow['stockTransferCount'])}}</td>
					</tr>
					<tr>
						<td>Sales</td>
						<td>{{HTML::link(URL::action('processes.sales.all'), $dailyWorkflow['salesCount'])}}</td>
					</tr>
					<tr>
						<td>Finance Account Verifications</td>
						<td>{{HTML::link(URL::action('finances.accounts.view'), $dailyWorkflow['financeAccountVerificationCount'])}}</td>
					</tr>
					<tr>
						<td>Stock Confirmations</td>
						<td>{{HTML::link(URL::action('stocks.all'), $dailyWorkflow['stockVerificationCount'])}}</td>
					</tr>
				</table>

			</div>
		</div>
	</div>

</div>
@stop