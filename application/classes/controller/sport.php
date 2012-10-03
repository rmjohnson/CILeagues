<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Sport extends Controller_CIL {

	//Listing / searching for sports
	//If a param is included then view a sport
	public function action_index() {
		$view = View::factory('sport');
		$id = $this->request->param('id');
		if($id) {
			$this->request->redirect(URL::base() . 'sport/view/' . $id);
		}
		$view->editwin = isset($_GET['editwin']);
		$view->editfail = isset($_GET['editfail']);
		$view->sports = DB::select()->from('sports')->execute()->as_array('sports_id');
		$this->template->content = $view;
	}

	//Add a sport to a community
	public function action_add() {

		$view = View::factory('sport_add');
		$view->description = '';
		if($_POST) {
			$refs = array();
			$refCount = $_POST['refCount'];
			for($i=1;$i<=$refCount;$i++) {
				array_push($refs,$_POST['ref'.$i]);
			}
			
			$ref_ids = array();
			foreach($refs as $ref) {
				if(!id_from_name($ref)) {
					array_push($this->template->errors,"Could not find user: " . $ref);
				} else {
					array_push($ref_ids,id_from_name($ref));
				}
			}
			
			$description = @(htmlspecialchars($_POST['description']));
			$name = @(htmlspecialchars($_POST['name']));
			
			if(empty($this->template->errors)) {
				DB::insert('sports', array('name','description'))->values(array($name,$description))->execute();
				$this->request->redirect("/sport?success");
			} else {
				$view->description = $description;
			}
		}
		$this->template->content = $view;
	}
	
	//View the bracket and other information of a sport
	public function action_view() {
		$sports_id = $this->request->param('id');
		if(!$sports_id) {
			$this->request->redirect(URL::base() . 'sport');
		}
		$view = View::factory('sport_view');
		$sport = DB::select()->from('sports')->where('sports_id','=',$sports_id)->execute()->current();
		$view->sports_id = $sports_id;
		$view->player_id = $this->user->id;
		$view->refs = explode(",",$sport['refs']);
		$view->teams = DB::select('teams_id')->from('sports_teams')->where('sports_id','=',$sports_id)->execute()->as_array('teams_id','teams_id');
		$view->description = $sport['description'];
		$this->template->content = $view;
	}
	
	//Edit information about a sport
	public function action_edit() {
		$id = $this->request->param();
		$view = View::factory('sport_edit');
		$sport = DB::select()->from('sports')->where('sports_id','=',$id)->execute()->current();
		if($this->user == $sport['admin']) {
			$view->refs = $sport['refs'];
			$view->description = $sport['description'];
			if($_POST) {
				$refs = $_POST['refs'];
				if(DB::update('sports')->set(array('refs' => $refs,'players' => $players))->where('sports_id','=',$sport['sports_id'])->execute()) {
					$this->request->redirect("/sport?editwin");
				} else {
					$this->request->redirect("/sport?editfail");
				}
			}
			$this->template->content = $view;
		} else {
			$this->request->redirect("/?accessdenied");
		}
	}
	
	//Join a sport (only a captain can join a sport)
	public function action_join() {
		$sports_id = $this->request->param('id');
		$view = View::factory('sport_join');
		if(check_captain($this->user->id)) {
			$teams_id = DB::select('teams_id')->from('teams')->where('captain','=',$this->user->id)->execute()->get('teams_id');
			if(!DB::select()->from('sports_teams')->where('sports_id','=',$sports_id)->where('teams_id','=',$teams_id)->execute()->current()) {
				if(DB::insert('sports_teams',array('sports_id','teams_id'))->values(array($sports_id,$teams_id))->execute()) {
					$view->message = "Successfully joined.";
				} else {
					$view->message = "Joining failed.";
				}
			} else {
				$view->message = "Your team is already particpating in this sport.";
			}
			$this->template->content = $view;
		} else {
			$this->request->redirect('/sport?notcaptin');
		}	
	}
	
	//Report results from a game
	public function action_results() {
		$sports_id = $this->request->param('sports_id');
		$teams_id = $this->request->param('teams_id');
		
		$view = View::factory('sport_results');
		$game = get_current_game($teams_id);
		$view->current_game = $game;
		if($_POST) {
			$score1 = @(htmlspecialchars($_POST['score1']));
			$score2 = @(htmlspecialchars($_POST['score2']));
			$double_check = @($_POST['double_check']);
			//Check if there is already a score set by the other team
			if($game['score1'] == '0' and $game['score2'] == '0') {
				DB::update('results')->set(array('score1' => $score1,'score2' => $score2))->where('results_id','=',$game['results_id'])->execute();
			//If scores are different, make sure the user entered them correctly
			} elseif(($game['score1'] != $score1 or $game['score2'] != $score2) and !$double_check) {
				$view->score1 = $score1;
				$view->score2 = $score2;
				array_push($this->template->errors,'These are not the results the other team reported, can you please double check that you didn\'t make a typo.');
			//Scores still aren't the same, email a ref to decide
			} elseif(($game['score1'] != $score1 or $game['score2'] != $score2) and $double_check) {
				array_push($this->template->errors,'A ref has been emailed to check the reported scores.');
				//Email da ref
			//Scores are the same, so input winner and then check if next match can be decided
			} else {
				$winner = $score1 > $score2 ? $game['team1'] : $game['team2'];
				DB::update('results')->set(array('winner' => $winner))->where('results_id','=',$game['results_id'])->execute();
			}
		}
		$this->template->content = $view;
	}
}