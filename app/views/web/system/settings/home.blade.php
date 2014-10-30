@extends('web._templates.template')

@section('body')

<div class="panel panel-default well">
	<div class="panel-heading">
		<h3 class="panel-title">Settings</h3>
	</div>
	<div class="panel-body">
		<br/>
		<ul class="list-group" style="width: 30%;">
			<li class="list-group-item">{{HTML::link(URL::action('system.settings.paymentSourceAccounts'),'Payment Source Accounts')}}</li>
			<li class="list-group-item">{{HTML::link(URL::action('system.settings.paymentTargetAccounts'),'Payment Target Accounts')}}</li>
			<li class="list-group-item">{{HTML::link(URL::action('system.settings.timezone'),'Time Zone')}}</li>
			<li class="list-group-item">{{HTML::link(URL::action('system.settings.imbalanceStock'),'Imbalance Stock')}}</li>
			<li class="list-group-item">{{HTML::link(URL::action('system.settings.mainStock'),'Main Stock')}}</li>
			<li class="list-group-item">{{HTML::link(URL::action('system.settings.financeAccounts'),'Finance Accounts')}}</li>
			<li class="list-group-item">{{HTML::link(URL::action('system.settings.organizationName'),'Organization Name')}}</li>
		</ul>

	</div>
</div>

@stop