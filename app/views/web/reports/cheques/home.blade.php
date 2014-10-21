@extends('web._templates.template')

@section('body')

<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">View Incoming Cheques Reports</h3>
	</div>
	<div class="panel-body"> 
		<div class="panel panel-default">
			<div class="panel-body">

				{{Form::open(['class'=>'form-inline', 'role'=>'form'])}}
				<div class="form-group inline-form">
					{{Form::label('rep',null, array('class' => 'control-label'))}}
					{{Form::select('rep',$reps,array('class' => 'form-control'), array('class' => 'form-control'))}}
				</div> 
				<div class="form-group inline-form">
					{{Form::label('route',null, array('class' => 'control-label'))}}
					{{Form::select('route',$routes,array('class' => 'form-control'), array('class' => 'form-control'))}}
				</div> 
				<div class="form-group inline-form">
					{{Form::label('customer',null, array('class' => 'control-label'))}}
					{{Form::select('customer',$customers,array('class' => 'form-control'), array('class' => 'form-control'))}}
				</div> 
				<div class="form-group inline-form">
					{{Form::label('bank',null, array('class' => 'control-label'))}}
					{{Form::select('bank',$banks,array('class' => 'form-control'), array('class' => 'form-control'))}}
				</div> 
				<div class="form-group inline-form">
					{{Form::label('from',null, array('class' => 'control-label'))}} 
					{{Form::input('date', 'date_from', null,array('class' => 'form-control'))}}
				</div>   
				<div class="form-group inline-form">
					{{Form::label('to',null, array('class' => 'control-label'))}} 
					{{Form::input('date', 'date_to', null,array('class' => 'form-control'))}}
				</div>   
				<div class="form-group inline-form">
					{{Form::label('cheque_num',null, array('class' => 'control-label'))}} 
					{{Form::input('text', 'cheque_num', null,array('class' => 'form-control'))}}
				</div>  
				<div class="form-group inline-form">
					{{Form::submit('Submit',array('class' => 'btn btn-default pull-right'))}}
				</div>
				{{Form::close()}} 
			</div>
		</div>


	</div>
</div>

@stop

@section('file-footer')
<script src="/js/reports/cheques/home.js"></script>
<script>
loadCustomers("{{URL::action('entities.customers.ajax.forRouteId')}}","{{csrf_token()}}");
</script>
@stop