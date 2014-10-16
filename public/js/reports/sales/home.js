function setOldCustomerId(oldCustomerId) {
	$(document).ready(function () {
		$('#route_id').change();
		setTimeout(function () {
			$("#customer_id").val(oldCustomerId);
		}, 2000);
	});
}

function loadCustomersForRout(url, token) { 
	$(document).on('change', '#route_id', function () {
		routeId = $('#route_id').val();
		$('#customer_id').find('option').remove();
		$('#customer_id').append(
			$('<option value=""></option>').
			text('All Customers')
			);
		$.post(
			url,
			{
				_token: token,
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
	});
}