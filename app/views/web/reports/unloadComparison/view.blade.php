@extends('web._templates.template')

@section('body')

<div class="panel panel-default well">
	<div class="panel-heading">
		<h3 class="panel-title">View Unload</h3>
	</div>
	<div class="panel-body">
		<div class="form-group">
			{{Form::label('date', null, array('class' => 'control-label'))}}
			{{$basicDetails->date_time}}
			<br/>
			{{Form::label('from', null, array('class' => 'control-label'))}}
			{{$basicDetails->fromStock->name}}
			<br/>
			{{Form::label('to', null, array('class' => 'control-label'))}}
			{{$basicDetails->toStock->name}}
		</div>
		<table class="table table-striped" style="width:80%;">
			<tr>
				<th>Item</th>
				<th>Loading Quantity</th>
				<th>Sales Quantity</th>
				<th>System Unloading Quantity</th>
				<th>Physical Balance Quantity</th>
				<th>DIFICIT/EXCESS QTY</th>
			</tr>
			{{Form::open()}}
			@foreach($transferData as $transferRow)
			<tr>
				<td>{{ $transferRow->item->name}}</td>
				<td>
					@if(array_key_exists($transferRow->item_id,$loadingItemQuantity))
					{{$loadingItemQuantity[$transferRow->item_id]}}
					@else
					0
					@endif
				</td>
				<td>
					@if(array_key_exists($transferRow->item_id,$sellingItemQuantity))
					{{$sellItemQuantity=$sellingItemQuantity[$transferRow->item_id]}}
					@else
					{{$sellItemQuantity=0}}
					@endif
				</td>
				<td>{{$sysLoadDiff[$transferRow->item_id]}}</td>
				<td>{{ $transferRow->quantity}}</td>
				<td>{{$difictQuantity[$transferRow->item_id]}}</td>
			</tr>
			@endforeach
			{{Form::close()}}
		</table>
	</div>
</div>
@stop