<!-- <script src="https://cdn.ckeditor.com/4.18.0/basic/ckeditor.js"></script> -->
<script src="https://cdn.ckeditor.com/ckeditor5/36.0.0/classic/ckeditor.js"></script>

<div class="row">
	<div class="card">
		<div class="border">
			<ul class="nav nav-pills custom-hover-nav-tabs">
				<li class="nav-item">
					<a href="javascript:void(0)" onclick="currentSystemTabs('Audit')" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
						<i class="ri-booklet-line nav-icon nav-tab-position"></i>
						<h5 class="nav-titl nav-tab-position m-0">Audit Trails</h5>
					</a>
				</li>
				<li class="nav-item">
					<a href="javascript:void(0)" onclick="currentSystemTabs('Logs')" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
						<i class="ri-error-warning-line nav-icon nav-tab-position"></i>
						<h5 class="nav-titl nav-tab-position m-0">Error Logs</h5>
					</a>
				</li>
				<li class="nav-item">
					<a href="javascript:void(0)" onclick="currentSystemTabs('Database')" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
						<i class="ri-database-2-line nav-icon nav-tab-position"></i>
						<h5 class="nav-titl nav-tab-position m-0">DB Backup</h5>
					</a>
				</li>
				<li class="nav-item">
					<a href="javascript:void(0)" onclick="currentSystemTabs('Editor')" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
						<i class="ri-file-edit-line nav-icon nav-tab-position"></i>
						<h5 class="nav-titl nav-tab-position m-0">Editor</h5>
					</a>
				</li>
			</ul>
		</div>
		<div class="card-body">
			<div class="tab-content text-muted">
				<div class="tab-pane show active">
					<div id="contentListSystem"></div>
				</div>
			</div>
		</div><!-- end card-body -->
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		currentSystemTabs('Audit');
	});

	async function currentSystemTabs(tabName = 'Audit') {

		$('#contentListSystem').empty();

		var url = 'rbac/audit-tab';

		if (tabName == 'Database') {
			url = 'rbac/database-tab';
			$('#contentListSystem').html(skeletonTable());
		} else if (tabName == 'Audit') {
			url = 'rbac/audit-tab';
			$('#contentListSystem').html(skeletonTable(1));
		} else if (tabName == 'Logs') {
			url = 'rbac/error-tab';
			$('#contentListSystem').html(skeletonTable(2));
		} else if (tabName == 'Editor') {
			url = 'rbac/editor-tab';
			$('#contentListSystem').html(skeletonTable('', false));
		} else {
			url = 'rbac/audit-tab';
			$('#contentListSystem').html(skeletonTable(1));
		}

		$('#subpage2').text('/ ' + tabName);
		const res = await callApi('get', url);
		$('#contentListSystem').html(res.data);
	}
</script>