<!-- <div class="row mb-2">
    <div class="col-12">
        <button type="button" class="btn btn-warning btn-sm float-end" onclick="getDataListStudent()" title="Refresh">
            <i class="ri-refresh-line"></i>
        </button>
    </div>
</div>

<div class="row mt-2">

    <input type="hidden" id="college_id_directory" placeholder="college_id" readonly>
    <input type="hidden" id="branch_id_directory" placeholder="branch_id" readonly>

    <div class="col-12">
        <div id="nodataStudentDiv" style="display: none;"> </div>
        <div id="dataListStudentDiv" style="display: none;">
            <table id="dataListStudent" class="table nowrap dt-responsive align-middle table-hover table-bordered dataTable no-footer dtr-inline collapsed" width="100%">
                <thead class="table-dark">
                    <tr>
                        <th> Student </th>
                        <th> Matric ID </th>
                        <th> Program </th>
                        <th> Level </th>
                        <th> Gender </th>
                        <th> Action </th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

</div>

<script>
    function getPassData(baseUrl, token, data) {
        $('#college_id_directory').val(data['college_id']);
        $('#branch_id_directory').val(data['branch_id']);
        $('#nodataStudentDiv').html(nodata());
        getDataListStudent();
    }

    async function getDataListStudent() {
        loading('#dataListStudentDiv', true);
        generateDatatable('dataListStudent', 'serverside', 'student/getListDtDirectory', 'nodataStudentDiv', {
            college_id: $('#college_id_directory').val(),
            branch_id: $('#branch_id_directory').val()
        });
        loading('#dataListStudentDiv', false);
    }

    async function addEnrollRecord(user_id) {

        loading('#dataListStudentDiv', true);
        const res = await callApi('student/assignCollege', {
            'user_id': user_id,
            'college_id': $('#college_id_directory').val(),
        });

        if (isSuccess(res)) {

            if (isSuccess(res.data.resCode)) {
                noti(res.status, 'Added');
                await getDataListStudent();
                await getDataListEnroll();
            } else {
                noti(500, res.data.message)
            }

            loading('#dataListStudentDiv', false);
        } else {
            noti(res.status);
        }
    }
</script> -->