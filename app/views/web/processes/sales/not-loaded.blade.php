@extends('web._templates.template')

@section('body')

<div class="panel panel-default well">
	<div class="panel-heading">
		<h3 class="panel-title">Not loaded</h3>
	</div>
	<div class="panel-body">
		<p>The stock is empty. You need to transfer items to start selling.</p>
		<p>{{ HTML::linkRoute('processes.transfers.selectStocksInvolved','Load now?') }}</p>
		<p>Or you can {{HTML::link ( URL::action ( 'processes.sales.setRep' ) , 'choose another Rep' )}}.</p>
	</div>
</div>

@stop