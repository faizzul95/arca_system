<div class="row">
	<div class="col-lg-12 col-md-12 fill border-right p-4 overflow-hidden">
		<div id="previewEmailDiv"></div>
	</div>
</div>

<script>
	function getPassData(baseUrl, token, data) {
		if (hasData(data)) {
			$('#previewEmailDiv').html(data['body']);
		} else {
			$('#previewEmailDiv').html(nodata());
		}
	}
</script>