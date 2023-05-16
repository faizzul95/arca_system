<!-- Form Upload Excel -->
<div class="row">

	<div class="col-12 mt-2">
		<p>
			<a id="downloadFormat" href="javascript:void(0)" class="btn btn-primary btn-sm"><i class="ri-download-2-line"></i> Download Format</a>
			<a id="downloadSample" href="javascript:void(0)" class="btn btn-primary btn-sm"><i class="ri-download-2-line"></i> Download Sample Data</a>
		</p>
	</div>

	<div class="col-12 mt-2">

		<form id="uploadBatchStudent" action="import/batch-preview" method="post" enctype="multipart/form-data">
			<div class="form-group row">
				<div class="col-md-12">
					<input type="file" id="fileUpload" name="fileBatch" accept=".xls,.xlsx" class="form-control" onchange="submitFormUpload()" required>
					<input type="hidden" id="role_id_upload" name="role_id" placeholder="role_id" value="6">
				</div>
			</div>
			<div class="col-12 mt-2">
				<span class="text-danger mb-2">
					<i class="ri-alert-line label-icon"></i>
					<strong>Reminder !</strong> Please use format provided from this system only. Upload only file with extension <strong><i style="color: red"> xls/xlsx </i></strong> and files size not greater than <strong><i style="color: red"> 8 MB </i></strong>.
				</span>
			</div>
		</form>

		<div id="uploadFileProgressBar"></div>
		<div id="uploadLoader" class="mt-2" style="display: none;">
			<center><img id="uploadLoaderImg" width="40%" class="img-fluid" alt="loader"></center>
			<br><br>
			<span class="text-danger mb-2">
				<strong>Please wait!</strong> Your data in progress and will be show shortly, please be patient.
			</span>
		</div>

	</div>

</div>

<!-- Saving Indicator -->
<div id="divShowSaveProgress" style="display: none;">
	<div class="row h-100">

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
							<p class="text-uppercase fw-semibold fs-12 text-muted mb-1">
								Total Successfully Saved
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
							<p class="text-uppercase fw-semibold fs-12 text-muted mb-1">
								Total Failed Saved
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
			Information is being saved. This process may take several minutes, please be patient.
		</span>

		<div class="progress mt-2 mb-3">
			<div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" id="progressbarSave" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
		</div>

		<center>
			<img id="processSavingImg" alt="process saving image" class="img-fluid" width="20%">
		</center>
	</div>

	<div id="refreshBtn" style="display : none;">
		<center>
			<p>
			<h2 style="letter-spacing :2px; font-family: 'Quicksand', sans-serif !important"> PROCESS COMPLETED ! </h2>
			<img id="processCompleteImg" alt="process complete image" class="img-fluid" width="25%">
			</p>

			<!-- <a href="javascript:void(0)" onclick="refreshPage()" class="btn btn-md btn-info">
                <i class="fa fa-refresh" aria-hidden="true"></i> Reload the page
            </a> -->
		</center>
	</div>
</div>

<!-- Preview & Form table -->
<div id="showDataPreview" style="display: none;">

	<div class="row h-100">
		<div class="col-lg-4 col-md-6">
			<div class="card border card-border-primary card-animate">
				<div class="card-body">
					<div class="d-flex align-items-center">
						<div class="avatar-sm flex-shrink-0">
							<span class="avatar-title bg-light text-primary rounded-circle fs-3">
								<i class="ri-file-excel-fill align-middle"></i>
							</span>
						</div>
						<div class="flex-grow-1 ms-3">
							<p class="text-uppercase fw-semibold fs-12 text-muted mb-1"> Total Data </p>
							<h4 class="mb-0"><span id="totalDataDisplay">0</span></h4>
						</div>
					</div>
				</div><!-- end card body -->
			</div><!-- end card -->
		</div><!-- end col -->
		<div class="col-lg-4 col-md-6">
			<div class="card border card-border-primary card-animate">
				<div class="card-body">
					<div class="d-flex align-items-center">
						<div class="avatar-sm flex-shrink-0">
							<span class="avatar-title bg-light text-success rounded-circle fs-3">
								<i class="ri-add-circle-fill align-middle"></i>
							</span>
						</div>
						<div class="flex-grow-1 ms-3">
							<p class="text-uppercase fw-semibold fs-12 text-muted mb-1"> Total New Data</p>
							<h4 class="mb-0"><span id="newDataDisplay">0</span></h4>
						</div>
					</div>
				</div><!-- end card body -->
			</div><!-- end card -->
		</div><!-- end col -->
		<div class="col-lg-4 col-md-6">
			<div class="card border card-border-primary card-animate">
				<div class="card-body">
					<div class="d-flex align-items-center">
						<div class="avatar-sm flex-shrink-0">
							<span class="avatar-title bg-light text-info rounded-circle fs-3">
								<i class="ri-refresh-fill align-middle"></i>
							</span>
						</div>
						<div class="flex-grow-1 ms-3">
							<p class="text-uppercase fw-semibold fs-12 text-muted mb-1">Total Data Exist</p>
							<h4 class="mb-0"><span id="updateDataDisplay">0</span></h4>
						</div>
					</div>
				</div><!-- end card body -->
			</div><!-- end card -->
		</div><!-- end col -->
	</div>

	<!-- progress load data -->
	<div id="progressbarLoadDataDiv" class="mt-2" style="display : none;">
		<span style="color: #FF0000">
			Information is being load. Please wait a moment.. <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
		</span>
		<div class="progress mt-2 mb-3">
			<div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" id="progressbarLoadData" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
		</div>
	</div>

</div>

<form id="formStudentBatch" action="user/save-student-bulk" method="POST" style="visibility: hidden;">

	<div class="row">
		<div class="col-lg-12 col-md-12">
			<div class="alert alert-danger alert-label-icon rounded-label fade show" role="alert">
				<i class="ri-alert-line label-icon"></i>
				<strong> Reminder! </strong> Please ensure the data is correct before confirming.
				<button type="submit" id="submitBtn" class="btn btn-info btn-sm float-end mb-4">
					<i class="fa fa-save"></i> Confirm
				</button>
			</div>
		</div>
	</div>

	<div class="col-12">
		<table id="tableList" class="table table-hover table-striped table-bordered table-responsive">
			<thead class="table-dark">
				<tr>
					<th style="width:3%!important">#</th>
					<th width="25%!important"> Name </th>
					<th width="7%!important"> Matric ID </th>
					<th width="8%!important"> NRIC </th>
					<th width="17%!important"> Contact Info </th>
					<th width="25%!important"> Program </th>
					<th width="15%!important"> Others </th>
				</tr>
			</thead>
			<tbody></tbody>
		</table>
	</div>

</form>

<!-- LIST SUCCESS  -->
<div id="tableListSuccessDiv" class="col-12" style="display : none;">
	<table id="tableListSuccess" class="table table-hover table-striped table-bordered table-responsive">
		<thead class="table-dark">
			<tr>
				<th width="66%!important"> Name </th>
				<th width="15%!important"> Matric ID </th>
				<th width="15%!important"> NRIC </th>
				<th width="4%!important"> Action </th>
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
				<th width="50%!important"> Error Message </th>
			</tr>
		</thead>
		<tbody></tbody>
	</table>
</div>

<script>
	var tableUpload = null;
	var tableListSuccess = null;
	var tableListError = null;
	var dataListError = [];
	var dataListSuccess = [];
	var width = total_data = totalProgress = totalSave = totalError = 0;

	function getPassData(baseUrl, token, data) {
		$('#role_id_upload').val(6);

		var format = baseUrl + "public/upload/excel/templates/DATA STUDENT - ARCA Format.xlsx";
		var sampelFormat = baseUrl + "public/upload/excel/templates/[SAMPLE] DATA STUDENT - ARCA Format.xlsx";

		$("#uploadLoaderImg").attr("src", baseUrl + "public/custom/img/excel_loader.gif");
		$("#processCompleteImg").attr("src", baseUrl + "public/custom/img/success.jpg");
		$("#processSavingImg").attr("src", baseUrl + "public/custom/img/saving.gif");
		$("#downloadFormat").attr("href", format);
		$("#downloadSample").attr("href", sampelFormat);
		$("#divShowSaveProgress").hide();
	}

	async function submitFormUpload() {
		if (validateDataUpload()) {

			const form = $("#uploadBatchStudent");
			const url = form.attr('action');
			$('#showDataPreview').hide();
			$('#formStudentBatch').css('visibility', 'hidden');
			$('#uploadBatchStudent').hide();
			$('#uploadLoader').show();

			const res = await uploadApi(url, 'uploadBatchStudent', 'uploadFileProgressBar');

			if (isSuccess(res.status)) {
				const status = res.data.resCode;
				const message = res.data.message;

				if (isSuccess(status)) {
					const countData = res.data.count;
					const response = res.data.data;

					if (countData.all > 0) {
						tableUpload = generateDatatable('tableList');

						$('#uploadLoader').hide();
						$('#showDataPreview').show();
						$('#progressbarLoadDataDiv').show();

						$('#totalDataDisplay').text(countData.all);
						$('#newDataDisplay').text(countData.new);
						$('#updateDataDisplay').text(countData.update);

						var widthLoad = 0;
						var loadDataProgress = 0;

						$.each(response, function(key, value) {

							loadDataProgress++;
							widthLoad = Math.round((loadDataProgress / countData.all) * 100);

							tableUpload.row.add([
								'<center>\
                                <input type="checkbox" name="addStudent[]" value="1" data-row="' + key + '" data-name="addStudent" class="batch_input" checked>\
                                <input type="hidden" name="user_id[]" value="' + trimData(response[key].user_id) + '" data-row="' + key + '" data-name="user_id" class="batch_input">\
                                <input type="hidden" name="user_full_name[]" value="' + trimData(response[key].user_full_name) + '" data-row="' + key + '" data-name="user_full_name" class="batch_input">\
                                <input type="hidden" name="user_nric[]" value="' + trimData(response[key].user_nric) + '" data-row="' + key + '" data-name="user_nric" class="batch_input">\
                                <input type="hidden" name="user_email[]" value="' + trimData(response[key].user_email) + '" data-row="' + key + '" data-name="user_email" class="batch_input">\
                                <input type="hidden" name="user_contact_no[]" value="' + trimData(response[key].user_contact_no) + '" data-row="' + key + '" data-name="user_contact_no" class="batch_input">\
                                <input type="hidden" name="user_gender[]" value="' + trimData(response[key].user_gender) + '" data-row="' + key + '" data-name="user_gender" class="batch_input">\
                                <input type="hidden" name="user_matric_code[]" value="' + trimData(response[key].user_matric_code) + '" data-row="' + key + '" data-name="user_matric_code" class="batch_input">\
                                <input type="hidden" name="program_id[]" value="' + trimData(response[key].program_id) + '" data-row="' + key + '" data-name="program_id" class="batch_input">\
                                <input type="hidden" name="edu_level_id[]" value="' + trimData(response[key].edu_level_id) + '" data-row="' + key + '" data-name="edu_level_id" class="batch_input">\
                                <input type="hidden" name="user_intake[]" value="' + trimData(response[key].user_intake) + '" data-row="' + key + '" data-name="user_intake" class="batch_input">\
                                <input type="hidden" name="user_password[]" value="' + trimData(response[key].user_password) + '" data-row="' + key + '" data-name="user_password" class="batch_input">\
                                <input type="hidden" name="user_status[]" value="' + trimData(response[key].user_status) + '" data-row="' + key + '" data-name="user_status" class="batch_input">\
                                <input type="hidden" name="branch_id[]" value="' + trimData(response[key].branch_id) + '" data-row="' + key + '" data-name="branch_id" class="batch_input">\
                                <input type="hidden" name="role_id[]" value="' + trimData(response[key].role_id) + '" data-row="' + key + '" data-name="role_id" class="batch_input">\
                                <input type="hidden" name="is_main[]" value="' + trimData(response[key].is_main) + '" data-row="' + key + '" data-name="is_main" class="batch_input">\
                                <input type="hidden" name="is_special[]" value="' + trimData(response[key].is_special) + '" data-row="' + key + '" data-name="is_special" class="batch_input">\
                                <input type="hidden" name="profile_id[]" value="' + trimData(response[key].profile_id) + '" data-row="' + key + '" data-name="profile_id" class="batch_input">\
                                <input type="hidden" name="has_position[]" value="' + trimData(response[key].has_position) + '" data-row="' + key + '" data-name="has_position" class="batch_input">\
                                <input type="hidden" name="profile_status[]" value="1" data-row="' + key + '" data-name="profile_status" class="batch_input>\
                                </center>',
								trimData(response[key].user_full_name),
								trimData(response[key].user_matric_code),
								trimData(response[key].user_nric),
								'<b> Email : </b>' + trimData(response[key].user_email) + '<br><b> Phone No. : </b>' + response[key].user_contact_no,
								'<b> Code : </b>' + response[key].program_code + '<br><b> Program Name : </b>' + response[key].program_name + '<br><b> Level : </b>' + response[key].edu_level_name + '<br><b> Intake : </b>' + response[key].user_intake,
								'<b> Gender : </b>' + response[key].user_gender_display + '<b><br> Disability : </b>' + response[key].is_special_display + '<br><b> Club/Association : </b>' + response[key].has_position_display,
							]).order([
								[1, 'asc']
							]).draw(false);

							$('#progressbarLoadData').css('width', widthLoad + '%');

						});

						setTimeout(function() {
							$('#progressbarLoadData').css('width', '0%');
							$('#progressbarLoadDataDiv').hide();
							$('#formStudentBatch').css('visibility', 'visible');
						}, 1500);

					} else {
						$('#uploadBatchStudent').show();
						$('#showDataPreview').hide();
						$('#formStudentBatch').css('visibility', 'hidden');
					}

				} else {
					$('#uploadBatchStudent').show();
					$('#showDataPreview').hide();
					$('#formStudentBatch').css('visibility', 'hidden');
					noti(status, message);
				}

				$('#uploadLoader').hide();

			} else {
				$('#uploadBatchStudent').show();
				$('#showDataPreview').hide();
				$('#uploadLoader').hide();
				noti(res.status);
			}

			$('#fileUpload').val(''); // reset

		} else {
			$('#fileUpload').val(''); // reset
			validationJsError('toastr', 'single'); // single or multi
		}
	};

	function validateDataUpload() {

		const rules = {
			'role_id': 'required|integer',
			'fileBatch': 'required|file|size:8|mimes:xls,xlsx',
		};

		const message = {
			'role_id': 'Role ID',
			'fileBatch': 'File',
		};

		return validationJs(rules, message);
	}

	$("#formStudentBatch").submit(function(event) {
		event.preventDefault();

		$("#divShowSaveProgress").hide();

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
					const input = tableUpload.$('input, checkbox');

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

						var data = tableUpload.$('input, checkbox').filter(function() {
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

					const perChunk = getDataPerChunk(Object.keys(objData).length); // get 10 percent from total data
					const chunk = chunkDataObj(objData, perChunk); // chunck data by per chunk result

					$("#totalDataSave").text(Object.keys(objData).length);
					$("#totalDataProcess").text(0); // reset
					$("#totalDataSuccess").text(0); // reset
					$("#totalDataFailed").text(0); // reset

					$("#divShowSaveProgress").show();
					$("#showDataPreview").hide();
					$("#formStudentBatch").hide();
					$("#modalCloseBtn").hide();

					// call function saveBulk()
					saveBulk(chunk, url, 0, Object.keys(objData).length);
				}
			})


	});

	async function saveBulk(data, url, index, totalData) {

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

				$("#refreshBtn").show();
				$("#modalCloseBtn").show();

				getDataList();
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
					trimData(dataListSuccess[index][key].action),
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
					trimData(dataListError[index][key].validation),
				]).order([
					[1, 'asc']
				]).draw(false);
			});
		}
	}

	function viewListTable(type = 'success') {
		$("#refreshBtn").hide();
		$("#tableListSuccessDiv").hide();
		$("#tableListErrorDiv").hide();
		type == 'success' ? $("#tableListSuccessDiv").show() : $("#tableListErrorDiv").show();
	}
</script>