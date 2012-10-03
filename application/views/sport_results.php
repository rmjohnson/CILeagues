<?debug::vars($current_game) ?>
<table>
<form action="" method="POST">
	<tr>
		<th align="center" style="font-size:24px;"><?=team_link_from_id($current_game['team1'])?></th>
		<th align="center" style="font-size:24px;"><?=team_link_from_id($current_game['team2'])?></th>
	</tr>
	<tr>
		<td align="center"><input type="text" name="score1" size="1" style="height:50px;font-size:38px;text-align:center;"></td>
		<td align="center"><input type="text" name="score2" size="1" style="height:50px;font-size:38px;text-align:center;"></td>
	</tr>
	<tr>
		<td colspan="2" align="center">
			<input type="submit" value="Report Results" style="height:50px;font-size:18px;">
		</td>
	</tr>
</form>
</table>