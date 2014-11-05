@extends('web._templates.template')

@section('body')

<div class="panel panel-default well">
	<div class="panel-heading">
		<h3 class="panel-title">View Incoming Cheques Reports</h3>
	</div>
	<div class="panel-body"> 
		<div class="panel panel-default">
			<div class="panel-body">

				{{Form::open(['class'=>'form-inline', 'role'=>'form'])}}
				<div class="form-group inline-form">
					{{Form::label( 'rep',null, array('class' => 'control-label'))}}
					{{Form::select( 'rep', $reps, $repId,array('class' => ''))}}
				</div>  
				<div class="form-group inline-form">
					{{Form::label('route',null, array('class' => 'control-label'))}}
					{{Form::select('route',$routes,$route,array('class' => ''))}}
				</div> 
				<div class="form-group inline-form">
					{{Form::label('customer',null, array('class' => 'control-label'))}}
					{{Form::select('customer', $customers, $customerId,array('class' => ''), array('class' => ''))}}
				</div> 
				<div class="form-group inline-form">
					{{Form::label('bank',null, array('class' => 'control-label'))}}
					{{Form::select('bank', $banks, $bankId,array('class' => ''), array('class' => ''))}}
				</div> 
				<div class="form-group inline-form">
					{{Form::label('from',null, array('class' => 'control-label'))}} 
					{{Form::input('date', 'date_from', $date_from,array('class' => ''))}}
				</div>   
				<div class="form-group inline-form">
					{{Form::label('to',null, array('class' => 'control-label'))}} 
					{{Form::input('date', 'date_to', $date_to,array('class' => ''))}}
				</div>   
				<div class="form-group inline-form">
					{{Form::label('cheque_num',null, array('class' => 'control-label'))}} 
					{{Form::input('text', 'cheque_num', $cheque_num,array('class' => ''))}}
				</div>  
				<div class="form-group inline-form">
					{{Form::submit('Submit',array('class' => 'btn btn-primary pull-right'))}}
				</div>
				{{Form::close()}} 
			</div>
		</div>
		<table class="table table-striped">
			<tr>
				<th>Cheque num</th>
				<th>Inv. Date</th>
				<th>Invoice num</th>
				<th>Sales rep</th>
				<th>Customer</th>
				<th>Bank</th>
				<th>Payable on</th>
				<th>Amount</th> 
			</tr>  
			@foreach($cheques as $cheque)
			<tr>
				<td>{{$cheque->cheque_number}}</td>
				<td>{{$cheque->issued_date}}</td>
				<td>{{$cheque-> financeTransfer-> getSellingInvoice ()['id']}}</td>
				<td>{{$cheque-> financeTransfer-> getSellingInvoice ()['rep']['username']}}</td>
				<td>{{$cheque-> financeTransfer-> getSellingInvoice ()['customer']['name']}}</td>
				<td>{{$cheque->bank->name}}</td>
				<td>{{$cheque->payable_date}}</td>
				<td align="right">{{number_format($cheque-> financeTransfer->amount, 2)}}</td> 
			</tr>
			@endforeach

		</table>
	</div>
</div>

@stop

@section('file-footer') 
<script src="/js/reports/cheques/home.js"></script>
<script>
loadCustomers("{{URL::action('entities.customers.ajax.forRouteId')}}", "{{csrf_token()}}");
</script>
@stop
