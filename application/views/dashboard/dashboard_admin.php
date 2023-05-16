@extends('templates.desktop_blade')

@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

@if($permission['dashboard-view'])

<div class="row">

	<div class="col-xxl-8">
		<div class="row">
			<div class="col-xl-4">
				<div class="card card-animate">
					<div class="card-body">
						<div class="d-flex align-items-center">
							<div class="avatar-sm flex-shrink-0">
								<span class="avatar-title bg-soft-primary text-primary rounded-2 fs-2">
									<i class="ri-user-2-line align-middle"></i>
								</span>
							</div>
							<div class="flex-grow-1 overflow-hidden ms-3">
								<p class="text-uppercase fw-medium text-muted text-truncate mb-3">TOTAL ALL STUDENT</p>
								<div class="d-flex align-items-center mb-3">
									<h4 class="fs-4 flex-grow-1 mb-0"><span id="totalAllStudentCount" class="counter-value" data-target="">0</span></h4>
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
								<span class="avatar-title bg-soft-warning text-warning rounded-2 fs-2">
									<i class="ri-men-line align-middle"></i>
								</span>
							</div>
							<div class="flex-grow-1 ms-3">
								<p class="text-uppercase fw-medium text-muted mb-3"> MALE STUDENT </p>
								<div class="d-flex align-items-center mb-3">
									<h4 class="fs-4 flex-grow-1 mb-0"><span id="totalMaleStudentCount" class="counter-value" data-target="">0</span></h4>
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
									<i class="ri-women-line accordion-bg-warningalign-middle"></i>
								</span>
							</div>
							<div class="flex-grow-1 overflow-hidden ms-3">
								<p class="text-uppercase fw-medium text-muted text-truncate mb-3"> FEMALE STUDENT </p>
								<div class="d-flex align-items-center mb-3">
									<h4 class="fs-4 flex-grow-1 mb-0"><span id="totalFemaleStudentCount" class="counter-value" data-target="">0</h4>
								</div>
							</div>
						</div>
					</div><!-- end card body -->
				</div>
			</div><!-- end col -->
		</div><!-- end row -->

		<div class="row">
			<div class="col-xl-12">

				<div class="row">
					<div class="col-xl-6 col-md-12">
						<div class="card card-height-100">
							<div class="card-header align-items-center d-flex">
								<h4 class="card-title mb-0 flex-grow-1">Report by Event Category</h4>
							</div><!-- end card header -->
							<div class="card-body">
								<div id="chartEvent" data-colors='["--vz-primary", "--vz-warning", "--vz-info", "--vz-danger", "--vz-default"]' class="apex-charts" dir="ltr"></div>

								<div class="table-responsive mt-3">
									<table class="table table-borderless table-sm table-centered align-middle table-nowrap mb-0">
										<tbody class="border-0">
											<tr>
												<td>
													<h4 class="text-truncate fs-14 fs-medium mb-0">
														<i class="ri-stop-fill align-middle fs-18 text-success me-2"></i>
														HEP
													</h4>
												</td>
												<td class="text-end">
													<p class="fw-medium fs-12 mb-0">
														<span id="chartEventHEP">0</span>
													</p>
												</td>
											</tr>
											<tr>
												<td>
													<h4 class="text-truncate fs-14 fs-medium mb-0">
														<i class="ri-stop-fill align-middle fs-18 text-warning me-2"></i>
														University
													</h4>
												</td>
												<td class="text-end">
													<p class="fw-medium fs-12 mb-0">
														<span id="chartEventUniversity">0</span>
													</p>
												</td>
											</tr>
											<tr>
												<td>
													<h4 class="text-truncate fs-14 fs-medium mb-0">
														<i class="ri-stop-fill align-middle fs-18 text-info me-2"></i>
														College
													</h4>
												</td>
												<td class="text-end">
													<p class="fw-medium fs-12 mb-0">
														<span id="chartEventCollege">0</span>
													</p>
												</td>
											</tr>
											<tr>
												<td>
													<h4 class="text-truncate fs-14 fs-medium mb-0">
														<i class="ri-stop-fill align-middle fs-18 text-danger me-2"></i>
														Academic/Faculty
													</h4>
												</td>
												<td class="text-end">
													<p class="fw-medium fs-12 mb-0">
														<span id="chartEventFaculty">0</span>
													</p>
												</td>
											</tr>
											<tr>
												<td>
													<h4 class="text-truncate fs-14 fs-medium mb-0">
														<i class="ri-stop-fill align-middle fs-18 text-primary me-2"></i>
														Association/Club
													</h4>
												</td>
												<td class="text-end">
													<p class="fw-medium fs-12 mb-0">
														<span id="chartEventClub">0</span>
													</p>
												</td>
											</tr>
										</tbody>
									</table>
								</div>

							</div><!-- end card body -->
						</div><!-- end card -->
					</div>
					<div class="col-xl-6 col-md-12">
						<div class="card card-height-100">
							<div class="card-header align-items-center d-flex">
								<h4 class="card-title mb-0 flex-grow-1">Report by Event Status</h4>
							</div>

							<div class="card-body">

								<div class="row align-items-center">
									<div class="col-6">
										<h6 class="text-muted text-uppercase fw-semibold text-truncate fs-12 mb-3">
											Total Event Registered
										</h6>
										<h4 class="mb-0" id="totalCountAllEvent">0</h4>
										<div id="previousDataDiv"></div>
									</div><!-- end col -->
									<div class="col-6">
										<div class="text-center">
											<img src="{{ asset('images/illustrator-1.png') }}" class="img-fluid" width="500px" alt="">
										</div>
									</div><!-- end col -->
								</div><!-- end row -->
								<div class="mt-4 pt-2">
									<div class="progress progress-lg rounded-pill">
										<!-- style="width: 0%" -->
										<div id="incomingCountProgress" class="progress-bar bg-primary" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
										<div id="ongoingCountProgress" class="progress-bar bg-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
										<div id="completedCountProgress" class="progress-bar bg-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
										<div id="cancelledCountProgress" class="progress-bar bg-danger" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
										<div id="othersCountProgress" class="progress-bar bg-warning" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
									</div>
								</div><!-- end -->

								<div class="mt-2 pt-2">
									<div class="d-flex mb-2 mt-4">
										<div class="flex-grow-1">
											<p class="text-truncate text-muted fs-14 mb-0">
												<i class="mdi mdi-circle align-middle text-primary me-2"></i>
												Incoming
											</p>
										</div>
										<div class="flex-shrink-0">
											<p id="incomingCountVal" class="mb-0">0%</p>
										</div>
									</div><!-- end -->
									<div class="d-flex mb-2">
										<div class="flex-grow-1">
											<p class="text-truncate text-muted fs-14 mb-0">
												<i class="mdi mdi-circle align-middle text-info me-2"></i>
												Ongoing
											</p>
										</div>
										<div class="flex-shrink-0">
											<p id="ongoingCountVal" class="mb-0">0%</p>
										</div>
									</div><!-- end -->
									<div class="d-flex mb-2">
										<div class="flex-grow-1">
											<p class="text-truncate text-muted fs-14 mb-0">
												<i class="mdi mdi-circle align-middle text-success me-2"></i>
												Completed
											</p>
										</div>
										<div class="flex-shrink-0">
											<p id="completedCountVal" class="mb-0">0%</p>
										</div>
									</div><!-- end -->
									<div class="d-flex mb-2">
										<div class="flex-grow-1">
											<p class="text-truncate text-muted fs-14 mb-0">
												<i class="mdi mdi-circle align-middle text-danger me-2"></i>
												Cancelled
											</p>
										</div>
										<div class="flex-shrink-0">
											<p id="cancelledCountVal" class="mb-0">0%</p>
										</div>
									</div><!-- end -->
									<div class="d-flex">
										<div class="flex-grow-1">
											<p class="text-truncate text-muted fs-14 mb-0">
												<i class="mdi mdi-circle align-middle text-warning me-2"></i>
												Others
											</p>
										</div>
										<div class="flex-shrink-0">
											<p id="othersCountVal" class="mb-0">0%</p>
										</div>
									</div><!-- end -->
								</div><!-- end -->

							</div><!-- end card body -->
						</div><!-- end card -->
					</div>
				</div>

			</div><!-- end row -->
		</div><!-- end col -->
	</div>

	<div class="col-xxl-4">
		<div class="card card-height-100">
			<div class="card-header border-0">
				<h4 class="card-title mb-0">Upcoming Events</h4>
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
		getDataDashboard();
	});

	async function getDataDashboard() {

		const res = await callApi('get', 'dashboard/admin-data');

		if (isSuccess(res)) {

			const data = res.data;

			$('#totalAllStudentCount').text(data.totalCount.totalStudent);
			$('#totalMaleStudentCount').text(data.totalCount.totalMale);
			$('#totalFemaleStudentCount').text(data.totalCount.totalFemale);

			$('#totalAllStudentCount').attr('data-target', data.totalCount.totalStudent);
			$('#totalMaleStudentCount').attr('data-target', data.totalCount.totalMale);
			$('#totalFemaleStudentCount').attr('data-target', data.totalCount.totalFemale);

			$('#chartEventHEP').text(data.chartEvent.HEP);
			$('#chartEventUniversity').text(data.chartEvent.University);
			$('#chartEventCollege').text(data.chartEvent.College);
			$('#chartEventFaculty').text(data.chartEvent.Faculty);
			$('#chartEventClub').text(data.chartEvent.Club);

			$('#totalCountAllEvent').text(data.reportStatus.totalActivity);
			$('#previousDataDiv').html(data.reportStatus.previousData);

			$('#incomingCountProgress').attr('aria-valuenow', data.reportStatus.incomingCount.percentage);
			$('#ongoingCountProgress').attr('aria-valuenow', data.reportStatus.ongoingCount.percentage);
			$('#completedCountProgress').attr('aria-valuenow', data.reportStatus.completedCount.percentage);
			$('#cancelledCountProgress').attr('aria-valuenow', data.reportStatus.cancelledCount.percentage);
			$('#othersCountProgress').attr('aria-valuenow', data.reportStatus.othersCount.percentage);

			$('#incomingCountProgress').css('width', data.reportStatus.incomingCount.percentage + '%');
			$('#ongoingCountProgress').css('width', data.reportStatus.ongoingCount.percentage + '%');
			$('#completedCountProgress').css('width', data.reportStatus.completedCount.percentage + '%');
			$('#cancelledCountProgress').css('width', data.reportStatus.cancelledCount.percentage + '%');
			$('#othersCountProgress').css('width', data.reportStatus.othersCount.percentage + '%');

			$('#incomingCountVal').text(data.reportStatus.incomingCount.total);
			$('#ongoingCountVal').text(data.reportStatus.ongoingCount.total);
			$('#completedCountVal').text(data.reportStatus.completedCount.total);
			$('#cancelledCountVal').text(data.reportStatus.cancelledCount.total);
			$('#othersCountVal').text(data.reportStatus.othersCount.total);

			$('#activityEvent').html(data.activity);

			chartDonut(data.chartEvent);

		}
	}

	function chartDonut(data) {
		var options = {
			series: [data.College, data.HEP, data.University, data.Faculty, data.Club],
			labels: ["College", "HEP", "University", "Academic/Faculty", "Association/Club"],
			chart: {
				type: "donut",
				height: 219
			},
			dataLabels: {
				enabled: true,
				formatter: function(val) {
					return val.toFixed(2) + "%"
				}
			},
			fill: {
				type: 'gradient',
			},
			plotOptions: {
				pie: {
					size: 100,
					donut: {
						size: "76%",
						labels: {
							show: true,
							name: {
								show: true,
								size: "30%",
							},
							value: {
								show: true,
							}
						}
					}
				}
			},
			legend: {
				show: false,
				position: "bottom",
				horizontalAlign: "center",
				offsetX: 0,
				offsetY: 0,
				markers: {
					width: 20,
					height: 6,
					radius: 2
				},
				itemMargin: {
					horizontal: 12,
					vertical: 0
				}
			},
			stroke: {
				width: 0
			},
		};

		var chart = new ApexCharts(document.querySelector("#chartEvent"), options);
		chart.render();
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