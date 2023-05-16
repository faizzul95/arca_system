<div class="row">

	<!-- <div class="col-xl-4 mb-4">
        <div class="card ribbon-box border shadow-none mb-lg-0" id="bodyFilesDiv">
            <div class="card-header">
                <span class="ribbon ribbon-primary ribbon-shape"><span>Files</span></span>
                <button type="button" class="btn btn-warning btn-sm float-end" onclick="getDataList()" title="Refresh">
                    <i class="ri-refresh-line"></i>
                </button>
            </div>
            <div class="card-body">
            </div>
        </div>
    </div> -->

	<div class="col-xl-12 mb-4">
		<div class="card ribbon-box border shadow-none mb-lg-0" id="bodyEditorDiv">
			<div class="card-header">
				<span class="ribbon ribbon-primary ribbon-shape"><span>Editor</span></span>
			</div>
			<div class="card-body">
				<form id="formEditor" action="rbac/save-editor" method="POST">
					<?php
					$filesPath = BASEPATH . '../.env';
					$myfile = fopen($filesPath, "r+") or die("Unable to open file!");
					echo '<textarea id="content" name="content" class="form-control">';
					while (!feof($myfile)) {
						echo fgets($myfile) . "<br>";
					}
					echo '</textarea>';
					fclose($myfile);
					?>
					<center class="mt-4">
						<button type="submit" id="submitBtn" class="btn btn-success"> <i class='fa fa-save'></i> Save </button>
					</center>
				</form>
			</div>
		</div>
	</div>

</div>

<script type="text/javascript">
	$(document).ready(function() {
		// CKEDITOR.replace('content');
		ClassicEditor
			.create(document.querySelector('#content'))
			.then(editor => {
				console.log(editor);
			})
			.catch(error => {
				console.error(error);
			});
	});

	$("#formEditor").submit(function(event) {
		event.preventDefault();

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
					const res = await submitApi(url, form.serializeArray(), 'formEditor', null, false);
					if (isSuccess(res)) {

						// if (isSuccess(res.data.resCode)) {
						//     noti(res.status, 'Save');
						//     getDataList();
						// } else {
						//     noti(500, res.data.message)
						// }

						// setTimeout(function() {
						//     $('#generalModal-lg').modal('hide');
						// }, 200);
					}
				}
			});

	});
</script>
