<?php

/**
 * internal array to convert array to xml
 */
namespace Xml\Data {

    /**
     * checks if functions are created or not
     */
    if (!defined('FUNCTIONS_DEFINED')) {

        /**
         * flag functions are created
         */
        define('FUNCTIONS_DEFINED', 1);

        /**
         * @param array $array
         * @param null|string $rootNode
         * @return string
         */
        function array_to_xml(array $array, $rootNode = null) {
            $xml = '';
            foreach ($array as $key => $value) {
                if (is_array($value)) {
                   if (is_numeric($key) && is_array(current($value))) {
                       foreach ($value as $k => $v) {
                           $xml .= array_to_xml($v, $k);
                       }
                   } else {
                       $xml .= array_to_xml($value, $key);
                   }
                } else {
                    $value = str_replace(
                        ['&', '"', "'", '<', '>'],
                        ['&amp;', '&quot;', '&apos;', '&lt;', '&gt;'],
                        $value
                    );
                    $xml .= "<{$key}>{$value}</{$key}>\n";
                }
            }
            if ($rootNode) {
                $xml = "<{$rootNode}>\n{$xml}</{$rootNode}>\n";
            }
            return $xml;
        }

        /**
         * @param string $filename
         * @param string $content
         * @return bool|int
         * @throws \Exception
         */
        function write_file($filename, $content) {
            $filePath = dirname($filename);
            if (!file_exists($filePath)) {
                $success = @mkdir($filePath, 0777, true);
                if (!$success) {
                    throw new \Exception(sprintf('Can not create directory: %s', $filePath));
                }
            }

            if (file_exists($filename) && is_file($filename)) {
                if (!is_writeable($filename)) {
                    throw new \Exception(sprintf('The file %s is not writable', $filename));
                }
            }

            $bytes = file_put_contents($filename, $content);

            if (false !== $bytes) {
                return $bytes > 0;
            }

            return $bytes;
        }
    }
}
