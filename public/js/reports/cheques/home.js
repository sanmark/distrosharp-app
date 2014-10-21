function loadCustomers(url, token){

 $(document).on('change', '#route', function() {
		var routeId = $(this).val();
		$('#customer').find('option').remove();
		$('#customer').append(
				$('<option value=""></option>')
				.text('All Customers')
				);
		$.post(
				url,
				{
					_token: token,
					routeId: routeId
				},
		function(data) {
			$.each(data, function(index, customer) {
				$('#customer').append(
						$('<option value=""></option>')
						.attr('value', customer.id)
						.text(customer.name)
						);
			});
		}
		);
	});
	
	$(document).ready(function() {
		$('#route').change();
	});
}