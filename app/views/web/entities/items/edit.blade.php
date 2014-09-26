@extends('web._templates.template')

@section('body')

<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Edit Item &nbsp;<b>{{$item->name}}</b></h3>
	</div>
	<div class="panel-body">
		{{Form::model ($item, ['class'=>'form-horizontal', 'role'=>'form'])}}
		<br />
		<div class="form-group">
			{{Form::label('code','Item Code', array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-4">
				{{Form::text('code', null, array('tabindex' => '1', 'class' => 'form-control','required'=>true,'pattern'=>'[^\s]+','title'=>'Empty spaces not allowed'))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('name','Item Name', array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-4">
				{{Form::text('name', null, array('tabindex' => '2', 'class' => 'form-control','required'=>true))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('reorder_level', null, array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-4">
				{{Form::input('number','reorder_level', null, array('tabindex' => '3', 'class' => 'form-control','required'=>true,'step'=>'any'))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('current_buying_price', null, array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-4">
				{{Form::input('number','current_buying_price', null, array('tabindex' => '4', 'class' => 'form-control','required'=>true,'step'=>'any'))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('current_selling_price', null, array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-4">
				{{Form::input('number','current_selling_price', null, array('tabindex' => '5', 'class' => 'form-control','required'=>true,'step'=>'any'))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('buying_invoice_order', null, array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-4">
				{{Form::input('number','buying_invoice_order', null, array('tabindex' => '6', 'class' => 'form-control','required'=>true,'step'=>'any'))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('selling_invoice_order', null, array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-4">
				{{Form::input('number','selling_invoice_order', null, array('tabindex' => '7', 'class' => 'form-control','required'=>true,'step'=>'any'))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('is_active', null, array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-4">
				{{Form::checkbox('is_active',TRUE,null,array('tabindex' => '8', 'style'=>'margin-top:10px;'))}}
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-4">
				{{Form::submit('Submit', array('tabindex' => '9', 'class' => 'btn btn-default pull-right'))}}
				{{ link_to(URL::previous(), 'Back', ['class' => 'btn btn-default pull-right back-btn-margin']) }}
			</div>
		</div>
		{{Form::close()}}

	</div>
</div>

@stop