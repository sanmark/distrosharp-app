@extends('web._templates.template')

@section('body')
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Add Sale</h3>
	</div>
	<div class="panel-body">

		{{Form::open(['class'=>'form-horizontal', 'role'=>'form'])}}
		<br />
		<div class="form-group">
			{{Form::label('id', 'Guessed ID', array('class' => 'col-sm-2 control-label'))}}
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
		<div class="form-group">
			{{Form::label('route_id', null, array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-3">
				{{Form::select('route_id',$routes, null, array('tabindex'=>'1', 'class' => 'form-control','required'=>true))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('customer_id', null, array('class' => 'col-sm-2 control-label'))}}
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
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<div class="row">
					<div class="col-sm-8">
						<div class="row">
							<div class="col-sm-2"><b>Available</b></div>
							<div class="col-sm-2"><b>Price</b></div>
							<div class="col-sm-2"><b>Paid Q</b></div>
							<div class="col-sm-2"><b>Free Q</b></div>
							<div class="col-sm-2"><b>GR Price</b></div>
							<div class="col-sm-2"><b>GR Q</b></div>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="row">
							<div class="col-sm-4"><b>CR Price</b></div>
							<div class="col-sm-4"><b>CR Q</b></div>
							<div class="col-sm-4"><b>Line Total</b></div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php $tab = 3 ; ?>
		@foreach($items as $item)
		<div class="form-group">
			@if(false!=$item->getImageUrl())
			<div class="col-sm-2 text-right">
				<a href="#"  class="img-label">
					{{$item->name}}
					<div class="tool-tip slideIn bottom" >
						<img class="tool-tip-img" src="{{$item->getImageUrl()}}" >
					</div>
				</a>
			</div>
			@else
			{{Form::label(null, $item->name, array('class' => 'col-sm-2 control-label'))}}
			@endif

			<div class="col-sm-10">
				<div class="row">
					<div class="col-sm-8">
						<div class="row">
							<div class="col-sm-2">
								<p class="form-control text-right" disabled="true">{{$stockDetails[$item->id]['good_quantity']}}</p>
								{{Form::hidden('items['.$item->id.'][available_quantity]', $stockDetails[$item->id]['good_quantity'])}}
							</div>
							<div class="col-sm-2">
								{{Form::input('number','items['.$item->id.'][price]',$item->current_selling_price, array('class' => 'form-control text-right saleDetail', 'data-item-id'=>$item->id, 'step'=>0.01))}}
							</div>

							<div class="col-sm-2">
								<?php $tab ++ ; ?>
								{{Form::input('number','items['.$item->id.'][paid_quantity]',NULL, array('tabindex'=> $tab, 'class' => 'form-control text-right saleDetail', 'data-item-id'=>$item->id))}}
							</div>
							<?php $tab ++ ; ?>
							<div class="col-sm-2">
								{{Form::input('number','items['.$item->id.'][free_quantity]',NULL, array('tabindex'=> $tab, 'class' => 'form-control text-right saleDetail', 'data-item-id'=>$item->id))}}
							</div>
							<div class="col-sm-2">
								{{Form::input('number','items['.$item->id.'][good_return_price]',$item->current_selling_price, array('class' => 'form-control text-right saleDetail', 'data-item-id'=>$item->id, 'step'=>0.01))}}
							</div>
							<div class="col-sm-2">
								{{Form::input('number','items['.$item->id.'][good_return_quantity]',NULL, array('class' => 'form-control text-right saleDetail', 'data-item-id'=>$item->id))}}
							</div>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="row">
							<div class="col-sm-4">
								{{Form::input('number','items['.$item->id.'][company_return_price]',$item->current_selling_price, array('class' => 'form-control text-right saleDetail', 'data-item-id'=>$item->id, 'step'=>0.01))}}
							</div>
							<div class="col-sm-4">
								{{Form::input('number','items['.$item->id.'][company_return_quantity]',NULL, array('class' => 'form-control text-right saleDetail', 'data-item-id'=>$item->id))}}
							</div>
							<div class="col-sm-4">
								{{Form::text('items['.$item->id.'][line_total]',NULL, array('class' => 'form-control text-right lineTotal', 'readonly'=>TRUE))}}
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		@endforeach
		<div class="form-group">
			<div class="col-sm-4 col-sm-offset-8">
				<div class="row">
					<div class="col-sm-4 col-sm-offset-8">
						{{Form::text ( 'subTotal', NULL, ['class'=>'form-control text-right', 'readonly'=>TRUE])}}
					</div>
				</div>
			</div>
		</div>
		<div class="form-group">
			{{Form::label('discount', null, array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-2">
				<?php $tab ++ ?>
				{{Form::input('number','discount', null, array('tabindex'=> $tab, 'class' => 'form-control'), ['step'=>0.01])}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('is_completely_paid', null, array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-2">
				<?php $tab ++ ?>
				{{Form::checkbox('is_completely_paid',TRUE,null,array('tabindex'=> $tab, 'style'=>'margin-top:10px;'))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('cash_payment', 'Cash Payment', array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-2">
				<?php $tab ++ ?>
				{{Form::input('number', 'cash_payment', NULL, array('tabindex'=> $tab, 'class' => 'form-control'))}}
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-12">
				<div class="row">
					<div class="col-sm-2 text-right"><b>Cheque Payment</b></div>
					<div class="col-sm-10">
						<div class="row">
							<div class="col-sm-2">Amount</div>
							<div class="col-sm-2">Bank</div>
							<div class="col-sm-2">Cheque Number</div>
							<div class="col-sm-2">Issued Date</div>
							<div class="col-sm-2">Payable Date</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-offset-2 col-sm-10">
				<?php $tab ++ ; ?>
				<div class="row">
					<div class="col-sm-2">
						{{Form::input('number', 'cheque_payment', NULL, array('tabindex'=> $tab,'class' => 'form-control'))}}
					</div>
					<div class="col-sm-2">
						{{Form::select('cheque_payment_bank_id', $banksList, null, array('class' => 'form-control'))}}
					</div>
					<div class="col-sm-2">
						{{Form::text('cheque_payment_cheque_number', null, array('class' => 'form-control'))}}
					</div>
					<div class="col-sm-2">
						{{Form::input('date', 'cheque_payment_issued_date', null, array('class' => 'form-control'))}}
					</div>
					<div class="col-sm-2">
						{{Form::input('date', 'cheque_payment_payable_date', null, array('class' => 'form-control'))}}
					</div>
				</div>
			</div>
		</div>
		<div id="creditPayments" class="form-group">
		</div>
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<?php $tab ++ ?>
				{{Form::submit('Submit', array('tabindex'=> $tab, 'class' => 'btn btn-default pull-right'))}}
			</div>
		</div>

		{{Form::close()}}

	</div>
</div>
@stop

@section('file-footer')
<script>
	$(document).ready(function() {
	$('#route_id').change();
			setTimeout(function() {
			$("#customer_id").val({{Input::old('customer_id')}});
					$("#customer_id").change();
			}, 2000);
	});</script>

<script>
			$(document).on('change', '#route_id', function() {
	routeId = $('#route_id').val();
			$('#customer_id').find('option').remove();
			$('#customer_id').append(
			$('<option value=""></option>').
			text('Select')
			);
			$.post(
					"{{URL::action('entities.customers.ajax.forRouteId')}}",
			{
			_token: "{{csrf_token()}}",
					routeId: routeId
			},
					function(data) {
					$.each(data, function(index, customer) {
					$('#customer_id').append(
							$('<option></option>')
							.attr('value', customer.id)
							.text(customer.name)
							);
					});
					}
			);
	});</script>

<script>
			$(document).on('change keyup', '.saleDetail', function() {
	var itemId = $(this).attr('data-item-id');
			var price = $("input[name='items[" + itemId + "][price]']").val();
			var paid_quantity = $("input[name='items[" + itemId + "][paid_quantity]']").val();
			var good_return_price = $("input[name='items[" + itemId + "][good_return_price]']").val();
			var good_return_quantity = $("input[name='items[" + itemId + "][good_return_quantity]']").val();
			var company_return_price = $("input[name='items[" + itemId + "][company_return_price]']").val();
			var company_return_quantity = $("input[name='items[" + itemId + "][company_return_quantity]']").val();
			var lineTotal = (price * paid_quantity) - ((good_return_price * good_return_quantity) + (company_return_price * company_return_quantity));
			$("input[name='items[" + itemId + "][line_total]']").val(lineTotal);
			displaySubTotal();
	});
			function displaySubTotal() {
			var subTotal = null;
					$('.lineTotal').each(function() {
			var value = parseFloat($(this).val());
					if (!isNaN(value)) {
			subTotal += value;
			}
			});
					$("input[name='subTotal']").val(subTotal);
			}
</script>

<script>
	var oldCreditPayments = jQuery.parseJSON('{{json_encode(Input::old("credit_payments"))}}');
			function getOldCreditPaymentDetails(index, property){
			if (oldCreditPayments){
			if (oldCreditPayments[index]){
			if (oldCreditPayments[index][property]){
			return oldCreditPayments[index][property];
			}
			}
			} else if (property == "cheque_issued_date"){
			return "{{date('Y-m-d')}}";
			}
			return null;
			}
	$(document).on('change', '#customer_id', function(){
	$('#creditPayments').html('');
			var customerId = $(this).val();
			$.post(
					"{{URL::action('entities.customers.ajax.creditInvoices')}}",
			{
			_token:"{{csrf_token()}}",
					customerId:customerId
			},
					function(data){
					$.each(data, function(index, sellingInvoice){
					var newDiv = $('<div></div>');
							newDiv.append(
									$('<div></div>')
									.append(sellingInvoice.id)
									);
							newDiv.append(
									$('<div></div>')
									.append(sellingInvoice.date_time)
									);
							newDiv.append(
									$('<div></div>')
									.append(sellingInvoice.balance)
									);
							newDiv.append(
									$('<div></div>')
									.append(
											$('<label></label>')
											.attr('for', 'credit_payments[' + sellingInvoice.id + '][cash_amount]')
											.text('Cash Amount')
											)
									.append(
											$('<input/>')
											.attr('type', 'number')
											.attr('id', 'credit_payments[' + sellingInvoice.id + '][cash_amount]')
											.attr('name', 'credit_payments[' + sellingInvoice.id + '][cash_amount]')
											.attr('value', getOldCreditPaymentDetails(sellingInvoice.id, 'cash_amount'))
											)
									);
							newDiv.append(
									$('<div></div>')
									.append(
											$('<label></label>')
											.attr('for', 'credit_payments[' + sellingInvoice.id + '][cheque_amount]')
											.text('Cheque Amount')
											)
									.append(
											$('<input/>')
											.attr('type', 'number')
											.attr('id', 'credit_payments[' + sellingInvoice.id + '][cheque_amount]')
											.attr('name', 'credit_payments[' + sellingInvoice.id + '][cheque_amount]')
											.attr('value', getOldCreditPaymentDetails(sellingInvoice.id, 'cheque_amount'))
											)
									.append(
											$('{{Form::select(null, $banksList, null, array("class" => ""))}}')
											.attr('id', 'credit_payments[' + sellingInvoice.id + '][cheque_bank_id]')
											.attr('name', 'credit_payments[' + sellingInvoice.id + '][cheque_bank_id]')
											.val(getOldCreditPaymentDetails(sellingInvoice.id, 'cheque_bank_id'))
											)
									.append(
											$('<input/>')
											.attr('type', 'text')
											.attr('id', 'credit_payments[' + sellingInvoice.id + '][cheque_number]')
											.attr('name', 'credit_payments[' + sellingInvoice.id + '][cheque_number]')
											.attr('value', getOldCreditPaymentDetails(sellingInvoice.id, 'cheque_number'))
											)
									.append(
											$('<input/>')
											.attr('type', 'date')
											.attr('id', 'credit_payments[' + sellingInvoice.id + '][cheque_issued_date]')
											.attr('name', 'credit_payments[' + sellingInvoice.id + '][cheque_issued_date]')
											.attr('value', getOldCreditPaymentDetails(sellingInvoice.id, 'cheque_issued_date'))
											)
									.append(
											$('<input/>')
											.attr('type', 'date')
											.attr('id', 'credit_payments[' + sellingInvoice.id + '][cheque_payable_date]')
											.attr('name', 'credit_payments[' + sellingInvoice.id + '][cheque_payable_date]')
											.attr('value', getOldCreditPaymentDetails(sellingInvoice.id, 'cheque_payable_date'))
											)
									);
							$('#creditPayments').append(
							newDiv
							);
					});
					}
			);
	});
</script>
@stop