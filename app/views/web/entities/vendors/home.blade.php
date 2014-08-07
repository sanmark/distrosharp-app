@extends('web._templates.template')

@section('body')
<h2>Vendors</h2>
<table border="1">
	<thead>
		<tr>
			<td>Name</td>
			<td>Details</td>
			<td>Is Active</td>
		</tr>
	</thead>
	<tbody>
		@foreach($vendors as $vendor)
		<tr>
			<td>{{HTML::link (URL::action ( 'entities.vendors.edit',[$vendor->id] ), $vendor->name )}}</td>
			<td>{{$vendor->details}}</td>
			<td>{{$vendor->is_active}}</td>
		</tr>
		@endforeach
	</tbody>
</table>

@stop