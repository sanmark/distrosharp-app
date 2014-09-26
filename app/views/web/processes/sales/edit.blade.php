@extends('web._templates.template')

@section('body')
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Edit Sale</h3>
	</div>
	<div class="panel-body">
		{{Form::open(['class'=>'form-horizontal', 'role'=>'form'])}}
		<br />
		<div class="form-group">
			{{Form::label('ID',null, array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-3">
				{{Form::label('ID', $sellingInvoice->id, array('class' => 'form-control'))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('date_time',null, array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-3">
				{{Form::input('text', 'date_time', $sellingInvoice->date_time,array('class' => 'form-control'))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('customer_id',null,array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-3">
				{{Form::select('customer_id', $customerDropDown, $sellingInvoice->customer_id,array('class' => 'form-control'))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('rep',null,array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-3">
				{{Form::label('rep',$sellingInvoice->rep->username,array('class' => 'form-control'))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('printed_invoice_number',null,array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-3">
				{{Form::text('printed_invoice_number', $sellingInvoice->printed_invoice_number,array('class' => 'form-control'))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('discount',null,array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-3">
				{{Form::input('number', 'discount', $sellingInvoice->discount,array('class' => 'form-control'))}}
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<div class="row">
					<div class="col-sm-7">
						<div class="row">
							<div class="col-sm-3"><b>Price</b></div>
							<div class="col-sm-3"><b>Paid Q</b></div>
							<div class="col-sm-3"><b>Free Q</b></div>
							<div class="col-sm-3"><b>GR Price</b></div>
						</div>
					</div>
					<div class="col-sm-5">
						<div class="row">							
							<div class="col-sm-4"><b>GR Q</b></div>
							<div class="col-sm-4"><b>CR Price</b></div>
							<div class="col-sm-4"><b>CR Q</b></div>
						</div>
					</div>
				</div>
			</div>
		</div>
		@foreach($items as $item)
		<div class="form-group">
			<?php
			$sellingItem = $sellingInvoice -> sellingItems -> filter ( function($sellingItem) use($item)
			{
				if ( $sellingItem -> item_id == $item -> id )
				{
					return TRUE ;
				}
			} ) -> first () ;
			?>
			{{Form::label(null, $item->name, array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-10">
				<div class="row">
					<div class="col-sm-7">
						<div class="row">
							<div class="col-sm-3">
								{{Form::input('number', 'items['.$item->id.'][price]', ObjectHelper::nullIfNonObject($sellingItem, 'price'),['class' => 'form-control text-right saleDetail'])}}
							</div>
							<div class="col-sm-3">
								{{Form::input('number', 'items['.$item->id.'][paid_quantity]', ObjectHelper::nullIfNonObject($sellingItem, 'paid_quantity'),['class' => 'form-control text-right saleDetail'])}}
							</div>
							<div class="col-sm-3">
								{{Form::input('number', 'items['.$item->id.'][free_quantity]', ObjectHelper::nullIfNonObject($sellingItem, 'free_quantity'),['class' => 'form-control text-right saleDetail'])}}
							</div>
							<div class="col-sm-3">
								{{Form::input('number', 'items['.$item->id.'][good_return_price]', ObjectHelper::nullIfNonObject($sellingItem, 'good_return_price'),['class' => 'form-control text-right saleDetail'])}}
							</div>							
						</div>
					</div>
					<div class="col-sm-5">
						<div class="row">
							<div class="col-sm-4">
								{{Form::input('number', 'items['.$item->id.'][good_return_quantity]', ObjectHelper::nullIfNonObject($sellingItem, 'good_return_quantity'),['class' => 'form-control text-right saleDetail'])}}
							</div>
							<div class="col-sm-4">
								{{Form::input('number', 'items['.$item->id.'][company_return_price]', ObjectHelper::nullIfNonObject($sellingItem, 'company_return_price'),['class' => 'form-control text-right saleDetail'])}}
							</div>
							<div class="col-sm-4">{{Form::input('number', 'items['.$item->id.'][company_return_quantity]', ObjectHelper::nullIfNonObject($sellingItem, 'company_return_quantity'),['class' => 'form-control text-right saleDetail'])}}</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		@endforeach
		<br/>
		<div class="form-group">
			<div class="col-sm-2 control-label" style="font-weight: bold;">Payments</div>
			<div class="col-sm-10">
				<td>
					<table class="table table-bordered" style="width: 50%">
						<tr>
							<th>ID</th>
							<th>From</th>
							<th>Date</th>
							<th class="text-right">Amount</th>
							<th>&nbsp;</th>
						</tr>
						@foreach($sellingInvoice->financeTransfers as $financeTransfer)
						<tr>
							<td>{{$financeTransfer->id}}</td>
							<td>{{HTML::link(URL::action('finances.transfers.view', [$financeTransfer->from_id]),$financeTransfer->fromAccount->name)}}</td>
							<td>{{$financeTransfer->date_time}}</td>
							<td class="text-right">{{number_format($financeTransfer->amount,2)}}</td>
							<td class="text-center">{{HTML::link(URL::action('finances.transfers.edit', [$financeTransfer->id]), 'Edit...')}}</td>
						</tr>
						@endforeach
					</table>
			</div>
		</div>
		<div class="form-group">
			{{Form::label('is_completely_paid', null, array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-10">
				{{Form::checkbox('is_completely_paid', TRUE, $sellingInvoice->is_completely_paid,array('style'=>'margin-top:10px;'))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('new_cash_payment', null, array('class'=>'col-sm-2 control-label'))}}
			<div class="col-sm-3">
				{{Form::input('number', 'new_cash_payment', NULL, array('class' => 'form-control'))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('new_cheque_payment', null, array('class'=>'col-sm-2 control-label'))}}
			<div class="col-sm-3">
				{{Form::input('number', 'new_cheque_payment', NULL, array('class' => 'form-control'))}}
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				{{Form::submit('Submit', array('class' => 'btn btn-default pull-right'))}}
				{{ link_to(URL::previous(), 'Back', ['class' => 'btn btn-default pull-right back-btn-margin']) }}
			</div>
		</div>
		{{Form::close()}}
	</div>
</div>
@stop