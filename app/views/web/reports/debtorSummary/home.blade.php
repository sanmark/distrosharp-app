@extends('web._templates.template')

@section('body')
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Debtor Summary Report</h3>
	</div>
	<div class="panel-body">
		<div class="panel panel-default">
			{{Form::open(['class'=>'form-inline', 'role'=>'form'])}}
			<br/>
			<div class="form-group inline-form">
				{{Form::label('from_date',null,array('class' => 'control-label'))}}
				{{Form::input('date', 'from_date', $fromDate,array('class' => 'form-control'))}}
			</div>
			<div class="form-group inline-form">
				{{Form::label('to_date',null,array('class' => 'control-label'))}}
				{{Form::input('date', 'to_date', $toDate,array('class' => 'form-control'))}}
			</div>
			<div class="form-group inline-form">
				{{Form::label('route_id','Route',array('class' => 'control-label'))}}
				{{Form::select('route_id', $routes,null,array('class' => 'form-control'))}}
			</div>
			<div class="form-group inline-form">
				{{Form::label('customer_id','Customer',array('class' => 'control-label'))}}
				{{Form::select('customer_id', $customers,null,array('class' => 'form-control'))}}
			</div>
			<div class="form-group inline-form">
				{{Form::submit('Submit',array('class' => 'btn btn-default pull-right'))}}
			</div>
			{{Form::close()}}
			<br/>
		</div>
	</div>
</div>
@stop

@section('file-footer')
<script>
	$(document).on('change', '#route_id', function() {
		var routeId = $(this).val();
		$('#customer_id').find('option').remove();
		$('#customer_id').append(
				$('<option></option>')
				.text('Select')
				);
		$.post(
				"{{URL::action('entities.customers.ajax.forRouteId')}}",
				{
					_token: "{{csrf_token()}}",
					routeId: routeId
				},
		function(data) {
			$.each(data, function(index, customer) {
				$('#customer_id').append(
						$('<option></option>')
						.attr('value', customer.id)
						.text(customer.name)
						);
			});
		}
		);
	});</script>

<script>
	$(document).ready(function() {
		$('#route_id').change();
	});
</script>
@stop