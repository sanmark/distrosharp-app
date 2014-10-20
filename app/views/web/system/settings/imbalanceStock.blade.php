@extends('web._templates.template')

@section('body')

<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Imbalance Stock</h3>
	</div>
	<div class="panel-body">
		{{Form::open(['class'=>'form-inline', 'role'=>'form'])}}
		<div class="form-group inline-form">
			{{Form::label('imbalance_stock', null, array('class' => 'control-label'))}}
			{{Form::select('imbalance_stock', $stocksForHtmlSelect, $imbalanceStockId, array('class' => 'form-control'))}}
		</div>
		<div class="form-group inline-form">
			{{Form::submit('Submit', array('class' => 'btn btn-default pull-right'))}}
		</div>
		{{Form::close()}}
	</div>
</div>

@stop