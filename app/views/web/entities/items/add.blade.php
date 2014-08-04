@extends('web._templates.template')

@section('body')
<h2>Add Item</h2>
@if($errors->count() > 0)
<ul>
	@foreach($errors->all() as $error)
	<li>{{$error}}</li>
	@endforeach
</ul>
@endif

{{Form::open()}}
<table>
	<tr>
		<td>{{Form::label('code')}}</td>
		<td>{{Form::text('code')}}</td>
	</tr>
	<tr>
		<td>{{Form::label('name')}}</td>
		<td>{{Form::text('name')}}</td>
	</tr>
	<tr>
		<td>{{Form::label('reorder_level')}}</td>
		<td>{{Form::text('reorder_level')}}</td>
	</tr>
	<tr>
		<td>{{Form::label('current_buying_price')}}</td>
		<td>{{Form::text('current_buying_price')}}</td>
	</tr>
	<tr>
		<td>{{Form::label('current_selling_price')}}</td>
		<td>{{Form::text('current_selling_price')}}</td>
	</tr>
	<tr>
		<td>{{Form::label('buying_invoice_order')}}</td>
		<td>{{Form::text('buying_invoice_order')}}</td>
	</tr>
	<tr>
		<td>{{Form::label('selling_invoice_order')}}</td>
		<td>{{Form::text('selling_invoice_order')}}</td>
	</tr>
	<tr>
		<td>{{Form::label('is_active')}}</td>
		<td>{{Form::checkbox('is_active')}}</td>
	</tr>
	<tr>
		<td colspan="2" style="text-align: right;">
			{{Form::submit('Submit')}}
		</td>
	</tr>
</table>
{{Form::close()}}
@stop