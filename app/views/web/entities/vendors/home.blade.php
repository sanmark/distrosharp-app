@extends('web._templates.template')

@section('body')

<div class="panel panel-default well">
	<div class="panel-heading">
		<div class="panel-title">
			<span>Vendors</span>
			{{HTML::link ( URL::action ( 'entities.vendors.add') ,'Add New Vendor',['class' => 'panel-title-btn btn btn-success btn-sm pull-right'] )}}
		</div>
	</div>
	<div class="panel-body">

		<div class="panel panel-default" style="">
			<div class="panel-body">

				{{Form::open(['class'=>'form-inline', 'role'=>'form'])}}

				<div class="form-group inline-form">
					{{Form::label('name', null, array('class' => 'control-label'))}}
					{{Form::text('name',$name, array('tabindex' => '1', 'class' => ''))}}
				</div>
				<div class="form-group inline-form">
					{{Form::label('is_active', null, array('class' => 'control-label'))}}
					{{Form::select ( 'is_active' , ViewButler::htmlSelectAnyYesNo(),$isActive, array('tabindex' => '2', 'class' => ''))}}
				</div>
				<div class="form-group inline-form">
					{{Form::submit('Submit', array('tabindex' => '3', 'class' => 'btn btn-primary pull-right'))}}
				</div>

				{{Form::close()}}

			</div>
		</div>
		@if(count($vendors)==0)
		<br>
		<div class="no-records-message text-center">
			There are no records to display
		</div>
		<br>
		@else
		<table class="table table-striped" style="width: 60%;">
			<thead>
				<tr>
					<td>Name</td>
					<td>Details</td>
					<td class="text-center">Is Active</td>
				</tr>
			</thead>
			<tbody>
				@foreach($vendors as $vendor)
				<tr>
					<td>{{HTML::link (URL::action ( 'entities.vendors.edit',[$vendor->id] ), $vendor->name )}}</td>
					<td>{{$vendor->details}}</td>
					<td class="text-center">{{ViewButler::getYesNoFromBoolean ( $vendor->is_active)}}</td>
				</tr>
				@endforeach
			</tbody>
		</table>
		@endif
	</div>
</div>

@stop