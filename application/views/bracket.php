<h2><?=sport_link_from_id($sports_id)?></h2>
<?
//die(debug::vars($bracket)); 
?>
<table>
	<!--<tr>
		<? foreach($rounds as $round): ?>
			<td>Round <?=$round?></td>
		<? endforeach; ?>
	</tr>-->
	<tr>
		<? foreach($rounds as $round): ?>
			<td>
				<? if(array_key_exists($round,$bracket)): ?>
					<? foreach($bracket[$round] as $game): ?>
					<p>
						<?= team_link_from_id($game['team1']) ?>
						<br />
						<?= team_link_from_id($game['team2']) ?>
					</p>
					<? endforeach; ?>
				<? endif; ?>
			</td>
		<? endforeach; ?>
	</tr>
</table>
