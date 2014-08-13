@extends('web._templates.template')

@section('body')

<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Vendors</h3>
	</div>
	<div class="panel-body">

		<table class="table table-striped">
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

	</div>
</div>

@stop