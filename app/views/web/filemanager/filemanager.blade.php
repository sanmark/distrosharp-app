@extends('web._templates.template')

@section('body')

<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">File Manager</h3>
	</div>
	<div class="panel-body">
		<!-- Element where elFinder will be created (REQUIRED) -->
		<div id="elfinder"></div>
	</div>
</div>
@stop

@section('file-footer')
{{ HTML::style('packages/elfinder-2.0-rc1/css/elfinder.min.css') }}
{{ HTML::style('packages/elfinder-2.0-rc1/css/theme.css') }}
{{ HTML::script('packages/elfinder-2.0-rc1/js/i18n/elfinder.ru.js') }}
{{ HTML::script('packages/elfinder-2.0-rc1/js/elfinder.min.js') }}

<script type="text/javascript" charset="utf-8">
	$().ready(function() {
		var elf = $('#elfinder').elfinder({
			url: 'packages/elfinder-2.0-rc1/php/connector.php?organization={{Session::get("organization")}}'  // connector URL (REQUIRED)
					// lang: 'ru',             // language (OPTIONAL)
		}).elfinder('instance');
	});
</script>
@stop