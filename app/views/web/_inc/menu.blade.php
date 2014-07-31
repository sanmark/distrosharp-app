@if(is_null(Session::get(SESSION_MENU)))
No menu :(
@else
<?php //var_dump ( Session::get ( SESSION_MENU ) ) ; ?>
{{ViewButler::makeMenuHtmlFromArray(Session::get ( SESSION_MENU ))}}
@endif