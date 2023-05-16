<div class="row">

    <div id="oldValueLebelDiv" class="col-12">
        <div class="alert alert-danger alert-dismissible alert-label-icon rounded-label fade show mb-xl-0" role="alert">
            <i class="ri-error-warning-line label-icon"></i> <strong> Old Value </strong>
        </div>
    </div>

    <div id="oldValueDiv" class="col-12 mt-2 mb-2">
        <div class="table-responsive">
            <table id="oldValue" class="table table-striped table-hover table-bordered">
                <thead>
                    <tr class="table-dark">
                        <th> Key </th>
                        <th> Value </th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

    <div id="newValueLebelDiv" class="col-12 mt-2">
        <div class="alert alert-info alert-dismissible alert-label-icon rounded-label fade show" role="alert">
            <i class="ri-edit-2-line label-icon"></i> <strong>New Value</strong>
        </div>
    </div>

    <div id="newValueDiv" class="col-12 mt-2 mb-2">
        <div class="table-responsive">
            <table id="newValue" class="table table-striped table-hover table-bordered">
                <thead>
                    <tr class="table-dark">
                        <th> Key </th>
                        <th> Value </th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

</div>

<script>
    function getPassData(baseUrl, token, data) {
        var event = data['event'];

        if (event == 'insert') {
            $('#oldValueLebelDiv').hide();
            $('#oldValueDiv').hide();
        } else if (event == 'delete') {
            $('#newValueLebelDiv').hide();
            $('#newValueDiv').hide();
        }

        generateOldValueDt(data);
        generateNewValueDt(data);
    }

    function generateOldValueDt(data) {
        var tableOld = generateDatatable('oldValue');
        const objOldValue = JSON.parse(data['old_values']);

        $.each(objOldValue, function(key, value) {
            tableOld.row.add([
                key,
                value,
            ]).draw();
        });
    }

    function generateNewValueDt(data) {
        var tableNew = generateDatatable('newValue');
        const objNewValue = JSON.parse(data['new_values']);

        $.each(objNewValue, function(key, value) {
            tableNew.row.add([
                key,
                value,
            ]).draw();
        });
    }
</script>