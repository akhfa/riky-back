<!DOCTYPE html>
<html>
<head>
	<title><?php echo $title; ?></title>
</head>
<body>
	<?php
		foreach($categories as $object){
			echo $object->kategori . '<br/>';
		}
		echo '<br/><br/>';

		foreach($content as $object){
			echo $object->kategori . '\'s id is' . $object->id . '<br/>';
		}
	?>
</body>
</html>