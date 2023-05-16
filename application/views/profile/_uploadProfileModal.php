<!-- upload profile -->
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.js"></script> -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.css" integrity="sha512-zxBiDORGDEAYDdKLuYU9X/JaJo/DPzE42UubfBw9yg8Qvb2YRRIQ8v4KsGHOx2H1/+sdSXyXxLXv5r7tHc9ygg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js" integrity="sha512-Gs+PsXsGkmr+15rqObPJbenQ2wB3qYvTHuJO6YJzPe/dTLvhy0fmae2BcnaozxDo5iaF8emzmCZWbQ1XXiX2Ig==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<div class="row">
	<form id="changeProfilePicture" method="POST" action="user/profile-upload">
		<div class="col-12">
			<input id="image" type="file" name="change_image" class="form-control mb-4" accept="image/x-png,image/jpeg,image/jpg">

			<div style="position: relative; display:inline-block;">
				<div id="resizer" class="mt-2"></div>
				<center>
					<button type="button" class="btn btn-sm btn-outline-info rotate float-left" data-deg="90" id="undoBtn" style="display: none;">
						<i class="ri-anticlockwise-fill"></i>
					</button>
					<button type="button" class="btn btn-sm btn-outline-info rotate float-right" data-deg="-90" id="redoBtn" style="display: none;">
						<i class="ri-clockwise-fill"></i>
					</button>
				</center>

				<div class="row mt-4 mb-4">
					<div class="col-6 col-lg-6 col-sm-6">
						<img id="previewSquare" src="" style="max-width: 100%;" class="img-fluid">
					</div>
					<div class="col-6 col-lg-6 col-sm-6">
						<img id="previewCircle" src="" style="max-width: 100%;" class="rounded-circle img-fluid">
					</div>
				</div>

			</div>
			<hr>

			<div class="alert alert-warning" role="alert">
				<span class="form-text text-muted"><b> A few notes before you upload a new profile picture </b></span>
				<span class="form-text text-muted">
					<ul>
						<li> Upload only file with extension jpeg and png. </li>
						<li> Files size support only <b><i style="color: red"> 8 MB. </i> </b></li>
						<li> Please wait for the upload to complete. </li>
					</ul>
				</span>
			</div>
			<center>
				<div id="uploadAvatarProgressBar"></div>
				<label>
					<input type="hidden" name="image" id="image64" placeholder="image crop result">
					<input type="hidden" name="user_id" id="user_id_upload" placeholder="user_id">
					<input type="hidden" name="entity_type" id="entity_type" value="User_model" placeholder="entity_type">
					<input type="hidden" name="entity_file_type" id="entity_file_type" value="USER_PROFILE" placeholder="entity_file_type">
					<input type="hidden" name="baseUrl" id="baseUrl">
					<input type="hidden" id="filename" name="filename">
					<button type="submit" id="uploadBtn" class="btn btn-info" disabled>
						<i class="fa fa-upload" aria-hidden="true"></i> Upload
					</button>
				</label>
			</center>
		</div>
	</form>
</div>

<script>
	var croppie = null;

	function getPassData(baseUrl, token, data) {
		$('#user_id_upload').val(data.user_id);
		$('#baseUrl').val(baseUrl);
	}

	// upload image
	$(function() {

		var el = document.getElementById('resizer');

		$.base64ImageToBlob = function(str) {
			// extract content type and base64 payload from original string
			var pos = str.indexOf(';base64,');
			var type = str.substring(5, pos);
			var b64 = str.substr(pos + 8);

			// decode base64
			var imageContent = atob(b64);

			// create an ArrayBuffer and a view (as unsigned 8-bit)
			var buffer = new ArrayBuffer(imageContent.length);
			var view = new Uint8Array(buffer);

			// fill the view, using the decoded base64
			for (var n = 0; n < imageContent.length; n++) {
				view[n] = imageContent.charCodeAt(n);
			}

			// convert ArrayBuffer to Blob
			var blob = new Blob([buffer], {
				type: type
			});

			return blob;
		}

		$.getImage = function(input, croppie) {
			if (input.files && input.files[0]) {
				var reader = new FileReader();
				reader.onload = function(e) {
					croppie.bind({
						url: e.target.result,
					});
				}
				reader.readAsDataURL(input.files[0]);
				setBase64();
			}
		}

		$("#image").on("change", function(event) {
			// croppie.destroy();
			if (validateUploadData()) {
				$('#filename').val(this.files[0].name); // this will clear the input val.
				$('#undoBtn').show();
				$('#redoBtn').show();
				// Initailize croppie instance and assign it to global variable
				croppie = new Croppie(el, {
					viewport: {
						width: 250,
						height: 250,
						type: 'square'
					},
					boundary: {
						width: 350,
						height: 350
					},

					// // resize controls
					// resizeControls: {
					//     width: true,
					//     height: true
					// },

					// // enable image resize
					enableResize: false,

					// // show image zoom control
					// showZoomer: true,

					// // image zoom with mouse wheel
					// mouseWheelZoom: false,

					// enable exif orientation reading
					enableExif: false,

					// restrict zoom so image cannot be smaller than viewport
					enforceBoundary: true,

					// enable orientation
					enableOrientation: true,

					// enable key movement
					// enableKeyMovement: true,
				});
				$.getImage(event.target, croppie);
				$("#uploadBtn").attr('disabled', false);
			} else {
				validationJsError('toastr', 'single'); // single or multi
				$("#uploadBtn").attr('disabled', true);
				$('#image').val(''); // this will clear the input val.
				$('#resizer').empty();
				$("#uploadBtn").attr('disabled', true);
				$('#undoBtn').hide();
				$('#redoBtn').hide();
			}
		});

		$('#resizer').on('update.croppie', function(ev, cropData) {
			setBase64();
		});

		// To Rotate Image Left or Right
		$(".rotate").on("click", function() {
			croppie.rotate(parseInt($(this).data('deg')));
			setBase64();
		});

		$('#generaloffcanvas-right').on('hidden.bs.modal', function(e) {
			$('#image').val(''); // this will clear the input val.
			$('#undoBtn').hide();
			$('#redoBtn').hide();
			// This function will call immediately after model close
			// To ensure that old croppie instance is destroyed on every model close
			setTimeout(function() {
				croppie.destroy();
			}, 100);
		});

		$("#changeProfilePicture").submit(function(event) {

			event.preventDefault();

			if (validateUploadData()) {

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
							croppie.result('base64').then(async function(base64) {

								var baseurl = $('#baseUrl').val();
								var user_id = $('#user_id_upload').val();
								$('#image64').val(base64);

								const submitBtnText = $('#uploadBtn').html();
								loadingBtn('uploadBtn', true); // block button from submit
								const res = await uploadApi(url, 'changeProfilePicture', 'uploadAvatarProgressBar');

								if (isSuccess(res)) {
									const result = res.data;

									$("#header_user_avatar").attr("src", baseurl + result.data.files_path);
									$("#user_avatar_view").attr("src", baseurl + result.data.files_path);
									$("#user_digital_avatar_view").attr("src", baseurl + result.data.files_path);

									noti(res.status, 'Profile uploaded');

									setTimeout(function() {
										$('#generaloffcanvas-right').offcanvas('toggle');
									}, 500);

								} else {
									noti(res.status);
								}

								loadingBtn('uploadBtn', false, submitBtnText); // unblock button from submit
							});
						}
					});
			} else {
				validationJsError('toastr', 'single'); // single or multi
			}
		});

	});

	function setBase64() {
		croppie.result(
			'base64',
			'viewport',
			'jpeg',
			1,
			false
		).then(function(base64) {
			$('#previewSquare').attr('src', base64);
			$('#previewCircle').attr('src', base64);
		});
	}

	function validateUploadData() {

		const rules = {
			'change_image': 'required|file|size:8|mimes:jpg,jpeg,png',
		};

		const message = {
			'change_image': 'Upload File',
		};

		return validationJs(rules, message);
	}
</script>