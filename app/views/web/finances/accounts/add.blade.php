@extends('web._templates.template')

@section('body')

<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Add Finance Account</h3>
	</div>
	<div class="panel-body">

		<ul>
			@foreach($errors->all() as $error)
			<li>{{$error}}</li>
			@endforeach
		</ul>

		{{Form::open(['class'=>'form-horizontal', 'role'=>'form'])}}
		<br />
		<div class="form-group">
			{{Form::label('name', null, array('class' => 'col-sm-1 control-label'))}}
			<div class="col-sm-3">
				{{Form::text('name',null, array('class' => 'form-control'),['required'=>'required'])}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('bank_id', null, array('class' => 'col-sm-1 control-label'))}}
			<div class="col-sm-3">
				{{Form::select('bank_id',$bankSelectBox,null, array('class' => 'form-control'))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('is_active', null, array('class' => 'col-sm-1 control-label', 'style'=>'padding-top: 0;'))}}
			<div class="col-sm-4">
				{{Form::checkbox('is_in_house')}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('is_active', null, array('class' => 'col-sm-1 control-label', 'style'=>'padding-top: 0;'))}}
			<div class="col-sm-4">
				{{Form::checkbox('is_active')}}
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-offset-1 col-sm-3">
				{{Form::submit('Submit', array('class' => 'btn btn-default pull-right'))}}
			</div>
		</div>
		{{Form::close()}}

	</div>
</div>

@stop
