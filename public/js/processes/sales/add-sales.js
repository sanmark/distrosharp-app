function autoloadItemForSales(csrfToken) {

	$("body").click(function () {
		$('#item_list_f_sales').empty();
	});

	$('#txtItemName').keyup(function (event) {

		$('#txtItemCode').val("");
		$('#txtAvailable').val("");
		$('#txtPaidQty').val("");
		$('#txtItemId').val("");
		$("#txtPrice").val("");
		$("#txtFreeQty").val("");
		$("#txtSalesLineTot").val("");

		if (event.keyCode === 13) { // enter 
			select_item_sales(csrfToken);
		}

		else if (event.keyCode === 38) { // up
			var selected = $(".selected");
			$("#item_list_f_sales li").removeClass("selected");
			if (selected.prev().length === 0) {
				selected.siblings().last().addClass("selected");
			} else {
				selected.prev().addClass("selected");
			}
		}

		else if (event.keyCode === 40) { // down
			var selected = $(".selected");
			$("#item_list_f_sales li").removeClass("selected");
			if (selected.next().length === 0) {
				selected.siblings().first().addClass("selected");
			} else {
				selected.next().addClass("selected");
			}
		}

		else {

			$('#loader-img').show();
			$('#item_list_f_sales').empty();

			var txtItemName = $('#txtItemName').val();
			var itemList = '';

			if (!txtItemName.trim()) {
				$('#loader-img').hide();
				$('#item_list_f_sales').empty();
			}
			else {
				$.post(
					"/entities/items/ajax/getItemByName", {
						_token: csrfToken,
						itemName: txtItemName
					},
				function (data)
				{
					if (data.length !== 0 && txtItemName.trim())
					{
						$.each(data, function (key, value) {
							if (key === 0) {
								itemList += '<li id="' + value.id + '" class="item-li selected">' + value.name + '</li>';
							}
							else {
								itemList += '<li id="' + value.id + '" class="item-li">' + value.name + '</li>';
							}
						});

						$('#loader-img').hide();
					}
					else
					{
						itemList += '<li id="error-li" class="error">';
						itemList += 'Not Found';
						itemList += '</li>';
						$('#loader-img').hide();
					}

					$('#item_list_f_sales').append(itemList);

				});
			}
		}
	});

	$("#item_list_f_sales").on("mouseover", "#item_list_f_sales li", function (event) {
		if ($(event.target).attr('class') !== 'error')
		{
			$("#item_list_f_sales li").removeClass("selected");
			$(this).addClass("selected");
		}
	}).click(function () {
		select_item_sales(csrfToken);
	});
}

function select_item_sales(csrfToken) {

	var itemId = $(".selected").attr('id');
	var rep_id = $('#rep_id').val();

	$('#item_list_f_sales').empty();

	$.post(
		"/entities/items/ajax/getItemById", {
			_token: csrfToken,
			itemId: itemId
		},
	function (data)
	{
		$('#txtItemCode').val(data[0].code);
		$('#txtItemName').val(data[0].name);
		$("#txtPrice").val(data[0].current_selling_price);
		$('#txtItemId').val(data[0].id);
		$('#txtPaidQty').focus();
		calculateSalesLineTotal();

	});

	$.post(
		"/stocks/ajax/getAvailableQuantity", {
			_token: csrfToken,
			itemId: itemId,
			rep_id: rep_id
		},
	function (data)
	{
		$('#txtAvailable').val(data);

	});
}


function addSalesRow() {

	$('#txtItemCode').keyup(function () {
		$('#txtItemName').val("");
		$('#txtAvailable').val("");
		$('#txtPaidQty').val("");
		$('#txtItemId').val("");
		$("#txtPrice").val("");
		$("#txtFreeQty").val("");
		$("#txtSalesLineTot").val("");
	});

	$('#add-new-salesl').click(function (event) {

		event.preventDefault();

		var txtItemCode = $('#txtItemCode').val();
		var txtItemName = $('#txtItemName').val();
		var txtAvailable = $('#txtAvailable').val();
		var txtPaidQty = $('#txtPaidQty').val();
		var txtPrice = $('#txtPrice').val();
		var txtFreeQty = $('#txtFreeQty').val();
		var txtSalesLineTot = $('#txtSalesLineTot').val();
		var txtItemId = $('#txtItemId').val();

		var validationVal = {
			"txtItemCode": txtItemCode,
			"txtItemName": txtItemName,
			"txtAvailable": txtAvailable,
			"txtPaidQty": txtPaidQty,
			"txtPrice": txtPrice,
			"txtFreeQty": txtFreeQty,
			"txtSalesLineTot": txtSalesLineTot,
			"txtItemId": txtItemId

		};

		var result = validateaddSalesRow(validationVal);

		if (result) {

			if (!txtPaidQty) {
				txtPaidQty = 0;
			}
			else if (!txtFreeQty) {
				txtFreeQty = 0;
			}

			var htmlOutput = '';
			htmlOutput += '<div id="salse_item_row_' + txtItemId + '" class="row item-list-table">';

			htmlOutput += '<div class="col-sm-6">';
			htmlOutput += '<div class="row">';
			htmlOutput += '<div class="col-sm-3">' + txtItemCode + '</div>';
			htmlOutput += '<div class="col-sm-3">' + txtItemName + '</div>';
			htmlOutput += '<div class="col-sm-3 text-right" ">' + txtAvailable + '</div>';
			htmlOutput += '<div class="col-sm-3 text-right" >' + txtPrice + '</div>';
			htmlOutput += '</div>';
			htmlOutput += '</div>';

			htmlOutput += '<div class="col-sm-6" >';
			htmlOutput += '<div class="row">';
			htmlOutput += '<div class="col-sm-3 text-right">' + txtPaidQty + '</div>';
			htmlOutput += '<div class="col-sm-3 text-right">' + txtFreeQty + '</div>';
			htmlOutput += '<div class="col-sm-3 text-right" >' + txtSalesLineTot + '</div>';
			htmlOutput += '<div class="col-sm-3 text-right" >';
			htmlOutput += '<a title="Click to edit ' + txtItemName + ' "  class="edit-sales" id=' + txtItemId + '> Edit </a> / ';
			htmlOutput += '<a title="Click to delete ' + txtItemName + ' "   class="delete-sales"  id=' + txtItemId + '> Delete </a>';
			htmlOutput += '</div>';
			htmlOutput += '</div>';
			htmlOutput += '</div>';

			htmlOutput += '<input type="hidden" id="item_code_' + txtItemId + '" value="' + txtItemCode + '">';
			htmlOutput += '<input type="hidden" id="item_name_' + txtItemId + '" value="' + txtItemName + '">';

			htmlOutput += '<input type="hidden" id="available_quantity' + txtItemId + '"  value="' + txtAvailable + '"  name="items[' + txtItemId + '][available_quantity]">';

			htmlOutput += '<input type="hidden" id="price' + txtItemId + '"  value="' + txtPrice + '"  name="items[' + txtItemId + '][price]">';

			htmlOutput += '<input type="hidden" id="paid_quantity' + txtItemId + '"  value="' + txtPaidQty + '"  name="items[' + txtItemId + '][paid_quantity]">';


			htmlOutput += '<input type="hidden"  id="free_quantity' + txtItemId + '"  value="' + txtFreeQty + '"  name="items[' + txtItemId + '][free_quantity]">';

			htmlOutput += '<input type="hidden" class="sales_line_total" id="sales_line_total' + txtItemId + '" value="' + txtSalesLineTot + '">';

			htmlOutput += '</div>';

			$('#table-sales-list').append(htmlOutput);

			$('#txtItemCode').val("");
			$('#txtItemName').val("");
			$('#txtAvailable').val("");
			$('#txtPaidQty').val("");
			$('#txtItemId').val("");
			$("#txtPrice").val("");
			$("#txtFreeQty").val("");
			$("#txtSalesLineTot").val("");
			$("#txtItemCode").focus();

			$('#add-new-salesl').text("Add");
			$("#txtItemCode").removeAttr("disabled");
			$("#txtItemName").removeAttr("disabled");

			setTotal();
			setSubTotal();
			displayBalance();

		}
	});

	$(document).on('change keyup', '.cal_return_line_tot', function () {
		calculateSalesLineTotal();
	});
}

function selectSalesItem(csrfToken) {

	$(window).keydown(function (event) {

		var valPreventDefault = false;

		if (event.keyCode === 13) {

			if (event.target.id === 'txtItemCode') {

				valPreventDefault = true;

				var txtItemCode = $('#txtItemCode').val();
				var rep_id = $('#rep_id').val();

				$.post(
					"/entities/items/ajax/getItemByCode", {
						_token: csrfToken,
						itemCode: txtItemCode
					},
				function (data)
				{
					if (data.length !== 0)
					{
						clearError();

						if ($('#item_code_' + data[0].id).length !== 0) {

							$('#dublicate-error-message').append(html_message);
							$("#txtItemCode").addClass('duplicate-error');
							$("#txtItemName").addClass('duplicate-error');
							$("#txtAvailable").addClass('duplicate-error');
							$("#txtPaidQty").addClass('duplicate-error');
							$("#txtPrice").addClass('duplicate-error');
							$("#txtFreeQty").addClass('duplicate-error');
							$("#txtSalesLineTot").addClass('duplicate-error');
							$('#salse_item_row_' + data[0].id).addClass('duplicate-error');
							$('#txtItemId').val("");
							$("#txtItemCode").select();

							var html_message = "";
							html_message += "<div id='return-exit-message'>";
							html_message += data[0].name + " is already exists in the list";
							html_message += "</div>";

							$('#dublicate-error-message').append(html_message);

							setTimeout(function () {
								$("div").removeClass("duplicate-error");
								$('#dublicate-error-message').empty();
								clearError();
							}, 3000);

							status = false;
						} else {

							$('#txtItemCode').val(data[0].code);
							$('#txtItemName').val(data[0].name);
							$("#txtPrice").val(data[0].current_selling_price);
							$('#txtItemId').val(data[0].id);
							$('#txtPaidQty').focus();
							calculateSalesLineTotal();

							$.post(
								"/stocks/ajax/getAvailableQuantity", {
									_token: csrfToken,
									itemId: data[0].id,
									rep_id: rep_id
								},
							function (data)
							{
								$('#txtAvailable').val(data);

							});
						}
					}
					else
					{
						$("#txtItemCode").addClass('duplicate-error');

						var html_message = "";
						html_message += "<div id='return-exit-message'>";
						html_message += "Please enter a valid item code";
						html_message += "</div>";

						$('#dublicate-error-message').append(html_message);

						setTimeout(function () {
							$('#dublicate-error-message').empty();
						}, 2000);

						$('#txtItemName').val("");
						$('#txtAvailable').val("");
						$('#txtPaidQty').val("");
						$('#txtItemId').val("");
						$("#txtPrice").val("");
						$("#txtFreeQty").val("");
						$("#txtSalesLineTot").val("");
						$("#txtItemCode").select();

					}

				});

			} else if (event.target.id === 'txtItemName') {

				valPreventDefault = true;
			}
			if (valPreventDefault) {
				event.preventDefault();
				return false;
			}

		}
	});

}

function calculateSalesLineTotal() {

	clearError();

	var txtPaidQty = $('#txtPaidQty').val();
	var txtPrice = $('#txtPrice').val();

	var lineTotal = txtPaidQty * txtPrice;

	$("#txtSalesLineTot").val(lineTotal);

}

function validateaddSalesRow(validationVal) {
	var status = true;

	clearError();

	if (parseInt(0 + validationVal.txtPaidQty) + parseInt(0 + validationVal.txtFreeQty) > validationVal.txtAvailable) {

		$("#txtPaidQty").addClass('duplicate-error');
		$("#txtFreeQty").addClass('duplicate-error');
		$("#txtAvailable").addClass('duplicate-error');

		var html_message = "";
		html_message += "<div id='return-exit-message'>";
		html_message += "Paid Qty + Free Qty must be less than or equal to Available";
		html_message += "</div>";

		$('#dublicate-error-message').append(html_message);

		setTimeout(function () {
			$("div").removeClass("duplicate-error");
			$('#dublicate-error-message').empty();
			clearError();
		}, 4000);

		status = false;
	}

	if (!validationVal.txtItemCode.trim()) {
		$("#txtItemCode").addClass('duplicate-error');
		status = false;
	}
	if (!validationVal.txtItemName.trim()) {
		$("#txtItemName").addClass('duplicate-error');
		status = false;
	}
	if (!validationVal.txtAvailable.trim()) {
		$("#txtAvailable").addClass('duplicate-error');
		status = false;
	}
	if (!validationVal.txtPrice.trim()) {
		$("#txtPrice").addClass('duplicate-error');
		status = false;
	}
	if (!validationVal.txtPaidQty.trim() && !validationVal.txtFreeQty.trim()) {
		$("#txtPaidQty").addClass('duplicate-error');
		$("#txtFreeQty").addClass('duplicate-error');
		status = false;
	}
	if (validationVal.txtPaidQty <= '0' && validationVal.txtFreeQty <= '0') {
		$("#txtPaidQty").addClass('duplicate-error');
		$("#txtFreeQty").addClass('duplicate-error');
		status = false;
	}
	if (validationVal.txtSalesLineTot === 0 && !validationVal.txtSalesLineTot.trim()) {
		$("#txtSalesLineTot").addClass('duplicate-error');
		status = false;
	}

	if (!validationVal.txtItemId.trim()) {
		$("#txtItemCode").addClass('duplicate-error');
		$("#txtItemName").addClass('duplicate-error');
		$("#txtAvailable").addClass('duplicate-error');
		$("#txtPaidQty").addClass('duplicate-error');
		$("#txtPrice").addClass('duplicate-error');
		$("#txtFreeQty").addClass('duplicate-error');
		$("#txtSalesLineTot").addClass('duplicate-error');
		status = false;
	}

	if ($('#item_code_' + validationVal.txtItemId).length !== 0) {


		$("#txtItemCode").addClass('duplicate-error');
		$("#txtItemName").addClass('duplicate-error');
		$("#txtAvailable").addClass('duplicate-error');
		$("#txtPaidQty").addClass('duplicate-error');
		$("#txtPrice").addClass('duplicate-error');
		$("#txtFreeQty").addClass('duplicate-error');
		$("#txtSalesLineTot").addClass('duplicate-error');
		$('#salse_item_row_' + validationVal.txtItemId).addClass('duplicate-error');
		$('#txtItemId').val("");

		var html_message = "";
		html_message += "<div id='return-exit-message'>";
		html_message += validationVal.txtItemName + " is already exists in the list";
		html_message += "</div>";

		$('#dublicate-error-message').append(html_message);

		setTimeout(function () {
			$("div").removeClass("duplicate-error");
			$('#dublicate-error-message').empty();
		}, 3000);

		status = false;
	}

	return status;
}

function editSales() {
	$(document).on('click', '.edit-sales', function () {
		var itemId = this.id;

		clearError();
 
		$('#txtItemCode').val($('#item_code_' + itemId).val());
		$('#txtItemName').val($('#item_name_' + itemId).val());
		$('#txtAvailable').val($('#available_quantity' + itemId).val());
		$('#txtPrice').val($('#price' + itemId).val());

		$('#txtPaidQty').val($('#paid_quantity' + itemId).val());
		$('#txtFreeQty').val($('#free_quantity' + itemId).val());
		$('#txtSalesLineTot').val($('#sales_line_total' + itemId).val());
		$('#txtItemId').val(itemId);

		$("#txtItemCode").attr("disabled", "TRUE");
		$("#txtItemName").attr("disabled", "TRUE");
		$("#salse_item_row_" + itemId).remove();
		$('#add-new-salesl').text("Save");

		$('html, body').animate({
			scrollTop: $("#scrollTopSales").offset().top
		}, 700);

	});
}

function deleteSales() {

	$(document).on('click', '.delete-sales', function () {

		$("#salse_item_row_" + this.id).remove();

		setTotal();
		setSubTotal();
		displayBalance();

		return false;
	});
}

function setTotal() {

	var sales_line_total = 0;

	$(".sales_line_total").each(function () {
		sales_line_total += parseInt($(this).val());
	});

	$('#lable_sales_total').text(sales_line_total.toFixed(2));
	$('#txt_sales_total').val(sales_line_total);
}
