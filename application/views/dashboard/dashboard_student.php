@extends('templates.desktop_blade')

@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

@if($permission['dashboard-view'])

<div class="row">

	<!-- Student Details -->
	<div class="col-xxl-4">
		<div class="row">

			<div class="col-xl-12">

				<div class="row">
					<div class="col-xl-12 col-md-12">
						<div class="card">
							<div class="card-body">
								<div class="text-center">
									<div class="profile-user position-relative d-inline-block mx-auto mb-4">
										<img src="{{ currentUserAvatar() }}" class="rounded-circle avatar-xl img-thumbnail user-profile-image" alt="Student Profile">
									</div>
									<h5 class="fs-16 mb-1">{{ currentUserFullName() }}</h5>
									<p class="text-muted mb-0">{{ currentMatricID() }}</p>
								</div>
							</div><!-- end card body -->
						</div><!-- end card -->
					</div>
				</div>

			</div>

			<!-- Sticker Collection -->
			<div class="col-xxl-12">

				<div class="alert alert-info alert-dismissible alert-label-icon label-arrow fade show" role="alert">
					<i class="fa fa-sticky-note label-icon"></i><strong>COLLECTION INFORMATION</strong>
				</div>

				<div class="row">
					<div class="col-xl-6">
						<div class="card card-animate">
							<div class="card-body">
								<div class="d-flex align-items-center">
									<div class="avatar-sm flex-shrink-0">
										<span class="avatar-title bg-soft-primary text-primary rounded-2 fs-2">
											<i class="fa fa-users align-middle"></i>
										</span>
									</div>
									<div class="flex-grow-1 overflow-hidden ms-3">
										<p class="text-uppercase fw-medium text-muted text-truncate mb-3"> HEP </p>
										<div class="d-flex align-items-center mb-3">
											<h4 class="fs-4 flex-grow-1 mb-0"><span id="totalHEPCount" class="counter-value" data-target="">0</span></h4>
											<!-- <span class="badge badge-soft-success fs-12">{{ currentAcademicName() }}</span> -->
										</div>
									</div>
								</div>
							</div><!-- end card body -->
						</div>
					</div>

					<div class="col-xl-6">
						<div class="card card-animate">
							<div class="card-body">
								<div class="d-flex align-items-center">
									<div class="avatar-sm flex-shrink-0">
										<span class="avatar-title bg-soft-warning text-warning rounded-2 fs-2">
											<i class="fa fa-graduation-cap align-middle"></i>
										</span>
									</div>
									<div class="flex-grow-1 ms-3">
										<p class="text-uppercase fw-medium text-muted mb-3"> UNIVERSITY </p>
										<div class="d-flex align-items-center mb-3">
											<h4 class="fs-4 flex-grow-1 mb-0"><span id="totalUniversityCount" class="counter-value" data-target="">0</span></h4>
										</div>
									</div>
								</div>
							</div><!-- end card body -->
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-xl-6">
						<div class="card card-animate">
							<div class="card-body">
								<div class="d-flex align-items-center">
									<div class="avatar-sm flex-shrink-0">
										<span class="avatar-title bg-soft-info text-info rounded-2 fs-2">
											<i class="fa fa-home align-middle"></i>
										</span>
									</div>
									<div class="flex-grow-1 overflow-hidden ms-3">
										<p class="text-uppercase fw-medium text-muted text-truncate mb-3"> COLLEGE </p>
										<div class="d-flex align-items-center mb-3">
											<h4 class="fs-4 flex-grow-1 mb-0"><span id="totalCollegeCount" class="counter-value" data-target="">0</h4>
										</div>
									</div>
								</div>
							</div><!-- end card body -->
						</div>
					</div>

					<div class="col-xl-6">
						<div class="card card-animate">
							<div class="card-body">
								<div class="d-flex align-items-center">
									<div class="avatar-sm flex-shrink-0">
										<span class="avatar-title bg-soft-success text-success rounded-2 fs-2">
											<i class="fa fa-tasks align-middle"></i>
										</span>
									</div>
									<div class="flex-grow-1 overflow-hidden ms-3">
										<p class="text-uppercase fw-medium text-muted text-truncate mb-3"> Others </p>
										<div class="d-flex align-items-center mb-3">
											<h4 class="fs-4 flex-grow-1 mb-0"><span id="totalOthersCount" class="counter-value" data-target="">0</h4>
										</div>
									</div>
								</div>
							</div><!-- end card body -->
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>

	<div class="col-xxl-4">

		<div class="card card-height-100">
			<div class="card-body mb-0">
				<!-- STUDENT INFO -->
				<div class="alert alert-info alert-dismissible alert-label-icon label-arrow fade show" role="alert">
					<i class="fa fa-user label-icon"></i><strong>STUDENT INFORMATION</strong>
				</div>

				<div class="row">
					<div class="col-4"> <label class="ps-0"> Mobile : </label></div>
					<div class="col-8"> <label class="ps-0"> <span id="showMobileNo" class="text-muted text-uppercase"> - </span></label> </label></div>
				</div>
				<div class="row">
					<div class="col-4"> <label class="ps-0"> E-mail : </label></div>
					<div class="col-8"> <label class="ps-0"> <span id="showEmail" class="text-muted"> - </span></label> </label></div>
				</div>

				<div class="row">
					<div class="col-4"> <label class="ps-0"> Gender : </label></div>
					<div class="col-8"> <label class="ps-0"> <span id="showGender" class="text-muted text-uppercase"> - </span></label> </label></div>
				</div>

				<!-- ACADEMIC INFO -->
				<div class="alert alert-info alert-dismissible alert-label-icon label-arrow fade show mt-3" role="alert">
					<i class="fa fa-graduation-cap label-icon"></i><strong>ACADEMIC INFORMATION</strong>
				</div>

				<div class="row">
					<div class="col-4"> <label class="ps-0"> Semester : </label></div>
					<div class="col-8"> <label class="ps-0"> <span id="showSemester" class="text-muted text-uppercase"> - </span></label> </label></div>
				</div>
				<div class="row">
					<div class="col-4"> <label class="ps-0"> Program : </label></div>
					<div class="col-8"> <label class="ps-0"> <span id="showProgName" class="text-muted text-uppercase"> - </span></label> </label></div>
				</div>
				<div class="row">
					<div class="col-4"> <label class="ps-0"> Faculty : </label></div>
					<div class="col-8"> <label class="ps-0"> <span id="showFaculty" class="text-muted text-uppercase"> - </span></label> </label></div>
				</div>

				<!-- COLLEGE INFO -->
				<div class="alert alert-info alert-dismissible alert-label-icon label-arrow fade show mt-3" role="alert">
					<i class="fa fa-home label-icon"></i><strong>COLLEGE INFORMATION</strong>
				</div>

				<div class="row">
					<div class="col-4"> <label class="ps-0"> Room No : </label></div>
					<div class="col-8"> <label class="ps-0"> <span id="showRoomNo" class="text-muted text-uppercase"> - </span> </label></label></div>
				</div>

				<div class="row">
					<div class="col-4"> <label class="ps-0"> Bed No : </label></div>
					<div class="col-8"> <label class="ps-0"> <label class="ps-0"> <span id="showBedNo" class="text-muted text-uppercase"> - </span></label> </label></div>
				</div>

				<div class="row">
					<div class="col-4"> <label class="ps-0"> College : </label></div>
					<div class="col-8"> <label class="ps-0"> <span id="showCollegeName" class="text-muted text-uppercase"> - </span></label> </label></div>
				</div>

			</div><!-- end card body -->
		</div>

	</div>

	<!-- Upcoming Event Information -->
	<div class="col-xxl-4">

		<div class="card card-height-100">
			<div class="card-header border-0">
				<h4 class="card-title mb-0">Calendar</h4>
			</div><!-- end cardheader -->
			<div class="card-body pt-0">
				<div class="upcoming-scheduled">
					<input type="text" class="form-control" onchange="getTodayEvent(this.value)" data-provider="flatpickr" data-date-format="Y-m-d" data-deafult-date="today" data-inline-date="true">
				</div>

				<h6 class="text-uppercase fw-semibold mt-4 mb-3 text-muted">Upcoming Events on this <span id="changeDate">month</span></h6>

				<div id="activityEvent"></div>
				<div class="mt-3 text-center">
					<a href="{{ url('event') }}" class="text-muted text-decoration-underline">
						View all Events
					</a>
				</div>

			</div><!-- end cardbody -->
		</div><!-- end card -->
	</div><!-- end col -->

</div>

@else
{{ nodataAccess() }}
@endif

<script>
	$(document).ready(function() {
		getProfile();
	});

	async function getProfile() {
		const res = await callApi('get', 'user/show');

		if (isSuccess(res)) {
			const data = res.data;
			const roles = data.currentProfile.roles;
			const programme = data.programme;
			const application = data.application;
			const enrollment = data.enrollment;
			const sticker = data.sticker;

			$('#showFullNameMain').text(data.user_full_name);
			$('#showProfileNameMain').text(roles.role_name);
			$('#showFullName').text(data.user_full_name);
			$('#showMatricID').text(data.user_matric_code);
			$('#showNRIC').text(data.user_nric);
			$('#showGender').text(data.user_gender == 1 ? 'Male' : 'Female');
			$('#showMobileNo').text(data.user_contact_no);
			$('#showEmail').text(data.user_email);
			$('#showIntake').text(data.user_intake);
			$('#showCollege').text('(No Information)');

			// academic
			$('#showSemester').text(enrollment.semester_number);
			$('#showProgName').text(programme.program_code + ' - ' + programme.program_name);
			$('#showFaculty').text(programme.faculty.faculty_code + ' - ' + programme.faculty.faculty_name);

			// sticker
			$('#totalCollegeCount').text(isset(sticker.total_college_sticker) ? sticker.total_college_sticker : 0);
			$('#totalUniversityCount').text(isset(sticker.total_university_sticker) ? sticker.total_university_sticker : 0);
			$('#totalHEPCount').text(isset(sticker.total_hep_sticker) ? sticker.total_hep_sticker : 0);
			var faculty = isset(sticker.total_faculty_sticker) ? sticker.total_faculty_sticker : 0;
			var club = isset(sticker.total_club_sticker) ? sticker.total_club_sticker : 0;
			var others = parseInt(faculty) + parseInt(club);

			$('#totalOthersCount').text(sticker != '' ? others : 0);

			// college
			$('#showRoomNo').text(enrollment.room.college_room_number);
			$('#showBedNo').text(enrollment.college_bed_no);
			$('#showCollegeName').text(enrollment.room.college.college_name);

			await getDataDashboard();
		}
	}

	async function getDataDashboard() {

		const res = await callApi('get', 'dashboard/student-event-list');

		if (isSuccess(res)) {
			const data = res.data;
			$('#activityEvent').html(data.activity);
		}
	}

	async function viewEventRecord(eventid) {
		const res = await callApi('get', "event/show/" + eventid);

		// check if request is success
		if (isSuccess(res)) {
			res.data['role_id'] = "<?= currentUserRoleID(); ?>";
			loadFileContent('event/_eventView.php', 'generalContent', 'fullscreen', 'Event Information', res.data);
		}
	}

	async function getTodayEvent(date) {
		$('#changeDate').text('DAY SELECTED');

		const res = await callApi('get', 'dashboard/calendar/' + date);

		if (isSuccess(res)) {
			$('#activityEvent').html(res.data);
		}
	}
</script>
@endsection