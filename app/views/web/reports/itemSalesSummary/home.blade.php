@extends('web._templates.template')

@section('body')

<div class="panel panel-default well">
	<div class="panel-heading">
		<h3 class="panel-title">Item Sales Summary</h3>
	</div>
	<div class="panel-body">

		<div class="panel panel-default">
			<div class="panel-body">
				{{Form::open(['class'=>'form-inline', 'role'=>'form'])}}
				<div class="form-group inline-form">
					{{Form::label('rep', null, array('class' => 'control-label'))}}
					{{Form::select('rep_id', $repSelectBox, $repId, array('class' => ''))}}
				</div>
				<div class="form-group inline-form">
					{{Form::label('from', null, array('class' => 'control-label'))}}
					{{Form::input('date', 'from_date', $fromDate, array('class' => ''))}}
				</div>
				<div class="form-group inline-form">
					{{Form::label('to', null, array('class' => 'control-label'))}}
					{{Form::input('date', 'to_date', $toDate, array('class' => ''))}}
				</div>
				<div class="form-group inline-form">
					{{Form::submit('Submit', array('class' => 'btn btn-primary pull-right'))}}
				</div>
				{{Form::close()}}
			</div>
		</div>


		@if(isset($items))
		<table class="table table-striped" style="width: 55%;">
			<thead>
				<tr>
					<th>Name</th>
					<th class="text-right">Total Free Amount</th>
					<th class="text-right">Total Paid Amount</th>
				</tr>
			</thead>
			<tbody>
				@foreach($items as $item)
				<tr>
					<td>{{$item->name}}</td>
					<td class="text-right">{{$item->totalFreeAmount}}</td>
					<td class="text-right">{{$item->totalPaidAmount}}</td>
				</tr>
				@endforeach
			</tbody>
		</table>
		@else
		<h4 class="text-center">Please define a criteria and press "Submit".</h4>
		@endif
	</div>
</div>

@stop