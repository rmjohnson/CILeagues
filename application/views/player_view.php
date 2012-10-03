<h2><?= real_name_from_id($id) ?> on Team <?= team_link_from_id($team)?></h2>
<h4>Sports Participated in:</h4>
<?
//die(debug::vars($sports));
foreach($sports as $sport) {
	echo sport_link_from_id($sport);
}
?>
<br />
<br />
<?= $edit ?>