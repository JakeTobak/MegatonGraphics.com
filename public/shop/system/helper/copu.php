<?php
function formatFilesize($size = 0) {
    $i = 0;

	$suffix = array(
		'B',
		'KB',
		'MB',
		'GB',
		'TB'
	);

	while (($size / 1024) >= 1) {
		$size = $size / 1024;
		$i++;
	}

    return round(substr($size, 0, strpos($size, '.') + 4), 2) . $suffix[$i];
}

function truncateFilename($filename, $length) {
	$extension = substr(strrchr($filename, '.'), 1);
	$filename = basename($filename, '.' . $extension);
    if (strlen($filename) > $length) {
        return (substr($filename, 0, $length) . '...' . $extension);
    }
    return $filename . '.' . $extension;
}
?>