@extends('web._templates.template')

@section('body')
<h2>Finance Accounts</h2>
{{Form::open()}}
<table>
	<tr>
		<td>Income Account</td>
		<td>{{Form::select('income_account', $inHouseAccounts, $incomeAccount)}}</td>
	</tr>
	<tr>
		<td>Expense Account</td>
		<td>{{Form::select('expense_account', $inHouseAccounts, $expenseAccount)}}</td>
	</tr>
	<tr>
		<td colspan="2">
			{{Form::submit()}}
		</td>
	</tr>
</table>
{{Form::close()}}
@stop