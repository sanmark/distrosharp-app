@extends('web._templates.template')

@section('body')

<div class="panel panel-default well">
	<div class="panel-heading">
		<h3 class="panel-title">Select Rep</h3>
	</div>
	<div class="panel-body">
		{{Form::open(['class'=>'form-inline', 'role'=>'form'])}}
		<div class="form-group inline-form">
			{{Form::label('rep_id', 'Rep', array('class' => 'control-label'))}}
			{{Form::select('rep_id', $repSelectBox, $currentRepId, array('class' => ''))}}
		</div>
		<div class="form-group inline-form">
			{{Form::submit('Submit', array('class' => 'btn btn-primary'))}}
		</div>
		{{Form::close()}}
	</div>
</div>

@stop