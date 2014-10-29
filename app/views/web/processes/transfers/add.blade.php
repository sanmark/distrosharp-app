@extends('web._templates.template')

@section('body')

<div class="panel panel-default well">
	<div class="panel-heading">
		<h3 class="panel-title">Add Transfer</h3>
	</div>
	<div class="panel-body">

		<p>Transfer Items from stock <b>{{$fromStock->name}}</b> to stock <b>{{$toStock->name}}</b>.</p>

		{{Form::open(['class'=>'form-horizontal', 'role'=>'form'])}}
		<br />
		<div class="form-group">
			{{Form::label('date_time', 'Date and Time', array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-4">
				{{Form::input ( 'datetime-local', 'date_time',$dateTime, array('class' => 'form-control','required'=>true))}}
			</div>
		</div>
		<br/>
		<div class="form-group">
			<div class=" col-sm-offset-2 col-sm-8">
				<div class="row">
					<div class="col-sm-1 text-right"><b>{{$fromStock->name}}</b></div>
					<div class="col-sm-2 text-right"><b>Amount</b></div>
					@if($isUnloaded==1)
					<div class="col-sm-2 text-right"><b>Imbalance Transfer</b></div>
					{{Form::hidden('is_unload',$isUnloaded)}}
					<?php $required	 = 'required' ; ?>
					@else
					<?php $required	 = NULL ; ?>
					@endif
					<div class="col-sm-1 text-right"><b>{{$toStock->name}}</b></div>
				</div>			
			</div>			
		</div>

		@foreach($items as $item)
		<div class="form-group">
			{{Form::label(null, $item->name, array('class' => 'col-sm-2 control-label', 'style'=>'padding-top: 0;'))}}
			<div class="col-sm-8">
				<div class="row">
					<div class="col-sm-1 text-right">
						{{$fromStockDetails[$item->id]}}{{Form::hidden('availale_amounts['.$item->id.']', $fromStockDetails[$item->id])}}
					</div>
					<div class="col-sm-2 text-right"> 
						{{Form::input('number','transfer_amounts['.$item->id.']', null, array('class' => 'form-control numField','step' => 'any','id'=>$item->id,$required))}}
					</div>
					@if($isUnloaded==1)
					<div class="col-sm-2 text-right"> 
						{{Form::input('number','imbalance_amount['.$item->id.']', null, array('class' => 'form-control','step' => 'any','readonly','disabled'=>'true'))}}
					</div>
					@endif
					<div class="col-sm-1 text-right">
						{{$toStockDetails[$item->id]}}
					</div>
				</div>
			</div>
		</div>
		@endforeach
		<div class="form-group">
			{{Form::label('description', null, array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-4">
				{{Form::textarea('description', null, array('class' => 'form-control'))}}
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-4">
				{{Form::submit('Submit', array('class' => 'btn btn-primary pull-right'))}}

			</div>
		</div>
		{{Form::close()}}

	</div>
</div>
@stop
@section('file-footer')
<script src="/js/processes/transfers/add.js"></script>
@stop