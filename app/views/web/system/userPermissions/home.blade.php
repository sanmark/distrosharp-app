@extends ('web._templates.template')

@section('body')
<div class="panel panel-default well">
	<div class="panel-heading">
		<h3 class="panel-title">User Permissions</h3>
	</div>
	<div class="panel-body">

		<div class="panel panel-default">
			<div class="panel-body">
				{{Form::open(['class'=>'form-inline', 'role'=>'form'])}}
				<div class="form-group inline-form">
					{{Form::label('select_user',null,array('class' => 'control-label'))}}
					{{Form::select('userId',$usersList,$userId, array('class' => ''))}}
				</div>
				<div class="form-group inline-form">
					{{Form::input('submit',null,'Submit',array('name'=>'submit','class' => 'btn btn-success'))}}
				</div>
			</div>
		</div>

		<br/>
		<div class="row">
			@if($permissions==NULL)
			No Permissions to assign
			@else
			@foreach($permissions as $permission)
			<div class="col-sm-4">
				<div class="row">
					<div class="col-sm-10">
						{{$permission->label}}
					</div>
					<div class="col-sm-2">
						@if(isset($userPermissions[$permission->id]))
						{{Form::checkbox('is_assigned_'.$permission->id,$permission->id,TRUE)}}
						@else
						{{Form::checkbox('is_assigned_'.$permission->id,$permission->id,FALSE)}}
						@endif
					</div>
				</div>
			</div>
			@endforeach
			@endif
			<tr>
				<td colspan="2">
					<br/>{{Form::input('submit',null,'Update',array('name'=>'update', 'class' => 'btn btn-primary pull-right'))}}
				</td>
			</tr>
		</div>
		{{Form::close()}}
	</div>
</div>

@stop
