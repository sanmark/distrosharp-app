@extends('web._templates.template')

@section('body')
<div class="panel panel-default well">
	<div class="panel-heading">
		<h3 class="panel-title">Edit Sale</h3>
	</div>
	<div class="panel-body">
		{{Form::open(['class'=>'form-horizontal', 'role'=>'form'])}}
		<br />
		<div class="form-group">
			{{Form::label('System Inv. Id',null, array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-3">
				{{Form::label('ID', $sellingInvoice->id, array('class' => 'form-control'))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('date_time',null, array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-3">
				{{Form::input('text', 'date_time', $sellingInvoice->date_time,array('class' => 'form-control','required'=>TRUE))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('rep',null,array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-3">
				{{Form::label('rep',$sellingInvoice->rep->username,array('class' => 'form-control','required'=>TRUE))}}
				{{Form::input('hidden', 'rep_id', $sellingInvoice->rep->id,array('id' => 'rep_id'))}}
			</div>
		</div> 
		<div class="form-group">
			{{Form::label('route_id', 'Route', array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-3"> 
				{{Form::select('route_id',$routes, $sellingInvoice->customer->route_id, array('tabindex'=>'1', 'class' => 'form-control'))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('customer_id','Customer',array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-3">
				{{Form::select('customer_id', $customerDropDown, $sellingInvoice->customer_id,array('class' => 'form-control','required'=>TRUE))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('printed_invoice_number',null,array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-3">
				{{Form::text('printed_invoice_number', $sellingInvoice->printed_invoice_number,array('class' => 'form-control','required'=>TRUE))}}
			</div>
		</div>




		<!-- sales ---------------------------------->

		<div class="form-group" style="margin-bottom: 3px;" id="scrollTopSales">
			<div class="col-sm-10 col-sm-offset-2">
				<div class="row">
					<div style="margin-bottom: 12px;"><h4><b>Sales</b></h4></div>
				</div>
				<div class="row">
					<div class="col-sm-7">
						<div class="row">
							<div class="col-sm-3"><b>Code</div>
							<div class="col-sm-5">Item Name</div>
							<div class="col-sm-2 text-right">Available</div>
							<div class="col-sm-2 text-right">Price</div>
						</div>
					</div>
					<div class="col-sm-5">
						<div class="row">
							<div class="col-sm-3 text-right">Paid Qty</div>
							<div class="col-sm-3 text-right">Free Qty</div>
							<div class="col-sm-3 text-right">Line Total</div>
							<div class="col-sm-3">&nbsp;</b></div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-10 col-sm-offset-2">
				<div class="row">					
					<div class="col-sm-7"> 
						<div class="row"> 
							<div class="col-sm-3">
								{{Form::text('txtItemCode', null, array('id' => 'txtItemCode', 'class' => 'form-control', 'autocomplete' => 'off','onClick'=>'this.select();'))}} 
								<div id="dublicate-error-message"></div>
								{{Form::input('hidden','txtItemId', null, array('id' => 'txtItemId'))}}
							</div>

							<div class="col-sm-5">
								{{Form::text('txtItemName', null, array('id' => 'txtItemName', 'class' => 'form-control', 'autocomplete' => 'off','onClick'=>'this.select();'))}}
								<img src="../../../images/loading_small.gif" style="display: none; position: absolute; margin: -27px 3px 0px 77px;" id="loader-img">
								<ul id="item_list_f_sales" class="item-list-main-bar"> </ul> 
							</div> 

							<div class="col-sm-2">
								{{Form::input('number','txtAvailable', null, array('id' => 'txtAvailable', 'class' => 'form-control text-right empty cal_return_line_tot', 'readonly'=>TRUE, 'step'=>'0.01'))}}
							</div> 

							<div class="col-sm-2">
								{{Form::input('number','txtPrice', null, array('id' => 'txtPrice', 'class' => 'form-control text-right empty cal_return_line_tot', 'step'=>'0.01'))}}
							</div>

						</div>
					</div>

					<div class="col-sm-5">
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


		<?php $salesTotal	 = 0 ; ?>
		<div class="form-group">
			<div class="col-sm-10 col-sm-offset-2">
				<div class="row">					
					<div class="col-sm-12" id="table-sales-list">
						@foreach($items as $item) 
						<?php
						$sellingItem = $sellingInvoice -> sellingItems -> filter ( function($sellingItem) use($item)
							{
								if ( $sellingItem -> item_id == $item -> id )
								{
									return TRUE ;
								}
							} ) -> first () ;
						?>

						@if($sellingItem['paid_quantity'] || $sellingItem['free_quantity'] )
						<?php $salesTotal += $sellingItem -> getSalesLineTotal () ?>
						<div id="salse-item-row_{{$item->id}}" class="row item-list-table">
							<div class="col-sm-7">
								<div class="row">
									<div class="col-sm-3">
										{{ $item->code }}
									</div>
									<div class="col-sm-5">
										{{$item->name}}
									</div>
									<div class="col-sm-2 text-right" id="divAvailable_{{$item->id}}">

									</div>
									<div class="col-sm-2 text-right"> 
										{{number_format(ObjectHelper::nullIfNonObject($sellingItem, 'price'),2)}} 
									</div> 
								</div> 
							</div>
							<div class="col-sm-5">
								<div class="row">
									<div class="col-sm-3 text-right"> 
										{{ObjectHelper::nullIfNonObject($sellingItem, 'paid_quantity')}} 
									</div>
									<div class="col-sm-3 text-right"> 
										{{ObjectHelper::nullIfNonObject($sellingItem, 'free_quantity')}}

									</div>
									<div class="col-sm-3 text-right"> 
										{{number_format($sellingItem->getSalesLineTotal(),2)}}
									</div>
									<div class="col-sm-3">
										<a title="Click to edit {{$item->name}}" class="edit-sales" id="{{$item->id}}"> Edit </a> 
										/ 
										<a title="Click to delete {{$item->name}}" class="delete-sales" id="{{$item->id}}"> Delete </a>
									</div> 
									{{Form::input('hidden', null, $item->code,['id' => 'item_code_'.$item->id])}}
									{{Form::input('hidden', null, $item->name,['id' => 'item_name_'.$item->id])}}
									{{Form::input('hidden', null, $item->id,['id' => 'available_quantity'.$item->id,'class' => 'available_quantity'])}}

									{{Form::input('hidden', 'items['.$item->id.'][price]', ObjectHelper::nullIfNonObject($sellingItem, 'price'),['id' => 'price'.$item->id])}} 
									{{Form::input('hidden', 'items['.$item->id.'][paid_quantity]', ObjectHelper::nullIfNonObject($sellingItem, 'paid_quantity'),['id' => 'paid_quantity'.$item->id])}} 
									{{Form::input('hidden', 'items['.$item->id.'][free_quantity]', ObjectHelper::nullIfNonObject($sellingItem, 'free_quantity'),['id' => 'free_quantity'.$item->id])}} 

									{{Form::input('hidden', 'sales_line_total', $sellingItem->getSalesLineTotal(),['id' => 'sales_line_total'.$item->id,'class' => 'sales_line_total'])}}

								</div>
							</div>
						</div>  	
						@endif  
						@endforeach
					</div>
				</div>
			</div>
		</div>
		<div class="form-group" style="margin-bottom: 3px;">
			<div class="col-sm-10 col-sm-offset-2"> 
				<div class="row">
					<div class="col-sm-7"> </div>
					<div class="col-sm-5">
						<div class="row">
							<div class="col-sm-3 text-right">&nbsp</div>
							<div class="col-sm-3 text-right"><b>Total :</b> </div>
							<div class="col-sm-3 text-right">
								<b id="lable_sales_total">{{number_format($salesTotal,2)}}</b>
								{{Form::hidden ( 'txt_sales_total', $salesTotal, ['id'=>'txt_sales_total'])}}
							</div>
							<div class="col-sm-3 text-right">&nbsp</div>
						</div>
					</div>
				</div>
			</div>
		</div> 






		<!-- return ---------------------------------->

		<div class="form-group" style="margin-bottom: 3px;"  id="scrollTopReturn">
			<div class="col-sm-10 col-sm-offset-2">
				<div class="row">
					<div style="margin-bottom: 12px;"><h4><b>Returns</b></h4></div>
				</div>
				<div class="row">
					<div class="col-sm-7">
						<div class="row">
							<div class="col-sm-3">Item Code</div>
							<div class="col-sm-5">Item Name</div>
							<div class="col-sm-2 text-right">GR Price</div>
							<div class="col-sm-2 text-right">GR Qty</div>
						</div>
					</div>
					<div class="col-sm-5">
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
					<div class="col-sm-7"> 
						<div class="row"> 
							<div class="col-sm-3">
								{{Form::text('txtReturnItemCode', null, array('id' => 'txtReturnItemCode', 'class' => 'form-control', 'autocomplete' => 'off','onClick'=>'this.select();'))}} 
								<div id="return-dublicate-error-message"></div>
								{{Form::input('hidden','txtreturnId', null, array('id' => 'txtreturnId'))}}
							</div>

							<div class="col-sm-5">
								{{Form::text('txtReturnItemName', null, array('id' => 'txtReturnItemName', 'class' => 'form-control', 'autocomplete' => 'off','onClick'=>'this.select();'))}}
								<img src="../../../images/loading_small.gif" style="display: none; position: absolute; margin: -27px 3px 0px 77px;" id="loader-img-return">
								<ul id="item_list_f_return" class="item-list-main-bar"> </ul> 
							</div> 

							<div class="col-sm-2">
								{{Form::input('number','txtGoodReturnPrice', null, array('id' => 'txtGoodReturnPrice', 'class' => 'form-control text-right empty cal_return_line_tot', 'step'=>'0.01'))}}
							</div> 

							<div class="col-sm-2">
								{{Form::input('number','txtGRQ', null, array('id' => 'txtGRQ', 'class' => 'form-control text-right empty cal_return_line_tot', 'step'=>'0.01'))}}
							</div>

						</div>
					</div>

					<div class="col-sm-5">
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




		<?php $returnTotal = 0 ; ?>
		<div class="form-group">
			<div class="col-sm-10 col-sm-offset-2">
				<div class="row">					
					<div class="col-sm-12" id="table-return-list">
						@foreach($items as $item)
						<?php
						$sellingItem = $sellingInvoice -> sellingItems -> filter ( function($sellingItem) use($item)
							{
								if ( $sellingItem -> item_id == $item -> id )
								{
									return TRUE ;
								}
							} ) -> first () ;
						?>

						@if($sellingItem['good_return_quantity'] || $sellingItem['company_return_quantity'] )

						<div id="return-item-row_{{$item->id}}" class="row item-list-table">
							<div class="col-sm-7">
								<div class="row">
									<div class="col-sm-3">
										{{ $item->code }}
									</div>
									<div class="col-sm-5">
										{{$item->name}}
									</div>
									<div class="col-sm-2 text-right">
                                        {{number_format(ObjectHelper::nullIfNonObject($sellingItem, 'good_return_price'),2)}}
									</div>
									<div class="col-sm-2 text-right"> 
										{{ ObjectHelper::nullIfNonObject($sellingItem, 'good_return_quantity')}} 
									</div> 
								</div> 
							</div>
							<div class="col-sm-5">
								<div class="row">
									<div class="col-sm-3 text-right"> 
										{{number_format(ObjectHelper::nullIfNonObject($sellingItem, 'company_return_price'),2)}}
									</div>
									<div class="col-sm-3 text-right"> 
										{{ ObjectHelper::nullIfNonObject($sellingItem, 'company_return_quantity')}}

									</div>
									<div class="col-sm-3 text-right"> 
										<?php $returnTotal += $sellingItem -> getReturnLineTotal () ; ?>
										{{number_format($sellingItem->getReturnLineTotal(),2)}}
									</div>
									<div class="col-sm-3">
										<a title="Click to edit {{$item->name}}" class="edit-return" id="{{$item->id}}"> Edit </a> 
										/ 
										<a title="Click to delete {{$item->name}}" class="delete-return" id="{{$item->id}}"> Delete </a>
									</div> 
									{{Form::input('hidden', null, $item->code,['id' => 'return_item_code_'.$item->id])}}
									{{Form::input('hidden', null, $item->name,['id' => 'item_name_'.$item->id])}}

									{{Form::input('hidden', 'items['.$item->id.'][good_return_price]', ObjectHelper::nullIfNonObject($sellingItem, 'good_return_price'),['id' => 'good_return_price_'.$item->id])}} 

									{{Form::input('hidden', 'items['.$item->id.'][good_return_quantity]', ObjectHelper::nullIfNonObject($sellingItem, 'good_return_quantity'),['id' => 'good_return_quantity'.$item->id])}} 
									{{Form::input('hidden', 'items['.$item->id.'][company_return_price]', ObjectHelper::nullIfNonObject($sellingItem, 'company_return_price'),['id' => 'company_return_price_'.$item->id])}} 
									{{Form::input('hidden', 'items['.$item->id.'][company_return_quantity]', ObjectHelper::nullIfNonObject($sellingItem, 'company_return_quantity'),['id' => 'company_return_quantity'.$item->id])}}  
									{{Form::input('hidden', 'return_line_total', $sellingItem->getReturnLineTotal(),['id' => 'line_total'.$item->id,'class' => 'return_line_total'])}}

								</div>
							</div>
						</div>

						@endif 
						@endforeach
					</div>
				</div>
			</div> 
		</div>


        <div class="form-group" style="margin-bottom: 3px;">
			<div class="col-sm-10 col-sm-offset-2"> 
				<div class="row">
					<div class="col-sm-7"> </div>
					<div class="col-sm-5">
						<div class="row">
							<div class="col-sm-3 text-right">&nbsp</div>
							<div class="col-sm-3 text-right"><b>Total :</b> </div>
							<div class="col-sm-3 text-right">
								<b id="lable_return_total">{{number_format($returnTotal,2)}}</b>
								{{Form::hidden ( 'txt_return_total', $returnTotal, ['id'=>'txt_return_total'])}}
							</div>
							<div class="col-sm-3 text-right">&nbsp</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<br><br>





		<!-- total ---------------------------------->
		<div class="form-group">
			<div class="col-sm-3 col-sm-offset-9">
				<div class="row">
					{{Form::label('subTotal', 'Sub Total', array('class' => 'col-sm-6 control-label'))}}
					<div class="col-sm-6">
						{{Form::text ( 'subTotal',number_format($salesTotal - $returnTotal,2) , ['class'=>'form-control text-right', 'readonly'=>TRUE])}}
					</div>
				</div>
			</div>
		</div>

		<div class="form-group">
			<div class="col-sm-3 col-sm-offset-9">
				<div class="row">
					{{Form::label('discount',null,array('class' => 'col-sm-6 control-label'))}}
					<div class="col-sm-6">
						{{Form::input('number', 'discount', $sellingInvoice->discount,array('class'=>'form-control text-right', 'step'=>'0.01'))}}
					</div>
				</div>
			</div>
		</div>

		<div class="form-group">
			<div class="col-sm-3 col-sm-offset-9">
				<div class="row">
					{{Form::label('total', 'Total', array('class' => 'col-sm-6 control-label'))}}
					<div class="col-sm-6">
						{{Form::text ( 'total', $salesTotal - $returnTotal - $sellingInvoice->discount, ['class'=>'form-control text-right', 'readonly'=>TRUE])}}
					</div>
				</div>
			</div>
		</div>

		<div class="form-group">
			<div class="col-sm-3 col-sm-offset-9 ">
				<div class="row">
					{{Form::label('new_cash_payment', null, array('class'=>'col-sm-6 control-label '))}}
					<div class="col-sm-6 ">
						{{Form::input('number', 'new_cash_payment', NULL, array('class' => 'form-control text-right', 'id' => 'cash_payment', 'step'=>'0.01'))}} 
					</div>
				</div>
			</div>
		</div>


		<div class="form-group" style="margin-bottom: 3px;">
			<div class="col-sm-7 col-sm-offset-2">
				<div class="row">
					<div style="margin-bottom: 12px;"><b>New Cheque Payment</b></div>
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
						<div class="row" style="background-color: #ECECEC; padding: 5px 0; border-radius: 4px 0 0 4px;">
							<div class="col-sm-3">

								{{Form::select('cheque_payment_bank_id', $banksList, null, array('class' => 'form-control'))}} 
							</div>
							<div class="col-sm-3">
								{{Form::text('cheque_payment_cheque_number', null, array('class' => 'form-control'))}}
							</div>
							<div class="col-sm-3">
								{{Form::input('date', 'cheque_payment_issued_date', null, array('class' => 'form-control'))}}
							</div>
							<div class="col-sm-3">
								{{Form::input('date', 'cheque_payment_payable_date', null, array('class' => 'form-control'))}}
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="col-sm-3">
				<div class="row" style="background-color: #ECECEC; padding: 5px 0; border-radius: 0 4px 4px 0;">
					<label for="cheque_payment" class="col-sm-6 control-label">Cheque Payment</label>
					<div class="col-sm-6">
						<div class="form-control-wrapper">
							{{Form::input('number', 'new_cheque_payment', NULL, array('class' => 'form-control text-right', 'id' => 'cheque_payment', 'step'=>'0.01'))}}
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="form-group">
			<div class="col-sm-2 col-sm-offset-2">
				{{Form::checkbox('is_completely_paid', TRUE, $sellingInvoice->is_completely_paid,array('style'=>'margin-top:10px;'))}}
				{{Form::label('is_completely_paid', null, array('class' => 'control-label'))}}

			</div>

			<div class="col-sm-3 col-sm-offset-5">

			</div>
		</div>


		<br><br>

		<!-- payments ---------------------------------->

		<div class="form-group" style="margin-bottom: 3px;" id="scrollTopSales">
			<div class="col-sm-10 col-sm-offset-2">
				<div class="row">
					<div style="margin-bottom: 12px;"><h4><b>Past Payments</b></h4></div>
				</div>
				<div class="row">
					<div class="col-sm-7">
						<div class="row">
							<div class="col-sm-2"><b>ID</div>
							<div class="col-sm-3">Type</div>
							<div class="col-sm-3">To</div>
							<div class="col-sm-4">Date</div>
						</div>
					</div>
					<div class="col-sm-5">
						<div class="row">
							<div class="col-sm-4 text-right">Amount</div>
							<div class="col-sm-4">Paid Invoice</div>
							<div class="col-sm-4">&nbsp;</b></div> 
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php $total_payment = 0 ; ?>
		<div class="form-group">
			@foreach($sellingInvoice->financeTransfers as $financeTransfer)	 
			<div class="col-sm-10 col-sm-offset-2">
				<div class="row">					
					<div class="col-sm-12" id="">
						<div id="salse-item-row_2" class="row item-list-table">
							<div class="col-sm-7">
								<div class="row">
									<div class="col-sm-2">
										{{$financeTransfer->id}}
									</div>
									<div class="col-sm-3"> 
										@if($financeTransfer->isCheque())
										Cheque
										@elseif($financeTransfer->isCash())
										Cash
										@endif
									</div>
									<div class="col-sm-3">
										{{HTML::link(URL::action('finances.transfers.view', [$financeTransfer->to_id]),$financeTransfer->toAccount->name)}}
									</div>
									<div class="col-sm-4">
										{{$financeTransfer->date_time}}
									</div> 
								</div> 
							</div>
							<div class="col-sm-5">
								<div class="row">
									<div class="col-sm-4 text-right">
										{{number_format($financeTransfer->amount,2)}}
										<?php $total_payment += $financeTransfer -> amount ; ?>
									</div>
									<div class="col-sm-4"> 
										@if($financeTransfer->getSellingInvoice()->pivot->paid_invoice_id == $sellingInvoice->id)
										This
										@else
										{{HTML::link(URL::action('processes.sales.edit', [$financeTransfer->getSellingInvoice()->pivot->paid_invoice_id]), $financeTransfer->getSellingInvoice()->pivot->paid_invoice_id)}}
										@endif
									</div>
									<div class="col-sm-4">
										{{HTML::link(URL::action('finances.transfers.edit', [$financeTransfer->id]), 'Edit...')}}
									</div> 
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>  
			@endforeach 
		</div>
		<div class="form-group" style="margin-bottom: 3px;" id="scrollTopSales">
			<div class="col-sm-10 col-sm-offset-2">
				<div class="row">

				</div>
				<div class="row">
					<div class="col-sm-7"> 
						<div class="row"> 
							<div class="col-sm-8"> </div>
							<div class="col-sm-4 text-right"><b>Total : </b></div>
						</div> 
					</div>
					<div class="col-sm-5">
						<div class="row">
							<div class="col-sm-4 text-right">
								<b>
									{{number_format($total_payment,2)}}
								</b>
								{{Form::input('hidden', 'total_payment', $total_payment, array(  'id' => 'total_payment' ))}}
							</div>
							<div class="col-sm-4"></div>
							<div class="col-sm-4">&nbsp;</b></div> 
						</div>
					</div>
				</div>
			</div>
		</div>


		<div class="form-group">
			<div class="col-sm-2 col-sm-offset-2">

			</div>

			<div class="col-sm-3 col-sm-offset-5">
				<div class="row">
					<label for="balance" class="col-sm-6 control-label">Credit</label>
					<div class="col-sm-6">
						<div class="form-control-wrapper">
							{{Form::text ( 'balance', $salesTotal - $returnTotal - $sellingInvoice->discount  - $total_payment, ['class'=>'form-control text-right', 'id'=>'balance', 'readonly'=>TRUE])}}
						</div>
					</div>
				</div>
			</div>
		</div>

		<br/><br/> 
 
		<div id="creditPayments" class="">
		</div> 
		
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				{{Form::submit('Submit', array('class' => 'btn btn-primary pull-right'))}}
				{{ link_to(URL::previous(), 'Back', ['class' => 'btn btn-default pull-right back-btn-margin']) }}
			</div>
		</div>
		{{Form::close()}}
	</div>
</div>
{{Form::input('hidden', 'current_edit_sales_id', NULL, ['id'=>'current_edit_sales_id'])}}
{{Form::input('hidden', 'current_edit_return_id', NULL, ['id'=>'current_edit_return_id'])}}


@stop

@section('file-footer')

<script src="/js/processes/sales/edit.js"></script> 
<script src="/js/processes/sales/edit-sales.js"></script>
<script src="/js/processes/sales/edit-return.js"></script> 
<script type="text/javascript">
populateCustomersForRoute("{{csrf_token()}}");


addSalesRow();
autoloadItemForSales("{{csrf_token()}}");
selectSalesItem("{{csrf_token()}}");
editSales();
deleteSales();

setMethodToEnter();

setAvailableQuantity("{{csrf_token()}}");


loadCreditInvoicesForCustomer("{{csrf_token()}}", jQuery.parseJSON('{{json_encode(Input::old("credit_payments"))}}'), "{{date('Y-m-d')}}", '{{Form::select(null, $banksList, null, array("class" => ""))}}');
addReturnRow();
selectReturnItem("{{csrf_token()}}");
autoloadItemForReturn("{{csrf_token()}}");
editReturn();
deleteReturn();
</script>

@stop