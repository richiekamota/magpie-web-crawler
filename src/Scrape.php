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

        //echo $pages;

        // https://www.magpiehq.com/developer-challenge/smartphones/?page={pagenumber}
        for ($i=1; $i <= $pages; $i++) {
            //echo "https://www.magpiehq.com/developer-challenge/smartphones/?page={$i}" . "\n";

            $document = ScrapeHelper::fetchDocument("https://www.magpiehq.com/developer-challenge/smartphones/?page={$i}");

            $products = $document->filter('.product');

            foreach ($products as $product) {

                $productCrawler = new Crawler($product);

                $productCrawler->filter('.text-blue-600')->children()->each(function(Crawler $span) {   

                    $product_name = $span->filter('span')->text();

                    echo $product_name. "\n";
        
                });

                $img_url = $productCrawler->filter('img')->attr('src');   
                 
                echo $img_url. "\n";

                $productCrawler->filter(".my-8 block text-center text-lg")->each(function(Crawler $prices) {
                     
                    // $product_price = null;
                    foreach ($prices as $price) {

                        $priceCrawler = new Crawler($price);

                        $priceCrawler->each(function(Crawler $price_elements){
                            
                            foreach($price_elements as $price_element){
                                $price_element->parentNode->removeChild($price_element);
                            }                             
                        });
                    }
                });

                $price_content = $productCrawler->filter('div')->text();
                echo $price_content. "\n";
                
                                 
                $productCrawler->filter('.flex')->children()->each(function(Crawler $flex) {
                   
                    $flex->filter('.px-2')->children()->each(function(Crawler $span) {
                       $data_colour = $span->filter('span')->eq(0)->attr('data-colour');
                       echo $data_colour. "\n";        
                    });
                });  
            }
           
        }

        // https://www.magpiehq.com/developer-challenge/smartphones/?page={pageNumber}

        // On each page we loop through each div with class product px-4 w-full md:w-1/2 mx-auto max-w-md mb-12

        // On each page we loop through each card div with class bg-white p-4 rounded-md

        // We must then de-dupe after the first parse

        // .........
        // remember capacity 1GB=1000MB
        // IsAvailable value dependent on the availabilityText
               

        file_put_contents('output.json', json_encode($pages));
    }
}

$scrape = new Scrape();
$scrape->run();
