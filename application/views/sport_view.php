<h2><?= sport_from_id($sports_id) ?></h2>
<!--<h4>Refs: 
<?
	$i = 0;
	foreach($refs as $ref)
	{
		echo name_from_id($ref);
		if($i+1 != count($refs)) {
			echo ", ";
		}
		$i++;
	}
?>
</h4>-->
<h4>Teams</h4>
<? if(empty($teams)): ?>
	No teams have joined yet.
<? else: ?>
	<ul>
		<? foreach($teams as $team) {
			echo "<li>" . team_link_from_id($team) . "</li>";
		}
		?>
	</ul>
<? endif; ?>
<h4>Description</h4>
<p><?= $description ?></p>
<? if(check_captain($player_id) and !check_participation(get_team($player_id),$sports_id)): ?>
	<a href="<?=URL::base()?>sport/join/<?=$sports_id?>">Add your team to this sport</a> 
<? elseif(check_captain($player_id)): ?>
	<a href="<?=URL::base()?>sport/results/<?=$sports_id?>/<?=get_team($player_id)?>">Report results from game</a>
<? endif; ?>
