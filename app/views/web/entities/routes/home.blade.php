@extends('web._templates.template')

@section('body')
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Routes</h3>
	</div>
	<div class="panel-body">
		<table border="1">
			{{Form::open()}}
			<tr>
				<td>{{Form::label('name')}}</td>
				<td>{{Form::text('name',$name)}}</td>
			</tr>			
			<tr>
				<td>{{Form::label('is_active')}}</td>
				<td>{{Form::select('is_active',ViewButler::htmlSelectAnyYesNo (), $isActive )}}</td>
			</tr>	
			<tr>
				<td>{{Form::label('rep_id')}}</td>
				<td>{{Form::select('rep_id',$repSelectBoxContent, $repId,['autocomplete'=>'off'])}}</td>
			</tr>	
			<tr>
				<td colspan="2">
					{{Form::submit('Submit')}}
				</td>
			</tr>
			{{Form::close()}}
		</table>
		<table class="table table-striped" style="width: 30%;">
			<thead>
				<tr>
					<td>Name</td>
					<td>Is Active</td>
					<td>Rep</td>
				</tr>
			</thead>
			<tbody>
				@foreach($routes as $route)
				<tr>
					<td>{{HTML::link(URL::action('entities.routes.edit', [$route->id]), $route->name)}}</td>
					<td>{{ViewButler::getYesNoFromBoolean ( $route->is_active)}}</td>
					<td>{{$route->rep->username}}</td>
				</tr>
				@endforeach
			</tbody>
		</table>

	</div>
</div>

@stop