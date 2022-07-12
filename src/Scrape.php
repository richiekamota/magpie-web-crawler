<?php

namespace App;

use App\Product;

require 'vendor/autoload.php';

use Symfony\Component\DomCrawler\Crawler;

class Scrape
{
    private array $myproducts = [];

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

                $options = $productCrawler->filter('.px-2')->filter('span')->count();

                $mycolours = [];

                $productCrawler->filter('.flex')->children()->each(function(Crawler $flex) use (&$mycolours){
    
                        $flex->filter('.px-2')->children()->each(function(Crawler $span) use (&$mycolours) {
    
                        $data_colour = $span->filter('span')->eq(0)->attr('data-colour');      

                        $mycolours[] = $data_colour;
                    });
                });  

                for ($o=1; $o<=$options;$o++) {

                    $product = new Product();

                    $title = $productCrawler->filter(".text-blue-600")->text();
                    $cur_price = $productCrawler->filter(".text-lg")->text();  
                    $price = floatval(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $cur_price));                
                    $capacity = $productCrawler->filter(".product-capacity")->text();
                    $capacityMB = intval($capacity)*1000;
                    $img_url = $productCrawler->filter('img')->attr('src');
                    $imageUrl = str_replace("..","https://www.magpiehq.com/developer-challenge/smartphones",$img_url);
                    $availabilityText = $productCrawler->filter('.bg-white')->children('div')->eq(2)->text();

                    $isAvailable = (preg_match("/Availability: In Stock/", $availabilityText)) ? true:false;
                    $shippingText = (!preg_match("/Availability: Out of Stock/", $productCrawler->filter('.bg-white')->children('div')->last()->text())) ? $productCrawler->filter('.bg-white')->children('div')->last()->text(): '';
                    $shippingDate = (preg_match("/Unavailable for delivery/", $shippingText) || (preg_match("/Free Shipping/", $shippingText)) || (preg_match("/^Free Delivery$/", $shippingText)) || (preg_match("/Availability: In Stock/", $shippingText))) || empty($shippingText)? '' : $product->standard_date_format($shippingText);
                     
                    $product->title = $title; 
                    $product->price = $price; 
                    $product->capacityMB = $capacityMB;
                    $product->colour = $mycolours[$o-1];
                    $product->imageUrl = $imageUrl;
                    $product->availabilityText = $availabilityText;
                    $product->isAvailable = $isAvailable;
                    $product->shippingText = $shippingText;
                    $product->shippingDate = $shippingDate;
                    $myproducts[] = $product;                      
                }          
            }
        }
        $myproducts = $product->remove_duplicates($myproducts); 
        // We must not forget to de-dupe
         file_put_contents('output.json', json_encode($myproducts));
    }    
}

$scrape = new Scrape();
$scrape->run();
