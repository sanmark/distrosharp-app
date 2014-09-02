@extends('web._templates.template')

@section('body')

<h3>Account - {{$account->name}}</h3>

{{Form::open()}}
<table border="1">
	<tr>
		<td>{{Form::label('from_date')}}</td>
		<td>{{Form::input('datetime-local','from_date',$fromDate)}}</td>
		<td>{{Form::label('to_date')}}</td>
		<td>{{Form::input('datetime-local','to_date',$toDate)}}</td>
	</tr>
	<tr colspan="4">
		<td>{{Form::label('transfer_account')}}</td>
		<td>{{Form::select('transfer_account',$accountName,$accountRefill)}}</td>
	</tr>
	<tr colspan="4">
		<td>{{Form::label('in_or_out')}}</td>
		<td>{{Form::select('in_or_out',$inOrOutSelectBox,$inOrOut)}}</td>
	</tr>
	<tr colspan="4">
		<td>{{Form::label('amount')}}</td>
		<td>{{Form::select('compare_sign',$compareSignSelectBox,$compareSign)}}</td>
		<td>{{Form::input('number','amount',$amount,['step'=>'any'])}}</td>
	</tr>
	<tr colspan="4">
		<td>{{Form::submit('Submit')}}</td>
	</tr>
</table>
{{Form::close()}}



<table border="1">
	<tr>
		<th>Date/Time</th>
		<th>Description</th>
		<th>Transfer</th>
		<th>In</th>
		<th>Out</th>
		<th>Edit</th>
	</tr>

	@foreach($accountTransfers as $accountTransfer)
	<tr>
		<td>{{$accountTransfer->date_time}}</td>
		<td>{{$accountTransfer->description}}</td>
		<td>
			@if($account->id==$accountTransfer->from_id)
			{{$accountTransfer->toAccount->name}}
			@elseif($account->id==$accountTransfer->to_id)
			{{$accountTransfer->fromAccount->name}}
			@endif
		</td>

		<td>@if($account->id==$accountTransfer->to_id) {{$accountTransfer->amount}} @endif</td>
		<td>@if($account->id==$accountTransfer->from_id) {{$accountTransfer->amount}} @endif</td>
		<td>{{HTML::link(URL::action('finances.transfers.edit',[$accountTransfer->id]),'Edit')}}</td>
	</tr>
	@endforeach
</table>

@stop
