@extends('web._templates.template')

@section('body')

<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Add finance transfers</h3>
	</div>
	<div class="panel-body">

		{{Form::open(['class'=>'form-horizontal', 'role'=>'form'])}}
		<br />
		<div class="form-group">
			{{Form::label('date_and_time', null, array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-3">
				{{Form::input('datetime-local','date_time',$currentDate, array('class' => 'form-control','required'=>true) )}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('from', null, array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-3" style="padding-top: 7px;">
				{{$fromAccount->name}} <b>({{number_format($fromAccount->account_balance,2)}})</b>
			</div>
		</div>
		<div class="form-group">
			{{Form::label('amount', null, array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-3">
				{{Form::input('number','amount',null, array('class' => 'form-control','required'=>true))}}
			</div>			
		</div>
		<div class="form-group">
			{{Form::label('to', null, array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-3" style="padding-top: 7px;">
				{{$toAccount->name}} <b>({{number_format($toAccount->account_balance,2)}})</b>
			</div>
		</div>
		<div class="form-group">
			{{Form::label('description', null, array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-3">
				{{Form::textarea('description', null, array('class' => 'form-control'))}}
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-3">
				{{Form::submit('Submit', array('class' => 'btn btn-default pull-right'))}}
			</div>
		</div>
		{{Form::close()}}

	</div>
</div>

@stop

