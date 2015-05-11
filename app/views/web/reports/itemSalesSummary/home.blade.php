@extends('web._templates.template')

@section('body')

<div id="error-mess"> 
</div>



<div class="panel panel-default well">
	<div class="panel-heading">
		<h3 class="panel-title">Item Sales Summary</h3>
	</div>
	<div class="panel-body">

		<div class="panel panel-default">
			<div class="panel-body">
				{{Form::open(['class'=>'form-inline', 'role'=>'form', 'id'=>'targrt'])}}
				<div class="form-group inline-form">
					{{Form::label('rep', null, array('class' => 'control-label'))}}
					{{Form::select('rep_id', $repSelectBox, $repId, array('class' => ''))}}
				</div>
				<div class="form-group inline-form">
					{{Form::label('from', null, array('class' => 'control-label'))}}
					{{Form::input('date', 'from_date', $fromDate, array('id'=>'from_date', 'class' => 'date-select' , 'required'=>'TRUE'))}}
				</div>
				<div class="form-group inline-form">
					{{Form::label('to', null, array('class' => 'control-label'))}}
					{{Form::input('date', 'to_date', $toDate, array('id'=>'to_date', 'class' => 'date-select', 'required'=>'TRUE'))}}
				</div>
				<div class="form-group inline-form">
					{{Form::submit('Submit', array('class' => 'btn btn-primary pull-right'))}}
				</div>
				{{Form::close()}}
			</div>
		</div>


		@if(isset($items))
		<table class="table table-striped" style="width:100%;">
			<thead>
				<tr>
					<th>Name</th>
					<th class="text-right">Total Free Amount</th>
					<th class="text-right">Total Free Amount Value</th>
					<th class="text-right">Total Free Amount Weight</th>
					<th class="text-right">Total Paid Amount</th>
					<th class="text-right">Total Paid Amount Value</th>
					<th class="text-right">Total Paid Amount Weight</th> 
				</tr>
			</thead>
			<tbody>
				@foreach($items as $item)
				<tr>
					<td>{{$item->name}}</td>
					<td class="text-right">{{$item->totalFreeAmount}}</td>
					<td class="text-right">
						{{number_format($item->totalFreeAmount * $item->selling_price,2)}}
					</td>
					<td class="text-right">{{$item->totalFreeAmount * $item->weight}}g</td>
					<td class="text-right">{{$item->totalPaidAmount}}</td>
					<td class="text-right">
						{{number_format($item->totalPaidAmount * $item->selling_price,2)}}
					</td>
					<td class="text-right">{{$item->totalPaidAmount * $item->weight}}g</td>
				</tr>
				@endforeach
			</tbody>
			<tfoot>
				<tr>
					<td class="text-right"></td>
					<td class="text-right"><b>Total</b></td>
					<td class="text-right"><b class="total-line-bottom">{{number_format($freeAmountValueSum,2)}}</b></td>
					<td class="text-right"><b class="total-line-bottom">{{$freeAmountWeightSum/1000}}kg</b></td>
					<td class="text-right"></td>
					<td class="text-right"><b class="total-line-bottom">{{number_format($paidAmountValueSum,2)}}</b></td>
					<td class="text-right"><b class="total-line-bottom">{{$paidAmountWeightSum/1000}}kg</b></td>
				</tr>
			</tfoot>
		</table>
		@else
		<h4 class="text-center">Please define a criteria and press "Submit".</h4>
		@endif
	</div>
</div> 
<script src="/js/reports/itemSalesSummary/home.js"></script>

@stop