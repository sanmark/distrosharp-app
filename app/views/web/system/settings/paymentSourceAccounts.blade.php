@extends('web._templates.template')

@section('body')
<h2>Payment Source Accounts</h2>
<table border="1">
	{{Form::open()}}
	<tr>
		<td>{{Form::label('payment_source_cash')}}</td>
		<td>{{Form::select('payment_source_cash', $inHouseAccounts, $paymentSourceCash)}}</td>
	</tr>
	<tr>
		<td>{{Form::label('payment_source_cheque')}}</td>
		<td>{{Form::select('payment_source_cheque', $inHouseAccounts, $paymentSourceCheque)}}</td>
	</tr>
	<tr style="text-align: right;">
		<td colspan="2">{{Form::submit('Submit')}}</td>
	</tr>
	{{Form::close()}}
</table>
@stop