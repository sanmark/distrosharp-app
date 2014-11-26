
// main 

function setMethodToEnter() {

	$(document).on("keypress", 'body', function (e) {

		var code = e.keyCode || e.which;
		var target_id = e.target.id;

		if (code === 13) {
			if (target_id === 'txtQuantity') {

				jQuery('#add-new-row').click();
			}

			e.preventDefault();
			return false;
		}

	});

}

function clearError() {

	$("input").removeClass("duplicate-error");
}

$(document).keydown(function (e) {

	if (e.which === 112) {

		$('#txtItemCode').focus();
		e.preventDefault();
	}
	if (e.which === 113) {

		$('#txtItemName').focus();
		e.preventDefault();
	}
	if (e.which === 114) {

		$('#txtQuantity').focus();
		e.preventDefault();
	}
});

//calculator 

function selectSalesItem(csrfToken) {

	$(window).keydown(function (event) {

		var valPreventDefault = false;

		if (event.keyCode === 13) {

			if (event.target.id === 'txtItemCode') {
				$('#loader-img-code').show();

				valPreventDefault = true;

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
							$('#item_row_' + data[0].id).addClass('duplicate-error');
							$('#txtItemId').val("");
							$("#txtItemCode").select();

							var html_message = "";
							html_message += "<div id='return-exit-message'>";
							html_message += data[0].name + " is already exists in the list";
							html_message += "</div>";

							$('#dublicate-error-message').empty();

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
							$("#txtUnitWeight").val(data[0].weight);
							$('#txtBuyingPrice').val(parseFloat(data[0].current_buying_price).toFixed(2));
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

						$('#dublicate-error-message').empty();

						$('#dublicate-error-message').append(html_message);

						setTimeout(function () {
							$('#dublicate-error-message').empty();
						}, 2000);


						$('#txtItemName').val("");
						$("#txtPrice").val("");
						$('#txtUnitWeight').val("");
						$("#txtBuyingPrice").val("");
						$("#txtItemId").val("");
						$("#txtQuantity").val("");
						$('#txtLineWeight').val("");
						$('#txtLineTotal').val("");
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
		$('#item_list').empty();
	});

	$('#txtItemName').keyup(function (event) {

		$('#txtItemCode').val("");
		$("#txtPrice").val("");
		$('#txtUnitWeight').val("");
		$("#txtBuyingPrice").val("");
		$("#txtItemId").val("");
		$("#txtQuantity").val("");
		$('#txtLineWeight').val("");
		$('#txtLineTotal').val("");

		if (event.keyCode === 13) { // enter 
			select_item_purchase(csrfToken);
		}

		else if (event.keyCode === 38) { // up
			var selected = $(".selected");
			$("#item_list li").removeClass("selected");
			if (selected.prev().length === 0) {
				selected.siblings().last().addClass("selected");
			} else {
				selected.prev().addClass("selected");
			}
		}

		else if (event.keyCode === 40) { // down
			var selected = $(".selected");
			$("#item_list li").removeClass("selected");
			if (selected.next().length === 0) {
				selected.siblings().first().addClass("selected");
			} else {
				selected.next().addClass("selected");
			}
		}

		else {

			$('#loader-img').show();
			$('#item_list').empty();

			var txtItemName = $('#txtItemName').val();
			var itemList = '';

			if (!txtItemName.trim()) {
				$('#loader-img').hide();
				$('#item_list').empty();
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
					$('#item_list').empty();
					$('#item_list').append(itemList);

				});
			}
		}
	});

	$("#item_list").on("mouseover", "#item_list li", function (event) {
		if ($(event.target).attr('class') !== 'error')
		{
			$("#item_list li").removeClass("selected");
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


	$('#item_list').empty();

	$.post(
		"/entities/items/ajax/getItemById", {
			_token: csrfToken,
			itemId: itemId
		},
	function (data)
	{
		if ($('#item_code_' + data[0].id).length !== 0) {

			$("#txtItemName").addClass('duplicate-error');
			$('#item_row_' + data[0].id).addClass('duplicate-error');
			$('#txtItemId').val("");
			$("#txtItemName").select();


			var html_message = "";
			html_message += "<div id='return-exit-message'>";
			html_message += data[0].name + " is already exists in the list";
			html_message += "</div>";

			$('#dublicate-error-message').empty();

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
			$("#txtUnitWeight").val(data[0].weight);
			$('#txtBuyingPrice').val(parseFloat(data[0].current_buying_price).toFixed(2));
			$('#txtItemId').val(data[0].id);
			$('#txtQuantity').focus();
			$('#loader-img').hide();

		}

	});

}

$(document).on('change keyup', '#txtQuantity', function () {

	var txtUnitWeight = $('#txtUnitWeight').val();
	var txtBuyingPrice = $('#txtBuyingPrice').val();
	var txtQuantity = $('#txtQuantity').val();
	var txtLineWeight = txtUnitWeight * txtQuantity / 1000;
	var txtLineTotal = txtBuyingPrice * txtQuantity;

	$('#txtLineWeight').val(parseFloat(txtLineWeight).toFixed(2));
	$('#txtLineTotal').val(parseFloat(txtLineTotal).toFixed(2));

});


function addItemRow() {

	$('#txtItemCode').keyup(function () {

		$('#txtItemName').val("");
		$("#txtPrice").val("");
		$('#txtUnitWeight').val("");
		$("#txtBuyingPrice").val("");
		$("#txtItemId").val("");
		$("#txtQuantity").val("");
		$('#txtLineWeight').val("");
		$('#txtLineTotal').val("");

	});

	$('#add-new-row').click(function (event) {

		event.preventDefault();

		var txtItemCode = $('#txtItemCode').val();
		var txtItemName = $('#txtItemName').val();
		var txtUnitWeight = $('#txtUnitWeight').val();
		var txtBuyingPrice = $('#txtBuyingPrice').val();
		var txtQuantity = $('#txtQuantity').val();
		var txtLineWeight = $('#txtLineWeight').val();
		var txtLineTotal = $('#txtLineTotal').val();
		var txtItemId = $('#txtItemId').val();

		var validationVal = {
			"txtItemCode": txtItemCode,
			"txtItemName": txtItemName,
			"txtQuantity": txtQuantity,
			"txtItemId": txtItemId
		};

		var result = validateaddItemRow(validationVal);

		if (result) {



			var htmlOutput = '';
			htmlOutput += '<div id="item_row_' + txtItemId + '" class="row item-list-table">';

			htmlOutput += '<div class="col-sm-7">';
			htmlOutput += '<div class="row">';
			htmlOutput += '<div class="col-sm-3">' + txtItemCode + '</div>';
			htmlOutput += '<div class="col-sm-5">' + txtItemName + '</div>';
			htmlOutput += '<div class="col-sm-2 text-right" ">' + parseFloat(txtUnitWeight).toFixed(2) + '</div>';
			htmlOutput += '<div class="col-sm-2 text-right" >' + parseFloat(txtBuyingPrice).toFixed(2) + '</div>';
			htmlOutput += '</div>';
			htmlOutput += '</div>';

			htmlOutput += '<div class="col-sm-5" >';
			htmlOutput += '<div class="row">';
			htmlOutput += '<div class="col-sm-3 text-right">' + txtQuantity + '</div>';
			htmlOutput += '<div class="col-sm-3 text-right">' + parseFloat(txtLineWeight).toFixed(2) + '</div>';
			htmlOutput += '<div class="col-sm-3 text-right" >' + parseFloat(txtLineTotal).toFixed(2) + '</div>';
			htmlOutput += '<div class="col-sm-3 text-right" >';
			htmlOutput += '<a title="Click to edit ' + txtItemName + ' "  class="edite_row" id=' + txtItemId + '> Edit </a> / ';
			htmlOutput += '<a title="Click to delete ' + txtItemName + ' "   class="delete-row"  id=' + txtItemId + '> Delete </a>';
			htmlOutput += '</div>';
			htmlOutput += '</div>';
			htmlOutput += '</div>';

			htmlOutput += '<input type="hidden" id="item_code_' + txtItemId + '" value="' + txtItemCode + '">';
			htmlOutput += '<input type="hidden" id="item_name_' + txtItemId + '" value="' + txtItemName + '">';
			htmlOutput += '<input type="hidden" name="item_id_' + txtItemId + '" id="item_id_' + txtItemId + '" value="' + txtItemId + '">';

			htmlOutput += '<input type="hidden" id="unitWeight' + txtItemId + '"  value="' + txtUnitWeight + '"  name="buying_price_' + txtItemId + '">';

			htmlOutput += '<input type="hidden" id="buyingPrice' + txtItemId + '"  value="' + txtBuyingPrice + '"  name="quantity_' + txtItemId + '">';


			htmlOutput += '<input type="hidden"  id="quantity' + txtItemId + '"  value="' + txtQuantity + '"  name="free_quantity_' + txtItemId + '">';

			htmlOutput += '<input type="hidden"  class="lineWeight" id="lineWeight' + txtItemId + '"  value="' + txtLineWeight + '"  name="exp_date_' + txtItemId + '">';


			htmlOutput += '<input type="hidden" class="lineTotal" id="lineTotal' + txtItemId + '"  value="' + txtLineTotal + '"  name="batch_number_' + txtItemId + '">';

			htmlOutput += '</div>';

			$('#table-item-list').append(htmlOutput);
			$('#current_edit_row_id').val("");
			setTotal();


			$('#txtItemCode').val("");
			$('#txtItemName').val("");
			$('#txtItemId').val("");
			$("#txtPrice").val("");
			$('#txtUnitWeight').val("");
			$("#txtBuyingPrice").val("");
			$("#txtQuantity").val("");
			$('#txtLineWeight').val("");
			$('#txtLineTotal').val("");

			$("#txtItemCode").focus();

			$('#add-new-row').text("Add");
			$("#txtItemCode").removeAttr("disabled");
			$("#txtItemName").removeAttr("disabled");

		}
	});

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

	if (!validationVal.txtQuantity.trim() || parseFloat(validationVal.txtQuantity) <= '0') {

		$("#txtQuantity").addClass('duplicate-error');
		status = false;
	}


	if (!validationVal.txtItemId.trim()) {


		$('#txtItemCode').addClass('duplicate-error');
		$('#txtItemName').addClass('duplicate-error');
		$('#txtItemId').addClass('duplicate-error');
		$("#txtPrice").addClass('duplicate-error');
		$('#txtUnitWeight').addClass('duplicate-error');
		$("#txtBuyingPrice").addClass('duplicate-error');
		$("#txtQuantity").addClass('duplicate-error');
		$('#txtLineWeight').addClass('duplicate-error');
		$('#txtLineTotal').addClass('duplicate-error');

		status = false;
	}

	if ($('#item_code_' + validationVal.txtItemId).length !== 0) {

		$("#txtItemCode").addClass('duplicate-error');
		$('#item_row_' + validationVal.txtItemId).addClass('duplicate-error');
		$('#txtItemId').val("");

		var html_message = "";
		html_message += "<div id='return-exit-message'>";
		html_message += validationVal.txtItemName + " is already exists in the list";
		html_message += "</div>";

		$('#dublicate-error-message').empty();

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

	var lineWeightTotal = 0;
	var subLineTotal = 0;

	$(".lineWeight").each(function () {
		lineWeightTotal += parseFloat($(this).val());
	});

	$(".lineTotal").each(function () {
		subLineTotal += parseFloat($(this).val());
	});
  
	if (lineWeightTotal + lineWeightTotal <= 0)
	{
		$('#total_text').text('');
		$('#lineWeightTotal').text('');
		$('#subLineTotal').text('');
	}
	else
	{
		$('#total_text').text('Total : ');
		$('#lineWeightTotal').text(lineWeightTotal.toFixed(2));
		$('#subLineTotal').text(subLineTotal.toFixed(2));
	}

}

function editRow() {
	$(document).on('click', '.edite_row', function () {
		var itemId = this.id;

		clearError();

		var current_edit_row_id = $('#current_edit_row_id').val();

		if (current_edit_row_id)
		{
			var txtItemCode = $('#txtItemCode').val();
			var txtItemName = $('#txtItemName').val();
			var txtQuantity = $('#txtQuantity').val();
			var txtItemId = $('#txtItemId').val();

			var validationVal = {
				"txtItemCode": txtItemCode,
				"txtItemName": txtItemName,
				"txtQuantity": txtQuantity,
				"txtItemId": txtItemId
			};

			var result = validateaddItemRow(validationVal);

			if (result)
			{
				jQuery('#add-new-row').click();

				$('#current_edit_row_id').val(itemId);

				$('#txtItemCode').val($('#item_code_' + itemId).val());
				$('#txtItemName').val($('#item_name_' + itemId).val());
				$('#txtUnitWeight').val($('#unitWeight' + itemId).val());
				$('#txtBuyingPrice').val($('#buyingPrice' + itemId).val());

				$('#txtQuantity').val($('#quantity' + itemId).val());
				$('#txtLineWeight').val($('#lineWeight' + itemId).val());
				$('#txtLineTotal').val($('#lineTotal' + itemId).val());
				$('#txtItemId').val(itemId);

				$("#txtItemCode").attr("disabled", "TRUE");
				$("#txtItemName").attr("disabled", "TRUE");
				$("#item_row_" + itemId).remove();
				$('#add-new-row').text("Save");

				$('html, body').animate({
					scrollTop: $("#scrollTop").offset().top
				}, 700);
			}

		}
		else
		{
			$('#current_edit_row_id').val(itemId);

			$('#txtItemCode').val($('#item_code_' + itemId).val());
			$('#txtItemName').val($('#item_name_' + itemId).val());
			$('#txtUnitWeight').val($('#unitWeight' + itemId).val());
			$('#txtBuyingPrice').val($('#buyingPrice' + itemId).val());

			$('#txtQuantity').val($('#quantity' + itemId).val());
			$('#txtLineWeight').val($('#lineWeight' + itemId).val());
			$('#txtLineTotal').val($('#lineTotal' + itemId).val());
			$('#txtItemId').val(itemId);

			$("#txtItemCode").attr("disabled", "TRUE");
			$("#txtItemName").attr("disabled", "TRUE");
			$("#item_row_" + itemId).remove();
			$('#add-new-row').text("Save");

			$('html, body').animate({
				scrollTop: $("#scrollTop").offset().top
			}, 700);
		}

		setTotal();

	});
}

function deleteSales() {

	$(document).on('click', '.delete-row', function () {

		$("#item_row_" + this.id).remove();

		setTotal();

		return false;
	});
}