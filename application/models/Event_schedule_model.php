<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');
class Event_schedule_model extends CI_Model
{
	public $table = 'event_schedule';
	public $id = 'schedule_id';
	public $order = 'DESC';

	public function __construct()
	{
		parent::__construct();
		// $this->abilities = parent::permission([]);
	}

	protected $fillable = [
		'event_id',
		'schedule_date',
		'schedule_day_id',
		'schedule_day_name',
		'schedule_venue',
	];

	public $with = ['slot', 'event'];

	public function slotRelation($data)
	{
		return hasMany('Event_slot_model', 'slot_schedule_id', $data[$this->id], NULL, ['qrCode']);
	}

	public function eventRelation($data)
	{
		return hasOne('Event_model', 'event_id', $data['event_id'], NULL, ['organizer']);
	}
}
