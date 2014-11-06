function populateCustomersForRoute(csrfToken)
{
	$(document).on('change', '#route_id', function () {
		routeId = SanmarkJsHelper.Input.get('#route_id');
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
		} else if (property === "cheque_issued_date") {
			return date;
		}
		return null;
	}

	$(document).on('change', '#customer_id', function () {

		$('#creditPayments').html('');
		var customerId = SanmarkJsHelper.Input.get(this);
		$.post("/entities/customers/ajax/creditInvoices", {
			_token: csrfToken,
			customerId: customerId
		}, function (data) {

			if (data.length > 0) {
				var formGroupTitle = $('<div></div>');
				formGroupTitle
					.attr('class', 'row');
				formGroupTitle.append(
					$('<div></div>')
					.attr('class', 'col -sm-10 col-sm-offset-2')
					.attr('style', 'padding-left: 0; border-top: 1px solid #000; margin-bottom: 20px;')
					.append($('<h4></h4>')
						.attr('class', 'mainTitle')
						.attr('style', 'font-weight: bold;')
						.append('Late Credit Payments')
						)
					);
				$('#creditPayments').append(formGroupTitle);
			}

			$.each(data, function (index, sellingInvoice) {
				var newDiv = $('<div></div>');

				newDiv
					.attr('style', 'padding-bottom: 15px;');

				var formGroupOne = $('<div></div>');
				var formGroupTwo = $('<div></div>');
				var formGroupThree = $('<div></div>');

				formGroupOne
					.attr('class', 'form-group');
				formGroupTwo
					.attr('class', 'form-group');
				formGroupThree
					.attr('class', 'form-group');


				var formGroupOne_Seven = $('<div></div>')
					.attr('class', 'col-sm-7 col-sm-offset-2')
					.append(
						$('<div></div>')
						.attr('class', 'row bgcolor')
						.attr('style', 'padding-top: 15px; border-top-left-radius: 4px;')
						.append(
							$('<div></div>')
							.attr('class', 'col-sm-3 text-right')
							.append('Invoice Number :')
							)
						.append(
							$('<div></div>')
							.attr('class', 'col-sm-2')
							.append(sellingInvoice.id)
							)
						.append(
							$('<div></div>')
							.attr('class', 'col-sm-2 text-right')
							.append('Date :')
							)
						.append(
							$('<div></div>')
							.attr('class', 'col-sm-3')
							.append(sellingInvoice.date_time)
							)
						);

				var formGroupOne_Three = $('<div></div>')
					.attr('class', 'col-sm-3')
					.append(
						$('<div></div>')
						.attr('class', 'row bgcolor')
						.attr('style', 'padding-top: 15px; border-top-right-radius: 4px;')
						.append(
							$('<div></div>')
							.attr('class', 'col-sm-6 text-right')
							.append($('<div></div>')
								.append('Credit Amount :')
								)
							)
						.append(
							$('<div></div>')
							.attr('class', 'col-sm-6')
							.append($('<div></div>')
								.append(sellingInvoice.balance)
								)
							)
						);


				var formGroupTwo_Seven = $('<div></div>')
					.attr('class', 'col-sm-7 col-sm-offset-2')
					.append(
						$('<div></div>')
						.attr('class', 'row bgcolor')
						.attr('style', 'padding-bottom: 2px;')
						.append(
							$('<div></div>')
							.attr('class', 'col-sm-3')
							.attr('style', 'padding-top: 37px;')
							.append('Bank')
							)
						.append(
							$('<div></div>')
							.attr('class', 'col-sm-3')
							.attr('style', 'padding-top: 37px;')
							.append('Cheque Number')
							)
						.append(
							$('<div></div>')
							.attr('class', 'col-sm-3')
							.attr('style', 'padding-top: 37px;')
							.append('Issued Date')
							)
						.append(
							$('<div></div>')
							.attr('class', 'col-sm-3')
							.attr('style', 'padding-top: 37px;')
							.append('Payable Date')
							)
						);


				var formGroupTwo_Three = $('<div></div>')
					.attr('class', 'col-sm-3')
					.append(
						$('<div></div>')
						.attr('class', 'row bgcolor')
						.attr('style', 'padding-top: 2px;')
						.append(
							$('<div></div>')
							.attr('class', 'col-sm-6 text-right')
							.append($('<label></label>')
								.attr('class', 'control-label')
								.attr('for', 'credit_payments[' + sellingInvoice.id + '][cash_amount]')
								.text('Cash Amount')
								)
							)
						.append(
							$('<div></div>')
							.attr('class', 'col-sm-6')
							.append($('<input/>')
								.attr('type', 'number')
								.attr('class', 'form-control text-right')
								.attr('id', 'credit_payments[' + sellingInvoice.id + '][cash_amount]')
								.attr('name', 'credit_payments[' + sellingInvoice.id + '][cash_amount]')
								.attr('value', getOldCreditPaymentDetails(sellingInvoice.id, 'cash_amount'))
								)
							)
						);


				var formGroupThree_Seven = $('<div></div>')
					.attr('class', 'col-sm-7 col-sm-offset-2')
					.append(
						$('<div></div>')
						.attr('class', 'row bgcolor')
						.attr('style', 'border-bottom-left-radius: 4px;')
						.append(
							$('<div></div>')
							.attr('class', 'col-sm-3')
							.append($(bankSelect)
								.attr('class', 'form-control')
								.attr('id', 'credit_payments[' + sellingInvoice.id + '][cheque_bank_id]')
								.attr('name', 'credit_payments[' + sellingInvoice.id + '][cheque_bank_id]')
								.val(getOldCreditPaymentDetails(sellingInvoice.id, 'cheque_bank_id'))
								)
							)

						.append(
							$('<div></div>')
							.attr('class', 'col-sm-3')
							.append($('<input/>')
								.attr('type', 'text')
								.attr('class', 'form-control')
								.attr('id', 'credit_payments[' + sellingInvoice.id + '][cheque_number]')
								.attr('name', 'credit_payments[' + sellingInvoice.id + '][cheque_number]')
								.attr('value', getOldCreditPaymentDetails(sellingInvoice.id, 'cheque_number'))
								)
							)

						.append(
							$('<div></div>')
							.attr('class', 'col-sm-3')
							.append($('<input/>')
								.attr('type', 'date')
								.attr('class', 'form-control')
								.attr('id', 'credit_payments[' + sellingInvoice.id + '][cheque_issued_date]')
								.attr('name', 'credit_payments[' + sellingInvoice.id + '][cheque_issued_date]')
								.attr('value', getOldCreditPaymentDetails(sellingInvoice.id, 'cheque_issued_date'))
								)
							)

						.append(
							$('<div></div>')
							.attr('class', 'col-sm-3')
							.append($('<input/>')
								.attr('type', 'date')
								.attr('class', 'form-control')
								.attr('id', 'credit_payments[' + sellingInvoice.id + '][cheque_payable_date]')
								.attr('name', 'credit_payments[' + sellingInvoice.id + '][cheque_payable_date]')
								.attr('value', getOldCreditPaymentDetails(sellingInvoice.id, 'cheque_payable_date'))
								)
							)

						);


				var formGroupThree_Three = $('<div></div>')
					.attr('class', 'col-sm-3')
					.append(
						$('<div></div>')
						.attr('class', 'row bgcolor')
						.attr('style', 'border-bottom-right-radius: 4px;')
						.append(
							$('<div></div>')
							.attr('class', 'col-sm-6 text-right')
							.append($('<label></label>')
								.attr('class', 'control-label')
								.attr('for', 'credit_payments[' + sellingInvoice.id + '][cheque_amount]')
								.text('Cheque Amount')
								)
							)

						.append(
							$('<div></div>')
							.attr('class', 'col-sm-6')
							.append($('<input/>')
								.attr('type', 'number')
								.attr('class', 'form-control late_cheque_payment text-right')
								.attr('id', 'credit_payments[' + sellingInvoice.id + '][cheque_amount]')
								.attr('name', 'credit_payments[' + sellingInvoice.id + '][cheque_amount]')
								.attr('value', getOldCreditPaymentDetails(sellingInvoice.id, 'cheque_amount'))
								.attr('data-selling-invoice-id', sellingInvoice.id)
								)
							)
						);


				formGroupOne.append(formGroupOne_Seven);
				formGroupOne.append(formGroupOne_Three);
				formGroupTwo.append(formGroupTwo_Seven);
				formGroupTwo.append(formGroupTwo_Three);
				formGroupThree.append(formGroupThree_Seven);
				formGroupThree.append(formGroupThree_Three);

				newDiv.append(formGroupOne);
				newDiv.append(formGroupTwo);
				newDiv.append(formGroupThree);

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
		var price = SanmarkJsHelper.Input.get("input[name='items[" + itemId + "][price]']");
		var paid_quantity = SanmarkJsHelper.Input.get("input[name='items[" + itemId + "][paid_quantity]']");
		var lineTotal = (price * paid_quantity);
		$("input[name='items[" + itemId + "][line_total]']").val(lineTotal.toFixed(2));
	});
}

function displaySubTotal()
{
	$(document).on('change keyup', '.saleDetail', function () {
		var subTotal = null;
		$('.lineTotal').each(function () {
			var value = parseFloat($(this).val());
			if (!isNaN(value)) {
				subTotal += value;
			}
		});
		$("input[name='subTotal']").val(subTotal.toFixed(2));
	});
}

function displayTotal()
{
	$(document).on('change keyup', '.saleDetail', function () {
		var net = SanmarkJsHelper.Input.get('#subTotal');
		var disc = SanmarkJsHelper.Input.get('#discount');
		if (disc == '.')
		{
			disc = 0;
		}
		$('#total').val((net - disc ? net - disc : 0).toFixed(2));
	});
}

function displayBalance()
{
	$(document).on('change keyup', '.saleDetail', function () {
		var sub = SanmarkJsHelper.Input.get('#total');
		var cash = SanmarkJsHelper.Input.get('#cash_payment');
		var cheque = SanmarkJsHelper.Input.get('#cheque_payment');
		$('#balance').val((sub - cash - cheque ? sub - cash - cheque : 0).toFixed(2));
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
		var freeQuantity = SanmarkJsHelper.Input.get('[name="items[' + i + '][free_quantity]"]');
		var availableQuantity = SanmarkJsHelper.Input.get('[name="items[' + i + '][available_quantity]"]');
		var paidQuantity = SanmarkJsHelper.Input.get('[name="items[' + i + '][paid_quantity]"]');

		var sumOfFreeAndPaid = Number(paidQuantity) + Number(freeQuantity);
		var input = document.getElementsByName('items[' + i + '][free_quantity]')[0];
		if (sumOfFreeAndPaid > availableQuantity)
		{
			input.setCustomValidity("Sum of free and paid is higher than " + availableQuantity + "");
			trueFalse.push("1");
		}
		else
		{
			input.setCustomValidity('');
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

function validateChequeDetails()
{
	$(document).on('change keyup', '#cheque_payment', function () {
		var cheqPay = $('#cheque_payment').val();

		if (cheqPay.length > 0) {
			$("#cheque_payment_bank_id").prop('required', true);
			$("#cheque_payment_cheque_number").prop('required', true);
			$("#cheque_payment_issued_date").prop('required', true);
			$("#cheque_payment_payable_date").prop('required', true);
		} else {
			$("#cheque_payment_bank_id").prop('required', false);
			$("#cheque_payment_cheque_number").prop('required', false);
			$("#cheque_payment_issued_date").prop('required', false);
			$("#cheque_payment_payable_date").prop('required', false);
		}
	});
}

function addReturnRow() {


	$(document).on("keypress", 'form', function (e) {
		var code = e.keyCode || e.which;
		if (code == 13) {
			jQuery('#add-new-return').click();
			e.preventDefault();
			return false;
		}

	});

	$('#txtReturnItemCode').keyup(function () {
		$('#txtReturnItemName').val("");
		$('#txtGoodReturnPrice').val("");
		$('#txtCompanyReturnPrice').val("");
		$('#txtreturnId').val("");
		$("#txtGRQ").val("");
		$("#txtCRQ").val("");
		$("#txtreturnLineTot").val("");
	});

	$('#add-new-return').click(function (event) {

		event.preventDefault();

		var txtReturnItemCode = $('#txtReturnItemCode').val();
		var txtReturnItemName = $('#txtReturnItemName').val();
		var txtGoodReturnPrice = $('#txtGoodReturnPrice').val();
		var txtCompanyReturnPrice = $('#txtCompanyReturnPrice').val();
		var txtGRQ = $('#txtGRQ').val();
		var txtCRQ = $('#txtCRQ').val();
		var txtreturnLineTot = $('#txtreturnLineTot').val();
		var txtreturnId = $('#txtreturnId').val();

		var validationVal = {
			"txtReturnItemCode": txtReturnItemCode,
			"txtReturnItemName": txtReturnItemName,
			"txtGoodReturnPrice": txtGoodReturnPrice,
			"txtCompanyReturnPrice": txtCompanyReturnPrice,
			"txtGRQ": txtGRQ,
			"txtCRQ": txtCRQ,
			"txtreturnLineTot": txtreturnLineTot,
			"txtreturnId": txtreturnId

		};
		var result = validateaddReturnRow(validationVal);

		if (result) {

			if (!txtGRQ) {
				txtGRQ = 0;
			}
			else if (!txtCRQ) {
				txtCRQ = 0;
			}

			var htmlOutput = '';
			htmlOutput += '<div id="return-item-row_' + txtreturnId + '" class="row" style="background-color: #ECECEC; padding: 5px 0; border-radius: 4px 0 0 4px;">';

			htmlOutput += '<div class="col-sm-6">';
			htmlOutput += '<div class="row">';
			htmlOutput += '<div class="col-sm-3">' + txtReturnItemCode + '</div>';
			htmlOutput += '<div class="col-sm-3">' + txtReturnItemName + '</div>';
			htmlOutput += '<div class="col-sm-3 text-right" ">' + txtGoodReturnPrice + '</div>';
			htmlOutput += '<div class="col-sm-3 text-right" >' + txtGRQ + '</div>';
			htmlOutput += '</div>';
			htmlOutput += '</div>';

			htmlOutput += '<div class="col-sm-6" >';
			htmlOutput += '<div class="row">';
			htmlOutput += '<div class="col-sm-3 text-right">' + txtCompanyReturnPrice + '</div>';
			htmlOutput += '<div class="col-sm-3 text-right">' + txtCRQ + '</div>';
			htmlOutput += '<div class="col-sm-3 text-right" >' + txtreturnLineTot + '</div>';
			htmlOutput += '<div class="col-sm-3 text-right" >';
			htmlOutput += '<a class="edit-return" id=' + txtreturnId + '>Edit</a> /';
			htmlOutput += '<a  class="delete-return"  id=' + txtreturnId + '>Delete</a>';
			htmlOutput += '</div>';
			htmlOutput += '</div>';
			htmlOutput += '</div>';
 

			htmlOutput += '<input type="hidden" id="item_code_' + txtreturnId + '" value="' + txtReturnItemCode + '">';
			htmlOutput += '<input type="hidden" id="item_name_' + txtreturnId + '" value="' + txtReturnItemName + '">';
			htmlOutput += '<input type="hidden" id="good_return_price_' + txtreturnId + '"  value="' + txtGoodReturnPrice + '" name="items[' + txtreturnId + '][good_return_price]">';

			htmlOutput += '<input type="hidden" id="good_return_quantity' + txtreturnId + '"  value="' + txtGRQ + '"  name="items[' + txtreturnId + '][good_return_quantity]">';

			htmlOutput += '<input type="hidden" id="company_return_price_' + txtreturnId + '"  value="' + txtCompanyReturnPrice + '"  name="items[' + txtreturnId + '][company_return_price]">';

			htmlOutput += '<input type="hidden"  id="company_return_quantity' + txtreturnId + '"  value="' + txtCRQ + '"  name="items[' + txtreturnId + '][company_return_quantity]">';

			htmlOutput += '<input type="hidden" id="line_total' + txtreturnId + '" value="' + txtreturnLineTot + '">';

			htmlOutput += '</div>';

			$('#table-return-list').append(htmlOutput);


			var subTotal = $('#subTotal').val();
			var newSubTotal = subTotal - txtreturnLineTot;
			$('#subTotal').val(newSubTotal);

			$('#txtReturnItemCode').val("");
			$('#txtReturnItemName').val("");
			$('#txtGoodReturnPrice').val("");
			$('#txtCompanyReturnPrice').val("");
			$('#txtreturnId').val("");
			$("#txtGRQ").val("");
			$("#txtCRQ").val("");
			$("#txtreturnLineTot").val("");
			$("#txtReturnItemCode").focus();

			$('#add-new-return').text("Add");
			$("#txtReturnItemCode").removeAttr("readonly");
			$("#txtReturnItemName").removeAttr("readonly");

		}
	}
	);

	$(document).on('change keyup', '.cal_return_line_tot', function () {
		calculateReturnLineTotal();
	});
}

function selectReturnItem(csrfToken) {

	$(window).keydown(function (event) {

		var valPreventDefault = false;

		if (event.keyCode === 13) {

			if (event.target.id === 'txtReturnItemCode') {

				valPreventDefault = true;

				var txtReturnItemCode = $('#txtReturnItemCode').val();

				$.post(
					"/entities/items/ajax/getItemByCode", {
						_token: csrfToken,
						itemCode: txtReturnItemCode
					},
				function (data)
				{
					if (data.length !== 0)
					{
						$("input").removeClass("duplicate-error");
						$("div").removeClass("duplicate-error");

						$('#txtReturnItemCode').val(data[0].code);
						$('#txtReturnItemName').val(data[0].name);
						$('#txtGoodReturnPrice').val(data[0].current_selling_price);
						$('#txtCompanyReturnPrice').val(data[0].current_selling_price);
						$('#txtreturnId').val(data[0].id);
						calculateReturnLineTotal();
						$("#txtGRQ").focus();
					}
					else
					{
						$("#txtReturnItemCode").addClass('duplicate-error');
						$('#txtReturnItemName').val("");
						$('#txtGoodReturnPrice').val("");
						$('#txtCompanyReturnPrice').val("");
						$('#txtreturnId').val("");
						$("#txtGRQ").val("");
						$("#txtCRQ").val("");
						$("#txtreturnLineTot").val("");
						$("#txtReturnItemCode").select();

					}

				});

			} else if (event.target.id === 'txtReturnItemName') {

				valPreventDefault = true;
			}
			if (valPreventDefault) {
				event.preventDefault();
				return false;
			}

		}
	});

}

function calculateReturnLineTotal() {

	$("input").removeClass("duplicate-error");
	$("div").removeClass("duplicate-error");

	var txtGoodReturnPrice = $('#txtGoodReturnPrice').val();
	var txtCompanyReturnPrice = $('#txtCompanyReturnPrice').val();
	var txtGRQ = $('#txtGRQ').val();
	var txtCRQ = $('#txtCRQ').val();

	var lineTotal = (txtGoodReturnPrice * txtGRQ) + (txtCompanyReturnPrice * txtCRQ);

	$("#txtreturnLineTot").val(lineTotal);

}

function validateaddReturnRow(validationVal) {
	var status = true;

	$("input").removeClass("duplicate-error");
	$("div").removeClass("duplicate-error");
	$('#dublicate-error-message').empty();

	if (!validationVal.txtReturnItemCode.trim()) {
		$("#txtReturnItemCode").addClass('duplicate-error');
		status = false;
	}
	if (!validationVal.txtReturnItemName.trim()) {
		$("#txtReturnItemName").addClass('duplicate-error');
		status = false;
	}
	if (!validationVal.txtGoodReturnPrice.trim()) {
		$("#txtGoodReturnPrice").addClass('duplicate-error');
		status = false;
	}
	if (!validationVal.txtCompanyReturnPrice.trim()) {
		$("#txtCompanyReturnPrice").addClass('duplicate-error');
		status = false;
	}
	if (!validationVal.txtGRQ.trim() && !validationVal.txtCRQ.trim()) {
		$("#txtGRQ").addClass('duplicate-error');
		$("#txtCRQ").addClass('duplicate-error');
		status = false;
	}
	if (validationVal.txtGRQ <= '0' && validationVal.txtCRQ <= '0') {
		$("#txtGRQ").addClass('duplicate-error');
		$("#txtCRQ").addClass('duplicate-error');
		status = false;
	}
	if (validationVal.txtreturnLineTot === 0 && !validationVal.txtreturnLineTot.trim()) {
		$("#txtreturnLineTot").addClass('duplicate-error');
		status = false;
	}
	if (!validationVal.txtreturnId.trim()) {
		$("#txtReturnItemCode").addClass('duplicate-error');
		$("#txtReturnItemName").addClass('duplicate-error');
		$("#txtGoodReturnPrice").addClass('duplicate-error');
		$("#txtCompanyReturnPrice").addClass('duplicate-error');
		$("#txtGRQ").addClass('duplicate-error');
		$("#txtCRQ").addClass('duplicate-error');
		$("#txtreturnLineTot").addClass('duplicate-error');
		status = false;
	}

	if ($('#item_code_' + validationVal.txtreturnId).length !== 0) {

		var html_message = "";
		html_message += "<div id='return-exit-message'>";
		html_message += validationVal.txtReturnItemName + " is already exists in the list";
		html_message += "</div>";

		$('#dublicate-error-message').append(html_message);

		$('#return-item-row_' + validationVal.txtreturnId).addClass('duplicate-error');

		$('#txtreturnId').val("");

		status = false;
	}

	return status;
}

function editReturn() {
	$(document).on('click', '.edit-return', function () {
		var itemId = this.id;

		$("input").removeClass("duplicate-error");
		$("div").removeClass("duplicate-error");

		if ($('#txtreturnId').val()) {
			$("#txtReturnItemCode").addClass('duplicate-error');
			$("#txtReturnItemName").addClass('duplicate-error');
			$("#txtGoodReturnPrice").addClass('duplicate-error');
			$("#txtCompanyReturnPrice").addClass('duplicate-error');
			$("#txtGRQ").addClass('duplicate-error');
			$("#txtCRQ").addClass('duplicate-error');
			$("#txtreturnLineTot").addClass('duplicate-error');
			$('html, body').animate({
				scrollTop: $("#scrollTop").offset().top
			}, 700);
			return false;
		}


		$('#txtReturnItemCode').val($('#item_code_' + itemId).val());
		$('#txtReturnItemName').val($('#item_name_' + itemId).val());
		$('#txtGoodReturnPrice').val($('#good_return_price_' + itemId).val());
		$('#txtCompanyReturnPrice').val($('#company_return_price_' + itemId).val());

		$('#txtGRQ').val($('#good_return_quantity' + itemId).val());
		$('#txtCRQ').val($('#company_return_quantity' + itemId).val());
		$('#txtreturnLineTot').val($('#line_total' + itemId).val());
		$('#txtreturnId').val(itemId);

		$("#txtReturnItemCode").attr("readonly", "TRUE");
		$("#txtReturnItemName").attr("readonly", "TRUE");
		$("#return-item-row_" + itemId).remove();
		$('#add-new-return').text("Update");

		$('html, body').animate({
			scrollTop: $("#scrollTop").offset().top
		}, 700);

	});
}

function deleteReturn() {

	$(document).on('click', '.delete-return', function () {

		$("#return-item-row_" + this.id).remove();

	});
}
