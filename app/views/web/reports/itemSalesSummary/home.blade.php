@extends('web._templates.template')

@section('body')
<h2>Item Sales Summary</h2>
<table>
	{{Form::open()}}
	<tr>
		<td>Rep</td>
		<td>{{Form::select('rep_id', $repSelectBox, $repId)}}</td>
	</tr>
	<tr>
		<td>From</td>
		<td>{{Form::input('date', 'from_date', $fromDate)}}</td>
	</tr>
	<tr>
		<td>To</td>
		<td>{{Form::input('date', 'to_date', $toDate)}}</td>
	</tr>
	<tr>
		<td colspan="2">
			{{Form::submit('Submit')}}
		</td>
	</tr>
	{{Form::close()}}
</table>

@if(isset($items))
<table>
	<tr>
		<th>Name</th>
		<th>Total Free Amount</th>
		<th>Total Paid Amount</th>
	</tr>
	@foreach($items as $item)
	<tr>
		<td>{{$item->name}}</td>
		<td>{{$item->totalFreeAmount}}</td>
		<td>{{$item->totalPaidAmount}}</td>
	</tr>
	@endforeach
</table>
@else
Please define a criteria and press "Submit".
@endif

@stop