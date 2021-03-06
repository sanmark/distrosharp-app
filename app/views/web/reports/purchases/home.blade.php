@extends('web._templates.template')

@section('body')

<div class="panel panel-default well">
	<div class="panel-heading">
		<div class="panel-title">
			<span>View Purchases</span>
			{{HTML::link ( URL::action ( 'processes.purchases.add') ,'Add New Purchase',['class' => 'panel-title-btn btn btn-success btn-sm pull-right'] )}}
		</div>
	</div>
	<div class="panel-body">

		<div class="panel panel-default">
			<div class="panel-body">
				{{Form::open(['class'=>'form-inline', 'role'=>'form'])}}
				<div class="form-group bottom-space">
					{{Form::label('invoice_id', null, array('class' => 'control-label'))}}
					{{Form::text('id',$id, array('tabindex' => '1', 'class' => ''))}}
				</div>
				<div class="form-group bottom-space">
					{{Form::label('vendor_id', null, array('class' => 'control-label'))}}
					{{Form::select('vendor_id',$vendorSelectBox,$vendorId, array('tabindex' => '2', 'class' => ''))}}
				</div>
				<div class="form-group bottom-space">
					{{Form::label('from_date', null, array('class' => 'control-label'))}}
					{{Form::input('datetime-local','from_date_time',$fromDate, array('tabindex' => '3', 'class' => ''))}}
				</div>
				<div class="form-group bottom-space">
					{{Form::label('to_date', null, array('class' => 'control-label'))}}
					{{Form::input('datetime-local','to_date_time',$toDate, array('tabindex' => '4', 'class' => ''))}}
				</div>
				<div class="form-group bottom-space">
					{{Form::label('is_paid', null, array('class' => 'control-label'))}}
					{{Form::select('is_paid',ViewButler::htmlSelectAnyYesNo (),$isPaid, array('tabindex' => '5', 'class' => ''))}}
				</div>
				<div class="form-group bottom-space">
					{{Form::label('stock_id', null, array('class' => 'control-label'))}}
					{{Form::select('stock_id',$stockSelectBox,$stockId, array('tabindex' => '6', 'class' => 'f'))}}
				</div>
				<div class="form-group bottom-space">
					{{Form::label('sort', null, array('class' => 'control-label'))}}
					{{Form::select('sort_by',[NULL=>'By', 'id'=>'Invoice ID', 'date_time'=>'Date'],$sortBy, array('class' => '', 'tabindex' => '7', 'style' => 'width: 145px;'))}}&nbsp;&nbsp;{{Form::select('sort_order',ViewButler::htmlSelectSortOrder(),$sortOrder, array( 'tabindex' => '8','class' => '', 'style' => 'width: 145px;'))}}
				</div>
				<div class="form-group bottom-space">
					{{Form::submit('Submit', array('tabindex' => '9', 'class' => 'btn btn-primary pull-right'))}}
				</div>
				{{Form::close()}}
			</div>
		</div>

		<br/>
		@if(count($buyingInvoiceRows)==0)
		<br>
		<div class="no-records-message text-center">
			There are no records to display
		</div>
		<br>
		@else
		<table class="table table-striped" style="">
			<tr>
				<th>Invoice ID</th>
				<th>Date</th>
				<th>Vendor</th>
				<th>Printed Invoice Number</th>
				<th class="text-center">Completely Paid</th>
				<th class="text-right">Other Expense Amount</th>
				<th>Stock</th>
				<th class="text-right">Invoice Total</th>
			</tr>
			<tbody>
				@foreach($buyingInvoiceRows as $buyingInvoiceRow)
				<tr>
					<td>{{HTML::link(URL::action('processes.purchases.edit', [$buyingInvoiceRow->id]), $buyingInvoiceRow->id)}}</td>
					<td>{{$buyingInvoiceRow->date_time}}</td>
					<td>{{$buyingInvoiceRow->vendor->name}}</td>
					<td>{{$buyingInvoiceRow->printed_invoice_num}}</td>
					<td class="text-center">{{ViewButler::getYesNoFromBoolean ( $buyingInvoiceRow->completely_paid)}}</td>
					<td class="text-right">{{number_format($buyingInvoiceRow->other_expenses_amount,2)}}</td>
					<td>{{$buyingInvoiceRow->stock->name}}</td>
					<td>{{Form::text(null,number_format($lineTotalArray[$buyingInvoiceRow->id],2),['readonly'=>'readonly','class'=>'form-control text-right'])}}</td>
				</tr>
				@endforeach
			</tbody>
		</table>
		@endif
	</div>
</div>

@stop
