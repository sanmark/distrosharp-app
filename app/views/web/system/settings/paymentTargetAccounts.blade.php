@extends('web._templates.template')

@section('body')
<h2>Payment Target Accounts</h2>
<table>
	{{Form::open()}}
	<tr>
		<td>{{Form::label('payment_target_cash')}}</td>
		<td>{{Form::select('payment_target_cash', $inHouseAccounts, $paymentTargetCash)}}</td>
	</tr>
	<tr>
		<td>{{Form::label('payment_target_cheque')}}</td>
		<td>{{Form::select('payment_target_cheque', $inHouseAccounts, $paymentTargetCheque)}}</td>
	</tr>
	<tr>
		<td colspan="2">
			{{Form::submit('Submit')}}
		</td>
	</tr>
	{{Form::close()}}
</table>
@stop