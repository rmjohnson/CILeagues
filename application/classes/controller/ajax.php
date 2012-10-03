<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Ajax extends Controller_ESB {

	public function action_index()
	{
		$action = $this->request->param('id');
		if($action == 'player_search')
		{
			$this->request->action('player_search');
		}
	}
}