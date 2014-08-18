@extends('web._templates.template')

@section('body')

<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Edit Item &nbsp;<b>{{$item->name}}</b></h3>
	</div>
	<div class="panel-body">

		@if($errors->count() > 0)
		<ul class="errorstring">
			@foreach($errors->all() as $error)
			<li>{{$error}}</li>
			@endforeach
		</ul>
		@endif

		{{Form::model ($item, ['class'=>'form-horizontal', 'role'=>'form'])}}
		<br />
		<div class="form-group">
			{{Form::label('code', null, array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-4">
				{{Form::text('code', null, array('class' => 'form-control'))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('name', null, array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-4">
				{{Form::text('name', null, array('class' => 'form-control'))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('reorder_level', null, array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-4">
				{{Form::text('reorder_level', null, array('class' => 'form-control'))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('current_buying_price', null, array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-4">
				{{Form::text('current_buying_price', null, array('class' => 'form-control'))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('current_selling_price', null, array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-4">
				{{Form::text('current_selling_price', null, array('class' => 'form-control'))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('buying_invoice_order', null, array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-4">
				{{Form::text('buying_invoice_order', null, array('class' => 'form-control'))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('selling_invoice_order', null, array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-4">
				{{Form::text('selling_invoice_order', null, array('class' => 'form-control'))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('is_active', null, array('class' => 'col-sm-2 control-label', 'style'=>'padding-top: 0;'))}}
			<div class="col-sm-4">
				{{Form::checkbox('is_active')}}
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