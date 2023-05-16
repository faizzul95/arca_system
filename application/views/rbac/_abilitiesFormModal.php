<div class="row">

	<div class="col-lg-4 col-md-12 fill border-right p-4">

		<div class="row">
			<div class="alert alert-info alert-label-icon rounded-label fade show" role="alert">
				<i class="fa fa-edit label-icon"></i><strong> Register / Update Form </strong>
			</div>

			<form id="formAbilities" action="rbac/abilities-save" method="POST">

				<div class="row">

					<div class="col-6 col-sm-6">
						<label class="form-label"> Abilities Name <span class="text-danger">*</span></label>
						<input type="text" id="abilities_name" name="abilities_name" maxlength="50" class="form-control" autocomplete="off" required>
					</div>

					<div class="col-6 col-sm-6">
						<label class="form-label"> Slug <span class="text-danger">*</span></label>
						<input type="text" id="abilities_slug" name="abilities_slug" maxlength="50" class="form-control" autocomplete="off" required>
					</div>

				</div>

				<div class="row mt-2">
					<div class="col-lg-12">
						<label class="form-label"> Description <span class="text-danger">*</span></label>
						<textarea id="abilities_desc" name="abilities_desc" maxlength="100" rows="3" class="form-control" autocomplete="off" required></textarea>
					</div>
				</div>

				<div class="row mt-2 mb-2">
					<div class="col-lg-12">
						<span class="text-danger">* Indicates a required field</span>
						<center class="mt-4">
							<input type="hidden" id="abilities_id" name="abilities_id" placeholder="abilities_id" readonly>
							<input type="hidden" id="menu_id_abilities" name="menu_id" placeholder="menu_id" readonly>
							<button type="button" class="btn btn-soft-danger" onclick="clearForm()"> Reset </button>
							<button type="submit" id="submitBtn" class="btn btn-info"> <i class='fa fa-save'></i> Save </button>
						</center>
					</div>
				</div>
			</form>

			<input type="hidden" id="menu_id_master" readonly>
		</div>

	</div>

	<div class="col-lg-8 col-md-12 fill border-right p-4 overflow-hidden">
		<div class="card ribbon-box border shadow-none mb-lg-0" id="bodyAbilitiesDiv">

			<div class="card-header">
				<span class="ribbon ribbon-primary ribbon-shape"><span> Abilities </span></span>
				<button type="button" class="btn btn-warning btn-sm float-end" onclick="getDataListAbilities()" title="Refresh">
					<i class="ri-refresh-line"></i>
				</button>
			</div>

			<div class="card-body">
				<!-- <div data-simplebar style="width: auto;height: calc(100vh - 112px);overflow-x: hidden"> -->
				<div id="nodataAbilitiesDiv" style="display: none;"></div>
				<div id="dataListAbilitiesDiv" class="card-datatable table-responsive" style="display: none;">
					<table id="dataListAbilities" class="table table-hover table-striped table-bordered" width="100%">
						<thead class="table-dark">
							<tr>
								<th> Name </th>
								<th> Slug </th>
								<th> Description </th>
								<th> Role </th>
								<th> Action </th>
							</tr>
						</thead>
						<tbody id="contentAbilities"></tbody>
					</table>
				</div>
				<!-- </div> -->
			</div>

		</div>
	</div>
</div>

<script>
	function getPassData(baseUrl, token, data) {
		$('#menu_id_master').val(data['menu_id']);
		$('#nodataAbilitiesDiv').html(nodata());
		$('#contentAbilities').empty();
		getDataListAbilities();
	}

	async function getDataListAbilities() {
		$('#menu_id_abilities').val($('#menu_id_master').val());
		var menu_id = $('#menu_id_master').val();
		loading('#bodyAbilitiesDiv', true);

		generateDatatable('dataListAbilities', 'serverside', 'rbac/list-abilities', 'nodataAbilitiesDiv', {
			menu_id: menu_id
		});

		loading('#bodyAbilitiesDiv', false);
	}

	async function updateAbilitiesRecord(id) {
		const res = await callApi('get', 'rbac/abilities/' + id);

		if (isSuccess(res)) {
			const data = res.data;
			$('#abilities_id').val(data['abilities_id']);
			$('#abilities_name').val(data['abilities_name']);
			$('#abilities_slug').val(data['abilities_slug']);
			$('#abilities_desc').val(data['abilities_desc']);
		}
	}

	async function deleteAbilitiesRecord(id) {
		Swal.fire({
			title: 'Are you sure?',
			html: "You won't be able to revert this!",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, Confirm!',
			reverseButtons: true
		}).then(
			async (result) => {
				if (result.isConfirmed) {
					loading('#bodyAbilitiesDiv', true);
					const res = await deleteApi(id, 'rbac/delete-abilities', getDataListAbilities);
					loading('#bodyAbilitiesDiv', false);
				}
			})
	}

	function clearForm() {
		$('#abilities_name').val('');
		$('#abilities_slug').val('');
		$('#abilities_desc').val('');
		$('#abilities_id').val('');
		$('#menu_id_abilities').val($('#menu_id_master').val());
	}

	$("#formAbilities").submit(function(event) {
		event.preventDefault();

		if (validateDataAbilities()) {
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
						const res = await submitApi(url, form.serializeArray(), 'formAbilities', null, false);
						if (isSuccess(res)) {

							if (isSuccess(res.data.resCode)) {
								noti(res.status, 'Save');
								clearForm();
								document.getElementById("formAbilities").reset();
								getDataListAbilities();
							} else {
								noti(500, res.data.message)
							}

						}
					}
				})
		} else {
			validationJsError('toastr', 'single'); // single or multi
		}
	});

	function validateDataAbilities() {

		const rules = {
			'abilities_name': 'required|min:5|max:50',
			'abilities_slug': 'required|min:5|max:100',
			'abilities_id': 'integer',
			'menu_id': 'required|integer',
		};

		const message = {
			'abilities_name': 'Name',
			'abilities_slug': 'Slug',
			'abilities_id': 'Abilities',
			'menu_id': 'Menu',
		};

		return validationJs(rules, message);
	}
</script>