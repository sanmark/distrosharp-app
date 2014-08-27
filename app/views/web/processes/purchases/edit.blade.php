@extends('web._templates.template')

@section('body')
<h2>Edit Purchase</h2>

<ul>

	@foreach($errors->all() as $error)
	<li>{{$error}}</li>
	@endforeach

</ul>
<table>
	{{Form::open()}}
	<tr>
		<td>Date</td>
		<td>{{Form::input('date','date',$purchaseInvoice->date,['required'=>'required'])}}</td>
		<td colspan="4"></td>
	</tr>
	<tr>
		<td>Vendor</td>
		<td>{{Form::select('vendor_id',$vendorSelectBox,$purchaseInvoice->vendor_id,['required'=>'required'])}}</td>
		<td colspan="4"></td>
	</tr>
	<tr>
		<td>Printed Invoice Number</td>
		<td>{{Form::text('printed_invoice_num',$purchaseInvoice->printed_invoice_num,['required'=>'required'])}}</td>
		<td colspan="4"></td>
	</tr>
	<tr>
		<td>Completely Paid</td>
		@if($purchaseInvoice->completely_paid=='0')
		<td>{{Form::checkbox('completely_paid')}}</td>
		@elseif($purchaseInvoice->completely_paid=='1')
		<td>{{Form::checkbox('completely_paid','1',true)}}</td>
		@endif
		<td colspan="4"></td>
	</tr>
	<tr>
		<td>Other Expenses Amount</td>
		<td>{{Form::text('other_expenses_amount',$purchaseInvoice->other_expenses_amount)}}</td>
		<td colspan="4"></td>
	</tr>
	<tr>
		<td>Other Expenses Total</td>
		<td>{{Form::text('other_expenses_total',$purchaseInvoice->other_expenses_total)}}</td>
		<td colspan="4"></td>
	</tr>
	<tr>
		<td>Stock</td>
		<td>{{$purchaseInvoice->stock->name}}</td>
		<td colspan="4"></td>
	</tr>
</table>
<?php
//foreach ( $purchaseInvoiceItemRows as $key )
//{
//	print_r($key->item_id);
//	echo '<br/>' ;
//}
//die();
//
//
?>
<table border="1">
	<tr>
		<th>Item Name</th>
		<th>Price</th>
		<th>Quantity</th>
		<th>Free Quantity</th>
		<th>Date</th>
		<th>Batch Number</th>

	</tr>
	@foreach($ItemRows as $ItemRowName=>$ItemRowValue )
	@if(in_array($ItemRowValue,$purchaseRows))

	<tr>
		<th>{{Form::hidden('item_id_'.$ItemRowValue,$ItemRowValue)}}{{$ItemRowName}}</th>
		<th>{{Form::input('number','buying_price_'.$ItemRowValue,$price[$ItemRowValue],['step'=>'any','required'=>'required'])}}</th>
		<th>{{Form::input('number','quantity_'.$ItemRowValue,$quantity[$ItemRowValue],['step'=>'any','required'=>'required'])}}</th>
		<th>{{Form::input('number','free_quantity_'.$ItemRowValue,$freeQuantity[$ItemRowValue],['step'=>'any'])}}</th>
		<th>{{Form::input('date','exp_date_'.$ItemRowValue,$expDate[$ItemRowValue],['step'=>'any'])}}</th>
		<th>{{Form::text('batch_number_'.$ItemRowValue,$batchNumber[$ItemRowValue])}}</th>
	</tr>
	@else
	<tr>
		<th>{{Form::hidden('item_id_'.$ItemRowValue,$ItemRowValue)}}{{$ItemRowName}}</th>
		<th>{{Form::input('number','buying_price_'.$ItemRowValue,null,['step'=>'any'])}}</th>
		<th>{{Form::input('number','quantity_'.$ItemRowValue,null,['step'=>'any'])}}</th>
		<th>{{Form::input('number','free_quantity_'.$ItemRowValue,null,['step'=>'any'])}}</th>
		<th>{{Form::input('date','exp_date_'.$ItemRowValue,null,['step'=>'any'])}}</th>
		<th>{{Form::text('batch_number_'.$ItemRowValue,null)}}</th>
	</tr>
	@endif
	@endforeach
	<tr>
		<td colspan="6">{{Form::submit('Submit')}}</td>
	</tr>
</table>
{{Form::close()}}
@stop