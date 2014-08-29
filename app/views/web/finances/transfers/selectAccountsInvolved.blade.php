@extends('web._templates.template')

@section('body')

<h3>Select Accounts</h3>

{{Form::open()}}

<table>
	<tr>
		<td>{{Form::label('from','from')}}</td>
		<td>{{Form::select('from',$accountSelectBox)}}</td>
	</tr>
	<tr>
		<td>{{Form::label('to','to')}}</td>
		<td>{{Form::select('to',$accountSelectBox)}}</td>
	</tr>
	<tr>
		<td colspan="2">{{Form::submit('Submit')}}</td>
	</tr>
</table>

{{Form::close()}}

@stop