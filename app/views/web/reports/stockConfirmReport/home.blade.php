@extends('web._templates.template')

@section('body')
<div class="panel panel-default well">
	<div class="panel-heading">
		<h3 class="panel-title">View Stock Confirm Report</h3>
	</div>
	<div class="panel-body">
		<div class="panel panel-default">
			<div class="panel-body">
				{{Form::open(['class'=>'form-inline', 'role'=>'form'])}}
				<div class="form-group inline-form">
					{{Form::label('stock', null, array('class' => 'control-label'))}}
					{{Form::select('stock',$stocksList,$selectedStock, array('class' => ''))}}
				</div>
				<div class="form-group inline-form">
					{{Form::label('from_date_time','From date', array('class' => 'control-label'))}}
					{{Form::input('datetime-local','from_date_time',$fromDate, array('class' => '','step'=>'1'))}}
				</div>
				<div class="form-group inline-form">
					{{Form::label('to_date_time','To date', array('class' => 'control-label'))}}
					{{Form::input('datetime-local','to_date_time',$toDate, array('class' => '','step'=>'1'))}}
				</div>
				<div class="form-group inline-form">
					{{Form::submit('Submit', array('class' => 'btn btn-primary pull-right'))}}
				</div>
				{{Form::close()}}
			</div>
		</div>
		<br/>
		@if(count($stockConfirmations)==0)
		<h4 class="text-center">There are no records to display...</h4>
		@else
		<table class="table table-striped" style="width:40%;">
			<tr>
				<th>Stock</th>
				<th>Last Confirmed Date/Time</th>
			</tr>
			@foreach($stockConfirmations as $stockConfirmation)
			<tr>
				<td>{{HTML::link(URL::action('reports.stockConfirmReport.view',[$stockConfirmation->id]),$stockConfirmation->stock->name)}}</td>
				<td>{{$stockConfirmation->date_time}}</td>
			</tr>
			@endforeach
		</table>
		@endif
	</div>
</div>
@stop
