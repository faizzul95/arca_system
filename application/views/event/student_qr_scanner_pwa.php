@extends('templates.pwa_blade')

@section('content')

<div id="setUUID" class="page-content-wrapper py-3" style="display: none;">
	<div class="container">
		<div class="card">
			<div class="card-body p-3">
				<center>
					<img src="{{ asset('custom/img/lock.png', null, false) }}" class="img-fluid" width="80%">
					<h2 style="letter-spacing :2px; font-family: Quicksand, sans-serif !important;font-size: 13px;">
						ARE YOU NEW TO THIS APPS?
					</h2>
				</center>
				<div class="row d-flex justify-content-center w-100">
					<div class="col-lg m-1 text-left" style="text-align: justify;max-width: 350px !important;letter-spacing :1px; font-family: Quicksand, sans-serif !important;font-size: 12px;">
						This app requires you to lock your account with this device. Only <b> ONE </b> account is allowed to use this QR SCANNER.
						<br><br>
						Once you lock your account with this device, Anothers login account can no longer use this function.
					</div>
					<center>
						<button onclick="lockDevice()" class="btn btn-sm btn-info mt-3 mb-2">
							<i class="bi bi-lock"></i>
							LOCK THIS ACCOUNT
						</button>
					</center>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="blockAccess" class="page-content-wrapper py-3" style="display: none;">
	<div class="container">
		<div class="card">
			<div class="card-body p-3">
				<center>
					<img src="{{ asset('custom/img/no-allowed.png', null, false) }}" class="img-fluid" width="100%">
					<h1 style="letter-spacing :2px; font-family: Quicksand, sans-serif !important;" class="text-danger">
						WARNING!
					</h1>
				</center>
				<div class="row d-flex justify-content-center w-100">
					<div class="col-lg m-1 text-left" style="text-align: justify;max-width: 350px !important;letter-spacing :1px; font-family: Quicksand, sans-serif !important;font-size: 12px;">
						THIS DEVICE IS ALREADY LOCKED USING ANOTHER ACCOUNT
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="scannerDiv" class="container px-0 position-relative">

	<style>
		#qr-video {
			position: static;
			object-fit: cover;
			width: 100% !important;
			height: 100% !important;
		}
	</style>

	<!-- Footer Content -->
	<div class="footer-nav position-relative">
		<ul class="h-100 d-flex align-items-center justify-content-between ps-0">
			<li onclick="changeSideCamera()">
				<a href="javascript:void(0)">
					<i class="bi bi-camera"></i>
					<span id="textCam">Rear Camera</span>
				</a>
				<input type="hidden" id="cam-list" value="environment">
			</li>
			<li id="flashLight" style="display: none;">
				<a href="javascript:void(0)">
					<i class="fa fa-bolt" style="color: red;" id="flash-toggle"></i>
					<span id="flash-state">FLASH OFF</span>
				</a>
			</li>
			<li>
				<a href="javascript:void(0)">
					<label>
						<i class="fa fa-image"></i>
						<span id="flash-state">Scan from file</span>
						<input type="file" id="file-selector" onchange="scanFile()" accept="image/x-png,image/jpeg,image/jpg" style="display: none" />
					</label>
				</a>
			</li>
		</ul>
	</div>

	<div class="col-lg-12 col-md-12">

		<div class="row">
			<button class="btn btn-info form-control" id="start-button" onclick="startScanner()" style="display: none;">Start Scanner</button>
			<button class="btn btn-danger form-control mb-4" id="stop-button" onclick="stopScanner()" style="display: none;">Stop Scanner</button>

			<div id="video-container">
				<video id="qr-video"></video>
			</div>
		</div>

		<div class="row" style="display: none;">
			<b>Detected QR code: </b>
			<span id="cam-qr-result">None</span>
			<br>
			<b>Last detected at: </b>
			<span id="cam-qr-result-timestamp"></span>
			<b>Detected QR code: </b>
			<span id="file-qr-result">None</span>
		</div>
	</div>
</div>

<script src="{{ asset('common/js/qr/qr-scanner.umd.min.js', null, false) }}"></script>
<script src="{{ asset('common/js/qr/qr-scanner.legacy.min.js', null, false) }}"></script>

<script type="text/javascript">
	$(document).ready(function() {

		$('#setUUID').hide();

		if (!isset(localStorage.getItem("arcaDeviceID"))) {
			$('#setUUID').show();
			$('#scannerDiv').hide();
		} else {
			checkDeviceUuiD();
		}

		// alert('width :' + window.screen.width + ', height: ' + window.screen.height);
		// var device_height = window.screen.height - 100;
		// var device_width = window.screen.width - 100;
		// console.log('width :' + device_width + ', height: ' + device_height);
		// document.getElementById('qr-video').style.width = device_width + 'px';
		// document.getElementById('qr-video').style.height = device_height + 'px';
	});

	let scanner = null;

	video = document.getElementById('qr-video');
	videoContainer = document.getElementById('video-container');
	camHasCamera = document.getElementById('cam-has-camera');
	camList = document.getElementById('cam-list');
	// const camHasFlash = document.getElementById('cam-has-flash');
	flashToggle = document.getElementById('flash-toggle');
	flashState = document.getElementById('flash-state');
	camQrResult = document.getElementById('cam-qr-result');
	camQrResultTimestamp = document.getElementById('cam-qr-result-timestamp');
	fileSelector = document.getElementById('file-selector');
	fileQrResult = document.getElementById('file-qr-result');

	function initFunction() {

		// ####### Web Cam Scanning #######
		scanner = new QrScanner(video, result => setResult(camQrResult, result), {
			onDecodeError: error => {
				camQrResult.textContent = error;
				camQrResult.style.color = 'inherit';
			},
			highlightScanRegion: true,
			highlightCodeOutline: true,
		});

		updateFlashAvailability();

		QrScanner.hasCamera().then(hasCamera => camHasCamera.textContent = hasCamera);
		scanner.hasFlash().then(hasFlash => hasFlash ? $('#flashLight').show() : $('#flashLight').hide());

		// for debugging
		window.scanner = scanner;

		// document.getElementById('scan-region-highlight-style-select').addEventListener('change', (e) => {
		//     videoContainer.className = 'default-style';
		//     scanner._updateOverlay(); // reposition the highlight because style 2 sets position: relative
		// });

		flashToggle.addEventListener('click', () => {
			scanner.toggleFlash().then(function() {
				flashState.textContent = scanner.isFlashOn() ? 'FLASH ON' : 'FLASH OFF';
				var currentFlashOn = scanner.isFlashOn() ? "#ebcf16" : "red";
				$("#flash-toggle").css('color', currentFlashOn);
			}).catch(function(err) {
				noti(500, err);
			});
		});

		startScanner();

	}

	function updateFlashAvailability() {
		scanner.hasFlash().then(hasFlash => {
			// camHasFlash.textContent = hasFlash;
			flashToggle.style.display = hasFlash ? 'block' : 'block';
		});
	}

	// ####### File Scanning #######
	function scanFile() {
		const file = fileSelector.files[0];
		if (!file) {
			noti(500, 'Failed to load files');
			return;
		}
		QrScanner.scanImage(file, {
				returnDetailedScanResult: true
			})
			.then(result => setResult(fileQrResult, result))
			.catch(e => setResult(fileQrResult, {
				// data: e || noti(500, 'No QR code found.')
				data: 'No QR code found'
			}));
	}

	function changeSideCamera() {
		var currentSide = $('#cam-list').val();
		if (currentSide == 'environment') {
			camList = 'user';
			$('#cam-list').val('user');
			$('#textCam').text('Selfie Camera');
		} else {
			camList = 'environment';
			$('#cam-list').val('environment');
			$('#textCam').text('Rear Camera');
		}

		scanner.setCamera(camList).then(updateFlashAvailability);
	}

	async function setResult(label, result) {
		stopScanner();

		if (result != null) {
			label.textContent = result.data;

			$('#file-selector').val(''); // reset file

			if (result.data !== 'No QR code found') {
				camQrResultTimestamp.textContent = new Date().toString();
				label.style.color = 'teal';
				clearTimeout(label.highlightTimeout);
				label.highlightTimeout = setTimeout(() => label.style.color = 'inherit', 100);
				beep();

				// // call api to record
				const res = await callApi('post', "attendance/record", {
					'slot_session_code': result.data,
					'attendance_device': 1,
					'attendance_status': 1,
				});

				if (isSuccess(res)) {
					// noti(res.status, 'Attendance record');

					const data = res.data;
					Swal.fire({
						icon: isSuccess(data.resCode) ? 'success' : 'error',
						title: data.message,
						showConfirmButton: false,
						timer: 2100
					});

				}

				setTimeout(function() {
					startScanner();
				}, 2000);

			} else {
				setTimeout(function() {
					startScanner();
				}, 250);
				noti(500, label.textContent);
			}
		} else {
			noti(500, 'QR Code is Invalid!');
		}

	}

	function startScanner() {
		// $('#start-button').hide();
		// $('#stop-button').show();
		scanner.start();
		updateFlashAvailability();
	}

	function stopScanner() {
		// $('#start-button').show();
		// $('#stop-button').hide();
		flashToggle.style.display = false ? 'block' : 'block';
		scanner.stop();
	}

	function beep() {
		var snd = new Audio("data:audio/wav;base64,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qJrx4KOQjahZxWbcZgztj2c49nKmkId44S71j0c8eV9yDK6uPRzx5X18eDvjvQ6yKo9ZSS6l//8elePK/Lf//IInrOF/FvDoADYAGBMGb7FtErm5MXMlmPAJQVgWta7Zx2go+8xJ0UiCb8LHHdftWyLJE0QIAIsI+UbXu67dZMjmgDGCGl1H+vpF4NSDckSIkk7Vd+sxEhBQMRU8j/12UIRhzSaUdQ+rQU5kGeFxm+hb1oh6pWWmv3uvmReDl0UnvtapVaIzo1jZbf/pD6ElLqSX+rUmOQNpJFa/r+sa4e/pBlAABoAAAAA3CUgShLdGIxsY7AUABPRrgCABdDuQ5GC7DqPQCgbbJUAoRSUj+NIEig0YfyWUho1VBBBA//uQZB4ABZx5zfMakeAAAAmwAAAAF5F3P0w9GtAAACfAAAAAwLhMDmAYWMgVEG1U0FIGCBgXBXAtfMH10000EEEEEECUBYln03TTTdNBDZopopYvrTTdNa325mImNg3TTPV9q3pmY0xoO6bv3r00y+IDGid/9aaaZTGMuj9mpu9Mpio1dXrr5HERTZSmqU36A3CumzN/9Robv/Xx4v9ijkSRSNLQhAWumap82WRSBUqXStV/YcS+XVLnSS+WLDroqArFkMEsAS+eWmrUzrO0oEmE40RlMZ5+ODIkAyKAGUwZ3mVKmcamcJnMW26MRPgUw6j+LkhyHGVGYjSUUKNpuJUQoOIAyDvEyG8S5yfK6dhZc0Tx1KI/gviKL6qvvFs1+bWtaz58uUNnryq6kt5RzOCkPWlVqVX2a/EEBUdU1KrXLf40GoiiFXK///qpoiDXrOgqDR38JB0bw7SoL+ZB9o1RCkQjQ2CBYZKd/+VJxZRRZlqSkKiws0WFxUyCwsKiMy7hUVFhIaCrNQsKkTIsLivwKKigsj8XYlwt/WKi2N4d//uQRCSAAjURNIHpMZBGYiaQPSYyAAABLAAAAAAAACWAAAAApUF/Mg+0aohSIRobBAsMlO//Kk4soosy1JSFRYWaLC4qZBYWFRGZdwqKiwkNBVmoWFSJkWFxX4FFRQWR+LsS4W/rFRb/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////VEFHAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAU291bmRib3kuZGUAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAMjAwNGh0dHA6Ly93d3cuc291bmRib3kuZGUAAAAAAAAAACU=");
		snd.play();
	}

	async function lockDevice() {

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

					const res = await callApi('post', 'student/qr-lock-device', {
						'useragent': navigator.userAgent
					});

					if (isSuccess(res)) {
						localStorage.setItem("arcaDeviceID", res.data.data.device_uuid);
						await checkDeviceUuiD();
					}
				}
			})

	}

	async function checkDeviceUuiD() {
		const res = await callApi('post', 'student/check-device-id', {
			'deviceid': localStorage.getItem("arcaDeviceID")
		});

		if (isSuccess(res)) {

			if (res.data == '1') {
				$('#scannerDiv').show();
				$('#blockAccess').hide();
				$('#setUUID').hide();
				initFunction();
			} else {
				$('#scannerDiv').hide();
				$('#blockAccess').show();
			}

		}

	}
</script>
@endsection