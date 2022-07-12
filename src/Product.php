<?php

namespace App;

class Product
{
    public $title;
    public $price;
    public $imageUrl;    
    public $capacityMB;
    public $colour;
    public $availabilityText;
    public $isAvailable;
    public $shippingText;
    public $shippingDate;

    public function standard_date_format($str)
    {

        preg_match_all('/(\d{1,2}) (\w+) (\d{4})/', $str, $matches) || preg_match_all('/\d{4}-\d{2}-\d{2}/', $str, $matches) || preg_match_all('/\s*(\d+)(?:[a-z]{2})?\s+(?:of\s+)?([a-z]{3})\s+(\d{4})/i', $str, $matches) || preg_match_all('/tomorrow/i', $str, $matches);

        $dates  = array_map('strtotime', $matches[0]);

        $result = array_map(function($v) {return date("Y-m-d", $v); }, $dates);

        return date("Y-m-d",strtotime($result[0]));        
    }

    public function remove_duplicates($array, $keep_key_assoc = false)
    {
        $duplicate_keys = array();
        $tmp = array();       
    
        foreach ($array as $key => $val){
            // convert objects to arrays, in_array() does not support objects
            if (is_object($val)){
                $val = (array)$val;
            }                
    
            if (!in_array($val, $tmp)){
                $tmp[] = $val;
            } else {
                $duplicate_keys[] = $key;
            }                
        }
    
        foreach ($duplicate_keys as $key){
            unset($array[$key]);
        }           
    
        return $keep_key_assoc ? $array : array_values($array);
    }
}
