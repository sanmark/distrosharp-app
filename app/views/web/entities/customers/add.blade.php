@extends('web._templates.template')

@section('body')
<h2>Add Customer</h2>
@if($errors->count()>0)
<ul>
	@foreach($errors->all() as $error)
	<li>{{$error}}</li>
	@endforeach
</ul>
@endif
{{Form::open()}}
<table>
	<tr>
		<td>{{Form::label('name')}}</td>
		<td>{{Form::text('name')}}</td>
	</tr>
	<tr>
		<td>{{Form::label('route id')}}</td>
		<td>{{Form::text('route_id')}}</td>
	</tr>
	<tr>
		<td>{{Form::label('is active')}}</td>
		<td>{{Form::checkbox('is_active')}}</td>
	</tr>
	<tr>
		<td>{{Form::label('details')}}</td>
		<td>{{Form::text('details')}}</td>
	</tr>
	<tr>
		<td>{{Form::submit('submit')}}</td>
		<td></td>
	</tr>
</table>
{{Form::close()}}
@stop