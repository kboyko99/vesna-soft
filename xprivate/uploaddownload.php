<?php
/*
don't forget to have proper line in .htaccess
php_value upload_max_filesize 10M
*/

$uploads_dir = "./downloads";
$pass = "yaya";

if (!isset($_GET[$pass])) die("die");
if (!isset ($_FILES["fileupload"])) die("sad params");

$name = $_FILES["fileupload"]["name"];
$tmp_name = $_FILES["fileupload"]["tmp_name"];
$error = $_FILES["fileupload"]["error"];
if ($error == UPLOAD_ERR_OK) {
    $dest = "$uploads_dir/$name";
    $b = makeBackup($dest);
    if (move_uploaded_file($tmp_name, $dest)) {
        if ($b != "" && identical($dest, $b))
            unlink($b);
        echo "ok";
    }
    else
        print_r(error_get_last());
}

exit;

function identical ($f, $f2) {    return (filesize($f) == filesize($f2) && md5_file($f) == md5_file($f2));    }
function makeBackup($file) {
    $b = $file.".".date('Y-m-d-H-i-s', time());
    if (file_exists($file)) {
        rename($file, $b);
        return $b;
    }
    return "";
}