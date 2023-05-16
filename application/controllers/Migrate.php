<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migrate extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		library('migration');
	}

	public function index()
	{
		exit('No direct access allowed');
	}

	public function all()
	{
		if (filter_var(env('MIGRATION'), FILTER_VALIDATE_BOOLEAN)) {
			if ($this->migration->current() === FALSE) {
				show_error($this->migration->error_string());
			}
		} else {
			exit('No access to this page');
		}
	}

	public function list()
	{
		if (filter_var(env('MIGRATION'), FILTER_VALIDATE_BOOLEAN)) {

			$files = array_diff(scandir(APPPATH . 'migrations'), array('.', '..'));
			echo '
        <base href="' . base_url . '">
        <meta name="csrf-token" content="' . csrfValue() . '" />
        <meta name="base_url" content="' . baseURL() . '" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <script src="https://cdn.jsdelivr.net/npm/js-cookie@3.0.1/dist/js.cookie.min.js"></script>
        <link href="' . asset('custom/css/toastr.min.css', null, false) . '" rel="stylesheet" type="text/css" />

        <script src="' . asset('custom/js/jquery.min.js', null, false) . '"></script>
        <script src="' . asset('custom/js/axios.min.js', null, false) . '"></script>
        <script src="' . asset('custom/js/common.js', null, false) . '"></script>
        <script src="' . asset('custom/js/toastr.min.js', null, false) . '"></script>';

			echo '<div class="container mt-5">
                <div class="row">
                    <table class="table table-bordered table-striped table-responsive-sm" width="100%">
                        <thead class="table-dark">
                            <tr>
                                <th> Filename </th>
                                <th width="10%"> Status </th>
                                <th width="25%"> Action </th>
                            </td>
                        </thead>
                        <tbody>';

			foreach ($files as $fileName) {

				$file = APPPATH . 'migrations/' . $fileName;

				include_once($file);
				$class = 'Migration_' . ucfirst(strtolower($this->_get_migration_name(basename($file, '.php'))));
				$obj = new $class;
				$tableName = $obj->table_name;

				$status = isTableExist($tableName) ? 1 : 0;
				$statusBadge = [
					0 => '<span class="badge bg-danger"> Not Exist </span>',
					1 => '<span class="badge bg-success"> Exist </span>',
				];

				$actionUp =  $status == 1 ? '' : '<a class="btn btn-sm btn-outline-success me-2" href="javascript:void(0);" onclick="migration(\'up\', \'' . $fileName . '\')"> <span class="fa fa-plus"></span> Migrate </a>';
				$actionDown =  $status != 1 ? '' : '<a class="btn btn-sm btn-outline-danger me-2" href="javascript:void(0);" onclick="migration(\'down\', \'' . $fileName . '\')"> <span class="fa fa-minus"></span> Drop </a>';
				$actionSeed =  $status != 1 ? '' : '<a class="btn btn-sm btn-outline-primary me-2" href="javascript:void(0);" onclick="migration(\'seeder\', \'' . $fileName . '\')"> <span class="fa fa-database"></span> Seed </a>';
				$actionTruncate =  $status != 1 ? '' : '<a class="btn btn-sm btn-outline-dark me-2" href="javascript:void(0);" onclick="migration(\'truncate\', \'' . $fileName . '\')"> <span class="fa fa-trash-alt"></span> Truncate </a>';

				echo '<tr>
                    <td> &nbsp; ' . basename($fileName, '.php') . ' </td>
                    <td> &nbsp; ' . $statusBadge[$status] . ' </td>
                    <td>
                        <center>' . $actionUp . $actionDown . $actionSeed . $actionTruncate . '
                        </center>
                    </td>
                </tr>';
			}

			echo '          </tbody>
                    </table>
                </div>
            </div>
            
            
            <script>
                async function migration(type="up", fileName=null)
                {
                    const res = await callApi(\'post\',\'migrate/specific-migration\', {
                        type: type,
                        fileName: fileName,
                    });

                    if (isSuccess(res)) {
                        noti(res.status, res.data);
                        setTimeout(function() {
                            window.location.reload();
                        }, 1800);
                    } else {
                        noti(res.status);
                    }
                }
            </script>

            ';
		} else {
			exit('No access to this page');
		}
	}

	public function specificMigration()
	{
		if (filter_var(env('MIGRATION'), FILTER_VALIDATE_BOOLEAN)) {
			$file = APPPATH . 'migrations/' . input('fileName');

			include_once($file);
			$class = 'Migration_' . ucfirst(strtolower($this->_get_migration_name(basename($file, '.php'))));

			$migration = array($class, input('type'));
			$migration[0] = new $migration[0];
			call_user_func($migration);
		} else {
			exit('No access to this page');
		}
	}

	protected function _get_migration_name($migration)
	{
		$parts = explode('_', $migration);
		array_shift($parts);
		return implode('_', $parts);
	}
}
