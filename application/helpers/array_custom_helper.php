<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

if (!function_exists('groupArray')) {
    function groupArray($arr, $colNames)
    {
        $groupStr = '$groupedArr';
        $groupedArr = array();

        foreach ($colNames as $groupCol) {
            $groupStr .= '[$value[\'' . $groupCol . '\']]';
        }
        foreach ($arr as $key => $value) {
            $grpStr = $groupStr . '[] = $value;';
            eval($grpStr);
        }

        return $groupedArr;
    }
}

if (!function_exists('fillUndefinedIndex')) {
    function fillUndefinedIndex($arr, $colNames, $emptyIndexStr = 'undefined')
    {
        foreach ($colNames as $colName) {
            foreach ($arr as $key => $value) {
                if (!isset($value[$colName])) {
                    $arr[$key][$colName] = $emptyIndexStr;
                }
            }
        }
        return $arr;
    }
}

if (!function_exists('searcharrayExist')) {
    function searcharrayExist($valueSearch, $array)
    {
        foreach ($array as $keyData => $data) {
            if ($keyData == $valueSearch) {
                return true;
            }
        }
        return false;
    }
}

if (!function_exists('isArray')) {
    function isArray($data)
    {
        return (is_array($data)) ? true : false;
    }
}

if (!function_exists('isJson')) {
    function isJson($string)
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
}

if (!function_exists('isAssociative')) {
    function isAssociative($arr)
    {
        foreach (array_keys($arr) as $key)
            if (!is_int($key)) return TRUE;
        return FALSE;
    }
}

if (!function_exists('isMultidimension')) {
    function isMultidimension($arr)
    {
        if (!empty($arr)) {
            rsort($arr);
            return isset($arr[0]) && is_array($arr[0]);
        } else {
            return $arr;
        }
    }
}

if (!function_exists('explodeArr')) {
    function explodeArr($param, $type = ',', $returnIndex = NULL)
    {
        $str = explode($type, $param);

        if ($returnIndex != NULL) {
            $str = $str[$returnIndex];
        }

        return $str;
    }
}

if (!function_exists('removeArraybyKey')) {
    function removeArraybyKey($arrToSearch, $keyName, $keyValueToRemove)
    {
        while (($idx = array_search($keyValueToRemove, array_column($arrToSearch, $keyName))) !== false) {
            unset($arrToSearch[$idx]);
            $arrToSearch = array_values($arrToSearch);
        }

        return $arrToSearch;
    }
}

if (!function_exists('getKeyListFromArr')) {
    function getKeyListFromArr($arrToExtract, $keyName)
    {
        $keyList = [];
        foreach ($arrToExtract as $key => $value) {
            $keyList[] = $value[$keyName];
        }
        return $keyList;
    }
}

if (!function_exists('filterArrayFromArray')) {
    function filterArrayFromArray($dataArr, $filterArr, $keyName)
    {
        $keyList = getKeyListFromArr($filterArr, $keyName);
        foreach ($keyList as $key => $value) {
            $dataArr = removeArraybyKey($dataArr, $keyName, $value);
        }
        return $dataArr;
    }
}
