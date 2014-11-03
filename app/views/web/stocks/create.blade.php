@extends('web._templates.template')

@section('body')

<div class="panel panel-default well">
	<div class="panel-heading">
		<h3 class="panel-title">Add New Stock</h3>
	</div>
	<div class="panel-body">
		{{Form::open(['class'=>'form-horizontal', 'role'=>'form'])}}
		<br />
		<div class="form-group">
			{{Form::label('stock_name','Stock name', array('class' => 'col-sm-1 control-label'))}}
			<div class="col-sm-3">

				{{Form::text('stock_name', null, array('tabindex' => '1', 'class' => 'form-control','required'=>true))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('stock_incharge','Stock incharge', array('class' => 'col-sm-1 control-label'))}}
			<div class="col-sm-3">

				{{Form::select('incharge_id',$usersList,null,array('tabindex' => '1', 'class' => 'form-control'))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('stock_type_id','Stock type',array('class' => 'col-sm-1 control-label'))}}
			<div class="col-sm-3">

				{{Form::select('stock_type_id',$stockTypes,null,array('tabindex' => '1', 'class' => 'form-control','required'=>true))}}
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-offset-1 col-sm-3">
				{{Form::submit('Create', array('tabindex' => '3', 'class' => 'btn btn-primary pull-right'))}}
			</div>
		</div>
		{{Form::close()}}

	</div>
</div>
@stop