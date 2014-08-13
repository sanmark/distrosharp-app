@extends('web._templates.template')

@section('body')

<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Banks</h3>
	</div>
	<div class="panel-body">

		<table class="table table-striped">
			<thead>
				<tr>
					<td>Name</td>
					<td>Is Active</td>
				</tr>
			</thead>
			<tbody>
				@foreach($banks as $bank)
				<tr>
					<td>{{HTML::link ( URL::action ('entities.banks.edit',[$bank->id] ),$bank->name )}}</td>
					<td>{{$bank->is_active}}</td>
				</tr>
				@endforeach
			</tbody>
		</table>

	</div>
</div>

@stop