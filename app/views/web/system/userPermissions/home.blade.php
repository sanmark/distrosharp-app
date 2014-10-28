@extends ('web._templates.template')

@section('body')

<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">User Permissions</h3>
	</div>
	<div class="panel-body">

		<div class="panel panel-default">
			<div class="panel-body">
				{{Form::open(['class'=>'form-inline', 'role'=>'form'])}}
				<div class="form-group inline-form">
					{{Form::label('select_user',null,array('class' => 'control-label'))}}
					{{Form::select('userId',$usersList,$userId, array('class' => 'form-control'))}}
				</div>
				<div class="form-group inline-form">
					{{Form::input('submit',null,'Submit',array('name'=>'submit','class' => 'btn btn-default'))}}
				</div>
			</div>
		</div>

		<br/>
		<table class="table table-striped" style="width: 30%;">
			<tr>
				<th>Permission</th>
				<th class="text-center">Status</th>
			</tr>
			@if($permissions==NULL)
			<tr>
				<td colspan="2" class="text-center">No Permissions to assign</td>
			</tr>
			@else
			@foreach($permissions as $permission)
			<tr>
				<td>{{$permission->label}}</td>
				<td class="text-center">
					@if(isset($userPermissions[$permission->id]))
					{{Form::checkbox('is_assigned_'.$permission->id,$permission->id,TRUE)}}
					@else
					{{Form::checkbox('is_assigned_'.$permission->id,$permission->id,FALSE)}}
					@endif
				</td>
			</tr>
			@endforeach
			@endif
			<tr>
				<td colspan="2">
					<br/>{{Form::input('submit',null,'Update',array('name'=>'update', 'class' => 'btn btn-default pull-right'))}}
				</td>
			</tr>
			{{Form::close()}}
		</table>
	</div>
</div>

@stop
