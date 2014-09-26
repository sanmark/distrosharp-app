@extends('web._templates.template')

@section('body')

<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Payment Target Accounts</h3>
	</div>
	<div class="panel-body">

		{{Form::open(['class'=>'form-horizontal', 'role'=>'form'])}}
		<br />
		<div class="form-group">
			{{Form::label('payment_target_cash', null, array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-3">
				{{Form::select('payment_target_cash', $inHouseAccounts, $paymentTargetCash, array('tabindex' => '1', 'class' => 'form-control'))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('payment_target_cheque', null, array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-3">
				{{Form::select('payment_target_cheque', $inHouseAccounts, $paymentTargetCheque, array('tabindex' => '2', 'class' => 'form-control'))}}
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-3">
				{{Form::submit('Submit', array('tabindex' => '3', 'class' => 'btn btn-default pull-right'))}}
				{{ link_to(URL::previous(), 'Back', ['class' => 'btn btn-default pull-right back-btn-margin']) }}
			</div>
		</div>
		{{Form::close()}}

	</div>
</div>

@stop