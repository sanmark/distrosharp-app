@extends('web._templates.template')

@section('body')
<div class="panel panel-default well">
	<div class="panel-heading">
		<h3 class="panel-title">Add Sale</h3>
	</div>
	<div class="panel-body">
		{{Form::open(['class'=>'form-horizontal', 'role'=>'form','onsubmit'=>'checkPaidAndFreeSum()'])}}
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
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<div class="row">
					<div class="col-sm-7">
						<div class="row">
							<div class="col-sm-4"><b>Available</b></div>
							<div class="col-sm-4"><b>Price</b></div>
							<div class="col-sm-4"><b>Paid Qty</b></div>
						</div>
					</div>
					<div class="col-sm-5">
						<div class="col-sm-6"><b>Free Qty</b></div> 
						<div class="col-sm-6"><b>Line Total</b></div> 
					</div>
				</div>
			</div>
		</div>
		<?php $tab = 3 ; ?>
		@foreach($items as $item)
		<div class="form-group">
			@if(false!=$item->getImageUrl())
			<div class="col-sm-2 text-right">
				<a href="#"  class="img-label">
					{{$item->name}}
					<div class="tool-tip slideIn bottom" >
						<img class="tool-tip-img" src="{{$item->getImageUrl()}}" >
					</div>
				</a>
			</div>
			@else
			{{Form::label(null, $item->name, array('class' => 'col-sm-2 control-label'))}}
			@endif

			<div class="col-sm-10">
				<div class="row">
					<div class="col-sm-7">
						<div class="row">
							<div class="col-sm-4">
								<p class="form-control text-right" disabled="true">{{$stockDetails[$item->id]['good_quantity']}}</p>
								{{Form::hidden('items['.$item->id.'][available_quantity]', $stockDetails[$item->id]['good_quantity'])}}
							</div>
							<div class="col-sm-4">
								{{Form::input('number','items['.$item->id.'][price]',$item->current_selling_price, array('class' => 'form-control text-right saleDetail paid_quantity', 'data-item-id'=>$item->id, 'step'=>0.01))}}
							</div>

							<div class="col-sm-4">
								<?php $tab ++ ; ?>
								{{Form::input('number','items['.$item->id.'][paid_quantity]',NULL, array('tabindex'=> $tab, 'class' => 'form-control text-right saleDetail', 'data-item-id'=>$item->id,'id'=>$item->id,'max'=>$stockDetails[$item->id]['good_quantity']))}}
							</div>
						</div>
					</div>

					<div class="col-sm-5">
						<div class="row">
							<?php $tab ++ ; ?>
							<div class="col-sm-6">
								{{Form::input('number','items['.$item->id.'][free_quantity]',NULL, array('tabindex'=> $tab, 'class' => 'form-control text-right saleDetail', 'data-item-id'=>$item->id,'id'=>$item->id,'max'=>$stockDetails[$item->id]['good_quantity']))}}
							</div> 

							<div class="col-sm-6">
								{{Form::text('items['.$item->id.'][line_total]',NULL, array('class' => 'form-control text-right lineTotal', 'readonly'=>TRUE))}}
							</div>  
						</div>  
					</div>  

				</div>
			</div>
		</div>
		@endforeach


		<div class="form-group" style="margin-bottom: 3px;">
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
					<div class="col-sm-6" id="scrollTop"> 
						<div class="row"> 
							<div class="col-sm-3">
								{{Form::text('txtReturnItemCode', null, array('id' => 'txtReturnItemCode', 'class' => 'form-control', 'autocomplete' => 'off','onClick'=>'this.select();'))}} 
								<div id="dublicate-error-message"></div>
								{{Form::input('hidden','txtreturnId', null, array('id' => 'txtreturnId'))}}
							</div>

							<div class="col-sm-3">
								{{Form::text('txtReturnItemName', null, array('id' => 'txtReturnItemName', 'class' => 'form-control', 'autocomplete' => 'off','onClick'=>'this.select();'))}}
								<img src="../../images/loading_small.gif" style="display: none; position: absolute; margin: -27px 0px 0px 109px;" id="loader-img">
								<ul id="item_list" class="item-list-main-bar"> </ul> 
							</div> 

							<div class="col-sm-3">
								{{Form::input('number','txtGoodReturnPrice', null, array('id' => 'txtGoodReturnPrice', 'class' => 'form-control text-right empty cal_return_line_tot'))}}
							</div> 

							<div class="col-sm-3">
								{{Form::input('number','txtGRQ', null, array('id' => 'txtGRQ', 'class' => 'form-control text-right empty cal_return_line_tot'))}}
							</div>

						</div>
					</div>

					<div class="col-sm-6">
						<div class="row">
							<div class="col-sm-3">
								{{Form::input('number','txtCompanyReturnPrice', null, array('id' => 'txtCompanyReturnPrice', 'class' => 'form-control text-right empty cal_return_line_tot'))}}
							</div> 

							<div class="col-sm-3">
								{{Form::input('number','txtCRQ', null, array('id' => 'txtCRQ', 'class' => 'form-control text-right empty cal_return_line_tot'))}}
							</div>

							<div class="col-sm-3">
								{{Form::input('number','txtreturnLineTot', null, array('id' => 'txtreturnLineTot', 'class' => 'form-control text-right empty', 'readonly'=>TRUE))}} 
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
						{{Form::input('number', 'balance', NULL, ['class'=>'form-control text-right balance', 'readonly'=>TRUE])}}
					</div>
				</div>
			</div>
		</div>

		<div id="creditPayments" class="">
		</div>

		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<?php $tab ++ ?>
				{{Form::submit('Submit', array('tabindex'=> $tab, 'class' => 'btn btn-primary pull-right','onclick'=>'checkPaidAndFreeSum()'))}}
			</div>
		</div>
		{{Form::hidden('item_list_amount',count($items))}}
		{{Form::close()}}

	</div>
</div>
@stop

@section('file-footer')
<script src="/js/processes/sales/add.js"></script>
<script src="/js/processes/sales/auto-select.js"></script>
<script>
loadPreviousValuesOnUnsuccessfulRedirectBack("{{Input::old('customer_id')}}");
populateCustomersForRoute("{{csrf_token()}}");
loadCreditInvoicesForCustomer("{{csrf_token()}}", jQuery.parseJSON('{{json_encode(Input::old("credit_payments"))}}'), "{{date('Y-m-d')}}", '{{Form::select(null, $banksList, null, array("class" => ""))}}');
calculateLineTotal();
displayTotal();
displaySubTotal();
displayBalance();
checkPaidAndFreeSum();
displayIsCompletelyPaid();
validateChequeDetails();
addReturnRow();
selectReturnItem("{{csrf_token()}}");
autoloadItem("{{csrf_token()}}");
editReturn();
deleteReturn();
</script>
@stop