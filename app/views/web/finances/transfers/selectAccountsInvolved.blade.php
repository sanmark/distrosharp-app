@extends('web._templates.template')

@section('body')

<div class="panel panel-default well">
	<div class="panel-heading">
		<h3 class="panel-title">Select Accounts</h3>
	</div>
	<div class="panel-body">

		{{Form::open(['class'=>'form-horizontal', 'role'=>'form'])}}
		<br />
		<div class="form-group">
			{{Form::label('from', null, array('class' => 'col-sm-1 control-label'))}}
			<div class="col-sm-3">
				{{Form::select('from',$accountSelectBox, null, array('tabindex' => '1', 'class' => 'form-control','required'=>''))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('to', null, array('class' => 'col-sm-1 control-label'))}}
			<div class="col-sm-3">
				{{Form::select('to',$accountSelectBox, null, array('tabindex' => '2', 'class' => 'form-control','required'=>''))}}
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-offset-1 col-sm-3">
				{{Form::submit('Submit', array('tabindex' => '3', 'class' => 'btn btn-primary pull-right'))}}
			</div>
		</div>

		{{Form::close()}}

	</div>
</div>

@stop