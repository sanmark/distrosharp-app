@extends('web._templates.template')

@section('body')

<div class="panel panel-default well">
	<div class="panel-heading">
		<h3 class="panel-title">Set Organization Name</h3>
	</div>
	<div class="panel-body">

		{{Form::open(['class'=>'form-horizontal', 'role'=>'form'])}}
		<div class="form-group">
			{{Form::label('organization_name', null, array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-3">
				{{Form::text('organization_name', $currentOrganizationName, array('tabindex' => '1', 'class' => 'form-control','required'=>true))}} 
			</div> 
		</div> 
		<div class="form-group">
			<div class=" col-sm-offset-2 col-sm-3">
				{{Form::submit('Submit', array('tabindex' => '2', 'class' => 'btn btn-primary pull-right'))}}
				{{ link_to(URL::previous(), 'Back', ['class' => 'btn btn-default pull-right back-btn-margin']) }}
			</div>
		</div>
		{{Form::close()}}

	</div>
</div>

@stop