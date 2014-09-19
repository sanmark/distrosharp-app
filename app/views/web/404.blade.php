@extends('web._templates.template')

@section('body')

<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">404 - Page not found</h3>
	</div>
	<div class="panel-body-404">
		<h1 class="text-center">404</h1>
		<h2 class="text-center">The page you are looking for isn't available.<br>
			Instead you can go to<br>{{ HTML::link('http://d-071-1-v2.loc', 'HOME',null, true)}}</h2>
	</div>
</div>
@stop