@extends('web._templates.template')

@section('body')

<div class="panel panel-default well">
	<div class="panel-heading">
		<h3 class="panel-title">Stocks</h3>
	</div>
	<div class="panel-body">
		@if(count($stocks)==0)
		<br>
		<div class="no-records-message text-center">
			There are no records to display
		</div>
		<br>
		@else
		<table class="table table-striped" style="width: 60%;">
			<tr>
				<th>Name</th>
				<th>In-charge</th>
				<th>Stock Type</th>
				<th></th>
			</tr>
			<tbody>
				@foreach($stocks as $stock)
				<tr>
					<td>{{HTML::link ( URL::action ( 'stocks.view', [$stock->id]), $stock->name)}}</td>
					<td>{{$stock['incharge']['username']}}</td>
					<td>{{$stock->stockType->label}}</td>
					<td class="text-right">{{HTML::link ( URL::action ( 'stocks.edit', [$stock->id]), 'Edit...')}}</td>
				</tr>
				@endforeach
			</tbody>
		</table>
		@endif
	</div>
</div>

@stop