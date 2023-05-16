<!-- <script src="https://cdn.ckeditor.com/ckeditor5/36.0.0/classic/ckeditor.js"></script> -->

<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>

<div class="row">
	<div class="card">
		<div class="border">
			<ul class="nav nav-pills custom-hover-nav-tabs">
				<li class="nav-item">
					<a href="javascript:void(0)" onclick="currentEmailTabs('Template')" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
						<i class="ri-mail-settings-line nav-icon nav-tab-position"></i>
						<h5 class="nav-titl nav-tab-position m-0"> Template </h5>
					</a>
				</li>
				<li class="nav-item">
					<a href="javascript:void(0)" onclick="currentEmailTabs('Queue')" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
						<i class="ri-mail-send-line nav-icon nav-tab-position"></i>
						<h5 class="nav-titl nav-tab-position m-0"> Job Queue </h5>
					</a>
				</li>
			</ul>
		</div>
		<div class="card-body">
			<div class="tab-content text-muted">
				<div class="tab-pane show active">
					<div id="contentListEmail"></div>
				</div>
			</div>
		</div><!-- end card-body -->
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		currentEmailTabs('Template');
	});

	async function currentEmailTabs(tabName = 'Template') {

		$('#contentListSystem').empty();

		var url = 'rbac/email-template-tab';

		if (tabName == 'Template') {
			url = 'rbac/email-template-tab';
		} else if (tabName == 'Queue') {
			url = 'rbac/email-queue-tab';
		} else {
			url = 'rbac/email-template-tab';
		}

		$('#contentListEmail').html(skeletonTable(1));
		$('#subpage2').text('/ ' + tabName);

		const res = await callApi('get', url);
		$('#contentListEmail').html(res.data);
	}
</script>