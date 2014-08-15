@extends('web._templates.template')

@section('body')
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Customers</h3>
	</div>
	<div class="panel-body">

		<table border="1">
			{{Form::open()}}
			<tr>
				<td>{{Form::label('name')}}</td>
				<td>{{Form::text('name', $name)}}</td>
			</tr>
			<tr>
				<td>{{Form::label('route')}}</td>
				<td>{{Form::select ( 'route' , $routeSelectBoxContent, $routeId )}}</td>
			</tr>
			<tr>
				<td>{{Form::label('is_active')}}</td>
				<td>{{Form::select ( 'is_active' , ViewButler::htmlSelectAnyYesNo (), $isActive )}}</td>
			</tr>
			<tr>
				<td colspan="2">
					{{Form::submit('Submit')}}
				</td>
			</tr>
			{{Form::close()}}
		</table>

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
					<td>{{$customer->route->name}}</td>
					<td>{{ViewButler::getYesNoFromBoolean ( $customer->is_active)}}</td>
					<td>{{$customer->details}}</td>
				</tr>
				@endforeach
			</tbody>
		</table>

	</div>
</div>
@stop