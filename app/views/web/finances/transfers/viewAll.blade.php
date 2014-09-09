@extends('web._templates.template')

@section('body')

<h3>All Finance Transfers</h3>

{{Form::open()}}
<table border="1">
	<tr>
		<td>{{Form::label('from_date')}}</td>
		<td>{{Form::input('datetime-local','from_date',$fromDate)}}</td>
		<td>{{Form::label('to_date')}}</td>
		<td>{{Form::input('datetime-local','to_date',$toDate)}}</td>
	</tr>
	<tr>
		<td>{{Form::label('from_account')}}</td>
		<td>{{Form::select('from_account',$fromAccountsIds,$fromAccount)}}</td>
		<td>{{Form::label('to_account')}}</td>
		<td>{{Form::select('to_account',$toAccountsIds,$toAccount)}}</td>
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
		<th>From account</th>
		<th>To account</th>
		<th>Amount</th>
	</tr>
	@foreach($financeData as $financeTransfer)
	<tr>
		<td>{{$financeTransfer->date_time}}</td>
		<td>{{$financeTransfer->description}}</td>
		<td>{{HTML::link(URL::action('finances.transfers.view',[$financeTransfer->from_id]),$financeTransfer->fromAccount->name)}}</td>
		<td>{{HTML::link(URL::action('finances.transfers.view',[$financeTransfer->to_id]),$financeTransfer->toAccount->name)}}</td>
		<td>{{$financeTransfer->amount}}</td>
	</tr>
	@endforeach
</table>
@stop