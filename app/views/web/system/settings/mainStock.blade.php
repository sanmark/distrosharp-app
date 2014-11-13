@extends('web._templates.template')

@section('body')

<div class="panel panel-default well">
	<div class="panel-heading">
		<h3 class="panel-title">Main Stock</h3>
	</div>
	<div class="panel-body">
		{{Form::open(['class'=>'form-horizontal', 'role'=>'form'])}}
		<br />
		<div class="form-group">
			<div class="col-sm-2 control-label"><b>Main Stock</b></div>
			<div class="col-sm-3">
				{{Form::select('main_stock', $stocksForHtmlSelect, $mainStockId, array('class' => 'form-control'))}}
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-3 col-sm-offset-2">
				{{Form::submit('Submit', array('class' => 'btn btn-primary pull-right'))}}
			</div>
		</div>
	</div>
</div>
@stop