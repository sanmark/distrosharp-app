@extends('web._templates.template')

@section('body')

<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Rep Finance Report</h3>
	</div>
	<div class="panel-body">

		<div class="panel panel-default">
			<div class="panel-body">
				{{Form::open(['class'=>'form-inline', 'role'=>'form'])}}
				<div class="form-group inline-form">
					{{Form::label('rep_id',null,array('class' => 'control-label'))}}
					{{Form::select('rep_id', $repSelectBox, $repId, array('class' => 'form-control'))}}
				</div>
				<div class="form-group inline-form">
					{{Form::label('date_from',null,array('class' => 'control-label'))}}
					{{Form::input('date', 'date_from', $dateFrom, array('class' => 'form-control'))}}
				</div>
				<div class="form-group inline-form">
					{{Form::label('date_to',null,array('class' => 'control-label'))}}
					{{Form::input('date', 'date_to', $dateTo, array('class' => 'form-control'))}}
				</div>
				<div class="form-group inline-form">
					{{Form::submit('Submit',array('class' => 'btn btn-default'))}}
				</div>
				{{Form::close()}}
			</div>
		</div>

		@if(isset($dates))
		@if(count($dates)>0)
		<table <table class="table table-striped" style="width: 57.5%;">
				<thead
					<tr>
						<th>Date</th>
						<th class="text-right">Total</th>
						<th class="text-right">Cash</th>
						<th class="text-right">Cheque</th>
						<th class="text-right">Credit</th>
					</tr>
				</thead>
				<tbody>
					@foreach($dates as $date=>$details)
					<tr>
						<td>{{$date}}</td>
						<td class="text-right">{{number_format($details['total'],2)}}</td>
						<td class="text-right">{{number_format($details['cash'],2)}}</td>
						<td class="text-right">{{number_format($details['cheque'],2)}}</td>
						<td class="text-right">{{number_format($details['credit'],2)}}</td>
					</tr>
					@endforeach
					<tr>
						<th>Total</th>
						<th class="text-right">{{number_format($totalTotal, 2)}}</th>
						<th class="text-right">{{number_format($totalCash, 2)}}</th>
						<th class="text-right">{{number_format($totalCheque, 2)}}</th>
						<th class="text-right">{{number_format($totalCredit, 2)}}</th>
					</tr>
				</tbody>
			</table>
			@else
			<h4 class="text-center">No results found.</h4>
			@endif
			@else
			<h4 class="text-center">Please define a criteria and press "Submit".</h4>
			@endif
	</div>
</div>

@stop