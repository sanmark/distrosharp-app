function autoloadItem(csrfToken) {

	$("body").click(function () {
		$('#item_list').empty();
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
			select_item(csrfToken);
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

			var txtReturnItemName = $('#txtReturnItemName').val();
			var itemList = '';

			if (!txtReturnItemName.trim()) {
				$('#loader-img').hide();
				$('#item_list').empty();
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
		select_item(csrfToken);
	});
}
function select_item(csrfToken) {

	var itemId = $(".selected").attr('id');
	$('#item_list').empty();

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