<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>Sanmark Distro#</title>

		{{ HTML::style('packages/bootstrap/css/bootstrap.min.css') }}
		{{ HTML::style('packages/bootstrap-material-design/css/ripples.min.css') }}
		{{ HTML::style('packages/bootstrap-material-design/css/material-wfont.min.css') }}
		{{ HTML::style('packages/bootstrap-material-design/css/material.min.css') }}
		{{ HTML::style('packages/jQueryUI/css/jquery-ui.css') }}
		{{ HTML::style('css/style.css') }}
		{{ HTML::style('css/bootstrap-override.css') }}

		{{ HTML::script('packages/jQuery/jquery.min.js') }}
		{{ HTML::script('packages/jQueryUI/js/jquery-ui.min.js') }}
		{{ HTML::script('packages/bootstrap/js/bootstrap.min.js') }}
		{{ HTML::script('packages/SanmarkJsHelper/SanmarkJsHelper.js') }}
	</head>
	<body>

		<div class="container-fluid">

			@if(Auth::check())
			<div class="main-nav">
				@include('web._inc.menu')
			</div>
			@endif


			@if(MessageButler::hasError())
			<div class="alert alert-danger alert-dismissible" role="alert">{{ViewButler::bootstrapDismissibleAlertCloseButton()}}{{MessageButler::getError()}}</div>
			@endif
			@if(MessageButler::hasInfo())
			<div class="alert alert-info">{{ViewButler::bootstrapDismissibleAlertCloseButton()}}{{MessageButler::getInfo()}}</div>
			@endif
			@if(MessageButler::hasSuccess())
			<div class="alert alert-success">{{ViewButler::bootstrapDismissibleAlertCloseButton()}}{{MessageButler::getSuccess()}}</div>
			@endif

			@if($errors->count()>0)
			<div class="alert alert-danger alert-dismissible" role="alert">
				{{ViewButler::bootstrapDismissibleAlertCloseButton()}}
				<ul>
					@foreach($errors->all() as $error)
					<li>{{$error}}</li>
					@endforeach
				</ul>
			</div>
			@endif

			<div class="main-content">
				@yield('body')
			</div>


			@if(Auth::check())
			<div class="footer navbar-default">
				<div class="row">
					<div class="col-sm-6 text-left">
						<a href="http://sapps.io/#/distrosharp" target="_blank">Distro# {{file_get_contents ( '../VERSION' )}}</a> | &COPY; <a href="http://thesanmark.com/" target="_blank">Sanmark</a>
					</div> 
					<div class="col-sm-6 text-right">
						Organization : {{SystemSettingButler::getValue ( 'organization_name')}}
					</div> 
				</div>
			</div>
			@endif

		</div>
		{{ HTML::script('packages/bootstrap-material-design/js/ripples.min.js') }}
		{{ HTML::script('packages/bootstrap-material-design/js/material.min.js') }}
		@yield('file-footer')
	</body>
</html>