<nav class="navbar navbar-default" role="navigation">
	<div class="container-fluid main-menu">
		@if(is_null(Session::get(SESSION_MENU)))
		No menu :(
		@else
		{{ViewButler::makeMenuHtmlFromArray(Session::get ( SESSION_MENU ))}}
		@endif
	</div>
</nav>