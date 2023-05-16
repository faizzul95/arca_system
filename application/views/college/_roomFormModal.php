<div class="row">
	<div class="col-lg-4 col-md-12 fill border-right p-4">

		<?php if (permission('college-room-register')) { ?>
			<div class="row">
				<div class="alert alert-info alert-label-icon rounded-label fade show" role="alert">
					<i class="fa fa-edit label-icon"></i><strong> Register / Update Form </strong>
				</div>

				<form id="formRoom" action="college/save-room" method="POST">

					<div class="row">

						<div class="col-6">
							<label class="form-label"> Room No <span class="text-danger">*</span></label>
							<input type="text" id="college_room_number" name="college_room_number" maxlength="10" class="form-control" autocomplete="off" onkeyup="this.value = this.value.toUpperCase();" required>
						</div>

						<div class="col-6">
							<label class="form-label"> Allocation / Total Bed <span class="text-danger">*</span></label>
							<input type="text" id="college_room_allocation" name="college_room_allocation" maxlength="2" onkeypress="isNumeric()" class="form-control" autocomplete="off" required>
						</div>

					</div>

					<div class="row mt-2">
						<div class="col-6">
							<label class="form-label"> Level <span class="text-danger">*</span></label>
							<select id="college_level_id" name="college_level_id" class="form-control" required>
								<option value=""> - Select - </option>
							</select>
						</div>

						<div class="col-6">
							<label class="form-label"> Status <span class="text-danger">*</span></label>
							<select id="college_room_status" name="college_room_status" class="form-control" required>
								<option value="" selected> - Select - </option>
								<option value="1"> Active </option>
								<option value="0"> Inactive </option>
							</select>
						</div>
					</div>

					<div class="row mt-2 mb-2">
						<div class="col-lg-12">
							<span class="text-danger">* Indicates a required field</span>
							<center class="mt-4">
								<input type="hidden" id="college_room_id" name="college_room_id" placeholder="college_room_id" readonly>
								<input type="hidden" id="college_id_room" name="college_id" placeholder="college_id" readonly>
								<button type="button" class="btn btn-soft-danger" onclick="clearForm()"> Reset </button>
								<button type="submit" id="submitBtn" class="btn btn-info"> <i class='fa fa-save'></i> Save </button>
							</center>
						</div>
					</div>
				</form>

			</div>
		<?php } ?>

		<input type="hidden" id="college_id_master" readonly>

	</div>
	<div class="col-lg-8 col-md-12 fill border-right p-4 overflow-hidden">
		<div class="card ribbon-box border shadow-none mb-lg-0" id="bodyCollegeRoomDiv">

			<div class="card-header">
				<span class="ribbon ribbon-primary ribbon-shape"><span> List Room </span></span>
				<button type="button" class="btn btn-warning btn-sm float-end" onclick="getDataListRoom()" title="Refresh">
					<i class="ri-refresh-line"></i>
				</button>
			</div>

			<div class="card-body">
				<!-- <div data-simplebar style="width: auto;height: calc(100vh - 112px);overflow-x: hidden"> -->
				<div id="nodataCollegeRoomDiv" style="display: none;"></div>
				<div id="dataListCollegeRoomDiv" class="card-datatable table-responsive" style="display: none;">
					<table id="dataListCollegeRoom" class="table table-hover table-striped table-bordered" width="100%">
						<thead class="table-dark">
							<tr>
								<th> Room No </th>
								<th> Allocation / Total Bed </th>
								<th> Level </th>
								<th> Status </th>
								<th> Action </th>
							</tr>
						</thead>
						<tbody id="contentCollegeRoom"></tbody>
					</table>
				</div>
				<!-- </div> -->
			</div>

		</div>
	</div>
</div>

<script>
	function getPassData(baseUrl, token, data) {
		$('#college_id_master').val(data['college_id']);
		$('#nodataCollegeRoomDiv').html(nodata());
		$('#contentCollegeRoom').empty();
		getDataListRoom();
		getCollegeLevel();
	}

	async function getDataListRoom() {
		$('#college_id_room').val($('#college_id_master').val());
		var college_id = $('#college_id_master').val();
		loading('#bodyCollegeRoomDiv', true);

		generateDatatable('dataListCollegeRoom', 'serverside', 'college/list-room', 'nodataCollegeRoomDiv', {
			college_id: college_id
		});

		loading('#bodyCollegeRoomDiv', false);
	}

	async function getCollegeLevel() {
		const res = await callApi('get', 'management/college-level-select');

		if (isSuccess(res)) {
			$('#college_level_id').html(res.data);
		}
	}

	async function updateCollegRoomRecord(id) {
		const res = await callApi('get', 'college/show-room/' + id);

		if (isSuccess(res)) {
			const data = res.data;
			$('#college_room_id').val(data['college_room_id']);
			$('#college_room_number').val(data['college_room_number']);
			$('#college_room_allocation').val(data['college_room_allocation']);
			$('#college_room_status').val(data['college_room_status']);
			$('#college_level_id').val(data['college_level_id']);
		}
	}

	async function deleteCollegeRoomRecord(id) {
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
					loading('#bodyCollegeRoomDiv', true);
					const res = await deleteApi(id, 'college/delete-room', getDataListRoom);
					getDataList();
					loading('#bodyCollegeRoomDiv', false);
				}
			})
	}

	function clearForm() {
		$('#college_room_number').val('');
		$('#college_room_allocation').val('');
		$('#college_room_status').val('');
		$('#college_room_id').val('');
		$('#college_level_id').val('');
		$('#college_id_room').val($('#college_id_master').val());
	}

	$("#formRoom").submit(function(event) {
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
						const res = await submitApi(url, form.serializeArray(), 'formRoom', null, false);
						if (isSuccess(res)) {

							if (isSuccess(res.data.resCode)) {
								noti(res.status, 'Save');
								clearForm();
								document.getElementById("formRoom").reset();
								getDataList();
								getDataListRoom();
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

	function validateDataAbilities() {

		const rules = {
			'college_room_number': 'required|min:1|max:10',
			'college_room_allocation': 'required|integer|min_length:1|max_length:2',
			'college_room_status': 'required|integer',
			'college_level_id': 'required|integer',
			'college_id': 'required|integer',
		};

		const message = {
			'college_room_number': 'Room No.',
			'college_room_allocation': 'Allocation',
			'college_room_status': 'Status',
			'college_level_id': 'Level',
			'college_id': 'College ID',
		};

		return validationJs(rules, message);
	}
</script>