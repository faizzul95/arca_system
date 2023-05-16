<div class="row">

	<p>
	<h3> COPY FROM
		<span id="academicDetails" class="text-uppercase text-bold">
			[academic name]
		</span>
	</h3>
	</p>

	<!-- Saving Indicator -->
	<div id="divShowSaveProgress" style="display: none;">
		<div class="row">

			<div class="col-lg-3 col-md-6">
				<div class="card border card-border-primary card-animate">
					<div class="card-body">
						<div class="d-flex align-items-center">
							<div class="avatar-sm flex-shrink-0">
								<span class="avatar-title bg-light text-primary rounded-circle fs-3">
									<i class="ri-database-line align-middle"></i>
								</span>
							</div>
							<div class="flex-grow-1 ms-3">
								<p class="text-uppercase fw-semibold fs-12 text-muted mb-1"> Total Data </p>
								<h4 class="mb-0"><span id="totalDataSave">0</span></h4>
							</div>
						</div>
					</div><!-- end card body -->
				</div><!-- end card -->
			</div><!-- end col -->

			<div class="col-lg-3 col-md-6">
				<div class="card border card-border-primary card-animate">
					<div class="card-body">
						<div class="d-flex align-items-center">
							<div class="avatar-sm flex-shrink-0">
								<span class="avatar-title bg-light text-success rounded-circle fs-3">
									<i class="ri-file-upload-line align-middle"></i>
								</span>
							</div>
							<div class="flex-grow-1 ms-3">
								<p class="text-uppercase fw-semibold fs-12 text-muted mb-1"> Total Data Processed </p>
								<h4 class="mb-0"><span id="totalDataProcess">0</span></h4>
							</div>
						</div>
					</div><!-- end card body -->
				</div><!-- end card -->
			</div><!-- end col -->

			<div class="col-lg-3 col-md-6">
				<div class="card border card-border-success card-animate">
					<div class="card-body">
						<div class="d-flex align-items-center">
							<div class="avatar-sm flex-shrink-0">
								<span class="avatar-title bg-light text-success rounded-circle fs-3">
									<i class="ri-check-fill align-middle"></i>
								</span>
							</div>
							<div class="flex-grow-1 ms-3">
								<p class="text-uppercase fw-semibold fs-12 text-muted mb-1">Total Successfully Saved
									<i id="viewSuccessTblBtn" role="button" onclick="viewListTable('success')" class="ri-eye-line float-end" style="display: none;" title="Click to view list table"></i>
								</p>
								<h4 class="mb-0"><span id="totalDataSuccess">0</span></h4>
							</div>
						</div>
					</div><!-- end card body -->
				</div><!-- end card -->
			</div><!-- end col -->

			<div class="col-lg-3 col-md-6">
				<div class="card border card-border-danger card-animate">
					<div class="card-body">
						<div class="d-flex align-items-center">
							<div class="avatar-sm flex-shrink-0">
								<span class="avatar-title bg-light text-danger rounded-circle fs-3">
									<i class="ri-error-warning-line align-middle"></i>
								</span>
							</div>
							<div class="flex-grow-1 ms-3">
								<p class="text-uppercase fw-semibold fs-12 text-muted mb-1">Total Failed Saved
									<i id="viewFailTblBtn" role="button" onclick="viewListTable('fail')" class="ri-eye-line float-end" style="display: none;" title="Click to view list table"></i>
								</p>
								<h4 class="mb-0"><span id="totalDataFailed">0</span></h4>
							</div>
						</div>
					</div><!-- end card body -->
				</div><!-- end card -->
			</div><!-- end col -->
		</div>

		<div id="progressbarSaveDiv" style="display : block;">
			<span style="color: #FF0000">
				Information is being saved. Please wait a moment..
			</span>

			<div class="progress mt-2 mb-3">
				<div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" id="progressbarSave" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
			</div>

			<center>
				<img id="processSavingImg" alt="process saving image" class="img-fluid" width="20%">
			</center>
		</div>

		<div id="DisplayImage" style="display : none;">
			<center>
				<p>
				<h2 style="letter-spacing :2px; font-family: 'Quicksand', sans-serif !important"> PROCESS COMPLETED ! </h2>
				<img id="processCompleteImg" alt="process complete image" class="img-fluid" width="25%">
				</p>
			</center>
		</div>

	</div>

	<div id="nodataStudentCopyDiv" style="display: none;"> </div>
	<form id="formStudentCopy" action="student/save-copy" method="POST">

		<div class="col-12">
			<table id="tableList" class="table table-hover table-striped table-bordered">
				<thead class="table-dark">
					<tr>
						<th style="width:5%!important">#</th>
						<th width="30%"> Name </th>
						<th width="10%"> Matric ID </th>
						<th width="10%"> NRIC </th>
						<th width="10%"> Program </th>
						<th width="7%"> Semester </th>
						<th width="8%"> Room No </th>
						<th width="8%"> Bed No </th>
						<th width="12%"> Application </th>
					</tr>
				</thead>
				<tbody></tbody>
			</table>
		</div>

		<div class="row mt-2">
			<div class="col-lg-12">
				<!-- <span class="text-danger">* Indicates a required field</span> -->
				<center class="mt-4">
					<input type="hidden" id="college_id_copy" placeholder="college_id" readonly>
					<input type="hidden" id="branch_id_copy" placeholder="branch_id" readonly>
					<button type="submit" id="submitBtn" class="btn btn-info"> <i class='fa fa-save'></i> Save </button>
				</center>
			</div>
		</div>

	</form>

	<!-- LIST SUCCESS  -->
	<div id="tableListSuccessDiv" class="col-12" style="display : none;">
		<table id="tableListSuccess" class="table table-hover table-striped table-bordered table-responsive">
			<thead class="table-dark">
				<tr>
					<th width="70%!important"> Name </th>
					<th width="15%!important"> Matric ID </th>
					<th width="15%!important"> NRIC </th>
					<th width="15%!important"> Room No / Bed No </th>
				</tr>
			</thead>
			<tbody></tbody>
		</table>
	</div>

	<!-- LIST ERROR  -->
	<div id="tableListErrorDiv" class="col-12" style="display : none;">
		<table id="tableListError" class="table table-hover table-striped table-bordered table-responsive">
			<thead class="table-dark">
				<tr>
					<th width="25%!important"> Name </th>
					<th width="10%!important"> Matric ID </th>
					<th width="10%!important"> NRIC </th>
					<th width="15%!important"> Room No / Bed No </th>
					<th width="50%!important"> Error Message </th>
				</tr>
			</thead>
			<tbody></tbody>
		</table>
	</div>

</div>

<script>
	var tableCopy = null;
	var tableListSuccess = null;
	var tableListError = null;
	var dataListError = [];
	var dataListSuccess = [];
	var width = total_data = totalProgress = totalSave = totalError = 0;

	async function getPassData(baseUrl, token, data) {
		$('#college_id_copy').val(data['college_id']);
		$('#branch_id_copy').val(data['branch_id']);
		$('#nodataStudentCopyDiv').html(nodata());
		$("#divShowSaveProgress").hide();
		$("#processCompleteImg").attr("src", baseUrl + "public/custom/img/success.jpg");
		$("#processSavingImg").attr("src", baseUrl + "public/custom/img/saving.gif");

		getPreviousAcademic(data['academic_order']);
	}

	async function getPreviousAcademic(currentAcademicOrder) {
		const res = await callApi('post', "academic/previous-academic-data", {
			branch_id: $('#branch_id_copy').val(),
			previous_academic_order: parseInt(currentAcademicOrder) - 1
		});

		if (isSuccess(res)) {
			const data = res.data;
			$('#academicDetails').html('<span class="badge badge-soft-secondary badge-border">' + data.academic_display_name + '</span>');
			getDataListStudent(data.academic_id);
		}
	}

	async function getDataListStudent(previousAcademicID) {

		const res = await callApi('post', "student/list-copy", {
			college_id: $('#college_id_copy').val(),
			branch_id: $('#branch_id_copy').val(),
			previous_academic_id: previousAcademicID,
		});

		if (isSuccess(res)) {
			const response = res.data;

			if (response.length > 0) {

				var eligibleStatus = {
					'1': "<span class='badge badge-label bg-success'> Eligible </span>",
					'2': "<span class='badge badge-label bg-warning' data-bs-toggle='tooltip' data-bs-placement='bottom' title='insufficient sticker for college, university and HEP.'> Not Eligible </span>",
				};

				var scrutinizeStatus = {
					'0': "<span class='badge badge-label bg-primary'> Pending </span>",
					'1': "<span class='badge badge-label bg-success'> Offered </span>",
					'2': "<span class='badge badge-label bg-danger'> Unoffered </span>",
				};

				var applyStatus = {
					'0': "<span class='badge badge-label bg-danger' title='This student not apply for college'> Not Apply </span>",
					'1': "<span class='badge badge-label bg-success'> Apply </span>",
				};

				tableCopy = generateDatatable('tableList'); // generate client side datatable
				$.each(response, function(key, value) {
					// check if student is active
					if (response[key].user_status == 1) {
						var checkedStatus = response[key].approval_status == 1 ? 'checked' : '';
						tableCopy.row.add([
							'<center> <input type="checkbox" name="addStudent[]" value="1" data-row="' + key + '" data-name="addStudent" class="batch_input" ' + checkedStatus + '> </center>',
							'<span class="text-truncate">' + trimData(response[key].user_full_name) + '</span>',
							trimData(response[key].user_matric_code),
							trimData(response[key].user_nric),
							trimData(response[key].program_code),
							'<input type="number" name="semester_number[]" value="' + (parseInt(response[key].semester_number) + 1) + '" data-row="' + key + '" data-name="semester_number" class="form-control form-control-sm" min="1" max="15" maxlength="2">',
							'<select type="select" name="college_room_id[]" value="" data-row="' + key + '" data-name="college_room_id" class="form-control form-control-sm select-form" id="college_room_id_copy-' + key + '">\
                                <option value=""> - Select - </option>\
                            </select>',
							'<input type="text" name="college_bed_no[]" value="' + trimData(response[key].college_bed_no) + '" data-row="' + key + '" data-name="college_bed_no" class="form-control form-control-sm" maxlength="10">\
                            <input type="hidden" name="user_id[]" value="' + trimData(response[key].user_id) + '" data-row="' + key + '" data-name="user_id" class="form-control form-control-sm" placeholder="user_id">\
                            <input type="hidden" name="branch_id[]" value="' + trimData(response[key].branch_id) + '"  data-row="' + key + '" data-name="branch_id" class="form-control form-control-sm" placeholder="branch_id">\
                            <input type="hidden" name="college_id[]" value="' + trimData(response[key].college_id) + '" data-row="' + key + '" data-name="college_id" class="form-control form-control-sm" placeholder="college_id">',
							applyStatus[response[key].is_apply] + '' + scrutinizeStatus[response[key].approval_status],
						]).draw();
					}

				});

				await collegeRoomNo()

				$.each(response, function(key, value) {
					$('#college_room_id_copy-' + key).val(trimData(response[key].college_room_id));
				});

			} else {
				$('#nodataStudentCopyDiv').show();
				$('#formStudentCopy').hide();
			}
		}
	}

	$("#formStudentCopy").submit(function(event) {
		event.preventDefault();

		if (validateDataCopy()) {
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

						// get all data from datatable
						const input = tableCopy.$('input, checkbox, select');

						// get row id
						let totalRow = [];
						for (i = 0; i < input.length; i++) {
							var inputRow = input[i].attributes[3].value;
							totalRow.push(inputRow);
						}

						// reduce same id using function distinct
						let dataRow = totalRow.filter(distinct);

						// create new object
						let objData = {};
						for (let key in dataRow) {
							var row = dataRow[key];

							var data = tableCopy.$('input, checkbox, select').filter(function() {
								return $(this).attr('data-row') == dataRow[key];
							});

							let objDataTemp = {};
							let checkboxChecked = true;
							for (let i in data) {
								if (isObject(data[i].attributes)) {

									var inputType = data[i].attributes[0].value;
									var inputRow = data[i].attributes[3].value;
									var inputValue = data[i].value;
									var inputName = data[i].attributes[4].value

									if (inputType == 'checkbox') {
										inputValue = checkboxChecked = data[i].checked;
									}

									objDataTemp[inputName] = inputValue;
								}
							}

							// remove unchecked data
							if (checkboxChecked === true)
								objData[row] = objDataTemp;
						}

						if (Object.keys(objData).length > 0) {
							const chunk = chunkDataObj(objData, 10); // chunck 10 data

							$("#totalDataSave").text(Object.keys(objData).length);
							$("#totalDataProcess").text(0); // reset
							$("#totalDataSuccess").text(0); // reset
							$("#totalDataFailed").text(0); // reset

							$("#divShowSaveProgress").show();
							$("#formStudentCopy").hide();
							$("#modalCloseBtn").hide();

							// call function saveBulk()
							saveCopyStudent(chunk, url, 0, Object.keys(objData).length);
						} else {
							noti(400, 'Please tick atleast one student');
						}
					}
				})

		} else {
			validationJsError('toastr', 'single'); // single or multi
		}
	});

	async function saveCopyStudent(data, url, index, totalData) {

		var total_data = totalData;
		var maxIndex = data.length;

		totalProgress += Object.keys(data[index]).length;

		const res = await callApi('post', url, {
			'dataSave': JSON.stringify(data[index])
		});

		if (isSuccess(res)) {
			const response = res.data;
			totalSave += parseInt(response.countSuccess);
			totalError += parseInt(response.countError);

			response.tableListError.length > 0 ? dataListError.push(response.tableListError) : '';
			response.tableListSuccess.length > 0 ? dataListSuccess.push(response.tableListSuccess) : '';

			$("#totalDataSuccess").text(totalSave);
			$("#totalDataFailed").text(totalError);

			width = Math.round((totalProgress / total_data) * 100);
			$("#totalDataProcess").text(totalProgress);

			$('#progressbarSave').css('width', width + '%');

			index++;

			if (index < maxIndex) {
				setTimeout(function() {
					saveBulk(data, url, index, totalData); // recall function for next data
				}, 2000);
			} else {

				tableSuccess();
				tableError();

				$('#progressbarSave').css('width', '0%');
				$('#progressbarSaveDiv').hide();

				$("#DisplayImage").show();
				$("#modalCloseBtn").show();

				getDataListEnroll();

			}

		}
	}

	function tableSuccess() {
		$('#viewSuccessTblBtn').show();
		tableListSuccess = generateDatatable('tableListSuccess');
		for (index in dataListSuccess) {
			$.each(dataListSuccess[index], function(key, value) {
				tableListSuccess.row.add([
					trimData(dataListSuccess[index][key].user_full_name),
					trimData(dataListSuccess[index][key].user_matric_code),
					trimData(dataListSuccess[index][key].user_nric),
					trimData(dataListSuccess[index][key].college_room_number) + ' / ' + trimData(dataListSuccess[index][key].college_bed_no),
				]).order([
					[1, 'asc']
				]).draw(false);
			});
		}
	}

	function tableError() {
		$('#viewFailTblBtn').show();
		tableListError = generateDatatable('tableListError');
		for (index in dataListError) {
			$.each(dataListError[index], function(key, value) {
				tableListError.row.add([
					trimData(dataListError[index][key].user_full_name),
					trimData(dataListError[index][key].user_matric_code),
					trimData(dataListError[index][key].user_nric),
					trimData(dataListSuccess[index][key].college_room_number) + ' / ' + trimData(dataListSuccess[index][key].college_bed_no),
					trimData(dataListError[index][key].validation),
				]).order([
					[1, 'asc']
				]).draw(false);
			});
		}
	}

	async function collegeRoomNo() {
		const res = await callApi('get', 'college/college-room-select/' + $('#college_id_copy').val());

		if (isSuccess(res)) {
			$('.select-form').html(res.data);
		}
	}

	function validateDataCopy() {

		const rules = {
			'semester_number': 'required|array|integer|min:1|max:15|max_length:2',
			'college_room_id': 'required|array|integer',
			'college_bed_no': 'required|array|min_length:1|max_length:15',
			'user_id': 'required|array|integer',
			'branch_id': 'required|array|integer',
		};

		const message = {
			'semester_number': 'Semester',
			'college_room_id': 'Room',
			'college_bed_no': 'Bed No',
			'user_id': 'Branch ID',
			'branch_id': 'User ID',
		};

		return validationJs(rules, message);
	}

	function viewListTable(type = 'success') {
		$("#refreshBtn").hide();
		$("#DisplayImage").hide();
		$("#tableListSuccessDiv").hide();
		$("#tableListErrorDiv").hide();
		type == 'success' ? $("#tableListSuccessDiv").show() : $("#tableListErrorDiv").show();
	}
</script>