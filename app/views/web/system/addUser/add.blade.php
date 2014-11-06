@extends('web._templates.template')

@section('body')

<div class="panel panel-default well">
	<div class="panel-heading">
		<h3 class="panel-title">Add New User</h3>
	</div>
	<div class="panel-body">
		{{Form::open(['class'=>'form-horizontal', 'role'=>'form'])}}
		<br />
		<div class="form-group">
			{{Form::label('first_name','First Name', array('class' => 'col-sm-1 control-label'))}}
			<div class="col-sm-3">

				{{Form::text('first_name', null, array('tabindex' => '1', 'class' => 'form-control','required'=>true))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('last_name','Last Name', array('class' => 'col-sm-1 control-label'))}}
			<div class="col-sm-3">

				{{Form::text('last_name',null,array('tabindex' => '1', 'class' => 'form-control','required'=>true))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('username','Username', array('class' => 'col-sm-1 control-label'))}}
			<div class="col-sm-3">

				{{Form::text('username',null,array('tabindex' => '1', 'class' => 'form-control','required'=>true))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('email','Email', array('class' => 'col-sm-1 control-label'))}}
			<div class="col-sm-3">

				{{Form::email('email',null,array('tabindex' => '1', 'class' => 'form-control email-input','required'=>true))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('password','Password', array('class' => 'col-sm-1 control-label'))}}
			<div class="col-sm-3">
				{{Form::password('password',array('tabindex' => '1', 'class' => 'form-control','required'=>true))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('password_confirmation','Confirm Password', array('class' => 'col-sm-1 control-label'))}}
			<div class="col-sm-3">
				{{Form::password('password_confirmation',array('tabindex' => '1', 'class' => 'form-control','required'=>true))}}
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-offset-1 col-sm-3">
				{{Form::submit('submit', array('tabindex' => '3', 'class' => 'btn btn-primary pull-right'))}}
			</div>
		</div>
		{{Form::close()}}

	</div>
</div>

@stop
