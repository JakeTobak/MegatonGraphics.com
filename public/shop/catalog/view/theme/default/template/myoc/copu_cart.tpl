<?php foreach ($uploads as $upload) { ?>
	<?php if($path == 'confirm') { ?><br />&nbsp;<?php } ?>
	- <small><?php echo $upload['option_name']; ?>: <a style="font-size:inherit;" href="<?php echo $upload['href']; ?>" title="<?php echo $text_download; ?>"><?php echo $upload['filename']; ?></a> [<?php echo $upload['size']; ?>]</small>
	<?php if($path == 'cart') { ?><br /><?php } ?>
<?php } ?>