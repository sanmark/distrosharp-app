
$(document).keydown(function (e) {
	
	$('#dublicate-error-message').empty();

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
		$('#txtTransfer').focus();
		e.preventDefault();
	}
});


function clearError() {

	$("input").removeClass("duplicate-error");
}

function setMethodToEnter() {

	$(document).on("keypress", 'form', function (e) {
		var code = e.keyCode || e.which;
		var target_id = event.target.id;
		if (code === 13) {
			if (target_id === 'txtTransfer') {
				var transferVal = $('#txtTransfer').val();
				var itemCode = $('#txtItemCode').val();
				var itemName = $('#txtItemName').val();
				if (transferVal == '' || itemCode == '' || itemName == '')
				{
					var html_message = "";
					html_message += "<div id='return-exit-message'>";
					html_message += "Please enter transfer/unload details";
					html_message += "</div>";

					$('#dublicate-error-message').append(html_message);

					setTimeout(function () {
						$("div").removeClass("duplicate-error");
						$('#dublicate-error-message').empty();
						clearError();
					}, 3000);
					return false;
				}
				jQuery('#add-new-transfer').click();
			}

			e.preventDefault();
			return false;
		}

	});

}

function autoloadItemForTransfer(csrfToken) {
	var isUnload = $("#isUnload").val();

	$("body").click(function () {
		$('#item_list_f_transfer').empty();
	});

	$('#txtItemName').keyup(function (event) {

		$('#txtItemCode').val("");
		$('#txtAvailable').val("");
		$('#txtTransfer').val("");
		$('#txtItemId').val("");
		$("#txtImbalanceTransfer").val("");
		$("#txtTargetQuantity").val("");

		if (event.keyCode === 13) { // enter 
			select_item_sales(csrfToken);
		}

		else if (event.keyCode === 38) { // up
			var selected = $(".selected");
			$("#item_list_f_transfer li").removeClass("selected");
			if (selected.prev().length === 0) {
				selected.siblings().last().addClass("selected");
			} else {
				selected.prev().addClass("selected");
			}
		}

		else if (event.keyCode === 40) { // down
			var selected = $(".selected");
			$("#item_list_f_transfer li").removeClass("selected");
			if (selected.next().length === 0) {
				selected.siblings().first().addClass("selected");
			} else {
				selected.next().addClass("selected");
			}
		}

		else {

			$('#loader-img').show();
			$('#item_list_f_transfer').empty();

			var txtItemName = $('#txtItemName').val();
			var fromStock = $('#fromStock').val();
			var itemList = '';

			if (!txtItemName.trim()) {
				$('#loader-img').hide();
				$('#item_list_f_transfer').empty();
			}
			else {
				if (isUnload == 1)
				{
					var route = "/stocks/ajax/getItemByName";
					var fromStockId = fromStock;
				}
				else
				{
					var route = "/entities/items/ajax/getItemByName";
					var fromStockId = null;
				}
				$.post(route
					, {
						_token: csrfToken,
						itemName: txtItemName,
						stock_id: fromStockId
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

					$('#item_list_f_transfer').empty();
					$('#item_list_f_transfer').append(itemList);

				});
			}
		}
	});

	$("#item_list_f_transfer").on("mouseover", "#item_list_f_transfer li", function (event) {
		if ($(event.target).attr('class') !== 'error')
		{
			$("#item_list_f_transfer li").removeClass("selected");
			$(this).addClass("selected");
		}
	}).click(function () {
		select_item_sales(csrfToken);
	});
}

function select_item_sales(csrfToken) {

	var itemId = $(".selected").attr('id');
	var fromStockId = $("#fromStock").val();
	var toStockId = $("#toStock").val();
	$('#item_list_f_transfer').empty();

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
			$("#txtItemName").addClass('duplicate-error');
			$("#txtTransfer").addClass('duplicate-error');
			$('#salse_item_row_' + data[0].id).children('.row').addClass('duplicate-error');

			$('#txtAvailable').val("");
			$('#txtTransfer').val("");
			$('#txtItemId').val("");
			$("#txtImbalanceTransfer").val("");
			$("#txtTargetQuantity").val("");
			$("#txtItemId").val("");

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
		$('#txtTransfer').focus();
	});

	$.post(
		"/processes/transfers/ajax/getAvailableQuantity", {
			_token: csrfToken,
			itemId: itemId,
			fromStock_id: fromStockId,
		},
		function (data)
		{
			$('#txtAvailable').val(data);

		});
	$.post(
		"/processes/transfers/ajax/getTargetStockQuantity", {
			_token: csrfToken,
			itemId: itemId,
			toStock_id: toStockId,
		},
		function (data)
		{
			$('#txtTargetQuantity').val(data);

		});
}


function addTransferRow() {

	$('#txtItemCode').keyup(function () {
		$('#txtItemName').val("");
		$('#txtAvailable').val("");
		$('#txtTransfer').val("");
		$('#txtItemId').val("");
		$("#txtImbalanceTransfer").val("");
		$("#txtTargetQuantity").val("");
	});

	$('#add-new-transfer').click(function (event) {

		var transferVal = $('#txtTransfer').val();
		var itemCode = $('#txtItemCode').val();
		var itemName = $('#txtItemName').val();
		if (transferVal == '' || itemCode == '' || itemName == '')
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

		var txtItemCode = $('#txtItemCode').val();
		var txtItemName = $('#txtItemName').val();
		var txtAvailable = $('#txtAvailable').val();
		var txtTransfer = $('#txtTransfer').val();
		var txtItemId = $('#txtItemId').val();
		var txtImbalanceTransfer = $('#txtImbalanceTransfer').val();
		var txtTargetQuantity = $('#txtTargetQuantity').val();
		var isUnload = $('#isUnload').val();

		var htmlOutput = '';
		htmlOutput += '<div id="salse_item_row_' + txtItemId + '" class="row item-list-table">';
		htmlOutput += '<div class="col-sm-8">';
		htmlOutput += '<div class="row">';
		htmlOutput += '<div class="col-sm-2">';
		htmlOutput += '<b>' + txtItemCode + '</b>';
		htmlOutput += '<input type="hidden" name="itemCode_' + txtItemId + '" value="' + txtItemCode + '" id="itemCode_' + txtItemId + '"/>';
		htmlOutput += '</div>';
		htmlOutput += '<div class="col-sm-6 item-name">';
		htmlOutput += '<b>' + txtItemName + '</b>';
		htmlOutput += '<input type="hidden" name="itemName_' + txtItemId + '" value="' + txtItemName + '" id="itemName_' + txtItemId + '"/>';
		htmlOutput += '<input type="hidden" name="itemId_' + txtItemId + '" value="' + txtItemId + '" id="itemId_' + txtItemId + '" class="submitIds"/>';
		htmlOutput += '</div>';
		htmlOutput += '<div class="col-sm-2 text-right">';
		htmlOutput += '<b>' + txtAvailable + '</b>';
		htmlOutput += '<input type="hidden" name="availale_amounts[' + txtItemId + ']" value="' + txtAvailable + '" id="available_' + txtItemId + '"/>';
		htmlOutput += '</div>';

		htmlOutput += '<div class="col-sm-2 text-right item-transfer">';
		htmlOutput += '<b>' + txtTransfer + '</b>';
		htmlOutput += '<input type="hidden" name="transfer_amounts[' + txtItemId + ']" value="' + txtTransfer + '" id="transfer_' + txtItemId + '"/>';
		htmlOutput += '</div>';
		htmlOutput += '</div>';
		htmlOutput += '</div>';
		htmlOutput += '<div class="col-sm-4">';
		htmlOutput += '<div class="row">';
		if (isUnload) {
			htmlOutput += '<div class="col-sm-4 unload text-right">';
			htmlOutput += '<b>' + txtImbalanceTransfer + '</b>';
			htmlOutput += '<input type="hidden" name="itemImTransfer_' + txtItemId + '"  value="' + txtImbalanceTransfer + '" id="itemImTransfer_' + txtItemId + '"/>';
			htmlOutput += '</div>';
		}

		htmlOutput += '<div class="col-sm-4 text-right">';
		htmlOutput += '<b>' + txtTargetQuantity + '</b>';
		htmlOutput += '<input type="hidden" name="itemTarget_' + txtItemId + '" value="' + txtTargetQuantity + '" id="itemTarget_' + txtItemId + '"/>';
		htmlOutput += '<input type="hidden" id="item_code_' + txtItemId + '" value="' + txtItemCode + '"/>';
		htmlOutput += '<input type="hidden" id="item_name_' + txtItemId + '" value="' + txtItemName + '"/>';
		htmlOutput += '</div>';


		htmlOutput += '<div class="col-sm-4">';
		htmlOutput += '<a title="Click to edit ' + txtItemName + ' "  class="edit-transfer" id=' + txtItemId + '> Edit/</a>';
		htmlOutput += '<a title="Click to delete ' + txtItemName + ' "   class="delete-transfer"  id=' + txtItemId + '>Delete</a>';
		htmlOutput += '</div>';
		htmlOutput += '</div>';
		htmlOutput += '</div>';
		htmlOutput += '</div>';

		$('#table-sales-list').append(htmlOutput);

		$('#txtItemCode').val("");
		$('#txtItemName').val("");
		$('#txtAvailable').val("");
		$('#txtTransfer').val("");
		$('#txtItemId').val("");
		$("#txtImbalanceTransfer").val("");
		$("#txtTargetQuantity").val("");
		$('#current_edit_sales_id').val("");
		$("#txtItemCode").focus();

		$('#add-new-transfer').text("Add");
		$("#txtItemCode").removeAttr("disabled");
		$("#txtItemName").removeAttr("disabled");
	});
}

function selectTransferItem(csrfToken) {
	$(window).keydown(function (event) {

		var valPreventDefault = false;

		if (event.keyCode === 13) {

			if (event.target.id === 'txtItemCode') {

				valPreventDefault = true;

				var txtItemCode = $('#txtItemCode').val();
				var isUnload = $("#isUnload").val();
				var fromStock = $('#fromStock').val();

				if (isUnload == 1)
				{
					var route = "/stocks/ajax/getItemByCode";
					var fromStockId = fromStock;
				}
				else
				{
					var route = "/entities/items/ajax/getItemByCode";
					var fromStockId = null;
				}


				$.post(
					route, {
						_token: csrfToken,
						itemCode: txtItemCode,
						stock_id: fromStockId
					},
				function (data)
				{
					if (data.length !== 0)
					{
						if (typeof data.length == 'undefined')
						{
							var html_message = "";
							html_message += "<div id='return-exit-message'>";
							html_message += data['availability'];
							html_message += "</div>";

							$('#dublicate-error-message').append(html_message);

							return false;
						}
						clearError();

						if ($('#item_code_' + data[0].id).length !== 0) {

							$('#dublicate-error-message').append(html_message);
							$("#txtItemCode").addClass('duplicate-error');
							$("#txtItemName").addClass('duplicate-error');
							$("#txtTransfer").addClass('duplicate-error');
							$('#salse_item_row_' + data[0].id).children('.row').addClass('duplicate-error');
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
							$('#txtTransfer').focus();

							var itemId = data[0].id;
							var fromStockId = $("#fromStock").val();
							var toStockId = $("#toStock").val();

							$.post(
								"/processes/transfers/ajax/getAvailableQuantity", {
									_token: csrfToken,
									itemId: itemId,
									fromStock_id: fromStockId,
								},
								function (data)
								{
									$('#txtAvailable').val(data);

								});
							$.post(
								"/processes/transfers/ajax/getTargetStockQuantity", {
									_token: csrfToken,
									itemId: itemId,
									toStock_id: toStockId,
								},
								function (data)
								{
									$('#txtTargetQuantity').val(data);

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
						$('#txtTransfer').val("");
						$('#txtItemId').val("");
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

function editTransferItem() {
	$(document).on('click', '.edit-transfer', function () {
		var itemId = this.id;

		clearError();

		var current_edit_sales_id = $('#current_edit_sales_id').val();
		if (current_edit_sales_id) {
			var txtItemCode = $('#txtItemCode').val();
			var txtItemName = $('#txtItemName').val();
			var txtAvailable = $('#txtAvailable').val();
			var txtTransfer = $('#txtTransfer').val();
			var txtImbalanceTransfer = $('#txtImbalanceTransfer').val();
			var txtTargetQuantity = $('#txtTargetQuantity').val();
			var txtItemId = $('#txtItemId').val();

			jQuery('#add-new-transfer').click();

			$('#current_edit_sales_id').val(itemId);

			$('#txtItemCode').val($('#itemCode_' + itemId).val());
			$('#txtItemName').val($('#itemName_' + itemId).val());
			$('#txtAvailable').val($('#available_' + itemId).val());
			$('#txtTransfer').val($('#transfer_' + itemId).val());

			$('#txtImbalanceTransfer').val($('#itemImTransfer_' + itemId).val());
			$('#txtTargetQuantity').val($('#itemTarget_' + itemId).val());
			$('#txtItemId').val(itemId);

			$("#txtItemCode").attr("disabled", "TRUE");
			$("#txtItemName").attr("disabled", "TRUE");
			$("#salse_item_row_" + itemId).remove();
			$('#add-new-transfer').text("Save");

			$('html, body').animate({
				scrollTop: $("#scrollTopTransfers").offset().top
			}, 700);
			$('#txtTransfer').focus();

		} else {


			$('#current_edit_sales_id').val(itemId);

			$('#txtItemCode').val($('#itemCode_' + itemId).val());
			$('#txtItemName').val($('#itemName_' + itemId).val());
			$('#txtAvailable').val($('#available_' + itemId).val());
			$('#txtTransfer').val($('#transfer_' + itemId).val());

			$('#txtImbalanceTransfer').val($('#itemImTransfer_' + itemId).val());
			$('#txtTargetQuantity').val($('#itemTarget_' + itemId).val());
			$('#txtItemId').val(itemId);

			$("#txtItemCode").attr("disabled", "TRUE");
			$("#txtItemName").attr("disabled", "TRUE");
			$("#salse_item_row_" + itemId).remove();
			$('#add-new-transfer').text("Save");

			$('html, body').animate({
				scrollTop: $("#scrollTopTransfers").offset().top
			}, 700);
			$('#txtTransfer').focus();
		}

	});
}

function deleteTransferItem() {

	$(document).on('click', '.delete-transfer', function () {

		$("#salse_item_row_" + this.id).remove();

		return false;
	});
}

function validateUnloadOnSubmit()
{
	$("#transferForm").submit(function (event) {

		$('#dublicate-error-message').empty();
		
		var submitItems = $("#table-sales-list").find(".submitIds").map(function () {
			return this.value;
		}).get();

		var loadedItems = $('#loadedItems').val();

		var loadedarray = JSON.parse(loadedItems);

		var unloadable = [];
		for (var i = 0; i < loadedarray.length; i++)
		{
			if (jQuery.inArray(loadedarray[i], submitItems) == -1) {
				unloadable.push(loadedarray[i]);
			}
		}
		if (unloadable.length != 0)
		{
			var loadedItemNames = $('#loadedItemNames').val();
			var loadedItemNames = jQuery.parseJSON(loadedItemNames);
			var unloadableItemNames = [];
			for (var i = 0; i < unloadable.length; i++)
			{
				unloadableItemNames.push(loadedItemNames[unloadable[i]]);
			}
			$("#txtItemCode").addClass('duplicate-error');
			if (unloadableItemNames.length > 3)
			{
				var html_message = "";
				html_message += "<div id='return-exit-message'>";
				html_message += "Please Unload " + unloadableItemNames.slice(0, 3) + " etc...";
				html_message += "</div>";
			}
			else
			{
				var html_message = "";
				html_message += "<div id='return-exit-message'>";
				html_message += "Please Unload " + unloadableItemNames;
				html_message += "</div>";
			}

			$('html, body').animate({
				scrollTop: $("#scrollTopTransfers").offset().top
			}, 700);

			$('#dublicate-error-message').append(html_message);

			$("#txtItemCode,#txtItemName").keyup(function () {
				$('#dublicate-error-message').empty();
			});

			event.preventDefault();
			return false;
		}
		else
		{
			return true;
		}
	});
}
