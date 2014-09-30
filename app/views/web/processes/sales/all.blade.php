@extends('web._templates.template')

@section('body')

<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">View Sales</h3>
	</div>
	<div class="panel-body">

		<div class="panel panel-default">
			<div class="panel-body">
				{{Form::open(['class'=>'form-inline', 'role'=>'form'])}}
				<div class="form-group bottom-space">
					{{Form::label('id', 'System Invoice Number', array('class' => 'control-label'))}}
					{{Form::text('id', $id, array('tabindex' => '1', 'class' => 'form-control'))}}
				</div>
				<div class="form-group bottom-space">
					{{Form::label('printed_invoice_number', null, array('class' => 'control-label'))}}
					{{Form::text('printed_invoice_number', $printedInvoiceNumber, array('tabindex' => '2', 'class' => 'form-control'))}}
				</div>
				<div class="form-group bottom-space">
					{{Form::label('date_time_from', null, array('class' => 'control-label'))}}
					{{Form::input('datetime-local','date_time_from', $dateTimeFrom, array('tabindex' => '3', 'class' => 'form-control'))}}
				</div>
				<div class="form-group bottom-space">
					{{Form::label('date_time_to', null, array('class' => 'control-label'))}}
					{{Form::input('datetime-local','date_time_to', $dateTimeTo, array('tabindex' => '4', 'class' => 'form-control'))}}
				</div>
				<div class="form-group bottom-space">
					{{Form::label('customer_id', null, array('class' => 'control-label'))}}
					{{Form::select('customer_id', $customerSelectBox, $customerId, array('tabindex' => '5', 'class' => 'form-control'))}}
				</div>
				<div class="form-group bottom-space">
					{{Form::label('rep_id', null, array('class' => 'control-label'))}}
					{{Form::select('rep_id', $repSelectBox, $repId, array('tabindex' => '6', 'class' => 'form-control'))}}
				</div>
				<div class="form-group bottom-space">
					{{Form::label('is_completely_paid', null, array('class' => 'control-label'))}}
					{{Form::select('is_completely_paid', $isActiveSelectBox, $isCompletelyPaid, array('tabindex' => '7', 'class' => 'form-control'))}}
				</div>
				<div class="form-group bottom-space">
					{{Form::submit('Submit', array('tabindex' => '8', 'class' => 'btn btn-default pull-right'))}}
				</div>
				{{Form::close()}}
			</div>
		</div>

		<br/>
		@if(count($sellingInvoices)==0)
		<br>
		<div class="no-records-message text-center">
			There are no records to display
		</div>
		<br>
		@else
		<table class="table table-striped" style="">
			<tr>
				<th>System Invoice Number</th>
				<th>Printed Invoice Number</th>
				<th>Date/Time</th>
				<th>Customer</th>
				<th>Rep</th>
				<th class="text-center">Is Completely Paid</th>
				<th class="text-right">Invoice Total</th>
			</tr>
			<tbody>
				@foreach($sellingInvoices as $sellingInvoice)
				<tr>
					<td>{{HTML::link(URL::action('processes.sales.edit', [$sellingInvoice->id]),$sellingInvoice->id)}}</td>
					<td>{{$sellingInvoice->printed_invoice_number}}</td>
					<td>{{$sellingInvoice->date_time}}</td>
					<td>{{$sellingInvoice->customer->name}}</td>
					<td>{{$sellingInvoice->rep->username}}</td>
					<td class="text-center">{{ViewButler::getYesNoFromBoolean($sellingInvoice->is_completely_paid)}}</td>
					<td class="text-right">{{number_format($sellingInvoice->getInvoiceTotal(), 2)}}</td>
				</tr>
				@endforeach
			</tbody>
		</table>
		@endif
	</div>
</div>

@stop