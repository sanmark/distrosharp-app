@extends('web._templates.template')

@section('body')
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Profit and Loss Report</h3>
	</div>
	<div class="panel-body">
		<div class="panel panel-default">
			{{Form::open(['class'=>'form-inline', 'role'=>'form'])}}
			<br/>
			<div class="form-group inline-form">
				{{Form::label('from_date',null,array('class' => 'control-label'))}}
				{{Form::input('date', 'from_date', null,array('class' => 'form-control','required'=>true))}}
			</div>
			<div class="form-group inline-form">
				{{Form::label('to_date',null,array('class' => 'control-label'))}}
				{{Form::input('date', 'to_date', null,array('class' => 'form-control','required'=>true))}}
			</div>
			<div class="form-group inline-form">
				{{Form::submit('Submit',array('class' => 'btn btn-default pull-right'))}}
			</div>
			{{Form::close()}}
			<br/>
		</div>
	</div>
</div>
@stop

@section('file-footer')

@stop