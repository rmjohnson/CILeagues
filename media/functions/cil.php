<?
function name_from_id($id) {
	return DB::select('username')->from('users')->where('id','=',$id)->execute()->get('username');
}
function name_link_from_id($id) {
	return '<a href="' . URL::base() . 'player/view/' . $id . '">' . name_from_id($id) . '</a>';
}
function real_name_from_id($id) {
	return DB::select('realname')->from('users')->where('id','=',$id)->execute()->get('realname');
}
function real_name_link_from_id($id) {
	return '<a href="' . URL::base() . 'player/view/' . $id . '">' . real_name_from_id($id) . '</a>';
}
function id_from_name($name) {
	return DB::select('id')->from('users')->where('username','LIKE','%'.$name.'%')->execute()->get('id');
}
function team_from_id($id) {
	return DB::select('name')->from('teams')->where('teams_id','=',$id)->execute()->get('name');
}
function team_link_from_id($id) {
	return '<a href="' . URL::base() . 'team/view/' . $id . '">' . team_from_id($id) . '</a>';
}
function sport_from_id($id) {
	return implode(" ",DB::select('name','yr')->from('sports')->where('sports_id','=',$id)->execute()->current());
}
function sport_link_from_id($id) {
	return '<a href="' . URL::base() . 'sport/view/' . $id . '">' . sport_from_id($id) . '</a>';
}
function count_from_id($id) {
	return DB::select(array('COUNT("user")', 'membercount'))->from('membership')->where('team','=',$id)->execute()->get('membercount');
}
function check_membership($player_id,$teams_id) {
	return DB::select()->from('membership')->where('user','=',$player_id)->where('team','=',$teams_id)->execute()->current();
}
function check_captain($player_id,$teams_id = NULL) {
	if($teams_id) {
		return DB::select()->from('teams')->where('captain','=',$player_id)->where('teams_id','=',$teams_id)->execute()->current();
	} else {
		return DB::select()->from('teams')->where('captain','=',$player_id)->execute()->current();
	}
}
function get_team($player_id) {
	return DB::select('team')->from('membership')->where('user','=',$player_id)->execute()->get('team');
}
function get_current_game($teams_id) {
	return DB::select()->from('results')
		->where('winner','=','0')
		->and_where_open()
			->where('team1','=',$teams_id)
			->or_where('team2','=',$teams_id)
		->and_where_close()
		->execute()->current();
}
function check_participation($teams_id,$sports_id) {
	return DB::select()->from('sports_teams')->where('sports_id','=',$sports_id)->where('teams_id','=',$teams_id)->execute()->current();
}
function generate_bracket($sports_id) {
	//Get the entire teams list
	$teams = DB::select('teams_id')->from('sports_teams')
		->where('sports_id','=',$sports_id)
		->execute()->as_array('teams_id','teams_id');
		
	//Scramble the teams
	shuffle($teams);
	
	//Pair up!
	$bracket = array();
	for($i=0;$i<=(count($teams)/2);$i+= 2) {
		array_push($bracket,array($teams[$i],$teams[$i+1]));
	}
	
	//Insert into results table with 0 in winner field
	$pos = 1;
	foreach($bracket as $pair) {
		DB::insert('results', array('sports_id','round','pos','team1','team2','winner'))
			->values(array($sports_id,1,$pos,$pair[0],$pair[1],0))
			->execute();
		$pos++;
	}
}
function view_bracket($sports_id) {
	//Get the results
	$results = DB::select('round','pos','team1','team2','winner')->from('results')
		->where('sports_id','=',$sports_id)
		->execute()->as_array();
	
	//Sort by round and position
	function cmp($a, $b) {
		if($a['round'] == $b['round']) {
			return $a['pos'] - $b['pos'];
		} else {
			return $a['round'] - $b['round'];
		}
	}
	usort($results,"cmp");
	
	//Form the bracket	
	$bracket = array();
	/*$team_count = DB::select(array('COUNT("teams_id")','count'))->from('sports_teams')
			->where('sports_id','=',$sports_id)
			->execute()->get('count');
	$rounds = range(1,(int)($team_count/2) + 1);*/
	foreach($results as $result) {
		$bracket = key_create($bracket,$result['round']);
		array_push($bracket[$result['round']],$result);
	}
	
	return $bracket;
}
function key_create($array,$key) {
	if(empty($array[$key])) {
		$array[$key] = array();
	}
	return $array;
}
?>