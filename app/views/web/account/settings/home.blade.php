@extends('web._templates.template')

@section('body')
<div>
	@if($errors->count()>0)
	<ul class="errorstring">
		@foreach($errors->all() as $error)
		<li>{{$error}}</li>
		@endforeach
	</ul>
	@endif

	<h2>Basic Details</h2>
	{{Form::model($user, [
		'action'=>'account.settings.basic'
	])}}
	<table>
		<tr>
			<td>{{Form::label('username')}}</td>
			<td>{{$user->username}}</td>
		</tr>
		<tr>
			<td>{{Form::label('first_name')}}</td>
			<td>{{Form::text('first_name')}}</td>
		</tr>
		<tr>
			<td>{{Form::label('last_name')}}</td>
			<td>{{Form::text('last_name')}}</td>
		</tr>
		<tr>
			<td colspan="2" style="text-align: right;">{{Form::submit('Submit')}}</td>
		</tr>
	</table>
	{{Form::close()}}
</div>
<div>
	<h2>Password</h2>
	{{Form::open([
		'action'=>'account.settings.password'
	])}}
	<table>
		<tr>
			<td>
				{{Form::label('existing_password')}}
			</td>
			<td>
				{{Form::password('existing_password')}}
			</td>
		</tr>
		<tr>
			<td>
				{{Form::label('new_password')}}
			</td>
			<td>
				{{Form::password('new_password')}}
			</td>
		</tr>
		<tr>
			<td>
				{{Form::label('confirm_new_password')}}
			</td>
			<td>
				{{Form::password('confirm_new_password')}}
			</td>
		</tr>
		<tr>
			<td colspan="2" style="text-align: right;">
				{{Form::submit('Submit')}}
			</td>
		</tr>
	</table>
	{{Form::close()}}
</div>
@stop