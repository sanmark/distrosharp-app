@extends('web._templates.template')

@section('body')

<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Vendors</h3>
	</div>
	<div class="panel-body">


		<table border="1">
			{{Form::open()}}
			<tr>
				<td>{{Form::label('name')}}</td>
				<td>{{Form::text('name',$name)}}</td>
			</tr>
			<tr>
				<td>{{Form::label('is_active')}}</td>
				<td>{{Form::select ( 'is_active' , ViewButler::htmlSelectAnyYesNo(),$isActive)}}</td>
			</tr>
			<tr>
				<td colspan="2">
					{{Form::submit('Submit')}}
				</td>
			</tr>
			{{Form::close()}}
		</table>
		<table class="table table-striped" style="width: 60%;">
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