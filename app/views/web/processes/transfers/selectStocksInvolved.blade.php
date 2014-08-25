@extends('web._templates.template')

@section('body')
<h2>Add Transfer</h2>
<table border="1">
	{{Form::open()}}
	<tr>
		<th style="width: 200px;">{{Form::label('from')}}</th>
		<th style="width: 200px;">{{Form::label('to')}}</th>
		<td rowspan="2" style="width: 100px;">{{Form::submit('Submit')}}</td>
	</tr>
	<tr>
		<td>{{Form::select('from', $stocksHtmlSelect)}}</td>
		<td>{{Form::select('to', $stocksHtmlSelect)}}</td>
	</tr>
	{{Form::close()}}
</table>
@stop