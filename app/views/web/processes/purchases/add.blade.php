@extends('web._templates.template')
@section('body')
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Add Purchase</h3>
	</div>
	<div class="panel-body">

		{{Form::open(['class'=>'form-horizontal', 'role'=>'form'])}}
		<br />
		<div class="form-group">
			{{Form::label('date', null, array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-3">
				{{Form::input ( 'datetime-local','date_time', $currentDateTime, array('class' => 'form-control','required'=>true))}} 
			</div>
		</div>
		<div class="form-group">
			{{Form::label('vendor', null, array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-3">
				{{Form::select('vendor_id',\Models\Vendor::getArrayForHtmlSelect('id','name',[''=>'Select Vendor']), null, array('class' => 'form-control','required'=>true))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label(null, 'Print Invoice Number', array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-3">
				{{Form::text('printed_invoice_num',null, array('class' => 'form-control','required'=>true))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label(null, 'Other Expense Amount', array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-3"> 
				{{Form::input('number','other_expense_amount', null, array('class' => 'form-control','step' => 'any'))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label(null, 'Other Expense Details', array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-3">
				{{Form::text('other_expenses_details',null, array('class' => 'form-control'))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('Stock', null, array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-3">
				{{Form::select('stock_id',$stocks, NULL, array('class' => 'form-control','required'=>true))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label(null, 'Completely Paid', array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-3">
				{{Form::checkbox('is_paid',TRUE,null,array('style'=>'margin-top:10px;'))}}
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<div class="row">
					<div class="col-sm-2"><b>Price</b></div>
					<div class="col-sm-2"><b>Quantity</b></div>
					<div class="col-sm-2"><b>Free Quantity</b></div>
					<div class="col-sm-2"><b>Expire Date</b></div>
					<div class="col-sm-2"><b>Batch Number</b></div>
					<div class="col-sm-2"><b>Line Total</b></div>
				</div>			
			</div>			
		</div>
		<div id="add_purchase_items">
			@foreach($itemRows as $itemRow)
			<div class="form-group">
				<div class="col-sm-2">
					{{Form::hidden('item_id_'.$itemRow->id,$itemRow->id)}}
					@if(false!=$itemRow->getImageUrl())
					<a href="#"  style="position:relative;">
						{{$itemRow->name}}
						<div class="tool-tip slideIn bottom" >
							<img class="tool-tip-img" src="{{$itemRow->getImageUrl()}}" >
						</div>
					</a>
					@else
					{{Form::label(null, $itemRow->name)}}
					@endif
				</div>
				<div class="col-sm-10">
					<div class="row">
						<div class="col-sm-2">
							{{Form::input('number','buying_price_'.$itemRow->id,$itemRow->current_buying_price, array('class' => 'form-control', 'step'=>'any','onkeyup'=>'changeOnPrice(this.id,this.value)','id'=>$itemRow->id))}}
						</div>
						<div class="col-sm-2">
							{{Form::input('number','quantity_'.$itemRow->id, null, array('class' => 'form-control', 'step'=>'any','onkeyup'=>'changeOnQuantity(this.id,this.value)','id'=>$itemRow->id))}}
						</div>
						<div class="col-sm-2">
							{{Form::input('number','free_quantity_'.$itemRow->id, null, array('class' => 'form-control', 'step'=>'any'))}}
						</div>
						<div class="col-sm-2">
							{{Form::input('date','exp_date_'.$itemRow->id, null, array('class' => 'form-control'))}}
						</div>
						<div class="col-sm-2">
							{{Form::text('batch_number_'.$itemRow->id, null, array('class' => 'form-control'))}} 
						</div>
						<div class="col-sm-2">
							{{Form::text('line_total_'.$itemRow->id, null, array('class' => 'form-control', 'step'=>'any','readonly'=>'readonly'))}}
						</div>
					</div>
				</div>
			</div>
			@endforeach
		</div>
		<div class="form-group">
			{{Form::label('Cash Payment', null, array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-2">
				{{Form::input('number', 'cash_payment', NULL, array('class' => 'form-control'))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('Cheque Payment', null, array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-2">
				{{Form::input('number', 'cheque_payment', NULL, array('class' => 'form-control'))}}
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<div class="row">
					<div class="col-sm-2"></div>
					<div class="col-sm-2"></div>
					<div class="col-sm-2"></div>
					<div class="col-sm-2"></div>
					<div class="col-sm-2"></div>
					<div class="col-sm-2">{{Form::text('full_total',null, array('class' => 'form-control', 'step'=>'any','readonly'=>'readonly','style'=>'font-weight:bolder;'))}}</div>
				</div>			
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
<script type="text/javascript">
	function changeOnQuantity(name, quantity)
	{
		var price = document.getElementsByName('buying_price_' + name)[0].value;
		var lineTotal = price * quantity;
		document.getElementsByName('line_total_' + name)[0].value = lineTotal;
		var i;
		var a = [<?php echo '"' . implode ( '","' , $itemRowsForTotal ) . '"' ?>];
		var finalTotal = 0;
		for (i = 0; i < a.length; i++) {
			var fullTotalCell = document.getElementsByName('line_total_' + a[i])[0].value;
			var finalTotal = Number(finalTotal) + Number(fullTotalCell);
		}
		document.getElementsByName('full_total')[0].value = finalTotal;
	}
	function changeOnPrice(name, price)
	{
		var quantity = document.getElementsByName('quantity_' + name)[0].value;
		var lineTotal = price * quantity;
		document.getElementsByName('line_total_' + name)[0].value = lineTotal;
		var i;
		var a = [<?php echo '"' . implode ( '","' , $itemRowsForTotal ) . '"' ?>];
		var finalTotal = 0;
		for (i = 0; i < a.length; i++) {
			var fullTotalCell = document.getElementsByName('line_total_' + a[i])[0].value;
			var finalTotal = Number(finalTotal) + Number(fullTotalCell);
		}
		document.getElementsByName('full_total')[0].value = finalTotal;
	}
</script>
@stop