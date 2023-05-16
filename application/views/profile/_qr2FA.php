<div class="row">
	<img id="image_qr_2fa" src="" class="img-fluid">
</div>

<script>
	async function getPassData(baseUrl, token, data) {
		$('#image_qr_2fa').attr('src', data.imageQR);
	}
</script>
