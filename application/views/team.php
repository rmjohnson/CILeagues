<? if($editwin): ?><p>Team successfully updated!</p><? endif; ?>
<? if($editfail): ?><p>Team failed to update. =(</p><? endif; ?>
<? if(empty($teams)): ?>
<h3>No teams have been created. =(</h3>
<? else: ?>
<table>
	<tr>
		<th>Name</th>
		<th>Captain</th>
		<th>Member Count</th>
	</tr>
<? 
foreach($teams as $team)
{
	echo "<tr>";
	echo "\t<td>" . team_link_from_id($team['teams_id']) ."</td>";
	echo "\t<td>" . real_name_link_from_id($team['captain']) . "</td>";
	echo "\t<td>" . count_from_id($team['teams_id']) . "</td>";
	echo "</tr>";
}
?>
</table>
<? endif; ?>
<br />
<a href="<?=URL::base()?>team/add">Create a team</a>