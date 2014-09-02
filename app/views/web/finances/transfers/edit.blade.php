@extends('web.._templates.template')

@section('body')

<h3>Edit Transfer</h3>

{{Form::model($financeTransfer)}}
<table>
	<tr colspan="3">
		<td>{{Form::label('date_time','date_time')}}</td>
		<td>{{Form::input('datetime-local','date_time',$dateTime,['required'=>'required'])}}</td>
	</tr>
	<tr>
		<td>{{Form::label('from','from')}}</td>
		<td>{{Form::label('amount','amount')}}</td>
		<td>{{Form::label('to','to')}}</td>
	</tr>
	<tr>
		<td>{{Form::select('from_id',$accountSelectBox)}}</td>
		<td>{{Form::input('number','amount',null,['step'=>'any','required'=>'required'])}}</td>
		<td>{{Form::select('to_id',$accountSelectBox)}}</td>
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

