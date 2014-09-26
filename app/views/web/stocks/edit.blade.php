@extends('web._templates.template')

@section('body')

<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Edit Stock &nbsp;<b>{{$stock->name}}</b></h3>
	</div>
	<div class="panel-body">

		{{Form::model($stock, ['class'=>'form-horizontal', 'role'=>'form'])}}
		<br />
		<div class="form-group">
			{{Form::label('name', null, array('class' => 'col-sm-2 control-label', 'style'=>'padding-top: 0;'))}}
			<div class="col-sm-3">
				{{$stock->name}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('incharge_id', null, array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-3">
				{{Form::select ( 'incharge_id', $users, null, array('tabindex' => '1', 'class' => 'form-control') )}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('stock_type_id', null, array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-3">
				{{Form::select('stock_type_id', $stockTypes, null, array('tabindex' => '2', 'class' => 'form-control'))}}
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-3">
				{{Form::submit('Submit', array('tabindex' => '3', 'class' => 'btn btn-default pull-right'))}}
				{{ link_to(URL::previous(), 'Back', ['class' => 'btn btn-default pull-right back-btn-margin']) }}
			</div>
		</div>
		{{Form::close()}}

	</div>
</div>

@stop