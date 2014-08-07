@extends('web._templates.template')

@section('body')
<h1>Routes</h1>
<table border="1">
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
@stop