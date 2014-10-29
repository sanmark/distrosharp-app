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
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<div class="row">
					<div class="col-sm-2"><b>Price</b></div>
					<div class="col-sm-2"><b>Quantity</b></div>
					<div class="col-sm-2"><b>Free Quantity</b></div>
					<div class="col-sm-2"><b>Expire Date</b></div>
					<div class="col-sm-2"><b>Batch Number</b></div>
					<div class="col-sm-2"><b>Line Total</b></div>
				</div>
			</div>
		</div>
		<div id="add_purchase_items">
			<?php $tab = 3 ; ?>
			@foreach($itemRows as $itemRow)
			<?php $tab ++ ; ?>
			<div class="form-group">
				{{Form::hidden('item_id_'.$itemRow->id,$itemRow->id)}}
				@if(false!=$itemRow->getImageUrl())
				<div class="col-sm-2 text-right">
					<a href="#"  class="img-label">
						{{$itemRow->name}}
						<div class="tool-tip slideIn bottom" >
							<img class="tool-tip-img" src="{{$itemRow->getImageUrl()}}" >
						</div>
					</a>
				</div>
				@else
				{{Form::label(null, $itemRow->name,array('class' => 'col-sm-2 control-label'))}}
				@endif
				<div class="col-sm-10">
					<div class="row">
						<div class="col-sm-2">
							{{Form::input('number','buying_price_'.$itemRow->id,$itemRow->current_buying_price, array('class' => 'form-control text-right', 'step'=>'any','onkeyup'=>'changeOnPrice(this.id,this.value)','id'=>$itemRow->id))}}
						</div>
						<div class="col-sm-2">
							<?php $tab ++ ; ?>
							{{Form::input('number','quantity_'.$itemRow->id, null, array('tabindex'=> $tab,'class' => 'form-control text-right', 'step'=>'any','onkeyup'=>'changeOnQuantity(this.id,this.value)','id'=>$itemRow->id))}}
						</div>
						<div class="col-sm-2">
							<?php $tab ++ ; ?>
							{{Form::input('number','free_quantity_'.$itemRow->id, null, array('tabindex'=> $tab, 'class' => 'form-control text-right', 'step'=>'any'))}}
						</div>
						<div class="col-sm-2">
							{{Form::input('date','exp_date_'.$itemRow->id, null, array('class' => 'form-control'))}}
						</div>
						<div class="col-sm-2">
							{{Form::text('batch_number_'.$itemRow->id, null, array('class' => 'form-control'))}}
						</div>
						<div class="col-sm-2">
							{{Form::text('line_total_'.$itemRow->id, null, array('class' => 'form-control text-right', 'step'=>'any','readonly'=>'readonly'))}}
						</div>
					</div>
				</div>
			</div>
			@endforeach
		</div>

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
								<?php $tab ++ ; ?>
								{{Form::input('text', 'cash_payment', NULL, array('id' => 'cash_payment', 'tabindex'=> $tab,'class' => 'form-control text-right saleDetail'))}}
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
								{{Form::input('text', 'cheque_payment', NULL, array('id' => 'cheque_payment','tabindex'=> $tab,'class' => 'form-control text-right saleDetail'))}}
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
								{{Form::input('text', 'balance', NULL, array('tabindex'=> $tab,'class' => 'form-control text-right','readonly'=>'readonly','style'=>'font-weight:bolder;'))}}
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
								<?php $tab ++ ; ?>
								{{Form::text('other_expenses_details',null, array('tabindex'=> $tab,'class' => 'form-control'))}}
							</div>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="row">
							{{Form::label(null, 'Other Expense Amount', array('class' => 'col-sm-6 control-label', 'style' => 'padding-top: 0;'))}}
							<div class="col-sm-6">
								<?php $tab ++ ; ?>
								{{Form::input('number','other_expense_amount', null, array('tabindex'=> $tab,'class' => 'form-control','step' => 'any'))}}
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<?php $tab ++ ; ?>
				{{Form::submit('Submit', array('tabindex'=> $tab,'class' => 'btn btn-primary pull-right'))}}
			</div>
		</div>

		{{Form::close()}}

	</div>
</div>

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
<script>
	displayBalance();
	displayIsCompletelyPaid();
	validateChequeDetails();
</script>
@stop