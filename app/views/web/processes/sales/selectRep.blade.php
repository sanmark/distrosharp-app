@extends('web._templates.template')

@section('body')
<h2>Select Rep</h2>
{{Form::open()}}
{{Form::label('rep_id', 'Rep')}}
{{Form::select('rep_id', $repSelectBox, $currentRepId)}}
{{Form::submit('Submit')}}
{{Form::close()}}
@stop