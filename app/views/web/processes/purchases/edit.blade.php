@extends('web._templates.template')

@section('body')

<div class="panel panel-default well">
	<div class="panel-heading">
		<h3 class="panel-title">Edit Purchase</h3>
	</div>
	<div class="panel-body">

		{{Form::open(['class'=>'form-horizontal', 'role'=>'form'])}}
		<br />
		<div class="form-group">
			{{Form::label('date', null, array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-3">
				{{Form::input('datetime-local','date_time',$purchaseDateRefill, array('class' => 'form-control'),['required'=>'required'])}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('vendor', null, array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-3">
				{{Form::select('vendor_id',$vendorSelectBox, $purchaseInvoice->vendor_id, array('tabindex' => '1','class' => 'form-control'),['required'=>'required'])}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label(null, 'Print Invoice Number', array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-3">
				{{Form::text('printed_invoice_num',$purchaseInvoice->printed_invoice_num, array('tabindex' => '2','class' => 'form-control'),['required'=>'required'])}}
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<div class="row">
					<div class="col-sm-2"><b>Quantity</b></div>
					<div class="col-sm-2"><b>Free Quantity</b></div>
					<div class="col-sm-3"><b>Expire Date</b></div>
					<div class="col-sm-3"><b>Batch Number</b></div>
					<div class="col-sm-2"><b>Line Total</b></div>
				</div>
			</div>
		</div>
		<?php $tabCount = 2 ; ?>
		@foreach($ItemRows as $ItemRowName=>$ItemRowValue )
		<div class="form-group">
			@if(in_array($ItemRowValue,$purchaseRows))
			{{Form::hidden('buying_price_'.$ItemRowValue,$price[$ItemRowValue])}}
			{{Form::hidden('item_id_'.$ItemRowValue,$ItemRowValue)}}
			{{Form::label($ItemRowName, null, array('class' => 'col-sm-2 control-label'))}}

			<div class="col-sm-10">
				<div class="row">
					<div class="col-sm-2">
						<?php $tabCount ++ ; ?>
						{{Form::input('number','quantity_'.$ItemRowValue,$quantity[$ItemRowValue],['step'=>'0.01','tabindex' => $tabCount, 'class' => 'form-control text-right','id'=>$ItemRowValue,'onkeyup'=>'changeOnQuantity(this.id,this.value)'])}}
					</div>
					<div class="col-sm-2">
						<?php $tabCount ++ ; ?>
						{{Form::input('number','free_quantity_'.$ItemRowValue,$freeQuantity[$ItemRowValue],['step'=>'0.01','tabindex' => $tabCount, 'class' => 'form-control text-right'])}}
					</div>
					<div class="col-sm-3">
						<?php $tabCount ++ ; ?>
						{{Form::input('date','exp_date_'.$ItemRowValue,$expDate[$ItemRowValue],['step'=>'any','tabindex' => $tabCount, 'class' => 'form-control'])}}
					</div>
					<div class="col-sm-3">
						<?php $tabCount ++ ; ?>
						{{Form::text('batch_number_'.$ItemRowValue,$batchNumber[$ItemRowValue],['tabindex' => $tabCount, 'class' => 'form-control'])}}
					</div>
					<div class="col-sm-2">

						{{Form::text('line_total_'.$ItemRowValue, null, ['class' => 'form-control text-right', 'step'=>'any','readonly'=>'readonly'])}}
					</div>
				</div>
			</div>
			@else

			{{Form::hidden('buying_price_'.$ItemRowValue,$price[$ItemRowValue])}}
			{{Form::hidden('item_id_'.$ItemRowValue,$ItemRowValue)}}
			{{Form::label($ItemRowName, null, array('class' => 'col-sm-2 control-label'))}}

			<div class="col-sm-10">
				<div class="row">
					<div class="col-sm-2">
						<?php $tabCount ++ ; ?>
						{{Form::input('number','quantity_'.$ItemRowValue,null, ['tabindex' => $tabCount, 'class' => 'form-control text-right','step'=>'0.01','id'=>$ItemRowValue,'onkeyup'=>'changeOnQuantity(this.id,this.value)'])}}
					</div>
					<div class="col-sm-2">
						<?php $tabCount ++ ; ?>
						{{Form::input('number','free_quantity_'.$ItemRowValue,null, ['tabindex' => $tabCount, 'class' => 'form-control text-right','step'=>'0.01'])}}
					</div>
					<div class="col-sm-3">
						<?php $tabCount ++ ; ?>
						{{Form::input('date','exp_date_'.$ItemRowValue,null, ['tabindex' => $tabCount, 'class' => 'form-control','step'=>'any'])}}
					</div>
					<div class="col-sm-3">
						<?php $tabCount ++ ; ?>
						{{Form::text('batch_number_'.$ItemRowValue,null, ['tabindex' => $tabCount, 'class' => 'form-control'])}}
					</div>
					<div class="col-sm-2">
						{{Form::text('line_total_'.$ItemRowValue, null,['class' => 'form-control text-right', 'step'=>'any','readonly'=>'readonly'])}}
					</div>
				</div>
			</div>

			@endif
		</div>
		@endforeach
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<div class="row">
					<div class="col-sm-offset-10 col-sm-2">{{Form::text('full_total',null, ['class' => 'form-control text-right', 'step'=>'any','readonly'=>'readonly','style'=>'font-weight:bolder;'])}}</div>
				</div>
			</div>
		</div>
		<div class="form-group">
			<span class="col-sm-2 control-label"><b>Payments</b></span>
			<div class="col-sm-10">
				<table class="table table-bordered" style="width: 40%">
					<tr>
						<th>ID</th>
						<th>Type</th>
						<th>From</th>
						<th>Date</th>
						<th>Amount</th>
						<th></th>
					</tr>
					<tbody>
						@foreach($purchaseInvoice->financeTransfers as $financeTransfer)
						<tr>
							<td>{{$financeTransfer->id}}</td>
							<td>
								@if($financeTransfer->isCheque())
								Cheque
								@elseif($financeTransfer->isCash())
								Cash
								@endif
							</td>
							<td>{{HTML::link(URL::action('finances.transfers.view', [$financeTransfer->from_id]),$financeTransfer->fromAccount->name)}}</td>
							<td>{{$financeTransfer->date_time}}</td>
							<td>{{number_format($financeTransfer->amount,2)}}</td>
							<td>{{HTML::link(URL::action('finances.transfers.edit', [$financeTransfer->id]), 'Edit...')}}</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
		<div class="form-group">
			{{Form::label(null, 'Completely Paid', array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-3">
				<?php $tabCount ++ ; ?>
				{{Form::checkbox('completely_paid',TRUE,$purchaseInvoice->completely_paid,array('tabindex' => $tabCount, 'style'=>'margin-top:10px;'))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('new_cash_payment', null, array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-2">
				{{Form::input('number', 'new_cash_payment', NULL, array('class' => 'form-control', 'step'=>'0.01'))}}
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-12">
				<div class="row">
					<div class="col-sm-2 text-right"><b>New Cheque Payment</b></div>
					<div class="col-sm-10">
						<div class="row">
							<div class="col-sm-2">Amount</div>
							<div class="col-sm-2">Bank</div>
							<div class="col-sm-2">Cheque Number</div>
							<div class="col-sm-2">Issued Date</div>
							<div class="col-sm-2">Payable Date</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-offset-2 col-sm-10">
				<div class="row">
					<div class="col-sm-2">
						{{Form::input('number', 'new_cheque_payment', NULL, array('class' => 'form-control', 'step'=>'0.01'))}}
					</div>
					<div class="col-sm-2">
						{{Form::select('cheque_payment_bank_id', $banksList, null, array('class' => 'form-control'))}}
					</div>
					<div class="col-sm-2">
						{{Form::text('cheque_payment_cheque_number', null, array('class' => 'form-control'))}}
					</div>
					<div class="col-sm-2">
						{{Form::input('date', 'cheque_payment_issued_date', null, array('class' => 'form-control'))}}
					</div>
					<div class="col-sm-2">
						{{Form::input('date', 'cheque_payment_payable_date', null, array('class' => 'form-control'))}}
					</div>
				</div>
			</div>
		</div>
		<div class="form-group">
			{{Form::label(null, 'Other Expense Amount', array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-2">
				<?php $tabCount ++ ; ?>
				{{Form::input('number','other_expenses_amount',number_format($purchaseInvoice->other_expenses_amount,2), array('tabindex' => $tabCount, 'class' => 'form-control','step'=>'0.01'))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('other_expense_details', null, array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-2">
				<?php $tabCount ++ ; ?>
				{{Form::text('other_expenses_details',$purchaseInvoice->other_expenses_details, array('tabindex' => $tabCount, 'class' => 'form-control'))}}
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<?php $tabCount ++ ; ?>
				{{Form::submit('Submit', array('tabindex' => $tabCount, 'class' => 'btn btn-primary pull-right'))}}</td>
				{{ link_to(URL::previous(), 'Back', ['class' => 'btn btn-default pull-right back-btn-margin']) }}
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

	var i;
	var a = [<?php echo '"' . implode ( '","' , $itemRowsForTotal ) . '"' ?>];
	var finalTotal = 0;
	for (i = 0; i < a.length; i++) {
		var price = document.getElementsByName('buying_price_' + a[i])[0].value;
		var quantity = document.getElementsByName('quantity_' + a[i])[0].value;
		var lineTotal = Number(price) * Number(quantity);
		document.getElementsByName('line_total_' + a[i])[0].value = lineTotal.toFixed(2);
		var fullTotalCell = document.getElementsByName('line_total_' + a[i])[0].value;
		var finalTotal = Number(finalTotal) + Number(fullTotalCell);
	}
	document.getElementsByName('full_total')[0].value = finalTotal.toFixed(2);
</script>
@stop