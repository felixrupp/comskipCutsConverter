<?php

namespace FelixRupp\ComskipCutsConverter;

/**
 * Class Converter
 * @package FelixRupp\ComskipCutsConverter
 *
 * @author Felix Rupp <kontakt@felixrupp.com>
 * @copyright Felix Rupp <kontakt@felixrupp.com>
 */
class Converter
{

    /**
     * @param $comskipFolder
     * @return bool
     */
    public static function run($comskipFolder, $comskipBasePath)
    {

        if (strlen($comskipBasePath) <= 0) {

            $comskipBasePath = ".";
        }

        $fileName = $comskipFolder;

        $cutsDataOld = self::readCuts($comskipBasePath . "/" . $comskipFolder . "/" . $fileName . ".ts.cuts");

        $plistData = self::readComskipPlist($comskipBasePath . "/" . $comskipFolder . "/" . $fileName . ".plist");

        $cutsDataToSave = array_merge($cutsDataOld, $plistData);
        asort($cutsDataToSave);

        $result = self::writeCuts($comskipBasePath . "/" . $comskipFolder . "/" . $fileName . ".ts.new.cuts", $plistData);

        if (is_file(realpath($comskipBasePath . "/" . $comskipFolder . "/" . $fileName . ".ts.new.cuts"))) {

            echo "Copy new Enigma2 cuts-file …\n\n";
            copy($comskipBasePath . "/" . $comskipFolder . "/" . $fileName . ".ts.new.cuts", $comskipBasePath . "/" . $comskipFolder . ".ts.cuts");
        }

        # Garbage Collection
        if (is_dir(realpath($comskipBasePath . "/" . $comskipFolder))) {

            $it = new \RecursiveDirectoryIterator($comskipBasePath . "/" . $comskipFolder, \RecursiveDirectoryIterator::SKIP_DOTS);
            $files = new \RecursiveIteratorIterator($it, \RecursiveIteratorIterator::CHILD_FIRST);

            foreach ($files as $file) {

                if ($file->isDir()) {

                    rmdir($file->getRealPath());
                } else {

                    unlink($file->getRealPath());
                }
            }

            rmdir($comskipBasePath . "/" . $comskipFolder);
        }

        return $result . "\n";
    }

    /**
     * @param string $cutsFile
     * @return array
     */
    private static function readCuts($cutsFile)
    {

        $newFileArray = [];

        if (is_file(realpath($cutsFile))) {

            $fileHandle = fopen($cutsFile, "rb");


            if ($fileHandle) {
                while (!feof($fileHandle)) {

                    if (!($where = fread($fileHandle, (64 / 8)))) {
                        break;
                    }
                    if (!($what = fread($fileHandle, (32 / 8)))) {
                        break;
                    }

                    $where = implode(unpack("J", $where));
                    $what = implode(unpack("N", $what));

                    if ($what < 4) {

                        $newFileArray[intval($where)] = [intval($where), intval($what)];
                    }
                }

                fclose($fileHandle);

                echo "CUTs file successfully read.\n\n";
            } else {

                echo "CUTs file not found!\n\n";
            }
        } else {

            echo "CUTs file not found!\n\n";
        }

        return $newFileArray;
    }

    /**
     * @param string $cutsFile
     * @param array $cutsData
     * @return bool
     */
    private static function writeCuts($cutsFile, $cutsData)
    {

        $fileHandle = fopen($cutsFile, "wb");

        if ($fileHandle) {

            foreach ($cutsData as $cutsEntry) {

                $where = pack("J", intval($cutsEntry[0]));
                $what = pack("N", intval($cutsEntry[1]));

                fwrite($fileHandle, $where, (64 / 8));
                fwrite($fileHandle, $what, (32 / 8));
            }

            fclose($fileHandle);

            echo "CUTs file has been written.\n\n";
            return TRUE;
        } else {

            echo "CUTs file could not be created!\n\n";
            return FALSE;
        }
    }

    /**
     * @param string $plistFile
     * @return array
     */
    private static function readComskipPlist($plistFile)
    {

        $newCutsArray = [];

        if (is_file(realpath($plistFile))) {
            $xmlNodes = simplexml_load_file($plistFile);


            if ($xmlNodes) {

                foreach ($xmlNodes->integer as $integerNode) {

                    $newCutsArray[(int)$integerNode] = [(int)$integerNode, 2];
                }

                echo "Comskip PLIST has been succesfully read.\n\n";

            } else {

                echo "Comskip PLIST file not found!\n\n";
            }
        } else {

            echo "Comskip PLIST file not found!\n\n";
        }

        return $newCutsArray;
    }
}

