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
			
			@include('web._inc.menu')
			
			<div class="row main-content">
				<div class="col-lg-9 col-md-9">
					@yield('body')
				</div>
				<div class="col-lg-3 col-md-3">
					aaa
				</div>
			</div>

			<div class="footer">
				footer
			</div>
			
		</div>
	</body>
</html>