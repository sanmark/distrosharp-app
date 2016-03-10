@extends('web._templates.template')

@section('body')
<div class="panel panel-default well">
	<div class="panel-heading">
		<h3 class="panel-title">Add Credit Invoices</h3>
	</div>
	<div class="panel-body">
		{{Form::open(['class'=>'form-horizontal', 'role'=>'form' ])}}
		<br />
		<div class="form-group">
			{{Form::label('id', 'System Inv. Id', array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-3">
				{{Form::label('id', $guessedInvoiceId, array('class' => 'form-control','required'=>true, 'disabled'=>TRUE))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('date_time', null, array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-3">
				{{Form::input('datetime-local','date_time', $currentDateTime, array('class' => 'form-control','required'=>true,'step'=>'1'))}}
			</div>
		</div>
		<div class="form-group" style="height: 39px;">
			{{Form::label('rep',null,array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-3">
				{{Form::label('rep',$rep->username,array('class' => 'form-control'))}}
				{{Form::hidden('rep_id', $rep->id, array('id' => 'rep_id'))}}
			</div>
			<div class="col-sm-1">
				{{HTML::link ( URL::action ( 'processes.sales.setRep' ) , 'Change Rep...' , ['class' => 'btn btn-success btn-sm', 'style'=>'position: relative; left: -25px; top: -15px;' ] )}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('route_id', 'Route', array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-3">
				{{Form::select('route_id',$routes, Session::get('oldRouteId'), array('tabindex'=>'1', 'class' => 'form-control','required'=>true))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('customer_id', 'Customer', array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-3">
				{{Form::select('customer_id',$customers, null,array('tabindex'=>'2', 'class' => 'form-control','required'=>true))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('printed_invoice_number', null, array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-3">
				{{Form::text('printed_invoice_number', null, array('tabindex'=>'3', 'class' => 'form-control','required'=>true))}}
			</div>
		</div>

		<?php  $tab = 3 ; ?>


		
		<div id="oldCreditPayments" class="">

	
		</div>

		<div id="creditPayments" class="">
		</div>

		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<?php // $tab ++ ?>
				{{Form::submit('Submit', array('tabindex'=> $tab, 'class' => 'btn btn-primary pull-right'))}}
			</div>
		</div> 
		{{Form::close()}}

	</div>
</div>

{{Form::input('hidden', 'current_edit_sales_id', NULL, ['id'=>'current_edit_sales_id'])}}
{{Form::input('hidden', 'current_edit_return_id', NULL, ['id'=>'current_edit_return_id'])}}


@stop

@section('file-footer')
<script src="/js/processes/sales/addOldInvoice.js"></script>
<script src="/js/processes/sales/add-return.js"></script>
<script src="/js/processes/sales/add-sales.js"></script>
<script>
loadPreviousValuesOnUnsuccessfulRedirectBack("{{Input::old('customer_id')}}");
populateCustomersForRoute("{{csrf_token()}}");
loadOldCreditInvoicesForCustomer("{{csrf_token()}}",jQuery.parseJSON('{{json_encode(Input::old("credit_payments"))}}'),"{{date('Y-m-d')}}",'{{Form::select(null,$banksList,null,array("class" => ""))}}');
displayIsCompletelyPaid("{{csrf_token()}}");
validateChequeDetails();
addReturnRow();
selectReturnItem("{{csrf_token()}}");
autoloadItemForReturn("{{csrf_token()}}");
editReturn();
deleteReturn();

//addSalesRow();
autoloadItemForSales("{{csrf_token()}}");
selectSalesItem("{{csrf_token()}}");
editSales();
deleteSales();



setMethodToEnter();
</script>
@stop