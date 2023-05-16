<form id="formClearError" action="rbac/clear-error-filter" method="POST">

	<div class="row">
		<div class="col-6">
			<label class="form-label"> Date From </label>
			<input type="date" id="date_from" name="date_from" class="form-control" autocomplete="off">
		</div>
		<div class="col-6">
			<label class="form-label"> Date To </label>
			<input type="date" id="date_to" name="date_to" class="form-control" autocomplete="off">
		</div>
	</div>

	<div class="row mt-2">
		<div class="col-12">
			<label class="form-label"> Type Error <span class="text-danger">*</span></label>
			<select id="type_arror" name="type_arror" class="form-control form-control">
				<option value=""> - Select - </option>
				<option value="Error"> Error </option>
				<option value="Warning"> Warning </option>
				<option value="Parsing Error"> Parsing Error </option>
				<option value="Notice"> Notice </option>
				<option value="Core Error"> Core Error </option>
				<option value="Core Warning"> Core Warning </option>
				<option value="Compile Error"> Compile Error </option>
				<option value="Compile Warning"> Compile Warning </option>
				<option value="User Error"> User Error </option>
				<option value="User Warning"> User Warning </option>
				<option value="User Notice"> User Notice </option>
				<option value="Runtime Notice"> Runtime Notice </option>
				<option value="Catchable error"> Catchable error </option>
			</select>
		</div>
	</div>

	<div class="row mt-2">
		<div class="col-lg-12">
			<span class="text-danger">* Indicates a required field</span>
			<center class="mt-4">
				<button type="submit" id="submitBtn" class="btn btn-outline-danger"> <i class='ri-eraser-line'></i> Remove </button>
				<button type="button" onclick="truncateErrorLog()" class="btn btn-danger"> <i class='ri-delete-bin-line'></i> Truncate Logs </button>
			</center>
		</div>
	</div>
</form>

<script>
	function getPassData(baseUrl, token, data) {}

	async function truncateErrorLog() {
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
					loading('#bodyDiv', true);
					const res = await deleteApi(null, 'rbac/truncate-error-log', getDataList);
					$('#generaloffcanvas-right').offcanvas('toggle');
					// $(modalID).modal('hide');
					loading('#bodyDiv', false);
				}
			})
	}

	$("#formClearError").submit(function(event) {
		event.preventDefault();

		if ($('#date_from').val() != '' || $('#date_to').val() != '' || $('#type_arror').val() != '') {

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
						const res = await submitApi(url, form.serializeArray(), 'formClearError');
						if (isSuccess(res)) {
							if (isSuccess(res.data.resCode)) {
								noti(res.status, 'Remove logs');
								getDataList();
							} else {
								noti(400, res.data.message)
							}
						}
					}
				})
		} else {
			noti(500, "Please select atleast 1 filter(s)")
		}

	});
</script>