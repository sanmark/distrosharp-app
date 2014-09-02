@extends('web._templates.template')

@section('body')

<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">View Finance Accounts</h3>
	</div>
	<div class="panel-body">

		{{Form::open(['class'=>'form-horizontal', 'role'=>'form'])}}
		<br/>
		<div class="form-group">
			{{Form::label('name', null, array('class' => 'col-sm-1 control-label'))}}
			<div class="col-sm-3">
				{{Form::text('name',$name, array('class' => 'form-control'))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('bank_id', null, array('class' => 'col-sm-1 control-label'))}}
			<div class="col-sm-3">
				{{Form::select('bank_id',$bankSelectBox,$bankId, array('class' => 'form-control'))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('is_in_house', null, array('class' => 'col-sm-1 control-label'))}}
			<div class="col-sm-3">
				{{Form::select('is_in_house',ViewButler::htmlSelectAnyYesNo (),$isInHouse, array('class' => 'form-control'))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('is_active', null, array('class' => 'col-sm-1 control-label'))}}
			<div class="col-sm-3">
				{{Form::select('is_active',ViewButler::htmlSelectAnyYesNo (),$isActive, array('class' => 'form-control'))}}
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-offset-1 col-sm-3">
				{{Form::submit('Submit', array('class' => 'btn btn-default pull-right'))}}
			</div>
		</div>
		{{Form::close()}}

		<br/>

		<table class="table table-striped" style="width: 80%;">
			<tr>
				<th>Name</th>
				<th>Bank</th>
				<th>Account Balance</th>
				<th>Is In House</th>
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
					<td>{{$financeAccount->is_in_house}}</td>
					<td>{{$financeAccount->is_active}}</td>
					<td>
						@if($financeAccount->is_in_house == TRUE)
						{{HTML::link ( URL::action ( 'finances.accounts.edit', [$financeAccount->id] ), 'Edit' ) }}
						@endif
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>

	</div>
</div>

@stop

