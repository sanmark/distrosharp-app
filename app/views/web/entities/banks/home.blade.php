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
			<td>{{$bank->name}}</td>
			<td>{{$bank->is_active}}</td>
		</tr>
		@endforeach
	</tbody>
</table>
@stop