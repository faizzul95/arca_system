<form id="formMenu" action="menu/save" method="POST">

	<div class="row">
		<div class="col-12">
			<label class="form-label"> Menu Title <span class="text-danger">*</span></label>
			<input type="text" id="menu_title" name="menu_title" maxlength="50" class="form-control" autocomplete="off" required>
		</div>
	</div>

	<div class="row mt-2">
		<div class="col-12">
			<label class="form-label"> Description <span class="text-danger">*</span></label>
			<textarea id="menu_description" name="menu_description" maxlength="100" rows="3" class="form-control" autocomplete="off" required></textarea>
		</div>
	</div>

	<div class="row mt-2">
		<div class="col-12">
			<label class="form-label"> URL <span class="text-danger">*</span></label>
			<input type="text" id="menu_url" name="menu_url" maxlength="50" class="form-control" autocomplete="off" required>
		</div>
	</div>

	<div class="row mt-2">
		<div class="col-12">
			<label class="form-label"> Icon <span class="text-danger">*</span></label>
			<input type="text" id="menu_icon" name="menu_icon" maxlength="100" class="form-control" autocomplete="off" required>
		</div>
	</div>

	<div class="row mt-2">
		<div class="col-12">
			<label class="form-label"> Menu Location <span class="text-danger">*</span></label>
			<select id="menu_location" name="menu_location" onchange="getMenuOrder();getSubMenu()" class="form-control">
				<option value="0"> Sidebar </option>
				<option value="1"> Header </option>
			</select>
		</div>
	</div>

	<div class="row">
		<div class="col-6 col-sm-6 mt-2">
			<label class="form-label"> Type of Menu <span class="text-danger">*</span></label>
			<select id="is_main_menu" name="is_main_menu" onchange="getSubMenu(this.value)" class="form-control" required>
				<option value="0" selected> Main Menu </option>
				<option value="1"> Sub Menu </option>
			</select>
		</div>

		<div class="col-6 col-sm-6 mt-2">
			<div class="form-group">
				<label class="form-label"> Sub Menu To </label><span id="starSubmenu" class="text-danger" style="display:none"> * </span>
				<select id="sub_menu" name="sub_menu" onchange="getMenuOrder(1, this.value)" class="form-control" disabled>
					<option value=""> - Select - </option>
				</select>
			</div>
		</div>

	</div>

	<div class="row mt-2">

		<div class="col-6 col-sm-6">
			<div class="form-group">
				<label class="form-label"> Menu Arrangement
				</label><span class="text-danger"> * </span>
				<select id="menu_order" name="menu_order" class="form-control" required>
					<option value=""> - Select - </option>
				</select>
			</div>
		</div>

		<div class="col-6 col-sm-6">
			<label class="form-label"> Status <span class="text-danger">*</span></label>
			<select id="is_active" name="is_active" class="form-control" required>
				<option value="1" selected> Active </option>
				<option value="0"> Inactive </option>
			</select>
		</div>

	</div>

	<div class="row mt-2">
		<div class="col-12">
			<span class="text-danger">* Indicates a required field</span>
			<center class="mt-4">
				<input type="hidden" id="menu_id" name="menu_id" placeholder="menu_id" readonly>
				<input type="hidden" name="old_menu_order" id="old_menu_order">
				<button type="submit" id="submitBtn" class="btn btn-info"> <i class='fa fa-save'></i> Save </button>
			</center>
		</div>
	</div>
</form>

<script>
	function getPassData(baseUrl, token, data) {
		var order = null;
		var typeMenu = 0;

		if (data != null) {
			order = data['menu_order'];
			typeMenu = (data['is_main_menu'] != 0) ? 1 : 0;

			if (typeMenu == 1)
				getSubMenu(1, data['is_main_menu']);
		}

		$('#old_menu_order').val(order);
		$('#is_main_menu').val(typeMenu);

		getMenuOrder(typeMenu);
	}

	async function getSubMenu(typeMenu = null, menuid = null) {
		var menu_id_pk = $('#menu_id').val();
		params = (typeMenu == null) ? $('#is_main_menu').val() : typeMenu;

		if (params != 0) {
			$('#sub_menu').prop("disabled", false);
			$('#sub_menu').prop("required", true);
			$('#starSubmenu').show();

			$('#menu_icon').val('#'); // reset
			$('#menu_icon').prop("readonly", true);

			const res = await callApi('post', 'menu/menu-select', {
				menu_id: menu_id_pk,
				menu_location: $('#menu_location').val()
			});

			if (isSuccess(res.status)) {
				$('#sub_menu').html(res.data);
				if (menuid != null) {
					$('#sub_menu').val(menuid);
					getMenuOrder(1, menuid);
				} else {
					getMenuOrder(0, menuid);
				}
			}

			setTimeout(function() {
				$('#menu_order').val('');
				$('#menu_order').prop("disabled", true);
			}, 200);

		} else {
			$('#starSubmenu').hide();
			$('#menu_order').prop("disabled", false);
			$('#sub_menu').prop("disabled", true);
			$('#sub_menu').prop("required", false);
			$('#sub_menu').val(''); // reset

			$('#menu_icon').val(''); // reset
			$('#menu_icon').prop("readonly", false);

			getMenuOrder(0, menuid);
		}
	}

	async function getMenuOrder(typeMenu = null, menuid = null, menu_order = null) {

		var menu_order = (menu_order == null) ? $('#old_menu_order').val() : menu_order;
		var menu_id_pk = $('#menu_id').val();

		const res = await callApi('post', 'menu/menu-order-select', {
			typeMenu: (typeMenu == null) ? $('#is_main_menu').val() : typeMenu,
			menuid: menuid,
			menu_id_pk: menu_id_pk,
			menu_order: menu_order,
			menu_location: $('#menu_location').val()
		});

		if (isSuccess(res.status)) {
			$('#menu_order').html(res.data);
			$('#menu_order').prop("disabled", false);
			$('#old_menu_order').val(menu_order);

			if (menu_order != 1) {
				$('#menu_order').val(menu_order - 1);
				// $('#menu_order').val(menu_order);
			} else {
				$('#menu_order').val(0);
			}

		}

	}

	$("#formMenu").submit(function(event) {
		event.preventDefault();

		if (validateDataMenu()) {
			const form = $(this);
			const url = form.attr('action');

			Swal.fire({
				title: 'Are you sure?',
				html: "Form will be submitted!",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes, Confirm!',
				reverseButtons: true
			}).then(
				async (result) => {
					if (result.isConfirmed) {
						const res = await submitApi(url, form.serializeArray(), 'formMenu');
						if (isSuccess(res)) {

							if (isSuccess(res.data.resCode)) {
								noti(res.status, 'Save');
								getDataList();
							} else {
								noti(400, res.data.message)
							}

						}
					}
				})
		} else {
			validationJsError('toastr', 'single'); // single or multi
		}
	});

	function validateDataMenu() {

		const rules = {
			'menu_title': 'required|min:3|max:50',
			'menu_url': 'required|min:1|max:50',
			'is_main_menu': 'required|integer',
			'menu_order': 'required|integer',
			'menu_icon': 'required|min:1|max:100',
			'is_active': 'required|integer',
		};

		const message = {
			'menu_title': 'Title',
			'menu_url': 'URL',
			'is_main_menu': 'Type Menu',
			'menu_order': 'Arrangement',
			'is_active': 'Status',
		};

		return validationJs(rules, message);
	}
</script>