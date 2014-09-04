@extends('web._templates.template')

@section('body')

<h3>Set Default Time Zone</h3>

{{Form::open()}}
<table>
	<tr>
		<td>{{Form::select('time_zone',$all,SystemSettingButler::getValue('time_zone'))}}</td>
	</tr>
	<tr>
		<td>{{Form::submit('Submit')}}</td>
	</tr>
</table>
{{Form::close()}}
@stop