@extends('web._templates.template')

@section('body')

<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Add Purchase</h3>
	</div>
	<div class="panel-body">

		{{Form::open(['class'=>'form-horizontal', 'role'=>'form'])}}
		<br />
		<div class="form-group">
			{{Form::label('date', null, array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-3">
				{{Form::input ( 'date','purchase_date', null, array('class' => 'form-control'))}}</td>
			</div>
		</div>
		<div class="form-group">
			{{Form::label('vendor', null, array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-3">
				{{Form::select('vendor_id',\Models\Vendor::getArrayForHtmlSelect('id','name',[''=>'Select Vendor']), null, array('class' => 'form-control'))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label(null, 'Print Invoice Number', array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-3">
				{{Form::text('printed_invoice_num',null, array('class' => 'form-control'))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label(null, 'Other Expense Amount', array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-3">
				{{Form::text('other_expense_amount',null, array('class' => 'form-control'))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label(null, 'Other Expense Details', array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-3">
				{{Form::textarea('other_expenses_details',null, array('class' => 'form-control'))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('Stock', null, array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-3">
				{{Form::select('stock_id',$stocks, NULL, array('class' => 'form-control'))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label(null, 'Completely Paid', array('class' => 'col-sm-2 control-label', 'style'=>'padding-top: 0;'))}}
			<div class="col-sm-3">
				{{Form::checkbox('is_paid')}}
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

		<div id="add_purchase_items">
			<?php $rowCounter = 0 ; ?>
			@foreach($itemRows as $itemRow)

			<div class="form-group">
				{{Form::hidden('item_id_'.$itemRow->id,$itemRow->id)}}
				{{Form::label(null, $itemRow->name, array('class' => 'col-sm-2 control-label'))}}
				<div class="col-sm-10">
					<div class="row">
						<div class="col-sm-2">
							{{Form::input('number','buying_price_'.$itemRow->id,$itemRow->current_buying_price, array('class' => 'form-control', 'step'=>'any'))}}
						</div>
						<div class="col-sm-2">
							{{Form::input('number','quantity_'.$itemRow->id, null, array('class' => 'form-control', 'step'=>'any'))}}
						</div>
						<div class="col-sm-2">
							{{Form::input('number','free_quantity_'.$itemRow->id, null, array('class' => 'form-control', 'step'=>'any'))}}
						</div>
						<div class="col-sm-3">
							{{Form::input('date','exp_date_'.$itemRow->id, null, array('class' => 'form-control'))}}
						</div>
						<div class="col-sm-3">
							{{Form::text('batch_number_'.$itemRow->id, null, array('class' => 'form-control'))}} 
						</div>
					</div>
				</div>
			</div>

			<?php //$rowCounter++; ?>
			@endforeach

		</div>

		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				{{Form::hidden('row_counter',$rowCounter)}}
				{{Form::submit('Submit', array('class' => 'btn btn-default pull-right'))}}
			</div>
		</div>

		{{Form::close()}}

	</div>
</div>

@stop