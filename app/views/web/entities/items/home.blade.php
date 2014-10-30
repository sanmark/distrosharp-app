@extends('web._templates.template')

@section('body')

<div class="panel panel-default well">
	<div class="panel-heading">
		<div class="panel-title">
			<span>Items</span>
			{{HTML::link ( URL::action ( 'entities.items.add') ,'Add New Item',['class' => 'panel-title-btn btn btn-success btn-sm pull-right'] )}}
		</div>
	</div>
	<div class="panel-body">

		<div class="panel panel-default" style="">
			<div class="panel-body">

				{{Form::open(['class'=>'form-inline', 'role'=>'form'])}}

				<div class="form-group inline-form">
					{{Form::label('code', null, array('class' => 'control-label'))}}
					{{Form::text('code',$code, array('tabindex' => '1', 'class' => ''))}}
				</div>
				<div class="form-group inline-form">
					{{Form::label('name', null, array('class' => 'control-label'))}}
					{{Form::text('name',$name, array('tabindex' => '2', 'class' => ''))}}
				</div>
				<div class="form-group inline-form">
					{{Form::label('is_active', null, array('class' => 'control-label'))}}
					{{Form::select('is_active',ViewButler::htmlSelectAnyYesNo (),$isActive, array('tabindex' => '3', 'class' => ''))}}
				</div>
				<div class="form-group inline-form">
					{{Form::label('sort by', null, array('class' => 'control-label'))}}
					{{Form::select('sort_by',ViewButler::htmlSelectSortItems () ,$sortBy, array('tabindex' => '4', 'class' => ''))}}
					&nbsp;
					{{Form::select('sort_order',ViewButler::htmlSelectSortOrder(),$sortOrder, array('tabindex' => '5', 'class' => ''))}}
				</div>
				<div class="form-group inline-form">
					{{Form::submit('Submit', array('tabindex' => '6', 'class' => 'btn btn-primary pull-right'))}}
				</div>
				{{Form::close()}}
			</div>
		</div>
		@if(count($items)==0)
		<br>
		<div class="no-records-message text-center">
			There are no records to display
		</div>
		<br>
		@else
		<table class="table table-striped">
			<thead>
				<tr>
					<th>Item Code</th>
					<th>Name</th>
					<th class="text-right">Reorder Level</th>
					<th class="text-right">Buying Price</th>
					<th class="text-right">Selling Price</th>
					<th class="text-right">Weight (g)</th>
					<th class="text-right">Buying Invoice Order</th>
					<th class="text-right">Selling Invoice Order</th>
					<th class="text-center">Is Active</th>
				</tr>
			</thead>
			<tbody>
				@foreach($items as $item)
				<tr>
					<td>{{$item->code}}</td>
					<td>{{HTML::link ( URL::action ( 'entities.items.edit' , [$item -> id ] ) , $item -> name )}}</td>
					<td class="text-right">{{$item->reorder_level}}</td>
					<td class="text-right">{{number_format($item->current_buying_price,2)}}</td>
					<td class="text-right">{{number_format($item->current_selling_price,2)}}</td>
					<td class="text-right">{{ $item->weight}}</td>
					<td class="text-right">{{$item->buying_invoice_order}}</td>
					<td class="text-right">{{$item->selling_invoice_order}}</td>
					<td class="text-center">{{ViewButler::getYesNoFromBoolean ( $item->is_active)}}</td>
				</tr>
				@endforeach
			</tbody>
		</table>
		@endif
	</div>
</div>
@stop