@extends('web._templates.template')

@section('body')


<div class="row">
	<div class="col-md-offset-4 col-md-4">
		<br />
		<div class="panel panel-default">
			<div class="panel-body">

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
							{{Form::submit('Login', array('class' => 'btn btn-default pull-right'))}}
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