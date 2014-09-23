@extends('web._templates.template')

@section('body')
<h2>Edit Sale</h2>
{{Form::open()}}
<table>
	<tr>
		<td>{{Form::label('id')}}</td>
		<td>{{Form::label('id', $sellingInvoice->id)}}</td>
	</tr>
	<tr>
		<td>{{Form::label('date_time')}}</td>
		<td>{{Form::input('text', 'date_time', $sellingInvoice->date_time)}}</td>
	</tr>
	<tr>
		<td>{{Form::label('customer_id')}}</td>
		<td>{{Form::select('customer_id', $customerDropDown, $sellingInvoice->customer_id)}}</td>
	</tr>
	<tr>
		<td>Rep</td>
		<td>{{$sellingInvoice->rep->username}}</td>
	</tr>
	<tr>
		<td>{{Form::label('printed_invoice_number')}}</td>
		<td>{{Form::text('printed_invoice_number', $sellingInvoice->printed_invoice_number)}}</td>
	</tr>
	<tr>
		<td>{{Form::label('discount')}}</td>
		<td>{{Form::input('number', 'discount', $sellingInvoice->discount)}}</td>
	</tr>
	<tr>
		<td>{{Form::label('is_completely_paid')}}</td>
		<td>{{Form::checkbox('is_completely_paid', TRUE, $sellingInvoice->is_completely_paid)}}</td>
	</tr>
	<tr>
		<td colspan="2">
			<table>
				<tr>
					<th>Item</th>
					<th>Price</th>
					<th>Paid Q</th>
					<th>Free Q</th>
					<th>GR Price</th>
					<th>GR Q</th>
					<th>CR Price</th>
					<th>CR Q</th>
				</tr>
				@foreach($items as $item)
				<?php
				$sellingItem = $sellingInvoice -> sellingItems -> filter ( function($sellingItem) use($item)
				{
					if ( $sellingItem -> item_id == $item -> id )
					{
						return TRUE ;
					}
				} ) -> first () ;
				?>
				<tr>
					<td>{{$item->name}}</td>
					<td>{{Form::input('number', 'items['.$item->id.'][price]', ObjectHelper::nullIfNonObject($sellingItem, 'price'))}}</td>
					<td>{{Form::input('number', 'items['.$item->id.'][paid_quantity]', ObjectHelper::nullIfNonObject($sellingItem, 'paid_quantity'))}}</td>
					<td>{{Form::input('number', 'items['.$item->id.'][free_quantity]', ObjectHelper::nullIfNonObject($sellingItem, 'free_quantity'))}}</td>
					<td>{{Form::input('number', 'items['.$item->id.'][good_return_price]', ObjectHelper::nullIfNonObject($sellingItem, 'good_return_price'))}}</td>
					<td>{{Form::input('number', 'items['.$item->id.'][good_return_quantity]', ObjectHelper::nullIfNonObject($sellingItem, 'good_return_quantity'))}}</td>
					<td>{{Form::input('number', 'items['.$item->id.'][company_return_price]', ObjectHelper::nullIfNonObject($sellingItem, 'company_return_price'))}}</td>
					<td>{{Form::input('number', 'items['.$item->id.'][company_return_quantity]', ObjectHelper::nullIfNonObject($sellingItem, 'company_return_quantity'))}}</td>
				</tr>
				@endforeach
			</table>
		</td>
	</tr>
	<tr>
		<td>Payments</td>
		<td>
			<table border="1">
				<tr>
					<th>ID</th>
					<th>From</th>
					<th>Date</th>
					<th>Amount</th>
					<th></th>
				</tr>
				@foreach($sellingInvoice->financeTransfers as $financeTransfer)
				<tr>
					<td>{{$financeTransfer->id}}</td>
					<td>{{HTML::link(URL::action('finances.transfers.view', [$financeTransfer->from_id]),$financeTransfer->fromAccount->name)}}</td>
					<td>{{$financeTransfer->date_time}}</td>
					<td>{{number_format($financeTransfer->amount,2)}}</td>
					<td>{{HTML::link(URL::action('finances.transfers.edit', [$financeTransfer->id]), 'Edit...')}}</td>
				</tr>
				@endforeach
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2" style="text-align: right;">
			{{Form::submit('Submit')}}
		</td>
	</tr>
</table>
{{Form::close()}}
@stop