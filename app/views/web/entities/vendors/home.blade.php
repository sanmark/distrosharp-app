@extends('web._templates.template')

@section('body')

<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Vendors</h3>
	</div>
	<div class="panel-body">

		<div class="panel panel-default" style="">
			<div class="panel-body">

				{{Form::open(['class'=>'form-inline', 'role'=>'form'])}}

				<div class="form-group inline-form">
					{{Form::label('name', null, array('class' => 'control-label'))}}
					{{Form::text('name',$name, array('class' => 'form-control'))}}
				</div>
				<div class="form-group inline-form">
					{{Form::label('is_active', null, array('class' => 'control-label'))}}
					{{Form::select ( 'is_active' , ViewButler::htmlSelectAnyYesNo(),$isActive, array('class' => 'form-control'))}}
				</div>
				<div class="form-group inline-form">
					{{Form::submit('Submit', array('class' => 'btn btn-default pull-right'))}}
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