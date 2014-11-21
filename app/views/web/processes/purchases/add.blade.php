@extends('web._templates.template')
@section('body')
<div class="panel panel-default well">
	<div class="panel-heading">
		<h3 class="panel-title">Add Purchase</h3>
	</div>
	<div class="panel-body">

		{{Form::open(['class'=>'form-horizontal', 'role'=>'form'])}}
		<br />
		<div class="form-group">
			{{Form::label('date', null, array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-3">
				{{Form::input ( 'datetime-local','date_time', $currentDateTime, array('class' => 'form-control','required'=>true,'step'=>'1'))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('vendor', null, array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-3">
				{{Form::select('vendor_id',$vendorList, null, array('tabindex'=> '1','class' => 'form-control','required'=>true))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label(null, 'Print Invoice Number', array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-3">
				{{Form::text('printed_invoice_num',null, array('tabindex'=> '2','class' => 'form-control','required'=>true))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('Stock', null, array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-3">
				{{Form::select('stock_id',$stocks, NULL, array('tabindex'=> '3','class' => 'form-control','required'=>true))}}
			</div>
		</div>
 
		<br>
		<br> 
 
		<div class="form-group" style="margin-bottom: 3px;"  id="scrollTopSales">
			<div class="col-sm-12 col-sm-offset-0"> 
				<div class="row" id="top-item-lable">
					<div class="col-sm-5">
						<div class="row">
							<div class="col-sm-2"><b>Code</b> <span>(F1)</span></div>
							<div class="col-sm-5"><b>Item Name</b> <span>(F2)</span></div>
							<div class="col-sm-3 text-right"><b>Price</b></div>
							<div class="col-sm-2 text-right"><b>Quantity</b><span>(F3)</span></div>
						</div>
					</div>
					<div class="col-sm-7">
						<div class="row">
							<div class="col-sm-2 text-right"><b>Free Qty</b><span>(F4)</span></div>
							<div class="col-sm-3 text-right"><b>Expire Date</b></div>
							<div class="col-sm-2"><b>Batch Number</b></div>
							<div class="col-sm-3 text-right"><b>Line Total</b></div>
							<div class="col-sm-2 text-right">&nbsp</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="form-group">
			<div class="col-sm-12 col-sm-offset-0">
				<div class="row">					
					<div class="col-sm-5"> 
						<div class="row"> 
							<div class="col-sm-2">
								{{Form::text('txtItemCode', null, array('id' => 'txtItemCode', 'class' => 'form-control', 'autocomplete' => 'off','onClick'=>'this.select();'))}}
								<img src="../../images/loading_small.gif" style="display: none; position: absolute; margin: -26px 0px 0px 32px;" id="loader-img-code">
								<div id="dublicate-error-message"></div>
								{{Form::input('hidden','txtItemId', null, array('id' => 'txtItemId'))}}
							</div>

							<div class="col-sm-5">
								{{Form::text('txtItemName', null, array('id' => 'txtItemName', 'class' => 'form-control', 'autocomplete' => 'off','onClick'=>'this.select();'))}}
								<img src="../../images/loading_small.gif" style="display: none; position: absolute; margin: -26px 0px 0px 165px;" id="loader-img">
								<ul id="item_list_f_purchase" class="item-list-main-bar"> </ul> 
							</div> 

							<div class="col-sm-3">
								{{Form::input('number','txtPrice', null, array('id' => 'txtPrice', 'class' => 'form-control text-right empty cal_line_tot', 'step'=>'0.01','readonly'=>TRUE))}}

							</div> 

							<div class="col-sm-2">
								{{Form::input('number','txtQuantity', null, array('id' => 'txtQuantity', 'class' => 'form-control text-right empty cal_line_tot' , 'step'=>'0.01'))}}
							</div>

						</div>
					</div>

					<div class="col-sm-7">
						<div class="row">
							<div class="col-sm-2">
								{{Form::input('number','txtFreeQuantity', null, array('id' => 'txtFreeQuantity', 'class' => 'form-control text-right empty cal_line_tot', 'step'=>'0.01'))}}
							</div> 

							<div class="col-sm-3">
								{{Form::input('date','txtExpireDate', null, array('id' => 'txtExpireDate', 'class' => 'form-control text-right empty', 'step'=>'0.01'))}}
							</div>

							<div class="col-sm-2">
								{{Form::input('text','txtBatchNumber', null, array('id' => 'txtBatchNumber', 'class' => 'form-control empty', 'step'=>'0.01'))}} 
							</div>


							<div class="col-sm-3">
								{{Form::input('number','txtPurchaseLineTot', null, array('id' => 'txtPurchaseLineTot', 'class' => 'form-control text-right empty', 'readonly'=>TRUE, 'step'=>'0.01'))}} 
							</div>

							<div class="col-sm-2"> 
								<div class="btn btn-primary pull-right" id="add-new-purchase" style="margin: 0px;">Add</div>  
							</div> 
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="form-group">
			<div class="col-sm-12 col-sm-offset-0">
				<div class="row">					
					<div class="col-sm-12" id="table-purchase-list"> </div>
				</div>
			</div>   
		</div> 
		<br>  



		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<div class="row">
					{{Form::label('netTotal', 'Sub Total', array('class' => 'col-sm-offset-8 col-sm-2 control-label'))}}
					<div class="col-sm-2">
						{{Form::text('full_total',null, array('id' => 'subTotal', 'class' => 'form-control text-right', 'step'=>'any','readonly'=>'readonly'))}}
					</div>
				</div>
			</div>
		</div>

		<div class="form-group" style="margin-bottom: 3px;">
			<div class="col-sm-10 col-sm-offset-2">
				<div class="row">					
					<div class="col-sm-8">
						<div style="margin-bottom: 12px;"><b>Cheque Details</b></div>
						<div class="row">
							<div class="col-sm-3">Bank</div>
							<div class="col-sm-3">Cheque Number</div>
							<div class="col-sm-3">Issued Date</div>
							<div class="col-sm-3">Payable Date</div>
						</div>
					</div>

					<div class="col-sm-4">
						<div class="row">
							{{Form::label('cash_payment', 'Cash Payment', array('class' => 'col-sm-6 control-label'))}}
							<div class="col-sm-6">

								{{Form::input('text', 'cash_payment', NULL, array('id' => 'cash_payment','class' => 'form-control text-right saleDetail'))}}
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="form-group">
			<div class="col-sm-10 col-sm-offset-2">
				<div class="row">
					<div class="col-sm-8">
						<div class="row">
							<div class="col-sm-3">
								{{Form::select('cheque_payment_bank_id', $banksList, null, array('id' => 'cheque_payment_bank_id','class' => 'form-control'))}}
							</div>
							<div class="col-sm-3">
								{{Form::text('cheque_payment_cheque_number', null, array('id' => 'cheque_payment_cheque_number','class' => 'form-control'))}}
							</div>
							<div class="col-sm-3">
								{{Form::input('date', 'cheque_payment_issued_date', date('Y-m-d'), array('id' => 'cheque_payment_issued_date','class' => 'form-control'))}}
							</div>
							<div class="col-sm-3">
								{{Form::input('date', 'cheque_payment_payable_date', null, array('id' => 'cheque_payment_payable_date','class' => 'form-control'))}}
							</div>
						</div>						
					</div>

					<div class="col-sm-4">
						<div class="row">
							{{Form::label('cheque_payment', 'Cheque Payment', array('class' => 'col-sm-6 control-label'))}}
							<div class="col-sm-6">
								{{Form::input('text', 'cheque_payment', NULL, array('id' => 'cheque_payment', 'class' => 'form-control text-right saleDetail'))}}
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="form-group">
			<div class="col-sm-10 col-sm-offset-2">
				<div class="row">
					<div class="col-sm-8">
						{{Form::checkbox('is_paid',TRUE,null,array('class' => 'myCheckbox', 'style'=>'margin-top:10px;'))}}&nbsp;&nbsp;
						{{Form::label(null, 'Completely Paid', array('class' => 'control-label'))}}

					</div>
					<div class="col-sm-4">
						<div class="row">
							{{Form::label('balance', 'Credit', array('class' => 'col-sm-6 control-label'))}}
							<div class="col-sm-6">
								{{Form::input('text', 'balance', NULL, array( 'class' => 'form-control text-right','readonly'=>'readonly','style'=>'font-weight:bolder;'))}}
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<div class="row">
					<div class="col-sm-8">
						<div class="row">
							{{Form::label(null, 'Other Expense Details', array('class' => 'col-sm-3 control-label'))}}
							<div class="col-sm-9">

								{{Form::text('other_expenses_details',null, array( 'class' => 'form-control'))}}
							</div>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="row">
							{{Form::label(null, 'Other Expense Amount', array('class' => 'col-sm-6 control-label', 'style' => 'padding-top: 0;'))}}
							<div class="col-sm-6">

								{{Form::input('number','other_expense_amount', null, array( 'class' => 'form-control','step' => '0.01'))}}
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">

				{{Form::submit('Submit', array( 'class' => 'btn btn-primary pull-right'))}}
			</div>
		</div>

		{{Form::close()}}

	</div>
</div>
{{Form::input('hidden', 'current_edite_purchase_id', NULL, ['id'=>'current_edite_purchase_id'])}}



<script type="text/javascript">
	function changeOnQuantity(name, quantity)
	{
		var price = document.getElementsByName('buying_price_' + name)[0].value;
		var lineTotal = price * quantity;
		document.getElementsByName('line_total_' + name)[0].value = lineTotal.toFixed(2);
		var i;
		var a = [<?php echo '"' . implode ( '","' , $itemRowsForTotal ) . '"' ?>];
		var finalTotal = 0;
		for (i = 0; i < a.length; i++) {
			var fullTotalCell = document.getElementsByName('line_total_' + a[i])[0].value;
			var finalTotal = Number(finalTotal) + Number(fullTotalCell);
		}
		document.getElementsByName('full_total')[0].value = finalTotal.toFixed(2);
	}
	function changeOnPrice(name, price)
	{
		var quantity = document.getElementsByName('quantity_' + name)[0].value;
		var lineTotal = price * quantity;
		document.getElementsByName('line_total_' + name)[0].value = lineTotal.toFixed(2);
		var i;
		var a = [<?php echo '"' . implode ( '","' , $itemRowsForTotal ) . '"' ?>];
		var finalTotal = 0;
		for (i = 0; i < a.length; i++) {
			var fullTotalCell = document.getElementsByName('line_total_' + a[i])[0].value;
			var finalTotal = Number(finalTotal) + Number(fullTotalCell);
		}
		document.getElementsByName('full_total')[0].value = finalTotal.toFixed(2);
	}
</script>

<script src="/js/processes/purchases/add.js"></script>
<script src="/js/processes/purchases/add-purchase.js"></script>
<script>
	displayBalance();
	displayIsCompletelyPaid();
	validateChequeDetails();


	selectSalesItem("{{csrf_token()}}");
	setMethodToEnter();
	autoloadItemForSales("{{csrf_token()}}");
	addItemRow();
	editSales();
	deleteSales();



</script>
@stop