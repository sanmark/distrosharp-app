@extends('web._templates.template')

@section('body')
<div class="panel panel-default well">
	<div class="panel-heading">
		<h3 class="panel-title">Confirm Account Balance</h3>
	</div>
	<div class="panel-body">
		<div class="panel panel-default">
			<div class="panel-body">
				{{Form::open(['class'=>'form-inline', 'role'=>'form'])}}
				<div class="form-group inline-form">
					{{Form::label('account','Finance Account', array('class' => 'control-label'))}}
					{{Form::select('account',$accountSelectBox,$accountId, array('class' => '','required'=>'required'))}}
				</div>
				<div class="form-group inline-form">
					{{Form::label('datetime','Date/Time', array('class' => 'control-label'))}}
					{{Form::input('datetime-local','datetime',$viewDateTime,array('class' => '','step'=>'1'))}}
				</div>
				<div class="form-group inline-form">
					{{Form::submit('Submit', array('tabindex' => '10', 'class' => 'btn btn-primary pull-right'))}}
				</div>
			</div>
		</div>

		@if(count($transferData)==0)
		<h4><b>There are no records to display...</b></h4>
		@else	

		<table class="table table-striped" style="width: 70%;">		
			<tr>
				<th colspan="4">
			<h5><b>Starting Balance = {{number_format($startingTotal,2)}}</b></h5>
			</th>
			</tr>
			<tr>
				<th>Date / Time</th>
				<th>From Account</th>
				<th>To Account</th>
				<th class="text-right">Amount</th>
			</tr>
			@foreach($transferData as $transfer)
			<tr>
				<td>{{$transfer->date_time}}</td>
				<td>{{$transfer->fromAccount['name']}}</td>
				<td>{{$transfer->toAccount['name']}}</td>
				<td class="text-right">{{$transfer->amount}}</td>
			</tr>
			@endforeach
			<tr>
				<th colspan="4">
			<h5><b>Ending Balance = {{number_format($endingTotal,2)}}
					{{Form::hidden('endBalance',$endingTotal)}}</b></h5>
			</th>
			</tr>
			<tr>
				<td colspan="5">{{Form::input('submit',null,'Confirm',array('class' => 'btn btn-danger pull-right','name'=>'confirm'))}}</td>
			</tr>			
		</table>
		@endif
		{{Form::close()}}
	</div>
</div>
@stop
