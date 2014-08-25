@extends('web._templates.template')

@section('body')
<h2>Add Transfer</h2>
<p>Transfer Items from stock "{{$fromStock->name}}" to stock "{{$toStock->name}}".</p>
{{Form::open()}}
<table>
	<tr>
		<td>{{Form::label('date_time', 'Date and Time')}}</td>
		<td>{{Form::input ( 'datetime-local', 'date_time')}}</td>
	</tr>
</table>
<table border="1">
	<tr>
		<th>Item</th>
		<th>Available Amount in Stock "{{$fromStock->name}}"</th>
		<th>Transferring Amount</th>
		<th>Current Amount in Stock "{{$toStock->name}}"</th>
	</tr>
	@foreach($items as $item)
	<tr>
		<td>{{$item->name}}</td>
		<td>{{$fromStockDetails[$item->id]}}{{Form::hidden('availale_amounts['.$item->id.']', $fromStockDetails[$item->id])}}</td>
		<td>{{Form::text('transfer_amounts['.$item->id.']')}}</td>
		<td>{{$toStockDetails[$item->id]}}</td>
	</tr>
	@endforeach
</table>
{{Form::submit('Submit')}}
{{Form::close()}}
@stop