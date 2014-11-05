@extends('web._templates.template')

@section('body')
<div class="panel panel-default well">
	<div class="panel-heading">
		<h3 class="panel-title">Profit and Loss Report</h3>
	</div>
	<div class="panel-body">

		<div class="panel panel-default">
			<div class="panel-body">
				{{Form::open(['class'=>'form-inline', 'role'=>'form'])}}
				<div class="form-group inline-form">
					{{Form::label('from_date',null,array('class' => 'control-label'))}}
					{{Form::input('date', 'from_date', $date_from,array('class' => '','required'=>true))}}
				</div>
				<div class="form-group inline-form">
					{{Form::label('to_date',null,array('class' => 'control-label'))}}
					{{Form::input('date', 'to_date', $date_to,array('class' => '','required'=>true))}}
				</div>
				<div class="form-group inline-form">
					{{Form::submit('Submit',array('class' => 'btn btn-primary pull-right'))}}
				</div>
				{{Form::close()}}
			</div>
		</div>
		<br/>
		@if($viwe_data) 

		<table class="table table-striped" style="width: 50%">
			<tr>
				<td><b>Sales :</b></td>
				<td class="text-right"><b>{{ number_format( $sales, 2) }}</b></td>
			</tr>
			<tr>
				<td><b>Discounts :</b></td>
				<td class="text-right"><b>{{ number_format( $discounts, 2) }}</b></td>
			</tr>
			<tr>
				<td><b>Net Sales :</b></td>
				<td class="text-right"><b>{{ number_format( $netSales, 2) }}</b></td>
			</tr>
			<tr>
				<td><b>Cost of Sold Goods :</b></td>
				<td class="text-right"><b>{{ number_format( $costOfSoldGoods, 2) }}</b></td>
			</tr>
			<tr>
				<td><b>Gross Profit :</b></td>
				<td class="text-right"><b>{{ number_format( $netSales - $costOfSoldGoods, 2) }}</b></td>
			</tr>
		</table>
		@else 
		<h4 class="text-center">Please define a criteria and press "Submit".</h4>
		@endif
	</div>
</div>
@stop