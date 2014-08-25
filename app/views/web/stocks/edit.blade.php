@extends('web._templates.template')

@section('body')
<h2>Edit Stock "{{$stock->name}}"</h2>

<table border="1">
	{{Form::model($stock)}}
	<tr>
		<td>{{Form::label('name')}}</td>
		<td>{{$stock->name}}</td>
	</tr>
	<tr>
		<td>{{Form::label('incharge_id')}}</td>
		<td>{{Form::select ( 'incharge_id' , $users )}}</td>
	</tr>
	<tr>
		<td>{{Form::label('stock_type_id')}}</td>
		<td>{{Form::select('stock_type_id', $stockTypes)}}</td>
	</tr>
	<tr>
		<td colspan="2" style="text-align: right;">
			{{Form::submit('Submit')}}
		</td>
	</tr>
	{{Form::close()}}
</table>

@stop