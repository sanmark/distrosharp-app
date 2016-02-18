@extends('web._templates.template')

@section('body')
<div class="panel panel-default well">
	<div class="panel-heading">
		<h3 class="panel-title">View Sale</h3>
		{{HTML::link(URL::action('processes.sales.edit', [$sellingInvoice->id]),'Edit Sale',['class'=>'panel-title-btn btn btn-success btn-sm pull-right withripple di-editsale'])}}
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-7">
				<div class="col-sm-3 sales-box control-label">System Inv. Id </div>
				<div class="col-sm-4 sales-box-bg">{{$sellingInvoice->id}}</div>                          
			</div>
			<div class="col-md-7">
				<div class="col-sm-3 sales-box control-label">Date Time </div>
				<div class="col-sm-4 sales-box-bg">{{$sellingInvoice->date_time}}</div>                   
			</div>
			<div class="col-md-7">
				<div class="col-sm-3 sales-box control-label">Rep </div>
				<div class="col-sm-4 sales-box-bg">{{$sellingInvoice->rep->username}}</div>               
			</div>
			<div class="col-md-7">
				<div class="col-sm-3 sales-box control-label">Route</div>
				<div class="col-sm-4 sales-box-bg">{{$sellingInvoice->customer->route['name']}}</div>
			</div>
			<div class="col-md-7">
				<div class="col-sm-3 sales-box control-label">Customer</div>
				<div class="col-sm-4 sales-box-bg">{{$sellingInvoice->customer->name}}</div>
			</div>
			<div class="col-md-7">
				<div class="col-sm-3 sales-box control-label">Printed nvoice Number</div>
				<div class="col-sm-4 sales-box-bg">{{$sellingInvoice->printed_invoice_number}}</div>
			</div>
		</div>
		
		<?php $salesTotal	 = 0 ; ?>
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
							</div> 
						</div>
					</div>
				</div>  	
				@endif  
				@endforeach
			</div>
		</div>
		<div class="form-group" style="margin-bottom: 10px;">
			<div class="col-sm-10 col-sm-offset-2"> 
				<div class="row">
					<div class="col-sm-7"> </div>
					<div class="col-sm-5">
						<div class="row">
							<div class="col-sm-3 text-right">&nbsp</div>
							<div class="col-sm-3 text-right"><b>Total :</b> </div>
							<div class="col-sm-3 text-right">
								<b id="lable_sales_total">{{number_format($salesTotal,2)}}</b>
							</div>
							<div class="col-sm-3 text-right">&nbsp</div>
						</div>
					</div>
				</div>
			</div>
		</div> 

		<!-- return ---------------------------------->


		<?php $returnTotal = 0 ; ?>
		<?php
		$sellingItem = $sellingInvoice -> sellingItems -> filter ( function($sellingItem) use($item)
			{
				if ( $sellingItem -> item_id == $item -> id )
				{
					return TRUE ;
				}
			} ) -> first () ;
		?>

		<?php $returnTotal += $sellingItem -> getReturnLineTotal () ; ?>


		<!-- total ---------------------------------->
		<div class="row sale-sub-total" >
			<div class="col-sm-4">
				<div class="col-sm-3 sales-box control-label">Sub Total</div>
				<div class="col-sm-4 sales-box-bg">{{number_format($salesTotal - $returnTotal,2)}}</div>
			</div>
			<div class="col-sm-4">
				<div class="col-sm-3 sales-box control-label">Discount</div>
				<div class="col-sm-4 sales-box-bg">{{$sellingInvoice->discount}}</div>
			</div>
			<div class="col-sm-4">
				<div class="col-sm-3 sales-box control-label">Total</div>
				<div class="col-sm-4 sales-box-bg">{{$salesTotal - $returnTotal - $sellingInvoice->discount}}</div>
			</div>
		</div>

		<!-- payments ---------------------------------->
		<div class="row">
			<div class="col-md-12" style="margin-bottom: 12px;"><h4><b>Past Payments</b></h4></div>
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

		<?php $total_payment = 0 ; ?>
		<div class="form-group">
			@foreach($sellingInvoice->financeTransfers as $financeTransfer)
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
									{{$financeTransfer->toAccount->name}}
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
					<div class="col-sm-7"> 
						<div class="row"> 
							<div class="col-sm-8"> </div>
							<div class="col-sm-4 text-right"><b>Total : </b></div>
						</div> 
					</div>
					<div class="col-sm-3">
						<div class="row">
							<div class="col-sm-4 text-right">
								<b>
									{{number_format($total_payment,2)}}
								</b>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div> 

		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
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