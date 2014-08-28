@extends('web._templates.template');

@section('body')

<h2>View Purchases</h2>

<table border="1">
	{{Form::open()}}
	<tr>
		<td>{{Form::label('invoice_id')}}</td>
		<td>{{Form::text('id',$id)}}</td>
	</tr>
	<tr>
		<td>{{Form::label('vendor_id')}}</td>
		<td>{{Form::select('vendor_id',$vendorSelectBox,$vendorId)}}</td>
	</tr>
	<tr>
		<td>{{Form::label('date')}}</td>
		<td>{{Form::input('date','date',$date)}}</td>
	</tr>
	<tr>
		<td>{{Form::label('is_paid')}}</td>
		<td>{{Form::select('is_paid',ViewButler::htmlSelectAnyYesNo (),$isPaid)}}</td>
	</tr>
	<tr>
		<td>{{Form::label('stock_id')}}</td>
		<td>{{Form::select('stock_id',$stockSelectBox,$stockId)}}</td>
	</tr>
	<tr>
		<td>Sort</td>
		<td>{{Form::select('sort_by',[
			NULL=>'By',
			'id'=>'Invoice ID',
			'date'=>'Date',
		],$sortBy)}}{{Form::select('sort_order',ViewButler::htmlSelectSortOrder(),$sortOrder)}}</td>
	</tr>
	<tr>
		<td colspan="2">{{Form::submit('Submit')}}</td>
	</tr>
	{{Form::close()}}
</table>

<table border="1">
	<tr>
		<th>Invoice ID</th>
		<th>Date</th>
		<th>Vendor</th>
		<th>Printed Invoice Number</th>
		<th>Completely Paid</th>
		<th>Other Expense Amount</th>
		<th>Other Expense Details</th>
		<th>Stock</th>
	</tr>
	@foreach($buyingInvoiceRows as $buyingInvoiceRow)
	<tr>
		<td>{{HTML::link(URL::action('processes.purchases.edit', [$buyingInvoiceRow->id]), $buyingInvoiceRow->id)}}</td>
		<td>{{$buyingInvoiceRow->date}}</td>
		<td>{{$buyingInvoiceRow->vendor->name}}</td>
		<td>{{$buyingInvoiceRow->printed_invoice_num}}</td>
		<td>{{$buyingInvoiceRow->completely_paid}}</td>
		<td>{{$buyingInvoiceRow->other_expenses_amount}}</td>
		<td>{{$buyingInvoiceRow->other_expenses_details}}</td>
		<td>{{$buyingInvoiceRow->stock->name}}</td>
	</tr>
	@endforeach
</table>



@stop
