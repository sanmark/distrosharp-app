@extends('web._templates.template')

@section('body')

<div class="panel panel-default well">
	<div class="panel-heading">
		<h3 class="panel-title">"<b>{{$stockDetails->stock->name}}</b>" Stock Confirmation Details on "<b>{{$stockDetails->date_time}}</b>"&nbsp;</h3>
	</div>
	<div class="panel-body">
		<table class="table table-striped" style="width: 50%;">
			<tr>
				<th>Item</th>
				<th class="text-right">Good Item Quantity</th>
				<th class="text-right">Return Item Quantity</th>
			</tr>
			<tbody>
				@foreach($stockConfirmationDetails as $stockConfirmationDetail)
				<tr>
					<td>{{$stockConfirmationDetail->item->name}}</td>
					<td class="text-right">{{$stockConfirmationDetail->good_item_quantity}}</td>
					<td class="text-right">{{$stockConfirmationDetail->return_item_quantity}}</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</div>
@stop