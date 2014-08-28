@extends('web._templates.template')

@section('body')
<h2>Add Sale</h2>
{{Form::open()}}
<table>
	<tr>
		<td>{{Form::label('date_time')}}</td>
		<td>{{Form::input('datetime-local','date_time')}}</td>
	</tr>
	<tr>
		<td>{{Form::label('customer_id')}}</td>
		<td>{{Form::select('customer_id',$customers)}}</td>
	</tr>
	<tr>
		<td>{{Form::label('printed_invoice_number')}}</td>
		<td>{{Form::text('printed_invoice_number')}}</td>
	</tr>
	<tr>
		<td>{{Form::label('discount')}}</td>
		<td>{{Form::input('number','discount', NULL, ['step'=>0.01])}}</td>
	</tr>
	<tr>
		<td>{{Form::label('is_completely_paid')}}</td>
		<td>{{Form::checkbox('is_completely_paid')}}</td>
	</tr>
</table>

<table border="1">
	<tr>
		<th>Item</th>
		<th>Available Amount</th>
		<th>Price</th>
		<th>Paid Q</th>
		<th>Free Q</th>
		<th>GR Price</th>
		<th>GR Q</th>
		<th>CR Price</th>
		<th>CR Q</th>
	</tr>
	@foreach($items as $item)
	<tr>
		<td>{{$item->name}}</td>
		<td>
			{{$stockDetails[$item->id]['good_quantity']}}
			{{Form::hidden('items['.$item->id.'][available_quantity]', $stockDetails[$item->id]['good_quantity'])}}
		</td>
		<td>{{Form::input('number','items['.$item->id.'][price]',$item->current_selling_price,['step'=>0.01])}}</td>
		<td>{{Form::input('number','items['.$item->id.'][paid_quantity]',NULL)}}</td>
		<td>{{Form::input('number','items['.$item->id.'][free_quantity]',NULL)}}</td>
		<td>{{Form::input('number','items['.$item->id.'][good_return_price]',NULL,['step'=>0.01])}}</td>
		<td>{{Form::input('number','items['.$item->id.'][good_return_quantity]',NULL)}}</td>
		<td>{{Form::input('number','items['.$item->id.'][company_return_price]',NULL,['step'=>0.01])}}</td>
		<td>{{Form::input('number','items['.$item->id.'][company_return_quantity]',NULL)}}</td>
	</tr>
	@endforeach
</table>
{{Form::submit('Submit')}}
{{Form::close()}}
@stop