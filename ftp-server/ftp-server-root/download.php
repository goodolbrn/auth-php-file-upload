<?php
	$filename = $_GET['filename'];
	$filepath = $_SERVER['DOCUMENT_ROOT']."/../uploads/".$filename;
	if (file_exists($filepath)) {
		header('Content-Description: File Transfer');
    		header('Content-Type: application/octet-stream');
            	header('Content-Disposition: attachment; filename="'.basename($filepath).'"');
            	header('Expires: 0');
           	header('Cache-Control: must-revalidate');
            	header('Pragma: public');
            	header('Content-Length: ' . filesize($filepath));
		flush();
		readfile($filepath);
	} else {
		echo "Path doesn't exist.";
	}
?>
