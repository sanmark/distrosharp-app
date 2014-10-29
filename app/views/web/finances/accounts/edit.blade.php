@extends('web._templates.template')

@section('body')

<div class="panel panel-default well">
	<div class="panel-heading">
		<h3 class="panel-title">Edit Finance Account - <b>{{$financeAccount->name}}</b></h3>
	</div>
	<div class="panel-body">

		<ul>

			@foreach($errors->all() as $error)
			<li>{{$error}}</li>
			@endforeach

		</ul>

		{{Form::model($financeAccount, ['class'=>'form-horizontal', 'role'=>'form'])}}
		<br />
		<div class="form-group">
			{{Form::label('name', null, array('class' => 'col-sm-1 control-label'))}}
			<div class="col-sm-3">
				{{Form::text('name', null, array('tabindex' => '1', 'class' => 'form-control'),['required'=>'required'])}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('bank_id', null, array('class' => 'col-sm-1 control-label'))}}
			<div class="col-sm-3">
				{{Form::select('bank_id',$bankSelectBox, null, array('tabindex' => '2', 'class' => 'form-control'))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('is_active', null,array('class' => 'col-sm-1 control-label'))}}
			<div class="col-sm-3">
				{{Form::checkbox('is_active',TRUE,null,array('tabindex' => '3', 'style'=>'margin-top:10px;'))}}
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-offset-1 col-sm-3">
				{{Form::submit('Submit', array('tabindex' => '4', 'class' => 'btn btn-primary pull-right'))}}
				{{ link_to(URL::previous(), 'Back', ['class' => 'btn btn-default pull-right back-btn-margin']) }}
			</div>
		</div>
		{{Form::close()}}

	</div>
</div>

@stop
