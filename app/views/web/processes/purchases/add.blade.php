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
		<?php $rowCounter = 0 ; ?>
		@foreach($itemRows as $itemRow)

		<tr>
			<td>{{Form::hidden('item_id_'.$itemRow->id,$itemRow->id)}}{{$itemRow->name}}</td>
			<td>{{Form::input('number','buying_price_'.$itemRow->id,$itemRow->current_buying_price,['step'=>'any'])}}</td>
			<td>{{Form::input('number','quantity_'.$itemRow->id,null,['placeholder'=>'Quantity','step'=>'any'])}}</td>
			<td>{{Form::input('number','free_quantity_'.$itemRow->id,null,['placeholder'=>'Free Quantity','step'=>'any'])}}</td>
			<td>{{Form::input('date','exp_date_'.$itemRow->id,null)}}</td>
			<td>{{Form::text('batch_number_'.$itemRow->id,null,['placeholder'=>'Batch Number'])}}</td>
		</tr>
		<?php //$rowCounter++; ?>
		@endforeach

	</tbody>
	<tr>
		<td colspan="6" style="text-align:right">{{Form::hidden('row_counter',$rowCounter)}}{{Form::submit('Submit')}}</td>
	</tr>
</table>
{{Form::close()}}
@stop