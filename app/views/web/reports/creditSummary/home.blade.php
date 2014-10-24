@extends('web._templates.template')

@section('body')

<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">View Credit Summary Report</h3>
	</div>
	<div class="panel-body">
		<div class="panel panel-default">
			<div class="panel-body">
				{{Form::open(['class'=>'form-inline', 'role'=>'form'])}}
				<div class="form-group inline-form">
					{{Form::label('route_id','Route', array('class' => 'control-label'))}}
					{{Form::select('route_id',$routeList,$routeId, array('class' => 'form-control'))}}
				</div>
				<div class="form-group inline-form">
					{{Form::submit('Submit', array('tabindex' => '10', 'class' => 'btn btn-default pull-right'))}}
				</div>
				{{Form::close()}}
			</div>
		</div>
		<table class="table table-striped" style="width:40%;">
			<tr>
				<th>Customer Name</th>
				<th class="text-right">Amount</th>
			</tr>
			@if(count($customersIds)==0)
			<tr>
				<td colspan="2" class="text-left">No records in selected route</td>
			</tr>
			@else
			@foreach($customersIds as $customer)
			<tr>
				<td>
					{{HTML::link ( URL::action ( 'reports.creditSummary.view' , [$customer->customer_id] ) , $customer->customer->name )}}</td>
				<td class="text-right">{{$creditBalanceWithCustomerId[$customer->customer_id]}}</td>
			</tr>
			@endforeach
			@endif
		</table>
	</div>
</div>
@stop