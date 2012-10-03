<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Bracket extends Controller_CIL {
	public function action_index() {
		$view = View::factory('bracket');
		
		$sports_id = $this->request->param('id');
		
		//Check if a bracket is already generated
		if(!DB::select()->from('results')->where('sports_id','=',$sports_id)->execute()) {
			generate_bracket($sports_id);
		}
		
		$view->bracket = view_bracket($sports_id);
		
		$team_count = DB::select(array('COUNT("teams_id")','count'))->from('sports_teams')
			->where('sports_id','=',$sports_id)
			->execute()->get('count');
		$view->rounds = range(1,(int)($team_count/2) + 1);
		
		$view->sports_id = $sports_id;
		
		$this->template->content = $view;
	}
}