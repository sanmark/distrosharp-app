@extends('web._templates.template')

@section('body')
<h2>Debtor Summary Report</h2>
{{Form::open()}}
<table>
	<tr>
		<td>{{Form::label('from_date')}}</td>
		<td>{{Form::input('date', 'from_date', $fromDate)}}</td>
	</tr>
	<tr>
		<td>{{Form::label('to_date')}}</td>
		<td>{{Form::input('date', 'to_date', $toDate)}}</td>
	</tr>
	<tr>
		<td>{{Form::label('route_id')}}</td>
		<td>{{Form::select('route_id', $routes)}}</td>
	</tr>
	<tr>
		<td>{{Form::label('customer_id')}}</td>
		<td>{{Form::select('customer_id', $customers)}}</td>
	</tr>
	<tr>
		<td colspan="2">
			{{Form::submit()}}
		</td>
	</tr>
</table>
{{Form::close()}}
@stop

@section('file-footer')
<script>
	$(document).on('change', '#route_id', function () {
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
		function (data) {
			$.each(data, function (index, customer) {
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
	$(document).ready(function () {
		$('#route_id').change();
	});
</script>
@stop