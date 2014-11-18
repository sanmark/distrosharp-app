@extends('web._templates.template')

@section('body')
<div class="panel panel-default well">
	<div class="panel-heading">
		<h3 class="panel-title">Add Sale</h3>
	</div>
	<div class="panel-body">
		{{Form::open(['class'=>'form-horizontal', 'role'=>'form' ])}}
		<br />
		<div class="form-group">
			{{Form::label('id', 'System Inv. Id', array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-3">
				{{Form::label('id', $guessedInvoiceId, array('class' => 'form-control','required'=>true, 'disabled'=>TRUE))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('date_time', null, array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-3">
				{{Form::input('datetime-local','date_time', $currentDateTime, array('class' => 'form-control','required'=>true,'step'=>'1'))}}
			</div>
		</div>
		<div class="form-group" style="height: 39px;">
			{{Form::label('rep',null,array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-3">
				{{Form::label('rep',$rep->username,array('class' => 'form-control'))}}
				{{Form::hidden('rep_id', $rep->id, array('id' => 'rep_id'))}}
			</div>
			<div class="col-sm-1">
				{{HTML::link ( URL::action ( 'processes.sales.setRep' ) , 'Change Rep...' , ['class' => 'btn btn-success btn-sm', 'style'=>'position: relative; left: -25px; top: -15px;' ] )}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('route_id', 'Route', array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-3">
				{{Form::select('route_id',$routes, Session::get('oldRouteId'), array('tabindex'=>'1', 'class' => 'form-control','required'=>true))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('customer_id', 'Customer', array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-3">
				{{Form::select('customer_id',$customers, null,array('tabindex'=>'2', 'class' => 'form-control','required'=>true))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('printed_invoice_number', null, array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-3">
				{{Form::text('printed_invoice_number', null, array('tabindex'=>'3', 'class' => 'form-control','required'=>true))}}
			</div>
		</div>

		<?php $tab = 3 ; ?>

		<div class="form-group" style="margin-bottom: 3px;"  id="scrollTopSales">
			<div class="col-sm-10 col-sm-offset-2">
				<div class="row">
					<div style="margin-bottom: 12px;"><h4><b>Add Sales</b></h4></div>
				</div>
				<div class="row">
					<div class="col-sm-6">
						<div class="row">
							<div class="col-sm-3">Item Code</div>
							<div class="col-sm-3">Item Name</div>
							<div class="col-sm-3 text-right">Available</div>
							<div class="col-sm-3 text-right">Price</div>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="row">
							<div class="col-sm-3 text-right">Paid Qty</div>
							<div class="col-sm-3 text-right">Free Qty</div>
							<div class="col-sm-3 text-right">Line Total</div>
							<div class="col-sm-3 text-right">&nbsp</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="form-group">
			<div class="col-sm-10 col-sm-offset-2">
				<div class="row">					
					<div class="col-sm-6"> 
						<div class="row"> 
							<div class="col-sm-3">
								{{Form::text('txtItemCode', null, array('id' => 'txtItemCode', 'class' => 'form-control', 'autocomplete' => 'off','onClick'=>'this.select();'))}} 
								<div id="dublicate-error-message"></div>
								{{Form::input('hidden','txtItemId', null, array('id' => 'txtItemId'))}}
							</div>

							<div class="col-sm-3">
								{{Form::text('txtItemName', null, array('id' => 'txtItemName', 'class' => 'form-control', 'autocomplete' => 'off','onClick'=>'this.select();'))}}
								<img src="../../images/loading_small.gif" style="display: none; position: absolute; margin: -27px 3px 0px 77px;" id="loader-img">
								<ul id="item_list_f_sales" class="item-list-main-bar"> </ul> 
							</div> 

							<div class="col-sm-3">
								{{Form::input('number','txtAvailable', null, array('id' => 'txtAvailable', 'class' => 'form-control text-right empty cal_return_line_tot', 'readonly'=>TRUE, 'step'=>'0.01'))}}
							</div> 

							<div class="col-sm-3">
								{{Form::input('number','txtPrice', null, array('id' => 'txtPrice', 'class' => 'form-control text-right empty cal_return_line_tot', 'step'=>'0.01'))}}
							</div>

						</div>
					</div>

					<div class="col-sm-6">
						<div class="row">
							<div class="col-sm-3">
								{{Form::input('number','txtPaidQty', null, array('id' => 'txtPaidQty', 'class' => 'form-control text-right empty cal_return_line_tot', 'step'=>'0.01'))}}
							</div> 

							<div class="col-sm-3">
								{{Form::input('number','txtFreeQty', null, array('id' => 'txtFreeQty', 'class' => 'form-control text-right empty cal_return_line_tot', 'step'=>'0.01'))}}
							</div>

							<div class="col-sm-3">
								{{Form::input('number','txtSalesLineTot', null, array('id' => 'txtSalesLineTot', 'class' => 'form-control text-right empty', 'readonly'=>TRUE, 'step'=>'0.01'))}} 
							</div>
							<div class="col-sm-3"> 
								<div class="btn btn-primary pull-right" id="add-new-salesl" style="margin: 0px;">Add</div>  
							</div> 
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="form-group">
			<div class="col-sm-10 col-sm-offset-2">
				<div class="row">					
					<div class="col-sm-12" id="table-sales-list"></div>
				</div>
			</div>   
		</div>




		<div class="form-group" style="margin-bottom: 3px;">
			<div class="col-sm-10 col-sm-offset-2"> 
				<div class="row">
					<div class="col-sm-6"> </div>
					<div class="col-sm-6">
						<div class="row">
							<div class="col-sm-3 text-right">&nbsp</div>
							<div class="col-sm-3 text-right"><b>Total :</b> </div>
							<div class="col-sm-3 text-right">
								<b id="lable_sales_total"></b>
								{{Form::hidden ( 'txt_sales_total', NULL, ['id'=>'txt_sales_total'])}}
							</div>
							<div class="col-sm-3 text-right">&nbsp</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<br>

		<div class="form-group" style="margin-bottom: 3px;"  id="scrollTopReturn">
			<div class="col-sm-10 col-sm-offset-2">
				<div class="row">
					<div style="margin-bottom: 12px;"><h4><b>Add Returns</b></h4></div>
				</div>
				<div class="row">
					<div class="col-sm-6">
						<div class="row">
							<div class="col-sm-3">Item Code</div>
							<div class="col-sm-3">Item Name</div>
							<div class="col-sm-3 text-right">GR Price</div>
							<div class="col-sm-3 text-right">GR Qty</div>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="row">
							<div class="col-sm-3 text-right">CR Price</div>
							<div class="col-sm-3 text-right">CR Qty</div>
							<div class="col-sm-3 text-right">Line Total</div>
							<div class="col-sm-3 text-right">&nbsp</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="form-group">
			<div class="col-sm-10 col-sm-offset-2">
				<div class="row">					
					<div class="col-sm-6"> 
						<div class="row"> 
							<div class="col-sm-3">
								{{Form::text('txtReturnItemCode', null, array('id' => 'txtReturnItemCode', 'class' => 'form-control', 'autocomplete' => 'off','onClick'=>'this.select();'))}} 
								<div id="return-dublicate-error-message"></div>
								{{Form::input('hidden','txtreturnId', null, array('id' => 'txtreturnId'))}}
							</div>

							<div class="col-sm-3">
								{{Form::text('txtReturnItemName', null, array('id' => 'txtReturnItemName', 'class' => 'form-control', 'autocomplete' => 'off','onClick'=>'this.select();'))}}
								<img src="../../images/loading_small.gif" style="display: none; position: absolute; margin: -27px 3px 0px 77px;" id="loader-img-return">
								<ul id="item_list_f_return" class="item-list-main-bar"> </ul> 
							</div> 

							<div class="col-sm-3">
								{{Form::input('number','txtGoodReturnPrice', null, array('id' => 'txtGoodReturnPrice', 'class' => 'form-control text-right empty cal_return_line_tot', 'step'=>'0.01'))}}
							</div> 

							<div class="col-sm-3">
								{{Form::input('number','txtGRQ', null, array('id' => 'txtGRQ', 'class' => 'form-control text-right empty cal_return_line_tot', 'step'=>'0.01'))}}
							</div>

						</div>
					</div>

					<div class="col-sm-6">
						<div class="row">
							<div class="col-sm-3">
								{{Form::input('number','txtCompanyReturnPrice', null, array('id' => 'txtCompanyReturnPrice', 'class' => 'form-control text-right empty cal_return_line_tot', 'step'=>'0.01'))}}
							</div> 

							<div class="col-sm-3">
								{{Form::input('number','txtCRQ', null, array('id' => 'txtCRQ', 'class' => 'form-control text-right empty cal_return_line_tot', 'step'=>'0.01'))}}
							</div>

							<div class="col-sm-3">
								{{Form::input('number','txtreturnLineTot', null, array('id' => 'txtreturnLineTot', 'class' => 'form-control text-right empty', 'readonly'=>TRUE, 'step'=>'0.01'))}} 
							</div>
							<div class="col-sm-3"> 
								<div class="btn btn-primary pull-right" id="add-new-return" style="margin: 0px;">Add</div>  
							</div> 
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="form-group">
			<div class="col-sm-10 col-sm-offset-2">
				<div class="row">					
					<div class="col-sm-12" id="table-return-list"></div>
				</div>
			</div>   
		</div> 






		<div class="form-group" style="margin-bottom: 3px;">
			<div class="col-sm-10 col-sm-offset-2"> 
				<div class="row">
					<div class="col-sm-6"> </div>
					<div class="col-sm-6">
						<div class="row">
							<div class="col-sm-3 text-right">&nbsp</div>
							<div class="col-sm-3 text-right"><b>Total :</b> </div>
							<div class="col-sm-3 text-right">
								<b id="lable_return_total"></b>
								{{Form::hidden ( 'txt_return_total', NULL, ['id'=>'txt_return_total'])}}
							</div>
							<div class="col-sm-3 text-right">&nbsp</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<br><br>


		<div class="form-group">
			<div class="col-sm-3 col-sm-offset-9">
				<div class="row">
					{{Form::label('subTotal', 'Sub Total', array('class' => 'col-sm-6 control-label'))}}
					<div class="col-sm-6">
						{{Form::text ( 'subTotal', NULL, ['class'=>'form-control text-right', 'readonly'=>TRUE])}}
					</div>
				</div>
			</div>
		</div>

		<div class="form-group">
			<div class="col-sm-3 col-sm-offset-9">
				<div class="row">
					{{Form::label('discount', null, array('class' => 'col-sm-6 control-label'))}}
					<div class="col-sm-6">
						<?php $tab ++ ?>
						{{Form::input('text','discount', null, ['tabindex'=> $tab, 'class' => 'form-control text-right saleDetail', 'step'=>0.01])}}
					</div>
				</div>
			</div>
		</div>

		<div class="form-group">
			<div class="col-sm-3 col-sm-offset-9">
				<div class="row">
					{{Form::label('total', 'Total', array('class' => 'col-sm-6 control-label'))}}
					<div class="col-sm-6">
						{{Form::text ( 'total', NULL, ['class'=>'form-control text-right', 'readonly'=>TRUE])}}
					</div>
				</div>
			</div>
		</div>

		<div class="form-group">
			<div class="col-sm-3 col-sm-offset-9">
				<div class="row">
					{{Form::label('cash_payment', 'Cash Payment', array('class' => 'col-sm-6 control-label'))}}
					<div class="col-sm-6">
						<?php $tab ++ ?>
						{{Form::input('text', 'cash_payment', NULL, array('tabindex'=> $tab, 'class' => 'form-control text-right saleDetail'))}}
					</div>
				</div>
			</div>
		</div>

		<div class="form-group" style="margin-bottom: 3px;">
			<div class="col-sm-7 col-sm-offset-2">
				<div class="row">
					<div style="margin-bottom: 12px;"><b>Cheque Details</b></div>
				</div>
				<div class="row">
					<div class="col-sm-12">
						<div class="row">
							<div class="col-sm-3">Bank</div>
							<div class="col-sm-3">Cheque Number</div>
							<div class="col-sm-3">Issued Date</div>
							<div class="col-sm-3">Payable Date</div>
						</div>
					</div>
				</div>
			</div>

		</div>

		<div class="form-group">
			<div class="col-sm-7 col-sm-offset-2">
				<div class="row">					
					<div class="col-sm-11">
						<?php $tab ++ ; ?>
						<div class="row" style="background-color: #ECECEC; padding: 5px 0; border-radius: 4px 0 0 4px;">
							<div class="col-sm-3">
								{{Form::select('cheque_payment_bank_id', $banksList, null, array('id' => 'cheque_payment_bank_id', 'class' => 'form-control'))}}
							</div>
							<div class="col-sm-3">
								{{Form::text('cheque_payment_cheque_number', null, array('id' => 'cheque_payment_cheque_number', 'class' => 'form-control'))}}
							</div>
							<div class="col-sm-3">
								{{Form::input('date', 'cheque_payment_issued_date', date('Y-m-d'), array('id' => 'cheque_payment_issued_date', 'class' => 'form-control'))}}
							</div>
							<div class="col-sm-3">
								{{Form::input('date', 'cheque_payment_payable_date', null, array('id' => 'cheque_payment_payable_date', 'class' => 'form-control'))}}
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="col-sm-3">
				<div class="row" style="background-color: #ECECEC; padding: 5px 0; border-radius: 0 4px 4px 0;">
					{{Form::label('cheque_payment', 'Cheque Payment', array('class' => 'col-sm-6 control-label'))}}
					<div class="col-sm-6">
						<?php $tab ++ ?>
						{{Form::input('text', 'cheque_payment', NULL, array('tabindex'=> $tab,'class' => 'form-control text-right saleDetail'))}}
					</div>
				</div>
			</div>
		</div>

		<div class="form-group">
			<div class="col-sm-2 col-sm-offset-2">
				<?php $tab ++ ?>
				{{Form::checkbox('is_completely_paid',TRUE,null,array('class'=>'myCheckbox', 'tabindex'=> $tab, 'style'=>'margin-top:10px;'))}}&nbsp;&nbsp;
				{{Form::label('is_completely_paid', null, array('class' => 'control-label'))}}

			</div>

			<div class="col-sm-3 col-sm-offset-5">
				<div class="row">
					{{Form::label('balance', 'Credit', array('class' => 'col-sm-6 control-label'))}}
					<div class="col-sm-6">
						<?php $tab ++ ?>
						{{Form::input('number', 'balance', NULL, ['class'=>'form-control text-right balance', 'readonly'=>TRUE, 'step'=>'0.01'])}}
					</div>
				</div>
			</div>
		</div>

		<div id="creditPayments" class="">
		</div>

		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<?php $tab ++ ?>
				{{Form::submit('Submit', array('tabindex'=> $tab, 'class' => 'btn btn-primary pull-right'))}}
			</div>
		</div> 
		{{Form::close()}}

	</div>
</div>

{{Form::input('hidden', 'current_edit_sales_id', NULL, ['id'=>'current_edit_sales_id'])}}
{{Form::input('hidden', 'current_edit_return_id', NULL, ['id'=>'current_edit_return_id'])}}


@stop

@section('file-footer')
<script src="/js/processes/sales/add.js"></script>
<script src="/js/processes/sales/add-return.js"></script>
<script src="/js/processes/sales/add-sales.js"></script>
<script>
loadPreviousValuesOnUnsuccessfulRedirectBack("{{Input::old('customer_id')}}");
populateCustomersForRoute("{{csrf_token()}}");
loadCreditInvoicesForCustomer("{{csrf_token()}}", jQuery.parseJSON('{{json_encode(Input::old("credit_payments"))}}'), "{{date('Y-m-d')}}", '{{Form::select(null, $banksList, null, array("class" => ""))}}'); 
displayIsCompletelyPaid();
validateChequeDetails();
addReturnRow();
selectReturnItem("{{csrf_token()}}");
autoloadItemForReturn("{{csrf_token()}}");
editReturn();
deleteReturn();

addSalesRow();
autoloadItemForSales("{{csrf_token()}}");
selectSalesItem("{{csrf_token()}}");
editSales();
deleteSales();



setMethodToEnter();
</script>
@stop