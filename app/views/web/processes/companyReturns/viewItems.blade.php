@extends('web._templates.template')

@section('body')
<div class="panel panel-default well">
	<div class="panel-heading">
		<div class="panel-title">
			<span>View Company Return Items</span>
			{{HTML::link ( URL::action ( 'processes.companyReturns.add') ,'Add New company Return',['class' => 'panel-title-btn btn btn-success btn-sm pull-right'] )}}
		</div>
	</div>
	<div class="panel-body">
		<p>Printed Return Number: <b>{{$returnDetails->printed_return_number}}</b></p><p>Date: <b>{{$returnDetails->date_time}}.</b></p>
	<br/>
		<table class="table table-striped" style="width: 40%;">
			<thead>
				<tr>
					<th>Item Name</th>
					<th>Buying Price</th>
					<th>Quantity</th>
				</tr>
			</thead>
			<tbody>
				@foreach($companyReturnItems as $companyReturnItem)
				<tr>
					<td>{{$companyReturnItem -> item->name}}</td>
					<td>{{$companyReturnItem->buying_price}}</td>
					<td>{{$companyReturnItem->quantity}}</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</div>
@stop
