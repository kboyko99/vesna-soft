<?php
if (isset($_POST['camera']) && $_POST['camera']) {
	file_put_contents('gotocamera.log', date('d.m.Y H:i')." goto kowo.me camera".PHP_EOL, FILE_APPEND);
}