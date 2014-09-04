@extends('web._templates.template')

@section('body')

<div class="panel panel-default">
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
				{{Form::select('vendor_id',$vendorSelectBox, $purchaseInvoice->vendor_id, array('class' => 'form-control'),['required'=>'required'])}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label(null, 'Print Invoice Number', array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-3">
				{{Form::text('printed_invoice_num',$purchaseInvoice->printed_invoice_num, array('class' => 'form-control'),['required'=>'required'])}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label(null, 'Other Expense Amount', array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-3">
				{{Form::text('other_expenses_amount',$purchaseInvoice->other_expenses_amount, array('class' => 'form-control'))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('other_expense_details', null, array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-3">
				{{Form::textarea('other_expenses_details',$purchaseInvoice->other_expenses_details, array('class' => 'form-control'))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label(null, 'Completely Paid', array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-3">
				{{Form::checkbox('completely_paid',TRUE,$purchaseInvoice->completely_paid,array('style'=>'margin-top:10px;'))}}
			</div>
		</div>

		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<div class="row">
					<div class="col-sm-2"><b>Price</b></div>
					<div class="col-sm-2"><b>Quantity</b></div>
					<div class="col-sm-2"><b>Free Quantity</b></div>
					<div class="col-sm-3"><b>Date</b></div>
					<div class="col-sm-3"><b>Batch Number</b></div>
				</div>			
			</div>			
		</div>


		@foreach($ItemRows as $ItemRowName=>$ItemRowValue )
		<div class="form-group">

			@if(in_array($ItemRowValue,$purchaseRows))

			{{Form::hidden('item_id_'.$ItemRowValue,$ItemRowValue)}}
			{{Form::label($ItemRowName, null, array('class' => 'col-sm-2 control-label'))}}

			<div class="col-sm-10">
				<div class="row">
					<div class="col-sm-2">
						{{Form::input('number','buying_price_'.$ItemRowValue,$price[$ItemRowValue], array('class' => 'form-control'),['step'=>'any','required'=>'required'])}}
					</div>
					<div class="col-sm-2">
						{{Form::input('number','quantity_'.$ItemRowValue,$quantity[$ItemRowValue], array('class' => 'form-control'),['step'=>'any','required'=>'required'])}}
					</div>
					<div class="col-sm-2">
						{{Form::input('number','free_quantity_'.$ItemRowValue,$freeQuantity[$ItemRowValue], array('class' => 'form-control'),['step'=>'any'])}}
					</div>
					<div class="col-sm-3">
						{{Form::input('date','exp_date_'.$ItemRowValue,$expDate[$ItemRowValue], array('class' => 'form-control'),['step'=>'any'])}}
					</div>
					<div class="col-sm-3">
						{{Form::text('batch_number_'.$ItemRowValue,$batchNumber[$ItemRowValue], array('class' => 'form-control'))}}
					</div>
				</div>
			</div>

			@else

			{{Form::hidden('item_id_'.$ItemRowValue,$ItemRowValue)}}
			{{Form::label($ItemRowName, null, array('class' => 'col-sm-2 control-label'))}}

			<div class="col-sm-10">
				<div class="row">
					<div class="col-sm-2">
						{{Form::input('number','buying_price_'.$ItemRowValue,null, array('class' => 'form-control'),['step'=>'any'])}}
					</div>
					<div class="col-sm-2">
						{{Form::input('number','quantity_'.$ItemRowValue,null, array('class' => 'form-control'),['step'=>'any'])}}
					</div>
					<div class="col-sm-2">
						{{Form::input('number','free_quantity_'.$ItemRowValue,null, array('class' => 'form-control'),['step'=>'any'])}}
					</div>
					<div class="col-sm-3">
						{{Form::input('date','exp_date_'.$ItemRowValue,null, array('class' => 'form-control'),['step'=>'any'])}}
					</div>
					<div class="col-sm-3">
						{{Form::text('batch_number_'.$ItemRowValue,null, array('class' => 'form-control'))}}
					</div>
				</div>
			</div>

			@endif

		</div>
		@endforeach

		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				{{Form::submit('Submit', array('class' => 'btn btn-default pull-right'))}}</td>
			</div>
		</div>

		{{Form::close()}}

	</div>
</div>

@stop