@extends('web._templates.template')

@section('body')

<h2>Add Purchase</h2>
@if($errors->count()>0)
<ul>
	@foreach($errors->all() as $error)
	<li>{{$error}}</li>
	@endforeach
</ul>
@endif
{{Form::open()}}
<table>
	<tr>
		<td>Date</td>
		<td>{{Form::input ( 'date','purchase_date',null)}}</td>
		<td colspan="4"></td>
	</tr>
	<tr>
		<td>Vendor</td>
		<td>{{Form::select('vendor_id',\Models\Vendor::getVendorsForHtmlSelect(),null)}}</td>
		<td colspan="4"></td>
	</tr>
	<tr>
		<td>Print Invoice Number</td>
		<td>{{Form::text('printed_invoice_num',null)}}</td>
		<td colspan="4"></td>
	</tr>
	<tr>
		<td>Completely Paid</td>
		<td>{{Form::checkbox('is_paid')}}</td>
		<td colspan="4"></td>
	</tr>
	<tr>
		<td>Other Expense Amount</td>
		<td>{{Form::text('other_expense_amount')}}</td>
		<td colspan="4"></td>
	</tr>
	<tr>
		<td>Other Expense Total</td>
		<td>{{Form::text('other_expense_total')}}</td>
		<td colspan="4"></td>
	</tr>
	<tbody id="add_purchase_items">
		<?php $row_counter = 0 ; ?>
		@foreach($item_rows as $item_row)

		<tr>
			<td>{{Form::hidden('item_id_'.$item_row->id,$item_row->id)}}{{$item_row->name}}</td>
			<td>{{Form::input('number','buying_price_'.$item_row->id,$item_row->current_buying_price,['step'=>'any'])}}</td>
			<td>{{Form::input('number','quantity_'.$item_row->id,null,['placeholder'=>'Quantity','step'=>'any'])}}</td>
			<td>{{Form::input('number','free_quantity_'.$item_row->id,null,['placeholder'=>'Free Quantity','step'=>'any'])}}</td>
			<td>{{Form::input('date','exp_date_'.$item_row->id,null)}}</td>
			<td>{{Form::text('batch_number_'.$item_row->id,null,['placeholder'=>'Batch Number'])}}</td>
		</tr>
		<?php //$row_counter++; ?>
		@endforeach

	</tbody>
	<tr>
		<td colspan="6" style="text-align:right">{{Form::hidden('row_counter',$row_counter)}}{{Form::submit('Submit')}}</td>
	</tr>
</table>
{{Form::close()}}
@stop