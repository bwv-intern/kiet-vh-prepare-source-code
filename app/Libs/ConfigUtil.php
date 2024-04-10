<?php

namespace App\Libs;

use Symfony\Component\Yaml\Yaml;

class ConfigUtil
{
    const PATH = 'Constant/';
    const CONFIG_DIR = 'Config';
    const VALUE_LIST_DIR = 'Values';
    const MESSAGE_DIR = 'Messages';

    /**
     * Get root path
     * @return string
     */
    public static function rootPath() {
        return __DIR__ . '/../';
    }

    /**
     * Get message from message_file, params is optional
     *
     * @param $key
     * @param array $paramArray
     * @return mixed|null
     */
    public static function getMessage($key, ...$params) {
        $message = self::getConfig(self::MESSAGE_DIR, $key);
        $message = vsprintf($message, $params);
        
        return $message;
    }

    // public static function getMessage($code, ...$params) {
    //     // Get YAML contents
    //     $yamlContents = Yaml::parse(file_get_contents(base_path('/config/constants/message.yaml')));

    //     // Get message from code
    //     $message = $yamlContents[$code];

    //     // Replace placeholders with parameters
    //     $message = vsprintf($message, $params);

    //     return $message;
    // }

    /**
     * Get $key value from common config file
     *
     * @param $key
     * @return string|array|null
     */
    public static function get($key) {
        return self::getConfig(self::CONFIG_DIR, $key);
    }

    /**
     * Get $key value from value_list_file
     *
     * @param $keys
     * @param array $options
     * @return array|null
     */
    public static function getValueList($keys, $options = array()) {
        $keys = explode('.', $keys);
        if(!is_array($keys) || count($keys) != 2){
            return null;
        }

        list($fileName, $param) = $keys;
        $valueList = self::loadValueList($fileName, $param);
        if ($valueList && is_array($valueList)) {
            $resultList = array();
            foreach ($valueList as $key => $value) {
                if (!is_array($value)) {
                    $value = explode('|', $value);
                    if (!isset($value[1])) {
                        $resultList[$key] = $value[0];
                    } else if (isset($options['getList']) && $options['getList']) {
                        $resultList[$key] = $value[0];
                    }
                } else {
                    $resultList[$key] = $value;
                }

            }

            return $resultList;
        }

        return $valueList;
    }

    /**
     * @param $keys
     * @param bool $getText
     * @return int|null|string
     */
    public static function getValue($keys, $getText = false) {
        $keys = explode('.', $keys);
        if (!is_array($keys) || count($keys) != 3) {
            return null;
        }

        list($fileName, $key, $const) = $keys;
        $valueList = self::loadValueList($fileName, $key);

        if ($valueList && is_array($valueList)) {
            foreach($valueList as $key => $value){
                $value = explode('|', $value);
                if(isset($value[1]) && $value[1] == $const){
                    if($getText){
                        return $value[0];
                    }

                    return $key;
                }
            }
        }

        return null;
    }

    /**
     * Load $key value from specific value_list_file
     * @param $fileName
     * @param $key
     * @return mixed
     */
    public static function loadValueList($fileName, $key) {
        global $cacheYaml;
        global $cacheValueList;

        if (!isset($cacheYaml)) {
            $cacheYaml = array();
        }

        if (!isset($cacheValueList)) {
            $cacheValueList = array();
        }

        $valueListKey = $fileName . '.' . $key;
        if (isset($cacheValueList[$valueListKey])) {
            // retreiving from local static cache
            return $cacheValueList[$valueListKey];
        }

        if (isset($cacheYaml[$fileName])) {
            // retreiving from local static cache
            $paramValue = $cacheYaml[$fileName];
        } else {
            $filePath = self::rootPath() . self::PATH . self::VALUE_LIST_DIR . '/' . $fileName . '.yml';
            $paramValue = Yaml::parse(file_get_contents($filePath));
            $cacheYaml[$fileName] = $paramValue; // cache
        }

        $cacheValueList[$valueListKey] = $paramValue[$key]; // cache
        return $paramValue[$key];
    }

    /**
     * Get config params from DemoBundle/Reosurce/config/folder_name
     *
     * @param $folderName
     * @param $paramKey
     * @return null
     */
    private static function getConfig($folderName, $paramKey) {
        global $cacheConfig;
        global $cacheConfigFile;

        if (!isset($cacheConfig)) {
            $cacheConfig = array();
        }

        if (!isset($cacheConfigFile)) {
            $cacheConfigFile = array();
        }

        if (isset($cacheConfig[$paramKey])) {
            return $cacheConfig[$paramKey];
        }

        $folderPath = self::rootPath() . self::PATH . $folderName;
        $paramKeyArr = explode('.', $paramKey);

        foreach (glob($folderPath . '/*.yml') as $yamlSrc) {
            if (isset($cacheConfigFile[basename($yamlSrc)])) {
                $paramValue = $cacheConfigFile[basename($yamlSrc)];
            } else {
                $paramValue = Yaml::parse(file_get_contents($yamlSrc));
                $cacheConfigFile[basename($yamlSrc)] = $paramValue;
            }

            $found = true;

            foreach ($paramKeyArr as $key) {
                if (!isset($paramValue[$key])) {
                    $found = false;
                    break;
                }
                $paramValue = $paramValue[$key];
            }

            if($found){
                $cacheConfig[$paramKey] = $paramValue;
                return $paramValue;
            }
        }

        return null;
    }
}
