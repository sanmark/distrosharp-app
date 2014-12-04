@extends('web._templates.template')

@section('body')
<div class="panel panel-default well">
	<div class="panel-heading">
		<div class="panel-title">
			<span>View Company Returns</span>
			{{HTML::link ( URL::action ( 'processes.companyReturns.add') ,'Add New company Return',['class' => 'panel-title-btn btn btn-success btn-sm pull-right'] )}}
		</div>
	</div>
	<div class="panel-body">

		<div class="panel panel-default" style="">
			<div class="panel-body">

				{{Form::open(['class'=>'form-inline', 'role'=>'form'])}}
				<div class="form-group inline-form">
					{{Form::label('vendor_id','Vendor', array('class' => 'control-label'))}}
					{{Form::select('vendor_id',$vendorList,$selectedVendor, array('tabindex' => '3', 'class' => ''))}}
				</div>
				<div class="form-group inline-form">
					{{Form::label('from_stock_id','From Stock', array('class' => 'control-label'))}}
					{{Form::select('from_stock_id',$stockList,$selectedStock, array('tabindex' => '4', 'class' => ''))}}
				</div>
				<div class="form-group inline-form">
					{{Form::label('from_date','From Date', array('class' => 'control-label'))}}
					{{Form::input('datetime-local','from_date',$fromDate,array('tabindex' => '4', 'class' => ''))}}
				</div>
				<div class="form-group inline-form">
					{{Form::label('to_date','To Date', array('class' => 'control-label'))}}
					{{Form::input('datetime-local','to_date',$toDate,array('tabindex' => '4', 'class' => ''))}}
				</div>
				<div class="form-group inline-form">
					{{Form::submit('Submit', array('tabindex' => '6', 'class' => 'btn btn-primary pull-right'))}}
				</div>
				{{Form::close()}}
			</div>
		</div>
		@if(count($companyReturns)==0)
		<br>
		<div class="no-records-message text-center">
			There are no records to display
		</div>
		<br>
		@else
		<table class="table table-striped" style="width: 60%;">
			<thead>
				<tr>
					<th>ID</th>
					<th>Return Number</th>
					<th>Vendor</th>
					<th>From Stock</th>
					<th>Date</th>
				</tr>
			</thead>
			<tbody>
				@foreach($companyReturns as $companyReturn)
				<tr>
					<td>{{HTML::link ( URL::action ( 'processes.companyReturns.viewItems' , [$companyReturn -> id ] ) , $companyReturn -> id )}}</td>
					<td>{{$companyReturn->printed_return_number}}</td>
					<td>{{$companyReturn->vendor->name}}</td>
					<td>{{$companyReturn->stock->name}}</td>
					<td>{{$companyReturn->date_time}}</td>
				</tr>
				@endforeach
			</tbody>
		</table>
		@endif
	</div>
</div>
@stop
