
$(document).keydown(function (e) {

	if (e.which === 112)
	{
		$('#txtItemCode').focus();
		e.preventDefault();
	}
	if (e.which === 113)
	{
		$('#txtItemName').focus();
		e.preventDefault();
	}
	if (e.which === 114)
	{
		$('#txtPrice').focus();
		e.preventDefault();
	}
	if (e.which === 115)
	{
		$('#txtQuantity').focus();
		e.preventDefault();
	}
});


function clearError() {

	$("input").removeClass("duplicate-error");
}

function validateIsFilledFields() {

	$(document).on("keypress", 'form', function (e) {
		var code = e.keyCode || e.which;
		var target_id = e.target.id;
		if (code === 13) {
			if (target_id === 'txtQuantity' || target_id === 'txtPrice' || target_id === 'txtItemName') {
				var itemCode = $('#txtItemCode').val();
				var itemName = $('#txtItemName').val();
				var price = $('#txtPrice').val();
				var quantity = $('#txtQuantity').val();

				if (quantity == '' || itemCode == '' || itemName == '' || price == '')
				{
					var html_message = "";
					html_message += "<div id='return-exit-message'>";
					html_message += "Please enter company return details";
					html_message += "</div>";

					$('#dublicate-error-message').append(html_message);

					setTimeout(function () {
						$("div").removeClass("duplicate-error");
						$('#dublicate-error-message').empty();
						clearError();
					}, 3000);
					return false;
				}
				else
				{
					jQuery('#add-new-company-return').click();
				}
			}

			e.preventDefault();
			return false;
		}

	});

}

function autoloadItemListForReturn(csrfToken) {

	$("body").click(function () {
		$('#item_list_f_return').empty();
	});

	$('#txtItemName').keyup(function (event) {

		$('#txtItemCode').val("");
		$('#txtPrice').val("");
		$('#txtQuantity').val("");
		$('#txtItemId').val("");
		$('#txtReturnLineTot').val("");

		if (event.keyCode === 13) { // enter 
			selectItemOnEnter(csrfToken);
		}

		else if (event.keyCode === 38) { // up
			var selected = $(".selected");
			$("#item_list_f_return li").removeClass("selected");
			if (selected.prev().length === 0) {
				selected.siblings().last().addClass("selected");
			} else {
				selected.prev().addClass("selected");
			}
		}

		else if (event.keyCode === 40) { // down
			var selected = $(".selected");
			$("#item_list_f_return li").removeClass("selected");
			if (selected.next().length === 0) {
				selected.siblings().first().addClass("selected");
			} else {
				selected.next().addClass("selected");
			}
		}

		else {

			$('#loader-img').show();
			$('#item_list_f_return').empty();

			var txtItemName = $('#txtItemName').val();
			var itemList = '';

			if (!txtItemName.trim()) {
				$('#loader-img').hide();
				$('#item_list_f_return').empty();
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

					$('#item_list_f_return').empty();
					$('#item_list_f_return').append(itemList);

				});
			}
		}
	});

	$("#item_list_f_return").on("mouseover", "#item_list_f_return li", function (event) {
		if ($(event.target).attr('class') !== 'error')
		{
			$("#item_list_f_return li").removeClass("selected");
			$(this).addClass("selected");
		}
	}).click(function () {
		selectItemOnEnter(csrfToken);
	});
}

function selectItemOnEnter(csrfToken) {

	var itemId = $(".selected").attr('id');
	var selectedStock = $('#from_stock').val();
	if (selectedStock.length == 0)
	{
		var html_message = "";
		html_message += "<div id='return-exit-message'>";
		html_message += "Please select stock before add returns";
		html_message += "</div>";

		$('#stock_select_msg').append(html_message);
		$('#from_stock').focus();
		setTimeout(function () {
			$("div").removeClass("duplicate-error");
			$('#stock_select_msg').empty();
			clearError();
		}, 3000);
		return false;
	}

	$('#item_list_f_return').empty();
	$.post(
		"/entities/items/ajax/getItemById", {
			_token: csrfToken,
			itemId: itemId
		},
	function (data)
	{

		if ($('#item_code_' + data[0].id).length !== 0) {
			$('#dublicate-error-message').append(html_message);
			$("#txtItemCode").addClass('duplicate-error');
			$('#return_item_row_' + data[0].id).addClass('duplicate-error');

			$('#txtQuantity').val("");
			$('#txtPrice').val("");
			$('#txtItemId').val("");

			$("#txtItemName").select();
			$("#txtItemName").focus();

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

			return false;
		}

		$('#txtItemCode').val(data[0].code);
		$('#txtItemName').val(data[0].name);
		$('#txtItemId').val(data[0].id);
		$('#txtPrice').val(data[0].current_buying_price);
		$('#txtQuantity').focus();
		
	});

	$.post(
		"/processes/CompanyReturns/ajax/getQuantity", {
			_token: csrfToken,
			itemId: itemId,
			stockId: selectedStock
		},
	function (data)
	{
		if (data == 0)
		{
			var itemName = $('#txtItemName').val()
			var html_message = "";
			html_message += "<div id='return-exit-message'>";
			html_message += itemName + " doesn't have returns";
			html_message += "</div>";

			$('#dublicate-error-message').append(html_message);

			setTimeout(function () {
				$("div").removeClass("duplicate-error");
				$('#dublicate-error-message').empty();
				clearError();
			}, 3000);

			emptyReturnInputs();

			return false;
		}
		$('#txtQuantity').val(data);
		var price = $('#txtPrice').val();
		$('#txtReturnLineTot').val(Number(data) * Number(price));

	});
}

function addCompanyReturnRow() {

	$('#txtItemCode').keyup(function () {
		$('#txtItemId').val("");
		$('#txtItemName').val("");
		$('#txtPrice').val("");
		$('#txtQuantity').val("");
	});

	$('#add-new-company-return').click(function (event) {

		var itemPrice = $('#txtPrice').val();
		var itemQuantity = $('#txtQuantity').val();
		var itemCode = $('#txtItemCode').val();
		var itemName = $('#txtItemName').val();
		if (itemPrice == '' || itemCode == '' || itemName == '' || itemQuantity == '')
		{
			var html_message = "";
			html_message += "<div id='return-exit-message'>";
			html_message += "Please fill fields";
			html_message += "</div>";

			$('#dublicate-error-message').append(html_message);

			setTimeout(function () {
				$('#dublicate-error-message').empty();
			}, 2000);

			return false;
		}
		event.preventDefault();

		var txtItemId = $('#txtItemId').val();
		var txtItemCode = $('#txtItemCode').val();
		var txtItemName = $('#txtItemName').val();
		var txtPrice = $('#txtPrice').val();
		var txtQuantity = $('#txtQuantity').val();
		var lineTotal = $('#txtReturnLineTot').val();

		var htmlOutput = '';
		htmlOutput += '<div id="return_item_row_' + txtItemId + '" class="row item-list-table">';
		htmlOutput += '<div class="col-sm-8">';
		htmlOutput += '<div class="row">';
		htmlOutput += '<div class="col-sm-2">';
		htmlOutput += '<input type="hidden" id="item_code_' + txtItemId + '" value="' + txtItemCode + '"/>';
		htmlOutput += '<input type="hidden" id="item_name_' + txtItemId + '" value="' + txtItemName + '"/>';
		htmlOutput += '<input type="hidden" name="itemId[' + txtItemId + ']" value="' + txtItemId + '" id="itemId_' + txtItemId + '"/>';
		htmlOutput += '<b>' + txtItemCode + '</b>';
		htmlOutput += '<input type="hidden" name="itemCode[' + txtItemId + ']" value="' + txtItemCode + '" id="itemCode_' + txtItemId + '"/>';
		htmlOutput += '</div>';
		htmlOutput += '<div class="col-sm-5">';
		htmlOutput += '<b>' + txtItemName + '</b>';
		htmlOutput += '<input type="hidden" name="itemName[' + txtItemId + ']" value="' + txtItemName + '" id="itemName_' + txtItemId + '"/>';
		htmlOutput += '</div>';
		htmlOutput += '<div class="col-sm-3 text-right">';
		htmlOutput += '<b>' + txtPrice + '</b>';
		htmlOutput += '<input type="hidden" name="itemPrice[' + txtItemId + ']" value="' + txtPrice + '" id="itemPrice_' + txtItemId + '"/>';
		htmlOutput += '</div>';
		htmlOutput += '<div class="col-sm-2 text-right">';
		htmlOutput += '<b>' + txtQuantity + '</b>';
		htmlOutput += '<input type="hidden" name="itemQuantity[' + txtItemId + ']" value="' + txtQuantity + '" id="itemQuantity_' + txtItemId + '"/>';
		htmlOutput += '</div>';
		htmlOutput += '</div>';
		htmlOutput += '</div>';

		htmlOutput += '<div class="col-sm-4">';
		htmlOutput += '<div class="row">';
		htmlOutput += '<div class="col-sm-6 text-right">';
		htmlOutput += '<b>' + lineTotal + '</b>';
		htmlOutput += '<input type="hidden" name="itemLineTot[' + txtItemId + ']" value="' + lineTotal + '" id="itemLineTot_' + txtItemId + '"/>';
		htmlOutput += '</div>';
		htmlOutput += '<div class="col-sm-6 text-right">';
		htmlOutput += '<a title="Click to edit ' + txtItemName + ' "  class="edit-return" id=' + txtItemId + '> Edit </a> / ';
		htmlOutput += '<a title="Click to delete ' + txtItemName + ' "   class="delete-return"  id=' + txtItemId + '> Delete </a>';
		htmlOutput += '</div>';
		htmlOutput += '</div>';
		htmlOutput += '</div>';
		htmlOutput += '</div>';

		$('#table-company-return-list').append(htmlOutput);

		emptyReturnInputs();
		$("#txtItemCode").focus();

		$('#add-new-company-return').text("Add");
		$("#txtItemCode").removeAttr("disabled");
		$("#txtItemName").removeAttr("disabled");
	});
}
//Start
function selectCompanyReturnItem(csrfToken) {
	$(window).keydown(function (event) {

		var valPreventDefault = false;

		if (event.keyCode === 13) {

			if (event.target.id === 'txtItemCode') {
				valPreventDefault = true;
				var selectedStock = $('#from_stock').val();
				if (selectedStock.length == 0)
				{
					var html_message = "";
					html_message += "<div id='return-exit-message'>";
					html_message += "Please select stock before add returns";
					html_message += "</div>";

					$('#stock_select_msg').append(html_message);
					$('#from_stock').focus();
					setTimeout(function () {
						$("div").removeClass("duplicate-error");
						$('#stock_select_msg').empty();
						clearError();
					}, 3000);
					return false;
				}
				else
				{
					var txtItemCode = $('#txtItemCode').val();

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
								$('#return_item_row_' + data[0].id).addClass('duplicate-error');
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
								$('#txtItemId').val(data[0].id);
								$('#txtPrice').val(data[0].current_buying_price);
								$('#txtQuantity').focus();

								var itemId = data[0].id;

								$.post(
									"/processes/CompanyReturns/ajax/getQuantity", {
										_token: csrfToken,
										itemId: itemId,
										stockId: selectedStock
									},
								function (data)
								{
									if (data == 0)
									{
										var itemName = $('#txtItemName').val()
										var html_message = "";
										html_message += "<div id='return-exit-message'>";
										html_message += itemName + " doesn't have returns";
										html_message += "</div>";

										$('#dublicate-error-message').append(html_message);

										setTimeout(function () {
											$("div").removeClass("duplicate-error");
											$('#dublicate-error-message').empty();
											clearError();
										}, 3000);

										emptyReturnInputs();
										$('#txtItemCode').focus();
										return false;
									}
									$('#txtQuantity').val(data);

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
							$('#txtPrice').val("");
							$('#txtQuantity').val("");
							$('#txtItemId').val("");
							$("#txtItemCode").select();

						}
					});
				}
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
//End
function editCompanyReturnItem() {
	$(document).on('click', '.edit-return', function () {
		var itemId = this.id;

		clearError();

		var current_edit_sales_id = $('#current_edit_sales_id').val();
		if (current_edit_sales_id) {
			emptyReturnInputs();

			jQuery('#add-new-company-return').click();

			$('#current_edit_sales_id').val(itemId);

			$('#txtItemCode').val($('#itemCode_' + itemId).val());
			$('#txtItemName').val($('#itemName_' + itemId).val());
			$('#txtPrice').val($('#itemPrice_' + itemId).val());
			$('#txtQuantity').val($('#itemQuantity_' + itemId).val());
			$('#txtReturnLineTot').val($('#itemLineTot_' + itemId).val());
			$('#txtItemId').val(itemId);

			$("#txtItemCode").attr("disabled", "TRUE");
			$("#txtItemName").attr("disabled", "TRUE");
			$("#return_item_row_" + itemId).remove();
			$('#add-new-company-return').text("Save");

			$('html, body').animate({
				scrollTop: $("#scrollTopSales").offset().top
			}, 700);
			$('#txtQuantity').focus();

		} else {


			$('#current_edit_sales_id').val(itemId);

			$('#txtItemCode').val($('#itemCode_' + itemId).val());
			$('#txtItemName').val($('#itemName_' + itemId).val());
			$('#txtPrice').val($('#itemPrice_' + itemId).val());
			$('#txtQuantity').val($('#itemQuantity_' + itemId).val());
			$('#txtReturnLineTot').val($('#itemLineTot_' + itemId).val());
			$('#txtItemId').val(itemId);

			$("#txtItemCode").attr("disabled", "TRUE");
			$("#txtItemName").attr("disabled", "TRUE");
			$("#return_item_row_" + itemId).remove();
			$('#add-new-company-return').text("Save");

			$('html, body').animate({
				scrollTop: $("#scrollTopSales").offset().top
			}, 700);
			$('#txtQuantity').focus();
		}

	});
}

function deleteCompanyReturnItem() {

	$(document).on('click', '.delete-return', function () {

		$("#return_item_row_" + this.id).remove();

		return false;
	});
}

function emptyReturnInputs()
{
	$('#txtItemId').val("");
	$('#txtItemCode').val("");
	$('#txtItemName').val("");
	$('#txtPrice').val("");
	$('#txtQuantity').val("");
	$('#txtReturnLineTot').val("");
}