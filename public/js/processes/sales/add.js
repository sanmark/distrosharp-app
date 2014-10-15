function populateCustomersForRoute(csrfToken)
{
	$(document).on('change', '#route_id', function () {
		routeId = $('#route_id').val();
		$('#customer_id').find('option').remove();
		$('#customer_id').append(
			$('<option value=""></option>').text('Select')
			);
		$.post(
			"/entities/customers/ajax/forRouteId", {
				_token: csrfToken,
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

function loadCreditInvoicesForCustomer(csrfToken, oldCreditPayments, date, bankSelect)
{
	function getOldCreditPaymentDetails(index, property) {
		if (oldCreditPayments) {
			if (oldCreditPayments[index]) {
				if (oldCreditPayments[index][property]) {
					return oldCreditPayments[index][property];
				}
			}
		} else if (property == "cheque_issued_date") {
			return date;
		}
		return null;
	}

	$(document).on('change', '#customer_id', function () {

		$('#creditPayments').html('');
		var customerId = $(this).val();
		$.post("/entities/customers/ajax/creditInvoices", {
			_token: csrfToken,
			customerId: customerId
		}, function (data) {
			$.each(data, function (index, sellingInvoice) {
				var newDiv = $('<div></div>');

				newDiv.append(
					$('<div></div>')
					.append(sellingInvoice.id)
					);

				newDiv.append(
					$('<div></div>')
					.append(sellingInvoice.date_time)
					);

				newDiv.append(
					$('<div></div>')
					.append(sellingInvoice.balance)
					);

				newDiv.append(
					$('<div></div>')
					.append(
						$('<label></label>')
						.attr('for', 'credit_payments[' + sellingInvoice.id + '][cash_amount]')
						.text('Cash Amount')
						)
					.append(
						$('<input/>')
						.attr('type', 'number')
						.attr('id', 'credit_payments[' + sellingInvoice.id + '][cash_amount]')
						.attr('name', 'credit_payments[' + sellingInvoice.id + '][cash_amount]')
						.attr('value', getOldCreditPaymentDetails(sellingInvoice.id, 'cash_amount'))
						)
					);

				newDiv.append(
					$('<div></div>')
					.append(
						$('<label></label>')
						.attr('for', 'credit_payments[' + sellingInvoice.id + '][cheque_amount]')
						.text('Cheque Amount')
						)
					.append(
						$('<input/>')
						.attr('type', 'number')
						.attr('id', 'credit_payments[' + sellingInvoice.id + '][cheque_amount]')
						.attr('name', 'credit_payments[' + sellingInvoice.id + '][cheque_amount]')
						.attr('value', getOldCreditPaymentDetails(sellingInvoice.id, 'cheque_amount'))
						)
					.append(
						$(bankSelect)
						.attr('id', 'credit_payments[' + sellingInvoice.id + '][cheque_bank_id]')
						.attr('name', 'credit_payments[' + sellingInvoice.id + '][cheque_bank_id]')
						.val(getOldCreditPaymentDetails(sellingInvoice.id, 'cheque_bank_id'))
						)
					.append(
						$('<input/>')
						.attr('type', 'text')
						.attr('id', 'credit_payments[' + sellingInvoice.id + '][cheque_number]')
						.attr('name', 'credit_payments[' + sellingInvoice.id + '][cheque_number]')
						.attr('value', getOldCreditPaymentDetails(sellingInvoice.id, 'cheque_number'))
						)
					.append(
						$('<input/>')
						.attr('type', 'date')
						.attr('id', 'credit_payments[' + sellingInvoice.id + '][cheque_issued_date]')
						.attr('name', 'credit_payments[' + sellingInvoice.id + '][cheque_issued_date]')
						.attr('value', getOldCreditPaymentDetails(sellingInvoice.id, 'cheque_issued_date'))
						)
					.append(
						$('<input/>')
						.attr('type', 'date')
						.attr('id', 'credit_payments[' + sellingInvoice.id + '][cheque_payable_date]')
						.attr('name', 'credit_payments[' + sellingInvoice.id + '][cheque_payable_date]')
						.attr('value', getOldCreditPaymentDetails(sellingInvoice.id, 'cheque_payable_date'))
						)
					);

				$('#creditPayments').append(newDiv);
			});
		}
		);
	});
}

function loadPreviousValuesOnUnsuccessfulRedirectBack(oldCustomerId)
{
	$(document).ready(function () {
		$('#route_id').change();
		setTimeout(function () {
			$("#customer_id").val(oldCustomerId);
			$("#customer_id").change();
		}, 2000);
	});
}

function calculateLineTotal()
{
	$(document).on('change keyup', '.saleDetail', function () {
		var itemId = $(this).attr('data-item-id');
		var price = $("input[name='items[" + itemId + "][price]']").val();
		var paid_quantity = $("input[name='items[" + itemId + "][paid_quantity]']").val();
		var good_return_price = $("input[name='items[" + itemId + "][good_return_price]']").val();
		var good_return_quantity = $("input[name='items[" + itemId + "][good_return_quantity]']").val();
		var company_return_price = $("input[name='items[" + itemId + "][company_return_price]']").val();
		var company_return_quantity = $("input[name='items[" + itemId + "][company_return_quantity]']").val();
		var lineTotal = (price * paid_quantity) - ((good_return_price * good_return_quantity) + (company_return_price * company_return_quantity));
		$("input[name='items[" + itemId + "][line_total]']").val(lineTotal);
	});
}

function displayNetTotal()
{
	$(document).on('change keyup', '.saleDetail', function () {
		var netTotal = null;
		$('.lineTotal').each(function () {
			var value = parseFloat($(this).val());
			if (!isNaN(value)) {
				netTotal += value;
			}
		});
		$("input[name='netTotal']").val(netTotal);
	});
}

function displaySubTotal()
{
	$(document).on('change keyup', '.saleDetail', function () {
		var net = $('#netTotal').val();
		var disc = $('#discount').val();
		$('#subTotal').val((net - disc ? net - disc : 0));
	});
}

function displayBalance()
{
	$(document).on('change keyup', '.saleDetail', function () {
		var sub = $('#subTotal').val();
		var cash = $('#cash_payment').val();
		var cheque = $('#cheque_payment').val();
		$('#balance').val((sub - cash - cheque ? sub - cash - cheque : 0));
	});
}

function displayIsCompletelyPaid()
{
	$(document).on('change keyup', '.saleDetail', function () {
		var balance = $('#balance').val();
		if (balance <= 0) {
			$('.myCheckbox').attr('checked', 'checked');
		} else {
			$('.myCheckbox').removeAttr('checked');
		}
	});
}

function checkValidit(input)
{
	if ($('#cheque_payment').val().length !== 0) {


		if ($('#cheque_payment_bank_id').val().length === 0) {
			document.getElementById('cheque_payment_bank_id').setCustomValidity("Select the bank.");
		} else {
			document.getElementById('cheque_payment_bank_id').setCustomValidity("");
		}

		if ($('#cheque_payment_cheque_number').val().length === 0) {
			document.getElementById('cheque_payment_cheque_number').setCustomValidity("Ente cheque number.");
		} else {
			document.getElementById('cheque_payment_cheque_number').setCustomValidity("");
		}

		if ($('#cheque_payment_issued_date').val().length === 0) {
			document.getElementById('cheque_payment_issued_date').setCustomValidity("Select the issue date.");
		} else {
			document.getElementById('cheque_payment_issued_date').setCustomValidity("");
		}

		if ($('#cheque_payment_payable_date').val().length === 0) {
			document.getElementById('cheque_payment_payable_date').setCustomValidity("Select the payable date");
		} else {
			document.getElementById('cheque_payment_payable_date').setCustomValidity("");
		}
	} else {
		return;
	}
}
function checkPaidAndFreeSum()
{
	var itemAmount = document.getElementsByName('item_list_amount')[0].value;
	var trueFalse = [];
	for (var i = 1; i <= itemAmount; i++)
	{
		var freeQuantity = document.getElementsByName('items[' + i + '][free_quantity]')[0].value;
		var availableQuantity = document.getElementsByName('items[' + i + '][available_quantity]')[0].value;
		var paidQuantity = document.getElementsByName('items[' + i + '][paid_quantity]')[0].value;
		var sumOfFreeAndPaid = Number(paidQuantity) + Number(freeQuantity);
		var input = document.getElementsByName('items[' + i + '][free_quantity]')[0];
		if (sumOfFreeAndPaid > availableQuantity)
		{
			input.setCustomValidity("Sum of free and paid is higher than " + availableQuantity + "");
			trueFalse.push("1");
		}
		else
		{
			input.setCustomValidity("");
			trueFalse.push("0");
		}
	}
	var resultArray = trueFalse.indexOf("1");
	if (resultArray !== (-1))
	{
		return true;
	}
	else if (resultArray == (-1))
	{
		return false;
	}
}