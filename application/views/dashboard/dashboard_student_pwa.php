@extends('templates.pwa_blade')

@section('content')

<div class="bg-img bg-overlay py-4">
	<div class="container direction-rtl">
		<div class="row align-items-center">

			<div class="col-4">
				<div class="single-counter-wrap">
					<h6 class="text-white mt-2"> COLLECTION <br> <small> {{ currentAcademicName() }} </small> </h6>
					<span class="solid-line ms-0 bg-warning"></span>
				</div>
			</div>

			<div class="col-2">
				<!-- Single Counter -->
				<div class="single-counter-wrap text-center" role="button" onclick="viewActivityList(3)">
					<i class="bi bi-house-door mb-1 text-white"></i>
					<h6 class="mb-0 text-white">
						<span id="collegeCounter" class="counter is-visible" style="visibility: visible;">0</span>
					</h6>
				</div>
			</div>

			<div class="col-2">
				<!-- Single Counter -->
				<div class="single-counter-wrap text-center" role="button" onclick="viewActivityList(2)">
					<i class="bi bi-mortarboard mb-1 text-white"></i>
					<h6 class="mb-0 text-white">
						<span id="universityCounter" class="counter is-visible" style="visibility: visible;">0</span>
					</h6>
				</div>
			</div>

			<div class="col-2">
				<!-- Single Counter -->
				<div class="single-counter-wrap text-center" role="button" onclick="viewActivityList(1)">
					<i class="bi bi-people mb-1 text-white"></i>
					<h6 class="mb-0 text-white">
						<span id="hepCounter" class="counter is-visible" style="visibility: visible;">0</span>
					</h6>
				</div>
			</div>

			<div class="col-2">
				<!-- Single Counter -->
				<div class="single-counter-wrap text-center" role="button" onclick="viewActivityList(6)">
					<i class="bi bi-activity mb-1 text-white"></i>
					<h6 class="mb-0 text-white">
						<span id="othersCounter" class="counter is-visible" style="visibility: visible;">0</span>
					</h6>
				</div>
			</div>

		</div>
	</div>
</div>

<div class="container">

	<div class="affan-element-item">
		<div class="element-heading-wrapper">
			<div class="heading-text">
				<h6 class="mb-1">Incoming Events</h6>
			</div>
		</div>
	</div>

	<div class="row g-3">

		<div id="nodataEvent" class="col-12 mb-4" style="display: none;">
			<div class="card">
				<div class="card-body text-center p-5">
					<img class="mb-4" src="{{ asset('custom/img/nodata/pwa.png', null, false) }}" title="Web illustrations by Storyset" alt="">
					<h4 class="mb-4"> No incoming events </h4>
				</div>
			</div>
		</div>

		<div id="dataEventList" class="col-12 mb-4" style="display: block;">

			<div id="listActivity"> </div>
		</div>

	</div>
</div>

<script>
	$(document).ready(async function() {
		await getDataDashboard();

		setTimeout(function() {
			getEventDashboardStudent();
		}, 100);
	});

	async function getDataDashboard() {

		const res = await callApi('get', 'dashboard/sticker-count');

		// check if request is success
		if (isSuccess(res)) {
			const data = res.data;
			$('#collegeCounter').text(isset(data.total_college_sticker) ? data.total_college_sticker : 0);
			$('#universityCounter').text(isset(data.total_university_sticker) ? data.total_university_sticker : 0);
			$('#hepCounter').text(isset(data.total_hep_sticker) ? data.total_hep_sticker : 0);
			var faculty = isset(data.total_faculty_sticker) ? data.total_faculty_sticker : 0;
			var club = isset(data.total_club_sticker) ? data.total_club_sticker : 0;
			var others = parseInt(faculty) + parseInt(club);

			$('#othersCounter').text(data != '' ? others : 0);
		}
	}

	async function getEventDashboardStudent() {
		const res = await callApi('get', 'event/list-event-pwa');

		// check if request is success
		if (isSuccess(res)) {

			if (hasData(res.data.trim())) {
				$('#nodataEvent').hide();
				$('#dataEventList').show();
				$('#listActivity').html(res.data);
			} else {
				$('#nodataEvent').show();
				$('#dataEventList').hide();
			}

		} else {
			$('#nodataEvent').show();
			$('#dataEventList').hide();
		}
	}

	function viewActivityList(categoryID) {
		var category = {
			'1': 'HEP',
			'2': 'University',
			'3': 'College',
			'4': 'Academic/Faculty',
			'5': 'Association/Club',
			'6': 'Faculty & Club', // only in this view, not used elsewhere
		};

		loadFileContent('event/_attendanceStudentListView.php', 'generalContent', 'fullscreen', 'Attendance : ' + category[categoryID], {
			'category_id': categoryID,
		});
	}

	function viewSlotInfo(slotID, eventName) {
		loadFileContent('event/_viewSlotInfoModal.php', 'generalContent', 'fullscreen', 'Event Details', {
			'slot_id': slotID,
		});
	}

	function activitySearch() {
		var input = document.getElementById('eventSearchInput');
		var filter = input.value.toUpperCase();
		var list = document.getElementById("listActivity");
		var listItem = list.getElementsByClassName('activity-list-item');

		for (i = 0; i < listItem.length; i++) {
			var a = listItem[i];
			var textValue = a.textContent || a.innerText;
			if (textValue.toUpperCase().indexOf(filter) > -1) {
				listItem[i].style.display = "";
			} else {
				listItem[i].style.display = "none";
			}
		}
	}
</script>

@endsection