<h2><?=$name?></h2>
<table>
	<tr>
		<td>Captain:</td>
		<td><?= real_name_link_from_id($captain) ?></td>
	</tr>
	<tr>
		<td>Description: </td>
		<td><?= $description ?></td>
	</tr>
</table>
<table>
	<tr>
		<th>Players</th>
	</tr>
	<?
		foreach($players as $player)
		{
			echo "<tr>\n";
			echo "\t<td>" .  real_name_link_from_id($player) . "</td>\n";
			echo "</tr>\n";
		}
	?>
</table>
<br />
<table>
	<tr>
		<th>Current Sports</th>
	</tr>
	<?
		foreach($current_sports as $sport)
		{
			echo "<tr>\n";
			echo "\t<td>" .  sport_link_from_id($sport) . "</td>\n";
			echo "</tr>\n";
		}
	?>
</table>
<table>
	<tr>
		<th>Past Sports</th>
	</tr>
	<?
		foreach($past_sports as $sport)
		{
			echo "<tr>\n";
			echo "\t<td>" .  sport_link_from_id($sport) . "</td>\n";
			echo "</tr>\n";
		}
	?>
</table>
<?= $join ?>