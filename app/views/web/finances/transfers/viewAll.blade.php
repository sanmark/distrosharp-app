@extends('web._templates.template')

@section('body')

<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">All Finance Transfers</h3>
	</div>
	<div class="panel-body">

		<div class="panel panel-default">
			<div class="panel-body">

				{{Form::open(['class'=>'form-inline', 'role'=>'form'])}}
				<div class="form-group bottom-space">
					{{Form::label('from_date')}}
					{{Form::input('datetime-local','from_date',$fromDate, array('class' => 'form-control'))}}
				</div>
				<div class="form-group bottom-space">
					{{Form::label('to_date')}}
					{{Form::input('datetime-local','to_date',$toDate, array('class' => 'form-control'))}}
				</div>
				<div class="form-group bottom-space">
					{{Form::label('from_account')}}
					{{Form::select('from_account',$fromAccountsIds,$fromAccount, array('class' => 'form-control'))}}
				</div>
				<div class="form-group bottom-space">
					{{Form::label('to_account')}}
					{{Form::select('to_account',$toAccountsIds,$toAccount, array('class' => 'form-control'))}}
				</div>
				<div class="form-group bottom-space">
					{{Form::label('amount')}}
					{{Form::select('compare_sign',$compareSignSelectBox,$compareSign, array('class' => 'form-control'))}}
					{{Form::input('number','amount',$amount, array('class' => 'form-control'),['step'=>'any'])}}
				</div>
				<div class="form-group bottom-space">
					{{Form::submit('Submit', array('class' => 'btn btn-default pull-right'))}}
				</div>
				{{Form::close()}}

			</div>
		</div>

		<br/>
		@if(count($financeData)==0)
		<br>
		<div class="no-records-message text-center">
			There are no records to display
		</div>
		<br>
		@else
		<table class="table table-striped" style="width: 70%;">
			<thead>
				<tr>
					<th>Date/Time</th>
					<th>Description</th>
					<th>From account</th>
					<th>To account</th>
					<th>Amount</th>
				</tr>
			</thead>
			<tbody>
				@foreach($financeData as $financeTransfer)
				<tr>
					<td>{{$financeTransfer->date_time}}</td>
					<td>{{$financeTransfer->description}}</td>
					<td>{{HTML::link(URL::action('finances.transfers.view',[$financeTransfer->from_id]),$financeTransfer->fromAccount->name)}}</td>
					<td>{{HTML::link(URL::action('finances.transfers.view',[$financeTransfer->to_id]),$financeTransfer->toAccount->name)}}</td>
					<td>{{$financeTransfer->amount}}</td>
				</tr>
				@endforeach
			</tbody>
		</table>
		@endif
	</div>
</div>

@stop