
function selectSalesItem(csrfToken) {

	$(window).keydown(function (event) {

		var valPreventDefault = false;

		if (event.keyCode === 13) {

			if (event.target.id === 'txtItemCode') {
				$('#loader-img-code').show();

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

							$("#txtItemCode").addClass('duplicate-error');
							$("#txtItemName").addClass('duplicate-error');
							$("#txtPrice").addClass('duplicate-error');
							$('#purchase_item_row_' + data[0].id).addClass('duplicate-error');
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
							$('#loader-img-code').hide();

							status = false;
						} else {

							$('#txtItemCode').val(data[0].code);
							$('#txtItemName').val(data[0].name);
							$("#txtPrice").val(parseFloat(data[0].current_buying_price).toFixed(2));
							$('#txtItemId').val(data[0].id);
							$('#txtQuantity').focus();
							$('#loader-img-code').hide();
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
						$("#txtPrice").val("");
						$('#txtQuantity').val("");
						$("#txtFreeQuantity").val("");
						$("#txtExpireDate").val("");
						$("#txtPurchaseLineTot").val("");
						$("#txtBatchNumber").val("");
						$("#txtPurchaseLineTot").val("");
						$("#txtItemCode").select();
						$('#loader-img-code').hide();

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

function autoloadItemForSales(csrfToken) {

	$("body").click(function () {
		$('#item_list_f_purchase').empty();
	});

	$('#txtItemName').keyup(function (event) {

		$('#txtItemCode').val("");
		$("#txtPrice").val("");
		$('#txtQuantity').val("");
		$("#txtFreeQuantity").val("");
		$("#txtExpireDate").val("");
		$("#txtBatchNumber").val("");
		$("#txtPurchaseLineTot").val("");
		$('#txtItemId').val("");

		if (event.keyCode === 13) { // enter 
			select_item_purchase(csrfToken);
		}

		else if (event.keyCode === 38) { // up
			var selected = $(".selected");
			$("#item_list_f_purchase li").removeClass("selected");
			if (selected.prev().length === 0) {
				selected.siblings().last().addClass("selected");
			} else {
				selected.prev().addClass("selected");
			}
		}

		else if (event.keyCode === 40) { // down
			var selected = $(".selected");
			$("#item_list_f_purchase li").removeClass("selected");
			if (selected.next().length === 0) {
				selected.siblings().first().addClass("selected");
			} else {
				selected.next().addClass("selected");
			}
		}

		else {

			$('#loader-img').show();
			$('#item_list_f_purchase').empty();

			var txtItemName = $('#txtItemName').val();
			var itemList = '';

			if (!txtItemName.trim()) {
				$('#loader-img').hide();
				$('#item_list_f_purchase').empty();
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
					}

					$('#loader-img').hide();
					$('#item_list_f_purchase').empty();
					$('#item_list_f_purchase').append(itemList);

				});
			}
		}
	});

	$("#item_list_f_purchase").on("mouseover", "#item_list_f_purchase li", function (event) {
		if ($(event.target).attr('class') !== 'error')
		{
			$("#item_list_f_purchase li").removeClass("selected");
			$(this).addClass("selected");
		}
	}).click(function () {
		select_item_purchase(csrfToken);
	});
}



function select_item_purchase(csrfToken) {


	$('#loader-img').show();

	var itemId = $(".selected").attr('id');
	var rep_id = $('#rep_id').val();

	if (!itemId) {
		$('#loader-img').hide();
	}


	$('#item_list_f_purchase').empty();

	$.post(
		"/entities/items/ajax/getItemById", {
			_token: csrfToken,
			itemId: itemId
		},
	function (data)
	{
		if ($('#item_code_' + data[0].id).length !== 0) {

			$("#txtItemCode").addClass('duplicate-error');
			$("#txtItemName").addClass('duplicate-error');
			$("#txtAvailable").addClass('duplicate-error');
			$("#txtQuantity").addClass('duplicate-error');
			$("#txtPrice").addClass('duplicate-error');
			$("#txtFreeQuantity").addClass('duplicate-error');
			$("#txtSalesLineTot").addClass('duplicate-error');
			$('#purchase_item_row_' + data[0].id).addClass('duplicate-error');
			$('#txtItemId').val("");
			$("#txtItemName").select();


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

			$('#loader-img').hide();
			return false;
		}
		else
		{
			$('#txtItemCode').val(data[0].code);
			$('#txtItemName').val(data[0].name);
			$("#txtPrice").val(parseFloat(data[0].current_buying_price).toFixed(2));
			$('#txtItemId').val(data[0].id);
			$('#txtQuantity').focus();
			$('#loader-img').hide();



		}

	});

}

function addItemRow() {

	$('#txtItemCode').keyup(function () {

		$('#txtItemName').val("");
		$("#txtPrice").val("");
		$('#txtQuantity').val("");
		$("#txtFreeQuantity").val("");
		$("#txtExpireDate").val("");
		$("#txtBatchNumber").val("");
		$("#txtPurchaseLineTot").val("");
		$('#txtItemId').val("");

	});

	$('#add-new-purchase').click(function (event) {

		event.preventDefault();

		var txtItemCode = $('#txtItemCode').val();
		var txtItemName = $('#txtItemName').val();
		var txtPrice = $('#txtPrice').val();
		var txtQuantity = $('#txtQuantity').val();
		var txtFreeQuantity = $('#txtFreeQuantity').val();
		var txtExpireDate = $('#txtExpireDate').val();
		var txtBatchNumber = $('#txtBatchNumber').val();
		var txtPurchaseLineTot = $('#txtPurchaseLineTot').val();
		var txtItemId = $('#txtItemId').val();

		var validationVal = {
			"txtItemCode": txtItemCode,
			"txtItemName": txtItemName,
			"txtPrice": txtPrice,
			"txtQuantity": txtQuantity,
			"txtFreeQuantity": txtFreeQuantity,
			"txtItemId": txtItemId
		};

		var result = validateaddItemRow(validationVal);


		if (result) {

			if (!txtQuantity) {
				txtQuantity = 0;
			}
			else if (!txtFreeQuantity) {
				txtFreeQuantity = 0;
			}

			var htmlOutput = '';
			htmlOutput += '<div id="purchase_item_row_' + txtItemId + '" class="row item-list-table">';

			htmlOutput += '<div class="col-sm-5">';
			htmlOutput += '<div class="row">';
			htmlOutput += '<div class="col-sm-2">' + txtItemCode + '</div>';
			htmlOutput += '<div class="col-sm-5">' + txtItemName + '</div>';
			htmlOutput += '<div class="col-sm-3 text-right" ">' + parseFloat(txtPrice).toFixed(2) + '</div>';
			htmlOutput += '<div class="col-sm-2 text-right" >' + txtQuantity + '</div>';
			htmlOutput += '</div>';
			htmlOutput += '</div>';

			htmlOutput += '<div class="col-sm-7" >';
			htmlOutput += '<div class="row">';
			htmlOutput += '<div class="col-sm-2 text-right">' + txtFreeQuantity + '</div>';
			if (!txtExpireDate) {
				txtExpireDate = " - - / - - / - - - - ";
			}
			htmlOutput += '<div class="col-sm-3 text-right">' + txtExpireDate + '</div>';
			if (!txtBatchNumber) {
				txtBatchNumber = " - - ";
			}
			htmlOutput += '<div class="col-sm-2" >' + txtBatchNumber + '</div>';
			htmlOutput += '<div class="col-sm-3 text-right" >' + txtPurchaseLineTot + '</div>';
			htmlOutput += '<div class="col-sm-2 text-right" >';
			htmlOutput += '<a title="Click to edit ' + txtItemName + ' "  class="edit-purchase" id=' + txtItemId + '> Edit </a> / ';
			htmlOutput += '<a title="Click to delete ' + txtItemName + ' "   class="delete-purchase"  id=' + txtItemId + '> Delete </a>';
			htmlOutput += '</div>';
			htmlOutput += '</div>';
			htmlOutput += '</div>';

			htmlOutput += '<input type="hidden" id="item_code_' + txtItemId + '" value="' + txtItemCode + '">';
			htmlOutput += '<input type="hidden" id="item_name_' + txtItemId + '" value="' + txtItemName + '">';
			htmlOutput += '<input type="hidden" name="item_id_' + txtItemId + '" id="item_id_' + txtItemId + '" value="' + txtItemId + '">';

			htmlOutput += '<input type="hidden" id="buying_price_' + txtItemId + '"  value="' + txtPrice + '"  name="buying_price_' + txtItemId + '">';

			htmlOutput += '<input type="hidden" id="quantity_' + txtItemId + '"  value="' + txtQuantity + '"  name="quantity_' + txtItemId + '">';


			htmlOutput += '<input type="hidden"  id="free_quantity_' + txtItemId + '"  value="' + txtFreeQuantity + '"  name="free_quantity_' + txtItemId + '">';

			htmlOutput += '<input type="hidden"  id="exp_date_' + txtItemId + '"  value="' + txtExpireDate + '"  name="exp_date_' + txtItemId + '">';


			htmlOutput += '<input type="hidden"  id="batch_number_' + txtItemId + '"  value="' + txtBatchNumber + '"  name="batch_number_' + txtItemId + '">';

			htmlOutput += '<input type="hidden" class="purchaseLineTot" id="purchaseLineTot' + txtItemId + '" value="' + txtPurchaseLineTot + '">';

			htmlOutput += '</div>';

			$('#table-purchase-list').append(htmlOutput);
			setTotal();
			displayBalance();

			$('#txtItemCode').val("");
			$('#txtItemId').val("");
			$('#txtItemName').val("");
			$("#txtPrice").val("");
			$('#txtQuantity').val("");
			$("#txtFreeQuantity").val("");
			$("#txtExpireDate").val("");
			$("#txtBatchNumber").val("");
			$("#txtPurchaseLineTot").val("");
			$("#txtItemCode").focus();
			$('#current_edite_purchase_id').val("");

			$('#add-new-purchase').text("Add");
			$("#txtItemCode").removeAttr("disabled");
			$("#txtItemName").removeAttr("disabled");

		}
	});

	$(document).on('change keyup', '.cal_line_tot', function () {
		calculatePurchaseLineTotal();
	});
}

function calculatePurchaseLineTotal() {

	clearError();

	var txtPrice = $('#txtPrice').val();
	var txtQuantity = $('#txtQuantity').val();

	var lineTotal = txtQuantity * txtPrice;

	$("#txtPurchaseLineTot").val(parseFloat(lineTotal).toFixed(2));

}

function validateaddItemRow(validationVal)
{

	clearError();
	var status = true;

	if (!validationVal.txtItemCode.trim()) {
		$("#txtItemCode").addClass('duplicate-error');
		status = false;
	}
	if (!validationVal.txtItemName.trim()) {
		$("#txtItemName").addClass('duplicate-error');
		status = false;
	}
	if (!validationVal.txtPrice.trim()) {
		$("#txtPrice").addClass('duplicate-error');
		status = false;
	}

	if (!validationVal.txtQuantity.trim() && !validationVal.txtFreeQuantity.trim()) {
		$("#txtQuantity").addClass('duplicate-error');
		$("#txtFreeQuantity").addClass('duplicate-error');
		status = false;
	}

	if (!validationVal.txtQuantity.trim() && parseFloat(validationVal.txtFreeQuantity) <= '0') {
		$("#txtQuantity").addClass('duplicate-error');
		$("#txtFreeQuantity").addClass('duplicate-error');
		status = false;
	}

	if (!validationVal.txtFreeQuantity.trim() && parseFloat(validationVal.txtQuantity) <= '0') {
		$("#txtQuantity").addClass('duplicate-error');
		$("#txtFreeQuantity").addClass('duplicate-error');
		status = false;
	}

	if (!validationVal.txtItemId.trim()) {

		$("#txtItemCode").addClass('duplicate-error');
		$("#txtPrice").addClass('duplicate-error');
		$("#txtQuantity").addClass('duplicate-error');
		$("#txtFreeQuantity").addClass('duplicate-error');
		$("#txtExpireDate").addClass('duplicate-error');
		$("#txtBatchNumber").addClass('duplicate-error');
		$("#txtPurchaseLineTot").addClass('duplicate-error');

		status = false;
	}

	if (parseFloat(validationVal.txtQuantity) <= '0' && parseFloat(validationVal.txtFreeQuantity) <= '0') {
		$("#txtQuantity").addClass('duplicate-error');
		$("#txtFreeQuantity").addClass('duplicate-error');
		status = false;
	}

	if ($('#item_code_' + validationVal.txtItemId).length !== 0) {

		$("#txtItemCode").addClass('duplicate-error');
		$('#purchase_item_row_' + validationVal.txtItemId).addClass('duplicate-error');
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


function setTotal() {

	var purchaseLineTot = 0;

	$(".purchaseLineTot").each(function () {
		purchaseLineTot += parseFloat($(this).val());
	});

	$('#subTotal').val(purchaseLineTot.toFixed(2));

}

$(document).on('change keyup', '.saleDetail', function () {
	displayBalance();
});




function editSales() {
	$(document).on('click', '.edit-purchase', function () {
		var itemId = this.id;

		clearError();

		var current_edite_purchase_id = $('#current_edite_purchase_id').val();

		if (current_edite_purchase_id)
		{
			var txtItemCode = $('#txtItemCode').val();
			var txtItemName = $('#txtItemName').val();
			var txtPrice = $('#txtPrice').val();
			var txtQuantity = $('#txtQuantity').val();
			var txtFreeQuantity = $('#txtFreeQuantity').val();
			var txtItemId = $('#txtItemId').val();

			var validationVal = {
				"txtItemCode": txtItemCode,
				"txtItemName": txtItemName,
				"txtPrice": txtPrice,
				"txtQuantity": txtQuantity,
				"txtFreeQuantity": txtFreeQuantity,
				"txtItemId": txtItemId
			};

			var result = validateaddItemRow(validationVal);



			if (result)
			{
				jQuery('#add-new-purchase').click();

				$('#current_edite_purchase_id').val(itemId);

				$('#txtItemCode').val($('#item_code_' + itemId).val());
				$('#txtItemName').val($('#item_name_' + itemId).val());
				$('#txtPrice').val($('#buying_price_' + itemId).val());
				$('#txtQuantity').val($('#quantity_' + itemId).val());

				$('#txtFreeQuantity').val($('#free_quantity_' + itemId).val());
				$('#txtExpireDate').val($('#exp_date_' + itemId).val());
				$('#txtBatchNumber').val($('#batch_number_' + itemId).val());
				$('#txtPurchaseLineTot').val($('#purchaseLineTot' + itemId).val());
				$('#txtItemId').val(itemId);

				$("#txtItemCode").attr("disabled", "TRUE");
				$("#txtItemName").attr("disabled", "TRUE");
				$("#purchase_item_row_" + itemId).remove();
				$('#add-new-purchase').text("Save");

				$('html, body').animate({
					scrollTop: $("#scrollTopSales").offset().top
				}, 700);
			}

		}
		else
		{
			$('#current_edite_purchase_id').val(itemId);

			$('#txtItemCode').val($('#item_code_' + itemId).val());
			$('#txtItemName').val($('#item_name_' + itemId).val());
			$('#txtPrice').val($('#buying_price_' + itemId).val());
			$('#txtQuantity').val($('#quantity_' + itemId).val());

			$('#txtFreeQuantity').val($('#free_quantity_' + itemId).val());
			$('#txtExpireDate').val($('#exp_date_' + itemId).val());
			$('#txtBatchNumber').val($('#batch_number_' + itemId).val());
			$('#txtPurchaseLineTot').val($('#purchaseLineTot' + itemId).val());
			$('#txtItemId').val(itemId);

			$("#txtItemCode").attr("disabled", "TRUE");
			$("#txtItemName").attr("disabled", "TRUE");
			$("#purchase_item_row_" + itemId).remove();
			$('#add-new-purchase').text("Save");

			$('html, body').animate({
				scrollTop: $("#scrollTopSales").offset().top
			}, 700);
		}

	});
}

function deleteSales() {

	$(document).on('click', '.delete-purchase', function () {

		$("#purchase_item_row_" + this.id).remove();

		setTotal();
		displayBalance();

		return false;
	});
}