@extends('web._templates.template')

@section('body')

<h3>View Finance Accounts</h3>

{{Form::open()}}
<table border="1">
	<tr>
		<td>{{Form::label('name','name')}}</td>
		<td>{{Form::text('name',$name)}}</td>
	</tr>
	<tr>
		<td>{{Form::label('bank_id','bank id')}}</td>
		<td>{{Form::select('bank_id',$bankSelectBox,$bankId)}}</td>
	</tr>
	<tr>
		<td>{{Form::label('is_in_house','is in house')}}</td>
		<td>{{Form::select('is_in_house',ViewButler::htmlSelectAnyYesNo (),$isInHouse)}}</td>
	</tr>
	<tr>
		<td>{{Form::label('is_active','is active')}}</td>
		<td>{{Form::select('is_active',ViewButler::htmlSelectAnyYesNo (),$isActive)}}</td>
	</tr>
	<tr>
		<td colspan="2">{{Form::submit('Submit')}}</td>
	</tr>
</table>
{{Form::close()}}

<table border="1">
	<tr>
		<th>Name</th>
		<th>Bank</th>
		<th>Account Balance</th>
		<th>Is In House</th>
		<th>Is Active</th>
		<th>Edit Account</th>
	</tr>
	@foreach($financeAccounts as $financeAccount)
	<tr>
		<th>{{$financeAccount->name}}</th>
		
		@if($financeAccount->bank_id==NULL)
		<th></th>
		@else
		<th>{{$financeAccount->bank->name}}</th>
		@endif
		
		<th>{{$financeAccount->account_balance}}</th>
		<th>{{$financeAccount->is_in_house}}</th>
		<th>{{$financeAccount->is_active}}</th>
		<th>{{HTML::link ( URL::action ( 'finances.accounts.edit', [$financeAccount->id] ), 'Edit' ) }}</th>
	</tr>
	@endforeach
</table>
@stop

