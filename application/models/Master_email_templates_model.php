<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Master_email_templates_model extends CI_Model
{
	public $table = 'master_email_templates';
	public $id = 'email_id';
	public $order = 'ASC';

	public function __construct()
	{
		parent::__construct();
	}

	protected $fillable = [
		'email_type',
		'email_subject',
		'email_body',
		'email_footer',
		'email_cc',
		'email_bcc',
		'email_status',
	];

	public $with = [];

	###################################################################
	#                                                                 #
	#               Start custom function below                       #
	#                                                                 #
	###################################################################

	public function getEmailTemplateListDt($status = NULL)
	{
		$statusQuery = hasData($status) ? "WHERE `template`.`email_status` = " . escape($status) : NULL;

		$serverside = serversideDT();
		$serverside->query("SELECT 
        `template`.`email_type`,
        `template`.`email_subject`,
        `template`.`email_body`,
        `template`.`email_footer`,
        `template`.`email_cc`,
        `template`.`email_bcc`,
        `template`.`email_status`,
        `template`.`email_id`
        FROM {$this->table} `template`
		$statusQuery
        ORDER BY {$this->id} {$this->order}");

		$serverside->hide('email_body'); // hides column from the output
		$serverside->hide('email_footer'); // hides column from the output
		$serverside->hide('email_bcc'); // hides column from the output

		$serverside->edit('email_cc', function ($data) {
			$cc = !empty($data['email_cc']) ? $data['email_cc'] : '<i><small> (not set) </small></i>';
			$bcc = !empty($data['email_bcc']) ? $data['email_cc'] : '<i><small> (not set) </small></i>';
			return 'CC : ' . $cc . '<br> BCC : ' . $bcc;
		});

		$serverside->edit('email_status', function ($data) {
			$badge = [
				'0' => '<span class="badge bg-danger"> Inactive </span>',
				'1' => '<span class="badge bg-success"> Active </span>',
			];

			return $badge[$data['email_status']];
		});

		$serverside->edit('email_id', function ($data) {
			$edit = $del = $preview = '';
			$edit = '<button class="btn btn-soft-info btn-sm" onclick="updateRecord(' . $data[$this->id] . ')" title="Update"><i class="fa fa-edit"></i> </button>';
			// $del = '<button class="btn btn-soft-danger btn-sm" onclick="deleteRecord(' . $data[$this->id] . ')" data-id="' . $data[$this->id] . '" title="Delete"> <i class="fa fa-trash"></i> </button>';
			return "<center> $del $edit </center>";
		});

		echo $serverside->generate();
	}
}
