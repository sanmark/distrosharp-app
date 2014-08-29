@extends('web._templates.template')

@section('body')

<h3>Edit Finance Account- {{$financeAccount->name}}</h3>

<ul>

	@foreach($errors->all() as $error)
	<li>{{$error}}</li>
	@endforeach

</ul>

{{Form::model($financeAccount)}}
<table>
	<tr>
		<td>{{Form::label('name','name')}}</td>
		<td>{{Form::text('name',null,['required'=>'required'])}}</td>
	</tr>
	<tr>
		<td>{{Form::label('bank_id','bank id')}}</td>
		<td>{{Form::select('bank_id',$bankSelectBox)}}</td>
	</tr>
	<tr>
		<td>{{Form::label('is_in_house','is in house')}}</td>
		<td>{{Form::checkbox('is_in_house')}}</td>
	</tr>
	<tr>
		<td>{{Form::label('is_active','is active')}}</td>
		<td>{{Form::checkbox('is_active')}}</td>
	</tr>
	<tr>
		<td colspan="2">{{Form::submit('Submit')}}</td>
	</tr>
</table>
{{Form::close()}}

@stop
