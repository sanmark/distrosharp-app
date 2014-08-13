@extends('web._templates.template')

@section('body')

<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Customers</h3>
	</div>
	<div class="panel-body">

		<table class="table table-striped" style="width: 60%;">
			<thead>
				<tr>
					<td>Name</td>
					<td>Route</td>
					<td>Is Active</td>
					<td>Details</td>
				</tr>
			</thead>
			<tbody>
				@foreach($customers as $customer)
				<tr>
					<td> {{HTML::link ( URL::action ( 'entities.customers.edit', [$customer->id] ), $customer->name ) }}</td>
					<td>{{$customer->route_id}}</td>
					<td>{{$customer->is_active}}</td>
					<td>{{$customer->details}}</td>
				</tr>
				@endforeach
			</tbody>
		</table>

	</div>
</div>

@stop