Edit your profile...<br />
<form action="" method="POST">
<table>
	<tr>
		<td>Your Name:</td>
		<td><input type="text" name="realname" value="<?=$realname?>"></td>
	</tr>
	<tr>
		<td>Current Password:</td>
		<td><input type="password" name="current_password"></td>
	</tr>
	<tr>
		<td>New Password:</td>
		<td><input type="password" name="new_password"></td>
	</tr>
	<tr>
		<td>Confirm New Password:</td>
		<td><input type="password" name="confirm_password"></td>
	</tr>
	<tr>
		<td colspan="2" align="right"><input type="submit" value="Save Changes"></td>
	</tr>
</table>
</form>