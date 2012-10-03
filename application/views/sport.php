<h2>Sports</h2>
<table cellspacing="10px">
	<?
		foreach($sports as $sport)
		{
			echo "<tr>\n";
			echo "\t<td>" . sport_link_from_id($sport['sports_id']) . "</td>\n";
			echo "</tr>\n";
		}
	?>
</table>