<?php

namespace FelixRupp\ComskipCutsConverter;

#require_once "Converter.php";
#require_once "Comskip.php";

require_once "phar://comskipCutsConverter.phar/Converter.php";
require_once "phar://comskipCutsConverter.phar/Comskip.php";

/**
 * Main File
 * @package FelixRupp\ComskipCutsConverter
 *
 * @author Felix Rupp <kontakt@felixrupp.com>
 * @copyright Felix Rupp <kontakt@felixrupp.com>
 */
if (($argc < 2 || $argc > 3) || in_array($argv[1], array('--help', '-help', '-h', '-?'))) {
?>

Create comskip .plist-file and convert it to Enigma2 .cuts file.

Usage: <?php echo $argv[0]; ?> <mpeg2_ts_file> (<mode>)

Access this help with --help, -help, -h oder -?

<mpeg2_ts_file> must be the path to a .ts file

(<mode>) (optional) define the mode of operation.
    'both':     default, which executes comskip
                and converts the output file to .cuts.
    'comskip':  only execute comskip.
    'convert':  only convert the comskip output to .cuts
                (you need to run comskip beforehand).

<?php
} else {

    if (isset($argv[1])) {

        $comskipFolder = ltrim(trim($argv[1]));

        $comskipFolderAbsolute = substr($comskipFolder, 0, strrpos($comskipFolder, ".ts"));

        $comskipFolder = ltrim(substr($comskipFolderAbsolute, strrpos($comskipFolderAbsolute, "/"), strlen($comskipFolderAbsolute)), '/');

        $comskipBasePath = rtrim(dirname($comskipFolderAbsolute), '/');
    } else {

        die(1);
    }

    $option = "both";

    if (isset($argv[2])) {

        $option = ltrim(trim($argv[2]));
    }

    echo "\nProcess recording: " . $comskipFolder . "\n\n";

    if ($option === "comskip") {

        Comskip::run($comskipFolder, $comskipBasePath);
    }

    if ($option === "convert") {

        Converter::run($comskipFolder, $comskipBasePath);
    }

    if ($option === "both") {

        Comskip::run($comskipFolder, $comskipBasePath);
        Converter::run($comskipFolder, $comskipBasePath);
    }
}