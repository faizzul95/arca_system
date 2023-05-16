<style>
	.swal2-customCss {
		z-index: 1500;
	}
</style>

<div class="row">

	<div class="col-12">
		<div id="nodataOrganizerDiv"> </div>
		<div id="dataListOrganizerDiv" style="display: none;">
			<table id="dataListOrganizer" class="table nowrap dt-responsive align-middle table-hover table-bordered dataTable no-footer dtr-inline collapsed" width="100%">
				<thead class="table-dark">
					<tr>
						<th> Name </th>
						<th> Matric ID </th>
						<th> Action </th>
					</tr>
				</thead>
				<tbody></tbody>
			</table>
		</div>
	</div>

</div>

<script>
	async function getPassData(baseUrl, token, data) {
		$('#nodataOrganizerDiv').html(nodata());
		getOrganizerList(data);
	}

	async function getOrganizerList(listArr) {
		loading('#dataListOrganizerDiv', true);
		let listOrganizerArr = [];

		if (Object.keys(listArr).length != 0) {
			for (let i in listArr) {
				var userid = listArr[i]['user_id'];
				listOrganizerArr.push(userid);
			}
		}

		generateDatatable('dataListOrganizer', 'serverside', 'profile/list-all-organizer', 'nodataOrganizerDiv', {
				'listOrganizer': implode(listOrganizerArr),
			},
			[{
				"width": "65%",
				"targets": 0
			}, {
				"width": "21%",
				"targets": 1
			}]);

		loading('#dataListOrganizerDiv', false);
	}

	async function addOrganizer(userID, fullname = null, matricID = null, program = null, contacNo = null, email = null) {

		Swal.fire({
			title: 'Are you sure?',
			html: "Add <b>" + fullname + "</b> as organizer to this event!",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, Confirm!',
			customClass: {
				container: 'swal2-customCss'
			},
			reverseButtons: true
		}).then(
			async (result) => {
				if (result.isConfirmed) {

					// push data to array
					organizerDtList[userID] = {
						"user_id": userID,
						"user_full_name": fullname,
						"user_matric_code": matricID,
						"program_code": program,
						"user_contact_no": contacNo,
						"user_email": email,
						"organizer_id": '',
					};

					await getOrganizerList(organizerDtList);
					await generateTableOrganizer();
					closeOffcanvas('#generaloffcanvas-right');
				}
			})
	}
</script>