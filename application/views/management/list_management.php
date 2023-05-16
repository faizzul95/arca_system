@extends('templates.desktop_blade')

@section('content')

<div class="row">

	<div class="col-xxl-12 mb-4">
		<div class="card">
			<div class="card-body">
				<div class="row">
					<div class="col-lg-2">
						<div class="nav nav-pills flex-column nav-pills-tab custom-verti-nav-pills text-center" role="tablist" aria-orientation="vertical">
							<a class="nav-link show active" id="custom-v-pills-rbac-tab" data-bs-toggle="pill" href="#custom-v-pills-rbac" role="tab" aria-controls="custom-v-pills-rbac" onclick="currentTab('Branch')" aria-selected="true">
								<i class="ri-community-line d-block fs-20 mb-1"></i> Branch
							</a>
							<a class="nav-link" id="custom-v-pills-rbac-tab" data-bs-toggle="pill" href="#custom-v-pills-rbac" role="tab" aria-controls="custom-v-pills-rbac" onclick="currentTab('Education')" aria-selected="false">
								<i class="ri-hand-coin-line d-block fs-20 mb-1"></i> Education Level
							</a>
							<a class="nav-link" id="custom-v-pills-rbac-tab" data-bs-toggle="pill" href="#custom-v-pills-rbac" role="tab" aria-controls="custom-v-pills-rbac" onclick="currentTab('Faculty')" aria-selected="false">
								<i class="ri-building-line d-block fs-20 mb-1"></i> Faculty
							</a>
							<a class="nav-link" id="custom-v-pills-rbac-tab" data-bs-toggle="pill" href="#custom-v-pills-rbac" role="tab" aria-controls="custom-v-pills-rbac" onclick="currentTab('Program')" aria-selected="false">
								<i class="ri-book-read-line d-block fs-20 mb-1"></i> Programme
							</a>
							<a class="nav-link" id="custom-v-pills-rbac-tab" data-bs-toggle="pill" href="#custom-v-pills-rbac" role="tab" aria-controls="custom-v-pills-rbac" onclick="currentTab('Level')" aria-selected="false">
								<i class="ri-bar-chart-line d-block fs-20 mb-1"></i> College Level
							</a>
						</div>
					</div> <!-- end col-->
					<div class="col-lg-10" id="loadModule">
						<div class="tab-content text-muted mt-3 mt-lg-0">
							<div class="tab-pane fade active show" id="custom-v-pills-rbac" role="tabpanel" aria-labelledby="custom-v-pills-rbac-tab">
								<div id="contentList"></div>
							</div>
						</div>
					</div> <!-- end col-->
				</div> <!-- end row-->
			</div><!-- end card-body -->
		</div>
		<!--end card-->
	</div>

</div>

<script type="text/javascript">
	$(document).ready(function() {
		currentTab('Branch');
	});

	async function currentTab(tabName) {

		$('#contentList').empty();

		if (tabName == 'Branch') {
			url = 'branch/branch-list-pages';
		} else if (tabName == 'Faculty') {
			url = 'management/faculty-list-pages';
		} else if (tabName == 'Level') {
			url = 'management/college-level-list-pages';
		} else if (tabName == 'Program') {
			url = 'management/program-list-pages';
		} else if (tabName == 'Education') {
			url = 'management/education-list-pages';
		} else {
			url = 'branch/branch-list-pages';
		}

		$('#subpage1').text('/ ' + tabName);

		$('#contentList').html(skeletonTable());
		const res = await callApi('get', url);
		$('#contentList').html(res.data);
	}
</script>

@endsection