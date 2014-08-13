@extends('web._templates.template')

@section('body')

<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Items</h3>
	</div>
	<div class="panel-body">

		<table class="table table-striped">
			<thead>
				<tr>
					<th>Item Code</th>
					<th>Name</th>
					<th>Reorder Level</th>
					<th>Buying Price</th>
					<th>Selling Price</th>
					<th>Buying Invoice Order</th>
					<th>Selling Invoice Order</th>
					<th>Is Active</th>
				</tr>
			</thead>
			<tbody>
				@foreach($items as $item)
				<tr>
					<td>{{$item->code}}</td>
					<td>{{HTML::link ( URL::action ( 'entities.items.edit' , [$item -> id ] ) , $item -> name )}}</td>
					<td>{{$item->reorder_level}}</td>
					<td>{{$item->current_buying_price}}</td>
					<td>{{$item->current_selling_price}}</td>
					<td>{{$item->buying_invoice_order}}</td>
					<td>{{$item->selling_invoice_order}}</td>
					<td>{{$item->is_active}}</td>
				</tr>
				@endforeach
			</tbody>
		</table>

	</div>
</div>
@stop