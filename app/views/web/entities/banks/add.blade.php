@extends('web._templates.template')

@section('body')

<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Add Bank</h3>
	</div>
	<div class="panel-body">

		@if($errors->count()>0)
		<ul class="errorstring">
			@foreach($errors->all() as $error)
			<li>{{$error}}</li>
			@endforeach
		</ul>
		@endif

		{{Form::open(['class'=>'form-horizontal', 'role'=>'form'])}}
		<br />
		<div class="form-group">
			{{Form::label('name', null, array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-6">
				{{Form::text('name', null, array('class' => 'form-control'))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('is_active', null, array('class' => 'col-sm-2 control-label', 'style'=>'padding-top: 0;'))}}
			<div class="col-sm-6">
				{{Form::checkbox('is_active')}}
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-6">
				{{Form::submit('submit', array('class' => 'btn btn-default pull-right'))}}
			</div>
		</div>
		{{Form::close()}}

	</div>
</div>

@stop