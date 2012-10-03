<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Team extends Controller_CIL {

	//Listing / searching for teams
	//If a param is included then view a team
	public function action_index() {
		$view = View::factory('team');
		$view->editwin = isset($_GET['editwin']);
		$view->editfail = isset($_GET['editfail']);
		$view->teams = DB::select()->from('teams')->execute()->as_array();
		$this->template->content = $view;
	}
	
	//Create a team
	public function action_add() {
		$view = View::factory('team_add');
		$view->name = '';
		$view->description = '';
		if($_POST) {
			//A function to retrieve ID using name or email should be made
			$name = @(htmlspecialchars($_POST['name']));
			$description = @(htmlspecialchars($_POST['description']));
			if($name && $description) {
				//Create the team and set the captain
				DB::insert('teams', array('captain','name','description'))->values(array($this->user->id,$name,$description))->execute();
				//Get newly created team id for membership insertion
				$team = DB::select('teams_id')->from('teams')->where('captain','=',$this->user->id)->execute()->get('teams_id');
				//Add the captain as the first member
				DB::insert('membership', array('user','team'))->values(array($this->user->id,$team))->execute();
				$this->request->redirect("/team?success");
			} else {
				array_push($this->template->errors,"Both a name and description are required to make a team.");
				$view->name = $name;
				$view->description = $description;
			}
		}
		$this->template->content = $view;
	}
	
	//View team information
	public function action_view() {
		$id = $this->request->param('id');
		if(!$id) {	
			$this->request->redirect('/team');
		} else {
			$view = View::factory('team_view');
			$team = DB::select()->from('teams')->where('teams_id','=',$id)->execute()->current();
			$view->captain = $team['captain'];
			$view->name = $team['name'];
			$view->description = $team['description'];
			$view->players = DB::select('user')->from('membership')->where('team','=',$id)->execute()->as_array('user','user');
			//Remove the captain from the players list
			unset($view->players[$team['captain']]);
			$view->join = '';
			$view->current_sports = DB::select('st.sports_id')->from(array('sports_teams','st'))->join(array('sports','s'))->on('s.sports_id','=','st.sports_id')->where('st.teams_id','=',$id)->where('s.active','=',1)->execute()->as_array('sports_id','sports_id');
			$view->past_sports = DB::select('st.sports_id')->from(array('sports_teams','st'))->join(array('sports','s'))->on('s.sports_id','=','st.sports_id')->where('st.teams_id','=',$id)->where('s.active','=',0)->execute()->as_array('sports_id','sports_id');
			// If the logged in user is not already in the team, add a join link
			if(!DB::select()->from('membership')->where('team','=',$id)->where('user','=',$this->user)->execute()->current()) {
				$view->join = '<a href="/team/join/' . $id[0] . '">Join this team!</a>';
			}
			
			$this->template->content = $view;
		}
	}
	
	//Edit information about a team
	public function action_edit() {
		$id = $this->request->param();
		if(!$id) {
			$this->request->redirect('/team');
		} else {
			$view = View::factory('team_edit');
			$team = DB::select()->from('teams')->where('teams_id','=',$id)->execute()->current();
			if($this->user == $team['captain']) {
				$view->mods = $team['mods'];
				$view->name = $team['name'];
				$view->description = $team['description'];
				if($_POST) {
					$mods = $_POST['mods'];
					$name = $_POST['name'];
					$description = $_POST['description'];
					if(DB::update('teams')->set(array('mods' => $mods,'name' => $name, 'description' => $description))->where('teams_id','=',$team['teams_id'])->execute())
					{
						$this->request->redirect("/team?editwin");
					} else {
						$this->request->redirect("/team?editfail");
					}
				}
				$this->template->content = $view;
			} else {
				$this->request->redirect("/?accessdenied");
			}
		}
	}
	
	//Permalink to join a team
	public function action_join() {
		$team = $this->request->param('id');
		$view = View::factory('team_join');
		$view->team_id = $team;
		$this->template->errors = array();
		if(DB::select()->from('membership')->where('user','=',$this->user)->where('team','=',$team)->execute()->current()) {
			array_push($this->template->errors, 'You are already a member of this team.');
		}
		if(empty($this->template->errors)) {
			if(DB::insert('membership',array('user','team'))->values(array($this->user,$team))->execute()) {
				$view->message = "Successfully joined.";
			} else {
				$view->message = "Joining failed.";
			}
		}
		$this->template->content = $view;
	}
}