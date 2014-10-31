@extends('web._templates.template')

@section('body')


<div class="row">
	<div class="col-md-offset-4 col-md-4 col-sm-4 col-sm-offset-4 col-xs-8 col-xs-offset-2 " id="login-form">

		<div class="panel panel-default well">
			<div class="panel-body offset3 span6">
				<div class="row">
					<div class="col-sm-12">
						<div id="login-form-logo">
							<img src="<?php echo url ( 'images/logo.jpg' ) ; ?>" id="login-form-logo-image"/>
							<span id="login-form-logo-text">Distro#</span>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-offset-1 col-md-10">
						<br />
						{{Form::open(['role' => 'form'])}}
						<div class="form-group">
							{{Form::label('organization')}}
							{{Form::text('organization', null, array('class' => 'form-control'))}}
						</div>
						<div class="form-group">
							{{Form::label('username')}}
							{{Form::text('username', null, array('class' => 'form-control'))}}
						</div>
						<div class="form-group">
							{{Form::label('password')}}
							{{Form::password('password', array('class' => 'form-control'))}}
						</div>
						<div class="form-group">
							{{Form::submit('Login', array('class' => 'btn btn-primary pull-right'))}}
							<span class="clearfix"></span>
						</div>
						{{Form::close()}}
						<br />

						@if(MessageButler::hasError())
						<p align="center" class="errorstring"><font color="red">{{MessageButler::getError()}}</font></p> 
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@stop