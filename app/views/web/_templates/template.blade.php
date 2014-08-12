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
			<div style="width: 100%; background-color: #D2D2D2;">
				@include('web._inc.menu')
			</div>
			<div style="width: 1000px; float: left;">
				@yield('body')
			</div>
			<div style="width: 200px; float: left; background-color: #D2D2D2;">
				aaa
			</div>
			<div style="width: 100%; height: 100px; float: left; background-color: #D2D2D2;">
				footer
			</div>
		</div>
	</body>
</html>