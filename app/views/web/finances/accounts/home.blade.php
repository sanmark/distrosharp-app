@extends('web._templates.template')

@section('body')

<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">View Finance Accounts</h3>
	</div>
	<div class="panel-body">

		<div class="panel panel-default">
			<div class="panel-body">

				{{Form::open(['class'=>'form-inline', 'role'=>'form'])}}
				<div class="form-group inline-form">
					{{Form::label('name', null, array('class' => 'control-label'))}}
					{{Form::text('name',$name, array('class' => 'form-control'))}}
				</div>
				<div class="form-group inline-form">
					{{Form::label('bank_id', null, array('control-label'))}}
					{{Form::select('bank_id',$bankSelectBox,$bankId, array('class' => 'form-control'))}}
				</div>
				<div class="form-group inline-form">
					{{Form::label('is_active', null, array('class' => 'control-label'))}}
					{{Form::select('is_active',ViewButler::htmlSelectAnyYesNo (),$isActive, array('class' => 'form-control'))}}
				</div>
				<div class="form-group inline-form">
					{{Form::submit('Submit', array('class' => 'btn btn-default pull-right'))}}
				</div>
				{{Form::close()}}

			</div>
		</div>

		<br/>
		@if(count($financeAccounts)==0)
		<br>
		<div class="no-records-message text-center">
			There are no records to display
		</div>
		<br>
		@else
		<table class="table table-striped" style="width: 80%;">
			<tr>
				<th>Name</th>
				<th>Bank</th>
				<th>Account Balance</th>
				<th>Is Active</th>
				<th>Edit Account</th>
			</tr>
			<tbody>
				@foreach($financeAccounts as $financeAccount)
				<tr>
					<td>{{HTML::link ( URL::action ( 'finances.transfers.view', [$financeAccount->id] ),"$financeAccount->name" ) }}</td>

					@if($financeAccount->bank_id==NULL)
					<td></td>
					@else
					<td>{{$financeAccount->bank->name}}</td>
					@endif

					<td>{{$financeAccount->account_balance}}</td>
					<td>{{ViewButler::getYesNoFromBoolean ( $financeAccount->is_active)}}</td>
					<td>
						@if($financeAccount->is_in_house == TRUE)
						{{HTML::link ( URL::action ( 'finances.accounts.edit', [$financeAccount->id] ), 'Edit' ) }}
						@endif
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
		@endif
	</div>
</div>

@stop

