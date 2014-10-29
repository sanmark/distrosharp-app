<nav class="navbar navbar-default" role="navigation">
	<div class="container-fluid main-menu">
		@if(is_null(Session::get(SESSION_MENU)))
		No menu :(
		@else
		{{ViewButler::makeMenuHtmlFromArray(Session::get ( SESSION_MENU ))}}
		<ul class="navbar-right">			
			<li class="no-bg">Hi, {{Auth::user()->username}}</li>
			<li>{{HTML::link(URL::action('account.logout'), 'Logout')}}</li>			
		</ul>
		@endif
	</div>
</nav>