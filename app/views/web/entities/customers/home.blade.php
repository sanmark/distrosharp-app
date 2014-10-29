@extends('web._templates.template')

@section('body')
<div class="panel panel-default well">
	<div class="panel-heading">
		<h3 class="panel-title">Customers</h3>
	</div>
	<div class="panel-body">

		<div class="panel panel-default" style="">
			<div class="panel-body">

				{{Form::open(['class'=>'form-inline', 'role'=>'form'])}}

				<div class="form-group inline-form">
					{{Form::label('name', null, array('class' => 'control-label'))}}
					{{Form::text('name', $name, array('tabindex' => '1', 'class' => ''))}}
				</div>
				<div class="form-group inline-form">
					{{Form::label('route', null, array('class' => 'control-label'))}}
					{{Form::select ( 'route' , $routeSelectBox, $routeId, array('tabindex' => '2', 'class' => ''))}}
				</div>
				<div class="form-group inline-form">
					{{Form::label('is_active', null, array('class' => 'control-label'))}}
					{{Form::select ( 'is_active' , ViewButler::htmlSelectAnyYesNo (), $isActive, array('tabindex' => '3', 'class' => ''))}}
				</div>
				<div class="form-group">
					{{Form::submit('Submit', array('tabindex' => '4', 'class' => 'btn btn-primary pull-right'))}}
				</div>
				{{Form::close()}}

			</div>
		</div>
		@if(count($customers)==0)
		<br>
		<div class="no-records-message text-center">
			There are no records to display
		</div>
		<br>
		@else
		<table class="table table-striped" style="width: 60%;">

			<thead>
				<tr>
					<td>Name</td>
					<td>Route</td>
					<td class="text-center">Is Active</td>
					<td>Details</td>
				</tr>
			</thead>
			<tbody>
				@foreach($customers as $customer)
				<tr>
					<td> {{HTML::link ( URL::action ( 'entities.customers.edit', [$customer->id] ), $customer->name ) }}</td>
					<td>{{$customer->route->name}}</td>
					<td class="text-center">{{ViewButler::getYesNoFromBoolean ( $customer->is_active)}}</td>
					<td>{{$customer->details}}</td>
				</tr>
				@endforeach
			</tbody>
		</table>
		@endif
	</div>
</div>
@stop