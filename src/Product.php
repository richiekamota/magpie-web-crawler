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

    public function standard_date_format($str) {

        preg_match_all('/(\d{1,2}) (\w+) (\d{4})/', $str, $matches) || preg_match_all('/\d{4}-\d{2}-\d{2}/', $str, $matches);

        $dates  = array_map('strtotime', $matches[0]);

        $result = array_map(function($v) {return date("Y-m-d", $v); }, $dates);

        //$value = array_key_exists(0, $result) ? $result[0] : '';
            return date("Y-m-d",strtotime($result[0]));        
    }

}
