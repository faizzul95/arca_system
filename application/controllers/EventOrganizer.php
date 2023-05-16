<?php

defined('BASEPATH') or exit('No direct script access allowed');

class EventOrganizer extends Controller
{
	public function __construct()
	{
		parent::__construct();
		model('Event_organizer_model', 'organizerM');
	}

	public function index()
	{
		errorpage('404');
	}

	public function getOrganizerByID()
	{
		if (isAjax() && input('organizer_id') != NULL) {
			json($this->organizerM::find(input('organizer_id')));
		} else {
			errorpage('404');
		}
	}

	public function delete($id)
	{
		if (isAjax()) {
			json($this->organizerM::delete(xssClean($id)));
		} else {
			errorpage('404');
		}
	}
}
