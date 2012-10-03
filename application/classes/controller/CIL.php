<?php defined('SYSPATH') or die('No direct script access.');

class Controller_CIL extends Controller_Template {

	public function before() {
		parent::before();
		$this->template->title = "Colliegate Intramural Leagues";
		$this->template->errors = array();
		$this->template->media_base = URL::Base().'media/';
		$this->user = Auth::instance()->get_user();
		include('media/functions/cil.php');
	}
}
