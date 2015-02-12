@extends('web._templates.template')

@section('body')

<div class="panel panel-default well">
	<div class="panel-heading">
		<h3 class="panel-title">"<b>{{$stockDetails->stock->name}}</b>" Stock Confirmation Details on "<b>{{$stockDetails->date_time}}</b>"&nbsp;</h3>
	</div>
	<div class="panel-body">
		<table class="table table-striped" style="width: 100%;">
			<tr>
				<th style="width: 230px;">Item</th>
				<th class="text-right">Buying Price</th>
				<th class="text-right">Selling Price</th>
				<th class="text-right">Good Item Quantity</th>
				<th class="text-right">Total Buying Value of Good Qnt</th>
				<th class="text-right">Total Selling Value of Good Qnt</th>
				<th class="text-right">Return Item Quantity</th>
				<th class="text-right">Total Buying Value of Return Qnt</th>
				<th class="text-right">Total Selling Value of Return Qnt</th>
			</tr>
			<tbody>
				@foreach($stockConfirmationDetails as $stockConfirmationDetail)
				<tr>
					<td style="width: 230px;">{{$stockConfirmationDetail->item->name}}</td>
					<td class="text-right">{{$stockConfirmationDetail->item->current_buying_price}}</td>
					<td class="text-right">{{$stockConfirmationDetail->item->current_selling_price}}</td>
					<td class="text-right">{{$stockConfirmationDetail->good_item_quantity}}</td>
					<td class="text-right">{{number_format($totalBuyingValueOfGoodQnt[$stockConfirmationDetail->item->id],2)}}</td>
					<td class="text-right">{{number_format($totalSellingValueOfGoodQnt[$stockConfirmationDetail->item->id],2)}}</td>
					<td class="text-right">{{$stockConfirmationDetail->return_item_quantity}}</td>
					<td class="text-right">{{number_format($totalBuyingValueOfReturnQnt[$stockConfirmationDetail->item->id],2)}}</td>
					<td class="text-right">{{number_format($totalSellingValueOfReturnQnt[$stockConfirmationDetail->item->id],2)}}</td>
				</tr>
				@endforeach
				<tr>
					<th style="width: 230px;">Totals</th>
					<th class="text-right" colspan="2"></th>
					<th class="text-right">{{$goodItemQuantityTotal}}</th>
					<th class="text-right">{{$goodItemQuantityBuyingValueTotal}}</th>
					<th class="text-right">{{$goodItemQuantitySellingValueTotal}}</th>
					<th class="text-right">{{$returnItemQuantityTotal}}</th>
					<th class="text-right">{{$returnItemQuantityBuyingValueTotal}}</th>
					<th class="text-right">{{$returnItemQuantitySellingValueTotal}}</th>
				</tr>
			</tbody>
		</table>
	</div>
</div>
@stop