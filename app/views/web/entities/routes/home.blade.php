@extends('web._templates.template')

@section('body')

<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Routes</h3>
	</div>
	<div class="panel-body">

		<table class="table table-striped" style="width: 30%;">
			<thead>
				<tr>
					<td>Name</td>
					<td>Is Active</td>
					<td>Rep</td>
				</tr>
			</thead>
			<tbody>
				@foreach($routes as $route)
				<tr>
					<td>{{HTML::link(URL::action('entities.routes.edit', [$route->id]), $route->name)}}</td>
					<td>{{$route->is_active}}</td>
					<td>{{$route->rep}}</td>
				</tr>
				@endforeach
			</tbody>
		</table>

	</div>
</div>

@stop