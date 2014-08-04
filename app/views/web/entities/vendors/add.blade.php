@extends('web._templates.template')

@section('body')
<h2>Add Vendor</h2>
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
		<td>{{Form::label('Name')}}</td>
		<td>{{Form::text('name')}}</td>
	</tr>
	<tr>
		<td>{{Form::label('Details')}}</td>
		<td>{{Form::textarea('details')}}</td>
	</tr>
	<tr>
		<td>{{Form::label('Status')}}</td>
		<td>{{Form::checkbox('is_active')}}</td>
	</tr>
	<tr>
		<td>{{Form::submit('Submit')}}</td>
		<td></td>
	</tr>
</table>
{{Form::close()}}
@stop
