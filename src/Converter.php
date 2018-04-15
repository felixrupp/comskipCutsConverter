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

        $cutsDataOld = self::readCuts($comskipBasePath . "/" . $fileName . ".ts.cuts");

        $plistData = self::readComskipPlist($comskipBasePath . "/" . $comskipFolder . "/" . $fileName . ".plist");

        $cutsDataToSave = array_merge($cutsDataOld, $plistData);
        asort($cutsDataToSave);

        $result = self::writeCuts($comskipBasePath . "/" . $comskipFolder . "/" . $fileName . ".ts.new.cuts", $plistData);

        return $result . "\n";
    }

    /**
     * @param string $cutsFile
     * @return array|bool
     */
    private static function readCuts($cutsFile)
    {

        $fileHandle = fopen($cutsFile, "rb");
        $newFileArray = [];

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
            return $newFileArray;
        } else {

            echo "CUTs file not found!\n\n";
            return FALSE;
        }
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
     * @return array|bool
     */
    private static function readComskipPlist($plistFile)
    {

        $xmlNodes = simplexml_load_file($plistFile);

        if ($xmlNodes) {

            $newCutsArray = [];

            foreach ($xmlNodes->integer as $integerNode) {

                $newCutsArray[(int)$integerNode] = [(int)$integerNode, 2];
            }

            echo "Comskip PLIST has been succesfully read.\n\n";
            return $newCutsArray;
        } else {

            echo "Comskip PLIST file not found!\n\n";
            return FALSE;
        }
    }
}

