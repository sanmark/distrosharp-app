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
				{{Form::input('datetime-local','date_time', $currentDateTime, array('class' => 'form-control','required'=>true))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('route_id', null, array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-3">
				{{Form::select('route_id',$routes, null, array('class' => 'form-control'))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('customer_id', null, array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-3">
				{{Form::select('customer_id',$customers, null, array('class' => 'form-control'))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('printed_invoice_number', null, array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-3">
				{{Form::text('printed_invoice_number', null, array('class' => 'form-control'))}}
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-offset-3 col-sm-1"><b>Available</b></div>
			<div class="col-sm-1"><b>Price</b></div>
			<div class="col-sm-1"><b>Paid Q</b></div>
			<div class="col-sm-1"><b>Free Q</b></div>
			<div class="col-sm-1"><b>GR Price</b></div>
			<div class="col-sm-1"><b>GR Q</b></div>
			<div class="col-sm-1"><b>CR Price</b></div>
			<div class="col-sm-1"><b>CR Q</b></div>
			<div class="col-sm-1"><b>Line Total</b></div>
		</div>

		@foreach($items as $item)
		<div class="form-group">
			<div class="col-sm-3">
				@if(false!=$item->getImageUrl())
				<a href="#"  style="position:relative;">
					{{$item->name}}
					<div class="tool-tip slideIn bottom" >
						<img class="tool-tip-img" src="{{$item->getImageUrl()}}" >
					</div>
				</a>
				@else
				{{Form::label(null, $item->name)}}
				@endif
			</div>
			<div class="col-sm-1">
				<p class="form-control text-right" disabled="true">{{$stockDetails[$item->id]['good_quantity']}}</p>
				{{Form::hidden('items['.$item->id.'][available_quantity]', $stockDetails[$item->id]['good_quantity'])}}
			</div>
			<div class="col-sm-1">
				{{Form::input('number','items['.$item->id.'][price]',$item->current_selling_price, array('class' => 'form-control text-right saleDetail', 'data-item-id'=>$item->id, 'step'=>0.01))}}
			</div>

			<div class="col-sm-1">
				{{Form::input('number','items['.$item->id.'][paid_quantity]',NULL, array('class' => 'form-control text-right saleDetail', 'data-item-id'=>$item->id))}}
			</div>
			<div class="col-sm-1">
				{{Form::input('number','items['.$item->id.'][free_quantity]',NULL, array('class' => 'form-control text-right saleDetail', 'data-item-id'=>$item->id))}}
			</div>
			<div class="col-sm-1">
				{{Form::input('number','items['.$item->id.'][good_return_price]',$item->current_selling_price, array('class' => 'form-control text-right saleDetail', 'data-item-id'=>$item->id, 'step'=>0.01))}}
			</div>
			<div class="col-sm-1">
				{{Form::input('number','items['.$item->id.'][good_return_quantity]',NULL, array('class' => 'form-control text-right saleDetail', 'data-item-id'=>$item->id))}}
			</div>
			<div class="col-sm-1">
				{{Form::input('number','items['.$item->id.'][company_return_price]',$item->current_selling_price, array('class' => 'form-control text-right saleDetail', 'data-item-id'=>$item->id, 'step'=>0.01))}}
			</div>
			<div class="col-sm-1">
				{{Form::input('number','items['.$item->id.'][company_return_quantity]',NULL, array('class' => 'form-control text-right saleDetail', 'data-item-id'=>$item->id))}}
			</div>
			<div class="col-sm-1">
				{{Form::text('items['.$item->id.'][line_total]',NULL, array('class' => 'form-control text-right lineTotal', 'disabled'=>TRUE))}}
			</div>
		</div>
		@endforeach
		<div class="form-group">
			<div class="col-sm-1 col-sm-offset-11">
				{{Form::text ( 'subTotal', NULL, ['class'=>'form-control text-right', 'disabled'=>TRUE])}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('discount', null, array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-3">
				{{Form::input('number','discount', null, array('class' => 'form-control'), ['step'=>0.01])}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('is_completely_paid', null, array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-3">
				{{Form::checkbox('is_completely_paid',TRUE,null,array('style'=>'margin-top:10px;'))}}
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				{{Form::submit('Submit', array('class' => 'btn btn-default pull-right'))}}
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
		}, 2000);
	});
</script>

<script>
	$(document).on('change', '#route_id', function() {
		routeId = $('#route_id').val();
		$('#customer_id').find('option').remove();
		$('#customer_id').append(
				$('<option></option>').
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
	});
</script>

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
@stop