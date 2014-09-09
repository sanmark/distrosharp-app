@extends('web._templates.template')

@section('body')

<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">View Sales</h3>
	</div>
	<div class="panel-body">

		{{Form::open(['class'=>'form-horizontal', 'role'=>'form'])}}
		<br />
		<div class="form-group">
			{{Form::label('id', null, array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-3">
				{{Form::text('id', $id, array('class' => 'form-control'))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('printed_invoice_number', null, array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-3">
				{{Form::text('printed_invoice_number', $printedInvoiceNumber, array('class' => 'form-control'))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('date_time_from', null, array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-3">
				{{Form::input('datetime-local','date_time_from', $dateTimeFrom, array('class' => 'form-control'))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('date_time_to', null, array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-3">
				{{Form::input('datetime-local','date_time_to', $dateTimeTo, array('class' => 'form-control'))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('customer_id', null, array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-3">
				{{Form::select('customer_id', $customerSelectBox, $customerId, array('class' => 'form-control'))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('rep_id', null, array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-3">
				{{Form::select('rep_id', $repSelectBox, $repId, array('class' => 'form-control'))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('is_completely_paid', null, array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-3">
				{{Form::select('is_completely_paid', $isActiveSelectBox, $isCompletelyPaid, array('class' => 'form-control'))}}
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-3">
				{{Form::submit('Submit', array('class' => 'btn btn-default pull-right'))}}
			</div>
		</div>
		{{Form::close()}}

		<br/>

		<table class="table table-striped" style="width: 80%;">
			<tr>
				<th>ID</th>
				<th>Printed Invoice Number</th>
				<th>Date/Time</th>
				<th>Customer</th>
				<th>Rep</th>
				<th>Is Completely Paid</th>
			</tr>
			<tbody>
				@foreach($sellingInvoices as $sellingInvoice)
				<tr>
					<td>{{HTML::link(URL::action('processes.sales.edit', [$sellingInvoice->id]),$sellingInvoice->id)}}</td>
					<td>{{$sellingInvoice->printed_invoice_number}}</td>
					<td>{{$sellingInvoice->date_time}}</td>
					<td>{{$sellingInvoice->customer->name}}</td>
					<td>{{$sellingInvoice->rep->username}}</td>
					<td>{{ViewButler::getYesNoFromBoolean($sellingInvoice->is_completely_paid)}}</td>
				</tr>
				@endforeach
			</tbody>
		</table>

	</div>
</div>

@stop