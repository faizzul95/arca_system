<style>
	.pointer {
		pointer-events: auto !important;
	}
</style>
<div class="row">

	<div class="col-lg-4 col-md-12 fill border-right p-4">
		<div class="row">
			<div class="alert alert-info alert-label-icon rounded-label fade show" role="alert">
				<i class="ri-menu-add-line label-icon"></i><strong> List Menu </strong>
			</div>
		</div>

		<div class="row" id="bodyMenuPermissionDiv">
			<div id="contentMenuList"></div>
			<span class="text-danger mt-4">Remark : Click on <i class="ri-eye-line pointer"></i> icon to view sub menu/abilities </span>

		</div>
	</div>

	<div class="col-lg-4 col-md-12 fill border-right p-4">
		<div class="row">
			<div class="alert alert-info alert-label-icon rounded-label fade show" role="alert">
				<i class="ri-list-settings-fill label-icon"></i><strong> Menu Abilities </strong>
			</div>
		</div>

		<div class="row" id="bodyMenuAbilitiesPermissionDiv">
			<div id="menuAbilitiesContent"></div>
		</div>
	</div>

	<div class="col-lg-4 col-md-12 fill border-right p-4">

		<div class="row">
			<div class="alert alert-primary alert-label-icon rounded-label fade show" role="alert">
				<i class="ri-menu-unfold-line label-icon"></i><strong> List Sub Menu </strong>
			</div>
		</div>

		<div class="row" id="bodySubMenuPermissionDiv">
			<div id="contentSubMenuList"></div>
		</div>

		<div class="row mt-4">
			<div class="alert alert-primary alert-label-icon rounded-label fade show" role="alert">
				<i class="ri-list-settings-fill label-icon"></i><strong> Sub Menu Abilities </strong>
			</div>
		</div>

		<div class="row" id="bodySubMenuAbilitiesPermissionDiv">
			<div id="subMenuAbilitiesContent"></div>
		</div>

	</div>

	<input type="hidden" id="role_id" readonly>

</div>

<script>
	function getPassData(baseUrl, token, data) {
		$('#role_id').val(data['role_id']);

		$('#menuAbilitiesContent').html(noSelectDataLeft('Menu'));
		$('#subMenuAbilitiesContent').html(noSelectDataLeft('Sub Menu'));

		$('#contentSubMenuList').html(noSelectDataLeft('Menu'));
		$('#contentMenuList').html(nodata());

		setTimeout(async function() {
			await getDataListMainMenu();
		}, 80);
	}

	async function getDataListMainMenu() {
		$('.cardColorMenu').addClass("bg-info text-white");
		$('.textColorMenu').addClass("text-white");

		loading('#bodyMenuPermissionDiv', true);
		const res = await callApi('get', 'menu/list-menu-div/' + $('#role_id').val());

		if (isSuccess(res)) {
			$('#contentMenuList').html(res.data);
			loading('#bodyMenuPermissionDiv', false);
		}
	}

	async function getListSubMenu(menuID) {

		$('.cardColorSubMenu').addClass("bg-info text-white");
		$('.textColorSubMenu').addClass("text-white");

		loading('#bodySubMenuPermissionDiv', true);
		const res = await callApi('get', 'menu/list-submenu-div/' + menuID + '/' + $('#role_id').val());

		if (isSuccess(res)) {
			$('#contentSubMenuList').html(res.data.length > 0 ? res.data : nodata(false));
			loading('#bodySubMenuPermissionDiv', false);
		}
	}

	async function getMenuAbilities(menuID) {

		$('#subMenuAbilitiesContent').html(noSelectDataLeft('Sub Menu'));

		$('.cardColorMenu').removeClass("bg-info text-white");
		$('#cardMenu-' + menuID).addClass("bg-info text-white");

		$('.textColorMenu').removeClass("text-white");
		$('#textMenu-' + menuID).addClass("text-white");

		setTimeout(async function() {
			await getListSubMenu(menuID);
		}, 80);

		loading('#bodyMenuAbilitiesPermissionDiv', true);

		const res = await callApi('get', 'rbac/abilities-menu/' + menuID + '/' + $('#role_id').val());

		if (isSuccess(res)) {
			$('#menuAbilitiesContent').html(res.data.length > 0 ? res.data : nodata(false));
			loading('#bodyMenuAbilitiesPermissionDiv', false);
		}
	}

	async function getSubMenuAbilities(menuID) {

		$('.cardColorSubMenu').removeClass("bg-info text-white");
		$('#cardSubMenu-' + menuID).addClass("bg-info text-white");

		$('.textColorSubMenu').removeClass("text-white");
		$('#textSubMenu-' + menuID).addClass("text-white");

		loading('#bodySubMenuAbilitiesPermissionDiv', true);
		const res = await callApi('get', 'rbac/abilities-menu/' + menuID + '/' + $('#role_id').val());

		if (isSuccess(res)) {
			$('#subMenuAbilitiesContent').html(res.data.length > 0 ? res.data : nodata(false));
			loading('#bodySubMenuAbilitiesPermissionDiv', false);
		}
	}

	async function givePermission(roleID, menuID, deviceID) {

		const res = await callApi('post', 'menu/permission', {
			'role_id': roleID,
			'menu_id': menuID,
			'access_device_type': deviceID,
		});

		if (isSuccess(res)) {
			noti(res.data.resCode, res.data.message)
		}
	}

	async function givePermissionAbilities(roleID, abilitiesID) {

		const res = await callApi('post', 'rbac/abilities-assign', {
			'role_id': roleID,
			'abilities_id': abilitiesID,
		});

		if (isSuccess(res)) {
			noti(res.data.resCode, res.data.message)
		}
	}

	function searchListMenu(value, type = 'cardMenu') {
		const searchEl = value.toLowerCase();
		const x = document.querySelectorAll('.' + type + ' > div:nth-child(1) > div:nth-child(1) > div:nth-child(1) > div:nth-child(2)');
		$('.' + type).show();

		if (value != '') {
			x.forEach((list, index) => {
				const data = list.querySelector('h6');
				const title = list.querySelector('h6').innerText.toLowerCase();
				let ids = data.getAttribute('data-card');
				if (!title.includes(searchEl))
					$('#' + ids).hide();
			});
		}
	}
</script>