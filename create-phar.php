<?php
/**
 * Created by PhpStorm.
 * User: felixrupp
 * Date: 15.04.18
 * Time: 21:50
 */

$srcRoot = "./src";
$buildRoot = "./build";
$vendorRoot = "./vendor";

$phar = new Phar($buildRoot . "/comskipCutsConverter.phar",
    FilesystemIterator::CURRENT_AS_FILEINFO | FilesystemIterator::KEY_AS_FILENAME, "comskipCutsConverter.phar");
$phar["index.php"] = file_get_contents($srcRoot . "/index.php");
$phar["Converter.php"] = file_get_contents($srcRoot . "/Converter.php");
$phar["Comskip.php"] = file_get_contents($srcRoot . "/Comskip.php");
$phar->setStub($phar->createDefaultStub("index.php"));

echo "Phar created at: ".$buildRoot . "/comskipCutsConverter.phar\n";

if (is_file($srcRoot . "/comskip.ini")) {

    copy($srcRoot . "/comskip.ini", $buildRoot . "/comskip.ini");

    echo "comskip.ini copied.\n";
}

if (is_file($vendorRoot . "/erikkaashoek/Comskip/comskip")) {

    copy($vendorRoot . "/erikkaashoek/Comskip/comskip", $buildRoot . "/comskip");

    echo "comskip executable copied.\n";
}

chmod($buildRoot . "/comskipCutsConverter.phar", 0755);
chmod($buildRoot . "/comskip", 0755);

echo "Accessrights corrected.\n";