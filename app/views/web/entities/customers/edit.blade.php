@extends('web._templates.template')

@section('body') 
 <h2>Edit Customer "{{$customer->name}}"</h2>
 
 @if($errors->count() > 0)
<ul>
	@foreach($errors->all() as $error)
	<li>{{$error}}</li>
	@endforeach
</ul>
@endif
 
 {{Form::model($customer)}}
 
	<table>
		<tr>
			<td>{{Form::label('name')}}</td>
			<td>{{Form::text('name')}}</td>
		</tr>
		<tr>
			<td>{{Form::label('route_id')}}</td>
			<td>{{Form::text('route_id')}}</td>
		</tr>
		<tr>
			<td>{{Form::label('is_active')}}</td>
			<td>{{Form::text('is_active')}}</td>
		</tr>
		<tr>
			<td>{{Form::label('details')}}</td>
			<td>{{Form::textarea('details')}}</td>
		</tr>
		<tr>
			<td>{{Form::submit('Submit')}}</td>
			<td> </td> 
	</tr>
	</table>
 {{Form::close()}}

@stop