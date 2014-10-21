@extends('web._templates.template')

@section('body')

<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Finance Accounts</h3>
	</div>
	<div class="panel-body">
		{{Form::open(['class'=>'form-horizontal', 'role'=>'form'])}}
		<br />
		<div class="form-group">
			<div class="col-sm-2 control-label"><b>Income Account</b></div>
			<div class="col-sm-3">
				{{Form::select('income_account', $inHouseAccounts, $incomeAccount, array('class' => 'form-control'))}}
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-2 control-label"><b>Expense Account</b></div>
			<div class="col-sm-3">
				{{Form::select('expense_account', $inHouseAccounts, $expenseAccount, array('class' => 'form-control'))}}
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-3 col-sm-offset-2">
				{{Form::submit('Submit', array('class' => 'btn btn-default pull-right'))}}
			</div>
		</div>
		{{Form::close()}}
	</div>
</div>
@stop