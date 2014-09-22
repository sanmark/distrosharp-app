@extends('web._templates.template')

@section('body')

<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Add Item</h3>
	</div>
	<div class="panel-body">
		{{Form::open(['class'=>'form-horizontal', 'role'=>'form'])}}
		<br />
		<div class="form-group">
			{{Form::label('code','Item Code', array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-4">
				{{Form::text('code', null, array('class' => 'form-control', 'required'=>true,'pattern'=>'[^\s]+','title'=>'Empty spaces not allowed'))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('name','Item Name', array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-4">
				{{Form::text('name', null, array('class' => 'form-control','required'=>true))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('reorder_level', null, array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-4">
				{{Form::input('number','reorder_level', null, array('class' => 'form-control','required'=>true,'step'=>'any'))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('current_buying_price', null, array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-4">
				{{Form::input('number','current_buying_price', null, array('class' => 'form-control','step' => 'any','required'=>true))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('current_selling_price', null, array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-4">
				{{Form::input('number','current_selling_price', null, array('class' => 'form-control','step' => 'any','required'=>true))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('buying_invoice_order', null, array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-4">
				{{Form::input('number','buying_invoice_order', \Models\Item::getMinBuyingInvoiceOrder(), array('class' => 'form-control','required'=>true))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('selling_invoice_order', null, array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-4">
				{{Form::input('number','selling_invoice_order', \Models\Item::getMinSellingInvoiceOrder(), array('class' => 'form-control', 'required'=>true))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('is_active', null, array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-4">
				{{Form::checkbox('is_active',TRUE,TRUE,array('style'=>'margin-top:10px;'))}}
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-4">
				{{Form::submit('Submit', array('class' => 'btn btn-default pull-right'))}}
			</div>
		</div>
		{{Form::close()}}

	</div>
</div>

@stop