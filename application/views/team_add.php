<table>
<form action="" method="POST">
	<tr>
		<td>Team Name:</td>
		<td><input type="text" name="name" value="<?= $name; ?>"></td>
	</tr>
	<tr>
		<td>Team Description:</td>
		<td><textarea name="description"><?= $description ?></textarea></td>
	</tr>
	<tr>
		<td colspan="2" align="center"><input type="submit" value="Add Team"></td>
	</tr>
</table>
