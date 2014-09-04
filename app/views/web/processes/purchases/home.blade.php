@extends('web._templates.template')

@section('body')

<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">View Purchases</h3>
	</div>
	<div class="panel-body">

		{{Form::open(['class'=>'form-horizontal', 'role'=>'form'])}}
		<br />
		<div class="form-group">
			{{Form::label('invoice_id', null, array('class' => 'col-sm-1 control-label'))}}
			<div class="col-sm-3">
				{{Form::text('id',$id, array('class' => 'form-control'))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('vendor_id', null, array('class' => 'col-sm-1 control-label'))}}
			<div class="col-sm-3">
				{{Form::select('vendor_id',$vendorSelectBox,$vendorId, array('class' => 'form-control'))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('from_date', null, array('class' => 'col-sm-1 control-label'))}}
			<div class="col-sm-3">
				{{Form::input('datetime-local','from_date_time',$fromDate, array('class' => 'form-control'))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('to_date', null, array('class' => 'col-sm-1 control-label'))}}
			<div class="col-sm-3">
				{{Form::input('datetime-local','to_date_time',$toDate, array('class' => 'form-control'))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('is_paid', null, array('class' => 'col-sm-1 control-label'))}}
			<div class="col-sm-3">
				{{Form::select('is_paid',ViewButler::htmlSelectAnyYesNo (),$isPaid, array('class' => 'form-control'))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('stock_id', null, array('class' => 'col-sm-1 control-label'))}}
			<div class="col-sm-3">
				{{Form::select('stock_id',$stockSelectBox,$stockId, array('class' => 'form-control'))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('sort', null, array('class' => 'col-sm-1 control-label'))}}
			<div class="col-sm-3 form-inline">
				{{Form::select('sort_by',[NULL=>'By', 'id'=>'Invoice ID', 'date_time'=>'Date'],$sortBy, array('class' => 'form-control', 'style' => 'width: 145px;'))}}&nbsp;&nbsp;{{Form::select('sort_order',ViewButler::htmlSelectSortOrder(),$sortOrder, array('class' => 'form-control', 'style' => 'width: 145px;'))}}
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-offset-1 col-sm-3">
				{{Form::submit('Submit', array('class' => 'btn btn-default pull-right'))}}
			</div>
		</div>
		{{Form::close()}}

		<br/>

		<table class="table table-striped">
			<tr>
				<th>Invoice ID</th>
				<th>Date</th>
				<th>Vendor</th>
				<th>Printed Invoice Number</th>
				<th>Completely Paid</th>
				<th>Other Expense Amount</th>
				<th>Other Expense Total</th>
				<th>Stock</th>
			</tr>
			<tbody>
				@foreach($buyingInvoiceRows as $buyingInvoiceRow)
				<tr>
					<td>{{HTML::link(URL::action('processes.purchases.edit', [$buyingInvoiceRow->id]), $buyingInvoiceRow->id)}}</td>
					<td>{{$buyingInvoiceRow->date_time}}</td>
					<td>{{$buyingInvoiceRow->vendor->name}}</td>
					<td>{{$buyingInvoiceRow->printed_invoice_num}}</td>
					<td>{{ViewButler::getYesNoFromBoolean ( $buyingInvoiceRow->completely_paid)}}</td>
					<td>{{$buyingInvoiceRow->other_expenses_amount}}</td>
					<td>{{$buyingInvoiceRow->other_expenses_details}}</td>
					<td>{{$buyingInvoiceRow->stock->name}}</td>
				</tr>
				@endforeach
			</tbody>
		</table>

	</div>
</div>

@stop
