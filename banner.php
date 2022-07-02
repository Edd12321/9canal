<center>
	<hr width="468px" />
	<?php
		#Afisam un banner aleatoriu.
		$dir = $root.'/_res/banner/board';
		$images = scandir($dir);
		$i = rand(2, sizeof($images) - 1);
	?>
	<a href="<?php echo $fakeroot.'/'.basename(basename($images[$i],'.png'), '.gif'); ?>/">
		<img src="<?php echo $fakeroot; ?>/_res/banner/board/<?php echo $images[$i]; ?>" width="468px" height="60px">
	</a>
</center>
