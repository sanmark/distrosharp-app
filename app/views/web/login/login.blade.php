@extends('web._templates.template')

@section('body')
@if(MessageButler::hasError())
<p>{{MessageButler::getError()}}</p>
@endif

{{Form::open()}}
{{Form::label('organization')}}
{{Form::text('organization')}}
<br>
{{Form::label('username')}}
{{Form::text('username')}}
<br>
{{Form::label('password')}}
{{Form::password('password')}}
<br>
{{Form::submit('Login')}}
{{Form::close()}}
@stop