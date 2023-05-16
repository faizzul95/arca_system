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

			<div class="col-xl-12">
				<div class="card card-height-100">
					<div class="card-body mb-0">
						<!-- ORGANIZER INFO -->
						<div class="alert alert-info alert-dismissible alert-label-icon label-arrow fade show" role="alert">
							<i class="fa fa-user label-icon"></i><strong>ORGANIZER INFORMATION</strong>
						</div>

						<div class="row">
							<div class="col-3"> <label class="ps-0"> Mobile : </label></div>
							<div class="col-9"> <label class="ps-0"> <span id="showMobileNo" class="text-muted text-uppercase"> - </span></label> </label></div>
						</div>

						<div class="row">
							<div class="col-3"> <label class="ps-0"> E-mail : </label></div>
							<div class="col-9"> <label class="ps-0"> <span id="showEmail" class="text-muted"> - </span></label> </label></div>
						</div>

						<div class="row">
							<div class="col-3"> <label class="ps-0"> Gender : </label></div>
							<div class="col-9"> <label class="ps-0"> <span id="showGender" class="text-muted text-uppercase"> - </span></label> </label></div>
						</div>

					</div><!-- end card body -->
				</div>
			</div>

		</div>
	</div>

	<!-- Upcoming Event Information -->
	<div class="col-xxl-8">

		<div class="row">
			<div class="col-xl-4">
				<div class="card card-animate">
					<div class="card-body">
						<div class="d-flex align-items-center">
							<div class="avatar-sm flex-shrink-0">
								<span class="avatar-title bg-soft-primary text-primary rounded-2 fs-2">
									<i class="ri-registered-line align-middle"></i>
								</span>
							</div>
							<div class="flex-grow-1 overflow-hidden ms-3">
								<p class="text-uppercase fw-medium text-muted text-truncate mb-3"> EVENT REGISTER</p>
								<div class="d-flex align-items-center mb-3">
									<h4 class="fs-4 flex-grow-1 mb-0"><span id="totalEventRegister" class="counter-value" data-target="0">0</span></h4>
									<span class="badge badge-soft-success fs-12">{{ currentAcademicName() }}</span>
								</div>
							</div>
						</div>
					</div><!-- end card body -->
				</div>
			</div><!-- end col -->

			<div class="col-xl-4">
				<div class="card card-animate">
					<div class="card-body">
						<div class="d-flex align-items-center">
							<div class="avatar-sm flex-shrink-0">
								<span class="avatar-title bg-soft-info text-info rounded-2 fs-2">
									<i class="ri-run-line align-middle"></i>
								</span>
							</div>
							<div class="flex-grow-1 ms-3">
								<p class="text-uppercase fw-medium text-muted mb-3"> ON-GOING SLOT </p>
								<div class="d-flex align-items-center mb-3">
									<h4 class="fs-4 flex-grow-1 mb-0"><span id="totalSlotOngoing" class="counter-value" data-target="0">0</span></h4>
								</div>
							</div>
						</div>
					</div><!-- end card body -->
				</div>
			</div><!-- end col -->

			<div class="col-xl-4">
				<div class="card card-animate">
					<div class="card-body">
						<div class="d-flex align-items-center">
							<div class="avatar-sm flex-shrink-0">
								<span class="avatar-title bg-soft-success text-success rounded-2 fs-2">
									<i class="ri-calendar-check-line accordion-bg-warningalign-middle"></i>
								</span>
							</div>
							<div class="flex-grow-1 overflow-hidden ms-3">
								<p class="text-uppercase fw-medium text-muted text-truncate mb-3"> COMPLETED SLOT </p>
								<div class="d-flex align-items-center mb-3">
									<h4 class="fs-4 flex-grow-1 mb-0"><span id="totalSlotCompleted" class="counter-value" data-target="0">0</span></h4>
								</div>
							</div>
						</div>
					</div><!-- end card body -->
				</div>
			</div><!-- end col -->
		</div>

		<div class="row">
			<div class="col-xxl-12">
				<div class="card">
					<div class="card-header border-0">
						<h4 class="card-title mb-0">Calendar</h4>
					</div><!-- end cardheader -->
					<div class="card-body pt-0">
						<div class="upcoming-scheduled">
							<input type="text" class="form-control" data-provider="flatpickr" data-date-format="Y-m-d" data-deafult-date="today" data-inline-date="true">
						</div>
					</div><!-- end cardbody -->
				</div><!-- end card -->
			</div><!-- end card -->
		</div>

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

			await getDataDashboard();
		}
	}

	async function getDataDashboard() {

		const res = await callApi('get', 'dashboard/organizer-data');

		if (isSuccess(res)) {
			const data = res.data;
			$('#totalEventRegister').text(data.eventRegister);
			$('#totalSlotOngoing').text(data.slotOngoing);
			$('#totalSlotCompleted').text(data.slotCompleted);
		}
	}
</script>
@endsection