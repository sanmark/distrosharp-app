@extends('web._templates.template')

@section('body')

<div class="panel panel-default well">
	<div class="panel-heading">
		<h3 class="panel-title">View Finance Accounts</h3>
	</div>
	<div class="panel-body">

		<div class="panel panel-default">
			<div class="panel-body">

				{{Form::open(['class'=>'form-inline', 'role'=>'form'])}}
				<div class="form-group inline-form">
					{{Form::label('name', null, array('class' => 'control-label'))}}
					{{Form::text('name',$name, array('tabindex' => '1', 'class' => ''))}}
				</div>
				<div class="form-group inline-form">
					{{Form::label('bank_id', null, array('control-label'))}}
					{{Form::select('bank_id',$bankSelectBox,$bankId, array('tabindex' => '2', 'class' => ''))}}
				</div>
				<div class="form-group inline-form">
					{{Form::label('is_active', null, array('class' => 'control-label'))}}
					{{Form::select('is_active',ViewButler::htmlSelectAnyYesNo (),$isActive, array('tabindex' => '3', 'class' => ''))}}
				</div>
				<div class="form-group inline-form">
					{{Form::submit('Submit', array('tabindex' => '4', 'class' => 'btn btn-primary pull-right'))}}
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
				<th class="text-right">Account Balance</th>
				<th class="text-center">Is In House</th>
				<th class="text-center">Is Active</th>
				<th class="text-center">Edit Account</th>
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
					<td class="text-right">{{$financeAccount->account_balance}}</td>
					<td class="text-center">{{ViewButler::getYesNoFromBoolean ( $financeAccount->is_in_house)}}</td>
					<td class="text-center">{{ViewButler::getYesNoFromBoolean ( $financeAccount->is_active)}}</td>
					<td class="text-center">
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

