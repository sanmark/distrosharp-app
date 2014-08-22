@extends('web._templates.template')

@section('body')
<div class="row">
	<div class="col-lg-6 col-md-6">

		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">Basic Details</h3>
			</div>
			<div class="panel-body">

				{{Form::model($user, ['action'=>'account.settings.basic', 'class'=>'form-horizontal', 'role'=>'form'])}}
				<br />
				<div class="form-group">
					{{Form::label('username', null, array('class' => 'col-sm-3 control-label', 'style'=>'padding-top: 0;'))}}
					<div class="col-sm-6">
						{{$user->username}}
					</div>
				</div>
				<div class="form-group">
					{{Form::label('first_name', null, array('class' => 'col-sm-3 control-label'))}}
					<div class="col-sm-6">
						{{Form::text('first_name', null, array('class' => 'form-control'))}}
					</div>
				</div>
				<div class="form-group">
					{{Form::label('last_name', null, array('class' => 'col-sm-3 control-label'))}}
					<div class="col-sm-6">
						{{Form::text('last_name', null, array('class' => 'form-control'))}}
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-offset-3 col-sm-6">
						{{Form::submit('Submit', array('class' => 'btn btn-default pull-right'))}}
					</div>				
				</div>
				{{Form::close()}}

			</div>
		</div>

	</div>

	<div class="col-lg-6 col-md-6">

		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">Password</h3>
			</div>
			<div class="panel-body">

				{{Form::open(['action'=>'account.settings.password', 'class'=>'form-horizontal', 'role'=>'form'])}}
				<br />
				<div class="form-group">
					{{Form::label('existing_password', null, array('class' => 'col-sm-4 control-label'))}}
					<div class="col-sm-6">
						{{Form::password('existing_password', array('class' => 'form-control'))}}
					</div>
				</div>
				<div class="form-group">
					{{Form::label('new_password', null, array('class' => 'col-sm-4 control-label'))}}
					<div class="col-sm-6">
						{{Form::password('new_password', array('class' => 'form-control'))}}
					</div>
				</div>
				<div class="form-group">
					{{Form::label('confirm_new_password', null, array('class' => 'col-sm-4 control-label'))}}
					<div class="col-sm-6">
						{{Form::password('confirm_new_password', array('class' => 'form-control'))}}
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-offset-4 col-sm-6">
						{{Form::submit('Submit', array('class' => 'btn btn-default pull-right'))}}
					</div>
				</div>
				{{Form::close()}}

			</div>
		</div>

	</div>
</div>
@stop