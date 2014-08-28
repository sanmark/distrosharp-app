@extends('web._templates.template')

@section('body')
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Customers</h3>
	</div>
	<div class="panel-body">

		<div class="panel panel-default" style="">
			<div class="panel-body">

				{{Form::open(['class'=>'form-inline', 'role'=>'form'])}}

				<div class="form-group inline-form">
					{{Form::label('name', null, array('class' => 'control-label'))}}
					{{Form::text('name', $name, array('class' => 'form-control'))}}
				</div>
				<div class="form-group inline-form">
					{{Form::label('route', null, array('class' => 'control-label'))}}
					{{Form::select ( 'route' , $routeSelectBox, $routeId, array('class' => 'form-control'))}}
				</div>
				<div class="form-group inline-form">
					{{Form::label('is_active', null, array('class' => 'control-label'))}}
					{{Form::select ( 'is_active' , ViewButler::htmlSelectAnyYesNo (), $isActive, array('class' => 'form-control'))}}
				</div>
				<div class="form-group">
					{{Form::submit('Submit', array('class' => 'btn btn-default pull-right'))}}
				</div>
				{{Form::close()}}

			</div>
		</div>

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