@extends('web._templates.template')

@section('body')
<h2>Settings</h2>
<table border="1">
	<tr>
		<td>{{HTML::link(URL::action('system.settings.paymentSourceAccounts'),'Payment Source Accounts')}}</td>
	</tr>
</table>
@stop