@extends('web._templates.template')

@section('body')

<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Add Vendor</h3>
	</div>
	<div class="panel-body">
		{{Form::open(['class'=>'form-horizontal', 'role'=>'form'])}}
		<br />
		<div class="form-group">
			{{Form::label('name', null, array('class' => 'col-sm-1 control-label'))}}
			<div class="col-sm-4">
				{{Form::text('name', null, array('class' => 'form-control'))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('details', null, array('class' => 'col-sm-1 control-label'))}}
			<div class="col-sm-4">
				{{Form::textarea('details', null, array('class' => 'form-control'))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('is_active', null, array('class' => 'col-sm-1 control-label'))}}
			<div class="col-sm-4">
				{{Form::checkbox('is_active',TRUE,null,array('style'=>'margin-top:10px;'))}}
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-offset-1 col-sm-4">
				{{Form::submit('submit', array('class' => 'btn btn-default pull-right'))}}
			</div>
		</div>
		{{Form::close()}}

	</div>
</div>

@stop
