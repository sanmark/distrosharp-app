@extends('web._templates.template')

@section('body')
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Profit and Loss Report</h3>
	</div>
	<div class="panel-body">
		<div class="panel panel-default">
			{{Form::open(['class'=>'form-inline', 'role'=>'form'])}}
			<br/>
			<div class="form-group inline-form">
				{{Form::label('from_date',null,array('class' => 'control-label'))}}
				{{Form::input('date', 'from_date', $date_from,array('class' => 'form-control','required'=>true))}}
			</div>
			<div class="form-group inline-form">
				{{Form::label('to_date',null,array('class' => 'control-label'))}}
				{{Form::input('date', 'to_date', $date_to,array('class' => 'form-control','required'=>true))}}
			</div>
			<div class="form-group inline-form">
				{{Form::submit('Submit',array('class' => 'btn btn-default pull-right'))}}
			</div>
			{{Form::close()}}
			<br/>
		</div>
	</div>
</div>


<table class="table table-striped" style="width: 50%">
	<tr>
		<td>Sales</td>
		<td>:</td>
		<td align="right">{{ number_format( $sales, 2) }}</td>
	</tr>
	<tr>
		<td>Discounts</td>
		<td>:</td>
		<td align="right">{{ number_format( $discounts, 2) }}</td>
	</tr>
	<tr>
		<td>Net Sales</td>
		<td>:</td>
		<td align="right">{{ number_format( $netSales, 2) }}</td>
	</tr>
	<tr>
		<td>Cost of Sold Goods</td>
		<td>:</td>
		<td align="right">{{ number_format( $costOfSoldGoods, 2) }}</td>
	</tr>
	<tr>
		<td>Gross Profit</td>
		<td>:</td>
		<td align="right">{{ number_format( $netSales - $costOfSoldGoods, 2) }}</td>
	</tr>
</table>



@stop

@section('file-footer')

@stop