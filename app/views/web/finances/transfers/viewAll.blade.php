@extends('web._templates.template')

@section('body')

<div class="panel panel-default well">
	<div class="panel-heading">
		<div class="panel-title">
			<span>All Finance Transfers</span>
			{{HTML::link ( URL::action ( 'finances.transfers.selectAccountsInvolved') ,'Add New Finance Transfer',['class' => 'panel-title-btn btn btn-success btn-sm pull-right'] )}}
		</div>
	</div>
	<div class="panel-body">

		<div class="panel panel-default">
			<div class="panel-body">

				{{Form::open(['class'=>'form-inline', 'role'=>'form'])}}
				<div class="form-group bottom-space">
					{{Form::label('from_date')}}
					{{Form::input('datetime-local','from_date',$fromDate, array('tabindex' => '1', 'class' => ''))}}
				</div>
				<div class="form-group bottom-space">
					{{Form::label('to_date')}}
					{{Form::input('datetime-local','to_date',$toDate, array('tabindex' => '2', 'class' => ''))}}
				</div>
				<div class="form-group bottom-space">
					{{Form::label('from_account')}}
					{{Form::select('from_account',$fromAccountsIds,$fromAccount, array('tabindex' => '3', 'class' => ''))}}
				</div>
				<div class="form-group bottom-space">
					{{Form::label('to_account')}}
					{{Form::select('to_account',$toAccountsIds,$toAccount, array('tabindex' => '4', 'class' => ''))}}
				</div>
				<div class="form-group bottom-space">
					{{Form::label('amount')}}
					{{Form::select('compare_sign',$compareSignSelectBox,$compareSign, array('tabindex' => '5', 'class' => ''))}}
					{{Form::input('number','amount',$amount,['tabindex' => '6', 'step'=>'0.01'])}}
				</div>
				<div class="form-group bottom-space">
					{{Form::submit('Submit', array('tabindex' => '7', 'class' => 'btn btn-primary pull-right'))}}
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
					<th class="text-right">Amount</th>
				</tr>
			</thead>
			<tbody>
				@foreach($financeData as $financeTransfer)
				<tr>
					<td>{{$financeTransfer->date_time}}</td>
					<td>{{$financeTransfer->description}}</td>
					<td>{{HTML::link(URL::action('finances.transfers.view',[$financeTransfer->from_id]),$financeTransfer->fromAccount->name)}}</td>
					<td>{{HTML::link(URL::action('finances.transfers.view',[$financeTransfer->to_id]),$financeTransfer->toAccount->name)}}</td>
					<td class="text-right">{{number_format($financeTransfer->amount,2)}}</td>
				</tr>
				@endforeach
			</tbody>
		</table>
		@endif
	</div>
</div>

@stop