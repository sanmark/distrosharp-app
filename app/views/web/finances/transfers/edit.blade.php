@extends('web.._templates.template')

@section('body')

<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Edit Transfer</h3>
	</div>
	<div class="panel-body">

		{{Form::model($financeTransfer,['class'=>'form-horizontal', 'role'=>'form'])}}
		<br />
		<div class="form-group">
			{{Form::label('date_time',null, array('class' => 'col-sm-1 control-label'))}}
			<div class="col-sm-3">
				{{Form::input('datetime-local','date_time',$dateTime, array('class' => 'form-control'),['required'=>'required'])}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('from',null, array('class' => 'col-sm-1 control-label'))}}
			<div class="col-sm-3">
				{{Form::select('from_id',$accountSelectBox,null, array('tabindex' => '1', 'class' => 'form-control'))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('amount',null, array('class' => 'col-sm-1 control-label'))}}
			<div class="col-sm-3">
				{{Form::input('number','amount',null, array('tabindex' => '2', 'class' => 'form-control'),['step'=>'any','required'=>'required'])}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('to',null, array('class' => 'col-sm-1 control-label'))}}
			<div class="col-sm-3">
				{{Form::select('to_id',$accountSelectBox,null, array('tabindex' => '3', 'class' => 'form-control'))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('description',null, array('class' => 'col-sm-1 control-label'))}}
			<div class="col-sm-3">
				{{Form::textarea('description',null, array('tabindex' => '4', 'class' => 'form-control'))}}
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-offset-1 col-sm-3">
				{{Form::submit('Submit', array('tabindex' => '5', 'class' => 'btn btn-default pull-right'))}}
			</div>
		</div>
		{{Form::close()}}

	</div>
</div>

@stop

