@extends('web._templates.template')

@section('body')
<h2>Customers</h2>
<table border="1">
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
@stop