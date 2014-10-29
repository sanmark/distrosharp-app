@extends('web._templates.template')

@section('body')

<div class="panel panel-default well">
	<div class="panel-heading">
		<h3 class="panel-title">Account - {{$account->name}}</h3>
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
					{{Form::label('transfer_account')}}
					{{Form::select('transfer_account',$accountName,$accountRefill, array('tabindex' => '3', 'class' => ''))}}
				</div>
				<div class="form-group bottom-space">
					{{Form::label('in_or_out')}}
					{{Form::select('in_or_out',$inOrOutSelectBox,$inOrOut, array('tabindex' => '4', 'class' => ''))}}
				</div>
				<div class="form-group bottom-space">
					{{Form::label('amount')}}
					{{Form::select('compare_sign',$compareSignSelectBox,$compareSign, array('tabindex' => '5', 'class' => ''))}}
					{{Form::input('number','amount',$amount, array('tabindex' => '6', 'class' => ''),['step'=>'any'])}}
				</div>
				<div class="form-group bottom-space">
					{{Form::submit('Submit', array('tabindex' => '7', 'class' => 'btn btn-primary pull-right'))}}
				</div>
				{{Form::close()}}

			</div>
		</div>

		<br/>
		@if(count($accountTransfers)==0)
		<br>
		<div class="no-records-message text-center">
			There are no records to display
		</div>
		<br>
		@else
		<table class="table table-striped" style="width: 60%;">
			<thead>
				<tr>
					<th>Date/Time</th>
					<th>Description</th>
					<th>Transfer</th>
					<th>In</th>
					<th>Out</th>
					<th>Edit</th>
				</tr>
			</thead>
			<tbody>
				@foreach($accountTransfers as $accountTransfer)
				<tr>
					<td>{{$accountTransfer->date_time}}</td>
					<td>{{$accountTransfer->description}}</td>
					<td>
						@if($account->id==$accountTransfer->from_id)
						{{HTML::link(URL::action('finances.transfers.view',[$accountTransfer->to_id]),$accountTransfer->toAccount->name)}}
						@elseif($account->id==$accountTransfer->to_id)
						{{HTML::link(URL::action('finances.transfers.view',[$accountTransfer->from_id]),$accountTransfer->fromAccount->name)}}
						@endif
					</td>

					<td>@if($account->id==$accountTransfer->to_id) {{$accountTransfer->amount}} @endif</td>
					<td>@if($account->id==$accountTransfer->from_id) {{$accountTransfer->amount}} @endif</td>
					<td>{{HTML::link(URL::action('finances.transfers.edit',[$accountTransfer->id]),'Edit')}}</td>
				</tr>
				@endforeach
			</tbody>
		</table>
		@endif
	</div>
</div>

@stop
