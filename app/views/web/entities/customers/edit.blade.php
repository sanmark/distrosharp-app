@extends('web._templates.template')

@section('body')

<div class="panel panel-default well">
	<div class="panel-heading">
		<h3 class="panel-title">Edit Customer &nbsp;<b>{{$customer->name}}</b></h3>
	</div>
	<div class="panel-body">
		{{Form::model($customer, ['class'=>'form-horizontal', 'role'=>'form'])}}
		<br />
		<div class="form-group">
			{{Form::label('name', null, array('class' => 'col-sm-1 control-label'))}}
			<div class="col-sm-4">
				{{Form::text('name', null, array('tabindex' => '1', 'class' => 'form-control','required'=>true))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('route_id','Route', array('class' => 'col-sm-1 control-label'))}}
			<div class="col-sm-4">
				{{Form::select('route_id',$routeSelectBox,null,array('tabindex' => '2', 'class' => 'form-control','required'=>true))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('details', null, array('class' => 'col-sm-1 control-label'))}}
			<div class="col-sm-4">
				{{Form::textarea('details', null, array('tabindex' => '3', 'class' => 'form-control'))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('is_active', null, array('class' => 'col-sm-1 control-label'))}}
			<div class="col-sm-4">
				{{Form::checkbox('is_active',TRUE,null,array('tabindex' => '4', 'style'=>'margin-top:10px;'))}}
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-offset-1 col-sm-4">
				{{Form::submit('Submit', array('tabindex' => '5', 'class' => 'btn btn-primary pull-right'))}}
				{{ link_to(URL::previous(), 'Back', ['class' => 'btn btn-default pull-right back-btn-margin']) }}
			</div>
		</div>
		{{Form::close()}}

	</div>
</div>

@stop