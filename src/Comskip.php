<?php

namespace FelixRupp\ComskipCutsConverter;

/**
 * Class Comskip
 * @package FelixRupp\ComskipCutsConverter
 *
 * @author Felix Rupp <kontakt@felixrupp.com>
 * @copyright Felix Rupp <kontakt@felixrupp.com>
 */
class Comskip
{

    /**
     * @const string
     */
    const INI_FILE = "/comskip.ini";

    /**
     * @param string $comskipFolder
     * @param string $comskipBasePath
     * @return string
     */
    public static function run($comskipFolder, $comskipBasePath)
    {

        if (strlen($comskipBasePath) <= 0) {

            $comskipBasePath = ".";
        }

        if (!is_dir($comskipBasePath . "/" . $comskipFolder)) {

            echo "Create folder for cut-files …\n\n";
            mkdir($comskipBasePath . "/" . $comskipFolder);
        }

        if (is_file($comskipBasePath . "/" . $comskipFolder . ".ts.cuts")) {

            echo "Copy Enigma2 cuts-file …\n\n";
            copy($comskipBasePath . "/" . $comskipFolder . ".ts.cuts", $comskipBasePath . "/" . $comskipFolder . "/" . $comskipFolder . ".ts.cuts");
        }

        $shellCommand = escapeshellcmd(escapeshellarg(dirname(__FILE__)) . "/../vendor/erikkaashoek/Comskip/comskip --ini=" . escapeshellarg(dirname(__FILE__) . self::INI_FILE) . " --hwassist --plist " . escapeshellarg($comskipBasePath . "/" . $comskipFolder) . ".ts --output=" . escapeshellarg($comskipBasePath . "/" . $comskipFolder));
        #$shellCommand = escapeshellcmd(escapeshellarg("phar://".dirname(__FILE__))."/comskipCutsConverter.phar/comskip --ini=" . escapeshellarg("phar://".dirname(__FILE__)."comskipCutsConverter.phar" . self::INI_FILE) . " --hwassist --plist " . escapeshellarg($comskipBasePath . "/".$comskipFolder) . ".ts --output=" . escapeshellarg($comskipBasePath . "/".$comskipFolder));

        return system($shellCommand);
    }
}