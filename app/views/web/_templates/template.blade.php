<!DOCTYPE html>
<html lang="en">
	<head>
		<title>d-071-1-v2-app</title>
		{{ HTML::style('css/style.css') }}
		{{ HTML::style('packages/bootstrap/css/bootstrap.min.css') }}
		{{ HTML::style('packages/bootstrap/css/bootstrap-theme.min.css') }}

		{{ HTML::script('packages/jQuery/jquery.min.js') }}
		{{ HTML::script('packages/bootstrap/js/bootstrap.min.js') }}
	</head>
	<body>
		<div class="container-fluid">

			<div class="main-nav">
				@include('web._inc.menu')
			</div>

			@if(MessageButler::hasError())
			<div class="alert alert-danger alert-dismissible" role="alert">{{ViewButler::bootstrapDismissibleAlertCloseButton()}}{{MessageButler::getError()}}</div>
			@endif
			@if(MessageButler::hasInfo())
			<div class="alert alert-info">{{ViewButler::bootstrapDismissibleAlertCloseButton()}}{{MessageButler::getInfo()}}</div>
			@endif
			@if(MessageButler::hasSuccess())
			<div class="alert alert-success">{{ViewButler::bootstrapDismissibleAlertCloseButton()}}{{MessageButler::getSuccess()}}</div>
			@endif

			<div class="main-content">
				@yield('body')
			</div>

			<div class="footer">
				footer
			</div>

		</div>
	</body>
</html>