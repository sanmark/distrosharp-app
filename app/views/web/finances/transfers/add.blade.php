@extends('web._templates.template')

@section('body')

<h3>Add finance transfers</h3>

{{Form::open()}}
<table>
	<tr colspan="3">
		<td>Date and Time</td>
		<td>{{Form::input('datetime-local','date_time',null,['required'=>'required'])}}</td>
	</tr>
	<tr>
		<td>{{Form::label('from','from')}}</td>
		<td>{{Form::label('amount','amount')}}</td>
		<td>{{Form::label('to','to')}}</td>
	</tr>
	<tr>
		<td>{{$fromAccount->name}}</td>
		<td>{{Form::input('number','amount',null,['step'=>'any','required'=>'required'])}}</td>
		<td>{{$toAccount->name}}</td>
	</tr>
	<tr colspan="3">
		<td>{{Form::label('description','description')}}</td>
		<td>{{Form::textarea('description')}}</td>
	</tr>
	<tr colspan="3">
		<td>{{Form::submit('Submit')}}</td>
	</tr>
</table>
{{Form::close()}}

@stop

