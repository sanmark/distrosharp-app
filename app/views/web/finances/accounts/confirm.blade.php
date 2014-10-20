@extends('web._templates.template')

@section('body')
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Confirm Bank Account Balance</h3>
	</div>
	<div class="panel-body">

		<div class="panel panel-default">
			<div class="panel-body">
				{{Form::open(['class'=>'form-inline', 'role'=>'form'])}}
				<div class="form-group inline-form">
					{{Form::label('account','Bank Account', array('class' => 'control-label'))}}
					{{Form::select('account',$bankAccountSelectBox,$bankAccountId, array('class' => 'form-control','required'=>'required'))}}
				</div>
				<div class="form-group inline-form">
					{{Form::label('datetime','Date/Time', array('class' => 'control-label'))}}
					{{Form::input('datetime-local','datetime',$viewDateTime,array('class' => 'form-control','step'=>'1'))}}
				</div>
				<div class="form-group inline-form">
					{{Form::submit('Submit', array('tabindex' => '10', 'class' => 'btn btn-default pull-right'))}}
				</div>
			</div>
		</div>
		<br/>
		<table class="table table-striped" style="width: 80%;">
			<tr>
				<td>Date/Time</td>
				<td>From Account</td>
				<td>To Account</td>
				<td>Amount</td>
			</tr>
			@if(count($transferData)==0)
			<tr>
				<td>There are no records to display</td>
			</tr>
			@else
			<tr>
				<td colspan="4">
					Starting Balance = {{number_format($startingTotal,2)}}
				</td>
			</tr>
			@foreach($transferData as $transfer)
			<tr>
				<td>{{$transfer->date_time}}</td>
				<td>{{$transfer->fromAccount['name']}}</td>
				<td>{{$transfer->toAccount['name']}}</td>
				<td>{{$transfer->amount}}</td>
			</tr>
			@endforeach
			<tr>
				<td colspan="4">
					Ending Balance = {{number_format($endingTotal,2)}}
					{{Form::hidden('endBalance',$endingTotal)}}
				</td>
			</tr>
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td>{{Form::input('submit',null,'Confirm',array('class' => 'btn btn-default','name'=>'confirm'))}}</td>
			</tr>
			@endif
		</table>
		{{Form::close()}}
	</div>
</div>
@stop
