@extends('web._templates.template')

@section('body') 
<div id="div-error-message"></div>
<div class="panel panel-default well"> 
	<div class="panel-heading">
		<h3 class="panel-title">Change Item Order</h3>
	</div>

	<div class="panel-body">
		{{Form::open(array( 'id'=>'formItemOrder'))}}
		<table  class="table table-striped">
			<tr>
				<th>Item Name</th>
				<th>Buying Invoice Order</th>
				<th>Selling Invoice Order</th> 
			</tr>
			@foreach($items as $key => $item)
			<tr>
				<td>{{$item->name}}</td>
				<td>{{Form::input('number','buyingOrder[]', $item->buying_invoice_order, array('class' => 'form-control','id' => 'buyingOrderId_'.$key))}}</td>
				<td>
					{{Form::input('number','sellingOrder[]',  $item->selling_invoice_order, array('class' => 'form-control','id' => 'sellingOrderId_'.$key))}} 
					{{Form::hidden('itemId[]', $item->id, array('tabindex' => '2', 'class' => 'form-control'))}}
				</td>
			</tr>
			@endforeach
			<tr>  
				<td colspan="3">{{Form::submit('Submit',array('class' => 'btn btn-primary pull-right'))}}</td>
			</tr>
		</table>
		{{Form::close()}} 
	</div>
</div>

@stop



@section('file-footer')
<script src="/js/entities/items/order.js"></script>
<script>
validateDublicate();
</script>
@stop

