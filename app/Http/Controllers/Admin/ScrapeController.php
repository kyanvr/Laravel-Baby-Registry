<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Store;
use Goutte\Client;
use Illuminate\Http\Request;
use stdClass;

class ScrapeController extends Controller
{
    public function show()
    {
        $shops = [
            'welp' => 'Welp',
            'blabloom' => 'Blabloom',
            'baby-en-co' => 'Baby & Co',
        ];

        $categories = Category::all();

        return view('scraper/scrape-form', compact('shops', 'categories'));
    }

    public static function slugify($text, string $divider = '-')
    {
        // replace non letter or digits by divider
        $text = preg_replace('~[^\pL\d]+~u', $divider, $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, $divider);

        // remove duplicate divider
        $text = preg_replace('~-+~', $divider, $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }

    public function scrapeCategories(Request $req)
    {

        switch ($req->shop) {
            case 'welp':
                return $this->scrapeWelpCategories($req->url);
                break;
            case 'blabloom':
                return $this->scrapeBlabloomCategories($req->url);
                break;
            case 'baby-en-co':
                return $this->scrapeBabyEnCoCategories($req->url);
                break;
        }
    }

    public function scrapeProducts(Request $req)
    {
        switch ($req->store_id) {
            case '1':
                return $this->scrapeWelpProducts($req->url, $req->category_id);
                break;
            case '2':
                return $this->scrapeBlabloomProducts($req->url, $req->category_id);
                break;
            case '3':
                return $this->scrapeBabyEnCoProducts($req->url, $req->category_id);
                break;
        }

    }

    private function scrapeWelpCategories($url)
    {
        $client = new Client();
        $crawler = $client->request('GET', $url);
        $categories = $crawler->filter('body > div:nth-child(4) > div.col-md-3 > div > ul > li.item.active > ul > li:nth-child(4) > ul > li')
         
            ->each(function ($node) {

                $title = $node->filter('a')->text();
                $url = $node->filter('a')->attr('href');
                
                $cat =  new stdClass();
                $cat->title = $title;
                $cat->url = $url;
                $cat->store_id = 1;

                return $cat;
            });

        foreach ($categories as $scrapeCategory) {
            // check if exists
            $exists = Category::where('url', $scrapeCategory->url)->count();
            if ($exists > 0) continue;

            // create/add category to database
            $categoryEntity = new Category();
            $categoryEntity->title = $scrapeCategory->title;
            $categoryEntity->url = $scrapeCategory->url;
            $categoryEntity->store_id = $scrapeCategory->store_id;
            $categoryEntity->save();
        }

        return redirect()->back();
    }

    private function scrapeWelpProducts($url, $category_id)
    {
        $client = new Client();
        $crawler = $client->request('GET', $url);

        $products = $this->scrapeWelpData($crawler);

        foreach ($products as $product) {

            $this->scrapeWelpExtra($product->url, $category_id);
        }

        return redirect()->back();
    }

    private function scrapeWelpData($crawler)
    {
        return $crawler->filter('body > div:nth-child(4) > div.col-md-9.col-xs-12.col-sm-12.row.right > div > div > div.info.text-center')
        ->each(function ($node) {
            $product = new stdClass();
            $product->url = $node->filter('a')->attr('href');
            return $product;
        });
    }

    private function scrapeWelpExtra($url, $category_id)
    {
        $client = new Client();
        $crawler = $client->request('GET', $url);

        $title = $crawler->filter('body > div:nth-child(4) > div > div.col-md-5.col-xs-12.col-sm-5.product-details > h1')->text('');
        $price = $crawler->filter('body > div:nth-child(4) > div > div.col-md-5.col-xs-12.col-sm-5.product-details > div.price-wrap > div')->text('');
        $price = str_replace(",", ".", rtrim(substr($price, 3), " Incl. btw"));
        $description = $crawler->filter('body > div:nth-child(4) > div > div.col-md-2.col-sm-2.col-xs-12.thumbsWrapper > div > a.active > img')->text($title);
        $image = $crawler->filter('body > div:nth-child(4) > div > div.col-md-2.col-sm-2.col-xs-12.thumbsWrapper > div > a > img')->attr('src');
        
        $exists = Product::where('title', $title)->count();
        if (!$exists > 0) {

            $cat = new Product();
            $cat->title = $title;
            $cat->slug = $this->slugify($title);
            $cat->price = $price;
            $cat->store_id = 1;
            $cat->description = $description;
            $cat->category_id = $category_id;
            $cat->image_src = $image;
            $cat->save();
        };
    }

    private function scrapeBlabloomCategories($url)
    {
        $client = new Client();
        $crawler = $client->request('GET', $url);
        $categories = $crawler->filter('#filterTarget > section > div > ul > li > div')
        ->each(function ($node) {
            $title = $node->filter('a > span')->text();
            $url = $node->filter('a')->attr('href');
            $cat = new stdClass();
            $cat->title = $title;
            $cat->url = $url;
            $cat->store_id = 2;
            return $cat;
        });

        foreach ($categories as $scrapeCategory) {
            // check if exists
            $exists = Category::where('url', $scrapeCategory->url)->count();
            if ($exists > 0) continue;

            // create/add category to database
            $categoryEntity = new Category();
            $categoryEntity->title = $scrapeCategory->title;
            $categoryEntity->url = $scrapeCategory->url;
            $categoryEntity->store_id = $scrapeCategory->store_id;
            $categoryEntity->save();
        }

        return redirect()->back();
    }

    private function scrapeBlabloomProducts($url, $category_id)
    {
        $client = new Client();
        $crawler = $client->request('GET', $url);
        $products = $this->scrapeBlabloomData($crawler);

        foreach ($products as $product) {
            $this->scrapeBlabloomExtra($product->url, $category_id, $product->price);
        }

        return redirect()->back();
    }

    private function scrapeBlabloomData($crawler)
    {
        return $crawler->filter('#filterTarget > section > div > div.medium_col-9.medium_omega.large_col-9.large_omega > div.categories.small_cols-6.medium_cols-6.large_cols-4 > div > div > a')
        ->each(function ($node) {
            $product = new stdClass();
            $product->url = $node->attr('href');
            $price = $node->filter('p')->text();
            $product->price = str_replace(",", ".", substr($price, 5));
            return $product;
        });
    }

    private function scrapeBlabloomExtra($url, $category_id, $price)
    {
        $client = new Client();
        $crawler = $client->request('GET', $url);
        $title = $crawler->filter('#wrapper > div.section.-no-pt.row > div > section > h1')->text();
        $description = $crawler->filter('#description > span:nth-child(3) > p:nth-child(1)')->text($title);
        $image = $crawler->filter('#zoom-img')->attr('data-img-large');

        $exists = Product::where('title', $title)->count();
        if (!$exists > 0) {

            $cat = new Product();
            $cat->title = $title;
            $cat->slug = $this->slugify($title);
            $cat->store_id = 2;
            $cat->price = $price;
            $cat->description = $description;
            $cat->category_id = $category_id;
            $cat->image_src = $image;
            $cat->save();
        };
    }

    private function scrapeBabyEnCoCategories($url)
    {

        $client = new Client();
        $crawler = $client->request('GET', $url);

        $categories = $crawler->filter('#search_filters > aside:nth-child(1) .facet-type-checkbox li')
        ->each(function ($node) {
            $title = $node->filter('a')->text();
            $url = $node->filter('a')->attr('href');

            $cat = new stdClass();
            // Title contains number of products -> remove with mb_substr
            $cat->title = mb_substr($title, 0, -5);
            $cat->url = $url;
            $cat->store_id = 3;
            return $cat;
        });

        foreach ($categories as $scrapeCategory) {
            // check if exists
            $exists = Category::where('url', $scrapeCategory->url)->count();
            if ($exists > 0) continue;

            // create/add category to database
            $categoryEntity = new Category();
            $categoryEntity->title = $scrapeCategory->title;
            $categoryEntity->url = $scrapeCategory->url;
            $categoryEntity->store_id = $scrapeCategory->store_id;
            $categoryEntity->save();
        }

        return redirect()->back();
    }

    private function scrapeBabyEnCoProducts($url, $category_id)
    {
        $client = new Client();
        $crawler = $client->request('GET', $url);

        $articles = $this->scrapeBabyEnCoData($crawler);

        foreach($articles as $article) {
            $this->scrapeBabyEnCoExtra($article->url, $category_id);
        }

        return redirect()->back();
    }

    private function scrapeBabyEnCoData($crawler)
    {
        return $crawler->filter('div.js-product-miniature-wrapper')->each(function ($node) {
            $article = new stdClass();
            $article->url = $node->filter('article.product-miniature div.thumbnail-container a')->attr('href');
            return $article;
        });
    }

    private function scrapeBabyEnCoExtra($url, $category_id)
    {
        $client = new Client();
        $crawler = $client->request('GET', $url);

        $title = $crawler->filter('#col-product-info > div.product_header_container.clearfix > h1 > span')->text();
        $price = $crawler->filter('#col-product-info > div.product_header_container.clearfix > div.product-prices.js-product-prices > div:nth-child(3) > div > span > span')->text();
        $price = str_replace(",", ".", substr($price, 5));
        $description = $crawler->filter('#product-description-short-5853')->text($title);
        $image = $crawler->filter('#product-images-large > div.swiper-wrapper > div.product-lmage-large > img')->first()->attr('content');

        $exists = Product::where('title', $title)->count();
        if (!$exists > 0) {

            $cat = new Product();
            $cat->title = $title;
            $cat->slug = $this->slugify($title);
            $cat->price = $price;
            $cat->store_id = 3;
            $cat->description = $description;
            $cat->category_id = $category_id;
            $cat->image_src = $image;
            $cat->save();
        };
    }

    public function scrapeImages()
    {
        $products = Product::all();

        $this->storeImages($products);

        return redirect()->back();
    }

    private function storeImages($products)
    {
        foreach($products as $product)
        {
            // only add if not added
            if($product->image_internal) continue;

            // read image details
            $info = pathinfo($product->image_src);
            $extension = substr($info['extension'], 0, 3);
            $image = file_get_contents($product->image_src);
            $slug = $this->slugify($product->title);
            $fileLocation = $slug . "." . $extension;
            file_put_contents(storage_path('app/public/products/' . $fileLocation), $image);

            $product->image_internal = $fileLocation;
            $product->save();
        }
    }
}
