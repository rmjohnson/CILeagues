<html>
<head>
	<title><?= $title; ?></title>
	<script type="text/javascript" src="<?= $media_base ?>scripts/cil.js"></script>
	<link rel="stylesheet" type="text/css" href="<?= $media_base ?>styles/cil.css" />
</head>
<body>
<h1><?= $title ?></h1>
<? 
if(@(!empty($errors)))
{
	echo "<p class=\"message error\">Ooops..Something went wrong:<br />";
	foreach($errors as $error)
	{
		echo $error . "<br />";
	}
	echo "</p>";
}
?>
<div class="content"><?= $content ?></div>
</body>
</html>