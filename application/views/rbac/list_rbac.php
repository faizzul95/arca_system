@extends('templates.desktop_blade')

@section('content')

<div class="row">

	<div class="col-xxl-12 mb-4">
		<div class="card">
			<div class="card-body">
				<div class="row">
					<div class="col-lg-2">
						<div class="nav nav-pills flex-column nav-pills-tab custom-verti-nav-pills text-center" role="tablist" aria-orientation="vertical">
							<a class="nav-link show active" id="custom-v-pills-rbac-tab" data-bs-toggle="pill" href="#custom-v-pills-rbac" role="tab" aria-controls="custom-v-pills-rbac" onclick="currentTab('Roles')" aria-selected="true">
								<i class="ri-user-2-line d-block fs-20 mb-1"></i> Roles
							</a>
							<a class="nav-link" id="custom-v-pills-rbac-tab" data-bs-toggle="pill" href="#custom-v-pills-rbac" role="tab" aria-controls="custom-v-pills-rbac" onclick="currentTab('Menu')" aria-selected="false">
								<i class="ri-menu-unfold-line d-block fs-20 mb-1"></i> Menu
							</a>
							<a class="nav-link" id="custom-v-pills-rbac-tab" data-bs-toggle="pill" href="#custom-v-pills-rbac" role="tab" aria-controls="custom-v-pills-rbac" onclick="currentTab('Email')" aria-selected="false">
								<i class="ri-mail-star-line d-block fs-20 mb-1"></i> Email
							</a>
							<a class="nav-link" id="custom-v-pills-rbac-tab" data-bs-toggle="pill" href="#custom-v-pills-rbac" role="tab" aria-controls="custom-v-pills-rbac" onclick="currentTab('Developer')" aria-selected="false">
								<i class="ri-code-s-slash-line d-block fs-20 mb-1"></i> Developers Section
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
		currentTab('Roles');
	});

	async function currentTab(tabName) {

		$('#contentList').empty();
		$('#subpage2').empty();

		var url = 'roles/roles-pages';

		if (tabName == 'Roles') {
			url = 'roles/roles-pages';
			$('#contentList').html(skeletonTable());
		} else if (tabName == 'Menu') {
			url = 'menu/menu-pages';
			$('#contentList').html(skeletonTable());
		} else if (tabName == 'Email') {
			url = 'rbac/email-section';
		} else if (tabName == 'Developer') {
			url = 'rbac/developer-section';
		} else {
			url = 'menu/menu-pages';
			$('#contentList').html(skeletonTable());
		}

		$('#subpage1').text('/ ' + tabName);
		const res = await callApi('get', url);
		$('#contentList').html(res.data);

	}
</script>

@endsection