function autoloadItemForReturn(csrfToken) {

	$("body").click(function () {
		$('#item_list_f_return').empty();
	});

	$('#txtReturnItemName').keyup(function (event) {

		$('#txtReturnItemCode').val("");
		$('#txtGoodReturnPrice').val("");
		$('#txtCompanyReturnPrice').val("");
		$('#txtreturnId').val("");
		$("#txtGRQ").val("");
		$("#txtCRQ").val("");
		$("#txtreturnLineTot").val("");

		if (event.keyCode === 13) { // enter 
			select_item_return(csrfToken);
		}

		else if (event.keyCode === 38) { // up
			var selected_return = $(".selected_return");
			$("#item_list_f_return li").removeClass("selected_return");
			if (selected_return.prev().length === 0) {
				selected_return.siblings().last().addClass("selected_return");
			} else {
				selected_return.prev().addClass("selected_return");
			}
		}

		else if (event.keyCode === 40) { // down
			var selected_return = $(".selected_return");
			$("#item_list_f_return li").removeClass("selected_return");
			if (selected_return.next().length === 0) {
				selected_return.siblings().first().addClass("selected_return");
			} else {
				selected_return.next().addClass("selected_return");
			}
		}

		else {

			$('#loader-img-return').show();
			$('#item_list_f_return').empty();

			var txtReturnItemName = $('#txtReturnItemName').val();
			var itemList = '';

			if (!txtReturnItemName.trim()) {
				$('#loader-img-return').hide();
				$('#item_list_f_return').empty();
			}
			else {
				$.post(
					"/entities/items/ajax/getItemByName", {
						_token: csrfToken,
						itemName: txtReturnItemName
					},
				function (data)
				{
					if (data.length !== 0 && txtReturnItemName.trim())
					{
						$.each(data, function (key, value) {
							if (key === 0) {
								itemList += '<li id="' + value.id + '" class="item-li selected_return">' + value.name + '</li>';
							}
							else {
								itemList += '<li id="' + value.id + '" class="item-li">' + value.name + '</li>';
							}
						});

						$('#loader-img-return').hide();
					}
					else
					{
						itemList += '<li id="error-li" class="error">';
						itemList += 'Not Found';
						itemList += '</li>';
						$('#loader-img-return').hide();
					}

					$('#item_list_f_return').empty();
					$('#item_list_f_return').append(itemList);

				});
			}
		}
	});

	$("#item_list_f_return").on("mouseover", "#item_list_f_return li", function (event) {
		if ($(event.target).attr('class') !== 'error')
		{
			$("#item_list_f_return li").removeClass("selected_return");
			$(this).addClass("selected_return");
		}
	}).click(function () {
		select_item_return(csrfToken);
	});
}

function select_item_return(csrfToken) {

	var itemId = $(".selected_return").attr('id');
	$('#item_list_f_return').empty();

	$.post(
		"/entities/items/ajax/getItemById", {
			_token: csrfToken,
			itemId: itemId
		},
	function (data)
	{
		$('#txtReturnItemCode').val(data[0].code);
		$('#txtReturnItemName').val(data[0].name);
		$('#txtGoodReturnPrice').val(data[0].current_selling_price);
		$('#txtCompanyReturnPrice').val(data[0].current_selling_price);
		$('#txtreturnId').val(data[0].id);
		$("#txtGRQ").focus();
		calculateReturnLineTotal();

	});
}

function addReturnRow() {

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
			htmlOutput += '<div id="return_item_row_' + txtreturnId + '" class="row item-list-table" >';

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
			htmlOutput += '<a title="Click to edit ' + txtReturnItemName + ' " class="edit-return" id=' + txtreturnId + '> Edit </a> / ';
			htmlOutput += '<a title="Click to delete ' + txtReturnItemName + ' "   class="delete-return"  id=' + txtreturnId + '> Delete </a>';
			htmlOutput += '</div>';
			htmlOutput += '</div>';
			htmlOutput += '</div>';


			htmlOutput += '<input type="hidden" id="return_item_code_' + txtreturnId + '" value="' + txtReturnItemCode + '">';
			htmlOutput += '<input type="hidden" id="item_name_' + txtreturnId + '" value="' + txtReturnItemName + '">';
			htmlOutput += '<input type="hidden" id="good_return_price_' + txtreturnId + '"  value="' + txtGoodReturnPrice + '" name="items[' + txtreturnId + '][good_return_price]">';

			htmlOutput += '<input type="hidden" id="good_return_quantity' + txtreturnId + '"  value="' + txtGRQ + '"  name="items[' + txtreturnId + '][good_return_quantity]">';

			htmlOutput += '<input type="hidden" id="company_return_price_' + txtreturnId + '"  value="' + txtCompanyReturnPrice + '"  name="items[' + txtreturnId + '][company_return_price]">';

			htmlOutput += '<input type="hidden"  id="company_return_quantity' + txtreturnId + '"  value="' + txtCRQ + '"  name="items[' + txtreturnId + '][company_return_quantity]">';

			htmlOutput += '<input type="hidden" class="return_line_total" id="line_total' + txtreturnId + '" value="' + txtreturnLineTot + '">';

			htmlOutput += '</div>';

			$('#table-return-list').append(htmlOutput);

			$('#txtReturnItemCode').val("");
			$('#txtReturnItemName').val("");
			$('#txtGoodReturnPrice').val("");
			$('#txtCompanyReturnPrice').val("");
			$('#txtreturnId').val("");
			$("#txtGRQ").val("");
			$("#txtCRQ").val("");
			$("#txtreturnLineTot").val("");
			$("#txtReturnItemCode").focus();
			$('#current_edit_return_id').val("");

			$('#add-new-return').text("Add");
			$("#txtReturnItemCode").removeAttr("disabled");
			$("#txtReturnItemName").removeAttr("disabled");

			setTotalReturn();
			setSubTotal();
			displayBalance();

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
						clearError();

						if ($('#return_item_code_' + data[0].id).length !== 0) {

							$("#txtReturnItemCode").addClass('duplicate-error');
							$("#txtReturnItemName").addClass('duplicate-error');
							$("#txtGoodReturnPrice").addClass('duplicate-error');
							$("#txtCompanyReturnPrice").addClass('duplicate-error');
							$("#txtGRQ").addClass('duplicate-error');
							$("#txtCRQ").addClass('duplicate-error');
							$("#txtreturnLineTot").addClass('duplicate-error');
							$('#txtreturnId').val("");
							$("#txtReturnItemCode").select();

							var html_message = "";
							html_message += "<div id='return-exit-message'>";
							html_message += data[0].name + " is already exists in the list";
							html_message += "</div>";

							$('#return-dublicate-error-message').append(html_message);
							$('#return_item_row_' + data[0].id).addClass('duplicate-error');

							setTimeout(function () {
								$("div").removeClass("duplicate-error");
								$('#return-dublicate-error-message').empty();
							}, 3000);


							status = false;
						}
						else {

							$('#txtReturnItemCode').val(data[0].code);
							$('#txtReturnItemName').val(data[0].name);
							$('#txtGoodReturnPrice').val(data[0].current_selling_price);
							$('#txtCompanyReturnPrice').val(data[0].current_selling_price);
							$('#txtreturnId').val(data[0].id);
							calculateReturnLineTotal();
							$("#txtGRQ").focus();
						}
					}
					else
					{
						$("#txtReturnItemCode").addClass('duplicate-error');

						var html_message = "";
						html_message += "<div id='return-exit-message'>";
						html_message += "Please enter a valid item code";
						html_message += "</div>";

						$('#return-dublicate-error-message').append(html_message);

						setTimeout(function () {
							$('#return-dublicate-error-message').empty();
						}, 2000);

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

	clearError();

	var txtGoodReturnPrice = $('#txtGoodReturnPrice').val();
	var txtCompanyReturnPrice = $('#txtCompanyReturnPrice').val();
	var txtGRQ = $('#txtGRQ').val();
	var txtCRQ = $('#txtCRQ').val();

	var lineTotal = (txtGoodReturnPrice * txtGRQ) + (txtCompanyReturnPrice * txtCRQ);

	$("#txtreturnLineTot").val(lineTotal);

}

function validateaddReturnRow(validationVal) {
	var status = true;

	clearError();

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
	if (parseFloat(validationVal.txtGRQ) <= '0' && parseFloat(validationVal.txtCRQ) <= '0') {
		$("#txtGRQ").addClass('duplicate-error');
		$("#txtCRQ").addClass('duplicate-error');
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

	if ($('#return_item_code_' + validationVal.txtreturnId).length !== 0) {

		$("#txtReturnItemCode").addClass('duplicate-error');
		$("#txtReturnItemName").addClass('duplicate-error');
		$("#txtGoodReturnPrice").addClass('duplicate-error');
		$("#txtCompanyReturnPrice").addClass('duplicate-error');
		$("#txtGRQ").addClass('duplicate-error');
		$("#txtCRQ").addClass('duplicate-error');
		$("#txtreturnLineTot").addClass('duplicate-error');
		$('#txtreturnId').val("");

		var html_message = "";
		html_message += "<div id='return-exit-message'>";
		html_message += validationVal.txtReturnItemName + " is already exists in the list";
		html_message += "</div>";

		$('#return-dublicate-error-message').append(html_message);
		$('#return_item_row_' + validationVal.txtreturnId).addClass('duplicate-error');

		setTimeout(function () {
			$("div").removeClass("duplicate-error");
			$('#return-dublicate-error-message').empty();
		}, 3000);


		status = false;
	}

	return status;
}

function editReturn() {
	$(document).on('click', '.edit-return', function () {
		var itemId = this.id;

		clearError();

		var current_edit_return_id = $('#current_edit_return_id').val();


		if (current_edit_return_id) {

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

				jQuery('#add-new-return').click();

				$('#current_edit_return_id').val(itemId);

				$('#txtReturnItemCode').val($('#return_item_code_' + itemId).val());
				$('#txtReturnItemName').val($('#item_name_' + itemId).val());
				$('#txtGoodReturnPrice').val($('#good_return_price_' + itemId).val());
				$('#txtCompanyReturnPrice').val($('#company_return_price_' + itemId).val());

				$('#txtGRQ').val($('#good_return_quantity' + itemId).val());
				$('#txtCRQ').val($('#company_return_quantity' + itemId).val());
				$('#txtreturnLineTot').val($('#line_total' + itemId).val());
				$('#txtreturnId').val(itemId);

				$("#txtReturnItemCode").attr("disabled", "TRUE");
				$("#txtReturnItemName").attr("disabled", "TRUE");
				$("#return_item_row_" + itemId).remove();
				$('#add-new-return').text("Save");

				$('html, body').animate({
					scrollTop: $("#scrollTopReturn").offset().top
				}, 700);

			}
		}
		else
		{

			$('#current_edit_return_id').val(itemId);

			$('#txtReturnItemCode').val($('#return_item_code_' + itemId).val());
			$('#txtReturnItemName').val($('#item_name_' + itemId).val());
			$('#txtGoodReturnPrice').val($('#good_return_price_' + itemId).val());
			$('#txtCompanyReturnPrice').val($('#company_return_price_' + itemId).val());

			$('#txtGRQ').val($('#good_return_quantity' + itemId).val());
			$('#txtCRQ').val($('#company_return_quantity' + itemId).val());
			$('#txtreturnLineTot').val($('#line_total' + itemId).val());
			$('#txtreturnId').val(itemId);

			$("#txtReturnItemCode").attr("disabled", "TRUE");
			$("#txtReturnItemName").attr("disabled", "TRUE");
			$("#return_item_row_" + itemId).remove();
			$('#add-new-return').text("Save");

			$('html, body').animate({
				scrollTop: $("#scrollTopReturn").offset().top
			}, 700);
		}



	});
}

function deleteReturn() {

	$(document).on('click', '.delete-return', function () {

		$("#return_item_row_" + this.id).remove();

		setTotalReturn();
		setSubTotal();
		displayBalance();

		return false;

	});
}

function setTotalReturn() {

	var return_line_total = 0;

	$(".return_line_total").each(function () {
		return_line_total += parseFloat($(this).val());
	});

	$('#lable_return_total').text(return_line_total.toFixed(2));
	$('#txt_return_total').val(return_line_total);
}
