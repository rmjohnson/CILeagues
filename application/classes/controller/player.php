<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Player extends Controller_CIL {
	
	//Listing / searching for players
	//If a param is included then view a player
	public function action_index() {
		$view = View::factory('player');
		$this->template->errors = array();
		if($_POST) {
			$username = @($_POST['username']);
			$id = DB::select('id')->from('users')->where('username','=',$username)->execute()->get('id');
			if($id) {
				$this->request->redirect(URL::base() . 'player/view/' . $id);
			} else {
				array_push($this->template->errors,"Player not found.");
			}
		}
		$this->template->content = $view;
	}
	
	//View a player's profile
	public function action_view() {
		$view = View::factory('player_view');
		$view->id = $this->request->param('id');
		if(!$view->id) {
			$this->request->redirect(URL::base() . 'player/');
		}
		
		$view->team = DB::select('team')->from('membership')->where('user','=',$view->id)->execute()->get('team');
		$view->sports = DB::select('sports_id')->from('sports_teams')->where('teams_id','=',$view->team)->execute()->as_array('sports_id','sports_id');
		
		if($view->id == $this->user) {
			$view->edit = "<a href=\"" . URL::base() . "player/edit/" . $this->user .  "\">Edit your profile</a>";
		} else {
			$view->edit = "";
		}
		$this->template->content = $view;
	}
	
	//Edit password/email for a user/player
	public function action_edit() {
		$id = $this->request->param('id');
		if($id != $this->user) {
			$this->request->redirect(URL::base());
		}
		$view = View::factory('player_edit');
		
		$view->realname = @(real_name_from_id($id));
		
		if($_POST) {
			$realname = @($_POST['realname']);
			$current_password = @($_POST['current_password']);
			$new_password = @($_POST['new_password']);
			$confirm_password = @($_POST['confirm_password']);
			
			if($new_password) {
				if(!Auth::check_password($current_password)) {
					array_push($this->template->errors,"Password did not match password on file.");
				}
				if($new_password != $confirm_password) {
					array_push($this->template->errors,"New passwords did not match.");
				}
				if(empty($this->template->errors)) {
					password(name_from_id($id));
				}
			}
			if($realname) {
				DB::update('users')->set(array('realname' => $realname))->where('id','=',$id)->execute();
			}
			$this->request->redirect('/player/view/2');
		}
		$this->template->content = $view;
	}
	
	public function action_resetpass() {
		$view = View::factory('player_resetpass');
		if($_POST) {
			
		}
		$this->template->content = $view;
	}
}