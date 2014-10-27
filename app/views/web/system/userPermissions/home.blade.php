@extends ('web._templates.template')

@section('body')

<table>
	<tr>
		{{Form::open()}}
		<td>Select User</td>
		<td>{{Form::select('userId',$usersList,$userId)}}</td>
		<td>{{Form::input('submit',null,'Submit',array('name'=>'submit'))}}</td>
	</tr>
</table>
<br/>
<br/>
<table class="table table-striped" style="width: 30%;">
	<tr>
		<th>Permission</th>
		<th>Status</th>
	</tr>
	@if($permissions==NULL)
	<tr>
		<td colspan="2" class="text-center">No Permissions to assign</td>
	</tr>
	@else
	@foreach($permissions as $permission)
	<tr>
		<td>{{$permission->label}}</td>
		<td>
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
		<td colspan="2">{{Form::input('submit',null,'Update',array('name'=>'update'))}}</td>
	</tr>
	{{Form::close()}}
</table>
@stop
