@extends('web._templates.template')

@section('body')
<h2>Edit Vendor {{$vendor->name}}</h2>
@if($errors->count()>0)
<ul>
	@foreach($errors->all() as $error)
	<li>{{$error}}</li>
	@endforeach
</ul>
@endif
{{Form::model($vendor)}}
<table>
	<tr>
		<td>{{Form::label('name')}}</td>
		<td>{{Form::text('name')}}</td>
	</tr>
	<tr>
		<td>{{Form::label('details')}}</td>
		<td>{{Form::text('details')}}</td>
	</tr>
	<tr>
		<td>{{Form::label('is active')}}</td>
		<td>{{Form::checkbox('is_active')}}</td>
	</tr>
	<tr>
		<td colspan="2" align="right">{{Form::submit('submit')}}</td>
	</tr>
</table>
{{Form::close()}}
@stop