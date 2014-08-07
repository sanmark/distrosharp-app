@extends('web._templates.template')

@section('body')
<h2>Banks</h2>
<table border="1">
	<thead>
		<tr>
			<td>Name</td>
			<td>Is Active</td>
		</tr>
	</thead>
	<tbody>
		@foreach($banks as $bank)
		<tr>
			<td>{{HTML::link ( URL::action ('entities.banks.edit',[$bank->id] ),$bank->name )}}</td>
			<td>{{$bank->is_active}}</td>
		</tr>
		@endforeach
	</tbody>
</table>
@stop