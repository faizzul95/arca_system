<div class="row">
	<div id="dataList"></div>
	<input type="hidden" id="event_categories" />
</div>

<script>
	function getPassData(baseUrl, token, data) {
		$('#event_categories').val(data.category_id);
		$('#nodatadivAttendance').html(nodata());

		setTimeout(function() {
			getDataListAttendance();
		}, 100);
	}

	function elementsSearch() {
		var input = document.getElementById('elementsSearchInput');
		var filter = input.value.toUpperCase();
		var list = document.getElementById("elementsSearchList");
		var listItem = list.getElementsByClassName('affan-element-item');

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

	async function getDataListAttendance() {
		// console.log($('#event_categories').val());
		loading('#bodyAttendanceDiv', true);

		const res = await callApi('get', 'attendance/attendance-student-category/' + $('#event_categories').val());

		if (isSuccess(res)) {
			$('#dataList').html(res.data)
		}

		loading('#bodyAttendanceDiv', false);
	}
</script>