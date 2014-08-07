@extends('web._templates.template')

@section('body')
<h2>Edit Route "{{$route->name}}"</h2>
@if($errors->count()>0)
<ul>
	@foreach($errors->all() as $error)
	<li>{{$error}}</li>
	@endforeach
</ul>
@endif

{{Form::model($route)}}
<table>
	<tr>
		<td>{{Form::label('name')}}</td>
		<td>{{Form::text('name')}}</td>
	</tr>
	<tr>
		<td>{{Form::label('is_active')}}</td>
		<td>{{Form::checkbox('is_active')}}</td>
	</tr>
	<tr>
		<td>{{Form::label('rep')}}</td>
		<td>{{Form::text('rep')}}</td>
	</tr>
	<tr>
		<td>{{Form::submit('submit')}}</td>
		<td></td>
	</tr>
</table>
{{Form::close()}}
@stop