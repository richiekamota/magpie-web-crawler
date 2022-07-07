<?php

namespace App;
require 'vendor/autoload.php';
use Symfony\Component\DomCrawler\Crawler;

class Scrape
{
    private array $products = [];

    public function run(): void
    {
        $document = ScrapeHelper::fetchDocument('https://www.magpiehq.com/developer-challenge/smartphones');
        
        // Get the number of pages so that we know how many pages we have to iterate through
        $pages = $document->filter('#pages .px-6')->count();

        // https://www.magpiehq.com/developer-challenge/smartphones/?page={pagenumber}

        $myproducts = [];
        
        for ($i=1; $i <= $pages; $i++) {
           
            //echo "https://www.magpiehq.com/developer-challenge/smartphones/?page={$i}" . "\n";

            $document = ScrapeHelper::fetchDocument("https://www.magpiehq.com/developer-challenge/smartphones/?page={$i}");

            $products = $document->filter('.product');

        

            foreach ($products as $product) {
        
                $productCrawler = new Crawler($product);

                $options = $productCrawler->filter('.px-2')->count();

                for ($o=1; $o <= $options; $o++) {

                    // $productCrawler = new Crawler($option);

                    $title = $productCrawler->filter(".text-blue-600")->text();
    
                    // $this->products['title'] = $title;
    
                    $myproducts[] = $title;
                }



                // echo $title. "\n";

                // $productCrawler->filter('.text-blue-600')->children()->each(function(Crawler $spans) { 

                //     $capacity = $spans->filter('span')->text();

                //     // $this->products['capacityMB'] = intval($capacity)*1000;                    

                // });

              
                // $productCrawler->children(".bg-white")->each(function(Crawler $price_avails) {   
                   
                //     $img_url = $price_avails->filter('img')->attr('src');
                 
                //     echo $img_url. "\n";

                //     // $this->products['imgUrl'] = str_replace("..","https://www.magpiehq.com/developer-challenge/smartphones",$img_url); 

                //     $price = $price_avails->children('div')->filter(".my-8")->text();

                //     echo $price. "\n";

                //     //$this->products['price']= preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $price);

                //     $price_avails->children('div')->filter(".my-4")->text();

                //     // foreach($price_avails as $price_avail){

                //     //     $priceCrawler = new Crawler($price_avail);

                //     //     $price_avail = $priceCrawler->children('div')->eq(2)->text();

                //     //     echo $price_avail. "\n";

                //     //     //$this->products['availabilityText'] = $price_avail;
                        
                //     //     $this->products['isAvailable'] = (preg_match("/^Availability: In Stock$/", $price_avail)) ? true:false;

                //     // }                   

                //     $price_avails->filter('div')->each(function(Crawler $shipping) {
                        
                //         echo $shipping->text(). "\n";

                //         //$this->products['shippingText'] = $shipping->text();
                        
                //         //$this->products['shippingDate'] = $this->standard_date_format($shipping->text());
                    
                //     });
                // });

                // $productCrawler->filter('.flex')->children()->each(function(Crawler $flex) {
                
                //     $flex->filter('.px-2')->children()->each(function(Crawler $span) {

                //     $data_colour = $span->filter('span')->eq(0)->attr('data-colour');

                //     //$this->products['colour'] = $data_colour;

                //     echo $data_colour. "\n";        
                //     });
                // });  
           
            }
        }
        // We must not forget to de-dupe
               

        file_put_contents('output.json', json_encode($myproducts));
    }

    public function standard_date_format($str) {

        preg_match_all('/(\d{1,2}) (\w+) (\d{4})/', $str, $matches);

        $dates  = array_map("strtotime", $matches[0]);

        $result = array_map(function($v) {return date("Y-m-d", $v); }, $dates);

        //$value = array_key_exists(0, $result) ? $result[0] : '';
            return date("Y-m-d",strtotime($result[0]));
                 
        
    }
    
}

$scrape = new Scrape();
$scrape->run();
