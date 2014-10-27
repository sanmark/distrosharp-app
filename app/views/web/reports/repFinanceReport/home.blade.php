@extends('web._templates.template')

@section('body')
<h2>Rep Finance Report</h2>
<table>
	{{Form::open()}}
	<tr>
		<td>{{Form::label('rep_id')}}</td>
		<td>{{Form::select('rep_id', $repSelectBox, $repId)}}</td>
	</tr>
	<tr>
		<td>{{Form::label('date_from')}}</td>
		<td>{{Form::input('date', 'date_from', $dateFrom)}}</td>
	</tr>
	<tr>
		<td>{{Form::label('date_to')}}</td>
		<td>{{Form::input('date', 'date_to', $dateTo)}}</td>
	</tr>
	<tr>
		<td></td>
		<td></td>
	</tr>
	<tr>
		<td colspan="2">{{Form::submit('Submit')}}</td>
	</tr>
	{{Form::close()}}
</table>

@if(isset($dates))
@if(count($dates)>0)
<table style="text-align: right;">
	<tr>
		<th>Date</th>
		<th>Total</th>
		<th>Cash</th>
		<th>Cheque</th>
		<th>Credit</th>
	</tr>
	@foreach($dates as $date=>$details)
	<tr>
		<td>{{$date}}</td>
		<td>{{number_format($details['total'],2)}}</td>
		<td>{{number_format($details['cash'],2)}}</td>
		<td>{{number_format($details['cheque'],2)}}</td>
		<td>{{number_format($details['credit'],2)}}</td>
	</tr>
	@endforeach
	<tr>
		<td>Total</td>
		<td>{{number_format($totalTotal, 2)}}</td>
		<td>{{number_format($totalCash, 2)}}</td>
		<td>{{number_format($totalCheque, 2)}}</td>
		<td>{{number_format($totalCredit, 2)}}</td>
	</tr>
</table>
@else
No results found.
@endif
@else
Please define a criteria and press "Submit".
@endif
@stop