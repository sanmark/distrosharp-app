@extends('web._templates.template')

@section('body')

<h3>Account - {{$account->name}}</h3>

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
		@if($account->id==$accountTransfer->from_id)
		<td>{{$accountTransfer->toAccount->name}}</td>
		@elseif($account->id==$accountTransfer->to_id)
		<td>{{$accountTransfer->fromAccount->name}}</td>
		@endif

		<td>@if($account->id==$accountTransfer->to_id) {{$accountTransfer->amount}} @endif</td>
		<td>@if($account->id==$accountTransfer->from_id) {{$accountTransfer->amount}} @endif</td>
		<td>{{HTML::link(URL::action('finances.transfers.edit',[$accountTransfer->id]),'Edit')}}</td>
	</tr>
	@endforeach
</table>

@stop
