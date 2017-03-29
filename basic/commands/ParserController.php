<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\helpers\Console;
use app\models\Link;
use app\models\Category;
use app\models\Product;
use app\models\ProductProperty;

use app\commands\tools\CurlClient;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class ParserController extends BaseCommand
{
    public $client;
    public $url;
    public $category_id;

    public function actionIndex()
    {
    	//ini_set('memory_limit', '512M');
        $this->setClient();

        for (;;) { 
    		$start = time();
    		$link = Link::findOne(['status' => Link::STATUS_WATING]);
	        if (empty($link)) {
	        	//$url = 'http://toyota-usa.epc-data.com/tacoma/';
	        	$this->error('The link is null');
	        	die;
	        }else{
	        	$this->url = $link->url;
	        }
	        $this->success('Parse url: '.$this->url);

	        $content = $this->client->parsePage($this->url);

            $breadcrumbs = $this->breadCrumbs($content);
            if (empty($breadcrumbs)) {
                $link->parsed();
                continue;
            }
            $this->saveCategory($breadcrumbs);

            $this->saveLinks($content);

            $this->saveProduct($content);

            $link->parsed();
            	        
	        $finish = time();
            $dif = $finish-$start;
            /*if ($dif < 3) {
            	$sleep = rand(1,5);
		        $this->success($sleep.' secs');
		        sleep($sleep);
            }*/
	        
    	}
        
    }

    public function setClient()
    {
        $this->client = new CurlClient();
    }

    public function setUrl($url)
    {
        $this->url = $url;
    }

    public function setCategoryId($categoryId)
    {
        $this->category_id = $categoryId;
    }

    public function breadCrumbs($content)
    {
    	$breadcrumbs = [];
    	$names = $this->client->parseProperty($content,'string','div.path span a span',null,null);

        if (empty($names)) {
            $this->error('Breadcrumbs is null');
            return;
        }

        foreach ($names as $name) {
            if ($name =='Home') {
                continue;
            }
            $breadcrumbs[] = trim($name);
        }
        
        if (!in_array('Women',$breadcrumbs)) {
            $this->error('Women is not found in breadcrumbs');
            return;
        }
    	return $breadcrumbs;
    }

    public function saveCategory($breadcrumbs)
    {
        if (empty($breadcrumbs)) {
            $this->error('Breadcrumbs is null');
            return;
        }
        $this->category_id = null;
        foreach ($breadcrumbs as $breadcrumb) {
            $parentCategory = Category::find()
                    ->where(
                        [
                            'and',
                                [
                                    'parent_id' => $this->category_id
                                ],
                                [
                                    'title' => $breadcrumb
                                ]
                        ]
                    )->limit(1)->one();
            if (empty($parentCategory) === false) {
                $this->category_id = $parentCategory->id;
            }else{
                $newCategory = new Category(['scenario' => 'create']);
                $newCategory->parent_id = $this->category_id;
                $newCategory->title = $breadcrumb;
                if ($newCategory->save()) {
                    $this->category_id = $newCategory->id;
                }    
            }
        }
    }

    public function saveLinks($content)
    {
        $patterns = ['div.catelist_sec a','div.catePro_ListBox ul#js_cateListUl p.proName a.js_goodsExp','div.pages p.listspan a'];
        foreach ($patterns as $pattern) {
            $links = $this->client->parseProperty($content,'link',$pattern,$this->url,null);
            if (empty($links)) {
                $this->error('Links for pattern '.$pattern.' is null');
                continue;
            }

            foreach ($links as $link) {
                $newLink = new Link(['scenario' => 'parse']);
                $newLink->url = trim($link);
                $newLink->status = Link::STATUS_WATING;
                if ($newLink->save()) {
                    
                }else{
                    foreach ($newLink->errors as $error) {
                        $this->error($error[0]);
                    }
                }
            }
        }
        return;
    }

    public function saveProduct($content)
    {
        $metaProperty = $this->client->parseProperty($content,'attribute','meta[property = "og:type"]',null,'content');
        if (empty($metaProperty)) {
            $this->whisper('Is not product page');
            return;
        }
        if ($metaProperty[0] !== 'product') {
            $this->whisper('Is not product page');
            return;
        }
        
        $title = $this->client->parseProperty($content,'string','div.pro_content div.pro_m h1',null,null);

        $star = $this->client->parseProperty($content,'string','div.pro_content div.pro_grade div.pro_price i.c_tagbg',null,null);

        $code = $this->client->parseProperty($content,'string','div.pro_content div.pro_grade div.pro_price em',null,null);

        $info_tips = $this->client->parseProperty($content,'string','div.pro_content div.pro_grade div.pro_price p.info-tips',null,null);

        $current_price = $this->client->parseProperty($content,'string','div.pro_content div.pro_price p.curPrice span#unit_price',null,null);

        $time_cout_down = $this->client->parseProperty($content,'attribute','div.pro_content div.pro_price div.pro_price_other strong#time_coutDown',null,'data-time');

        $cost_price = $this->client->parseProperty($content,'string','div.pro_content div.pro_price div.pro_price_other span.costPrice span.my_shop_price',null,null);

        $size = $this->client->parseProperty($content,'attribute','div.pro_content div.pro_property div.choose_size ul#js_property_size li.item a',null,'title');

        $image_url = $this->client->parseProperty($content,'attribute','div.pro_content div.pro_img div#js_n_bigImg img.myImgs',null,'src');        

        $description = $this->client->parseProperty($content,'html','div#js_proMain div.xxkkk div.xxkkk20',null,null);

        
        $product = new Product();
        $product->source_url = $this->url;
        $product->category_id = $this->category_id;
        $product->title = (empty($title[0]) === false) ? $title[0] : null;
        $product->star = (empty($star[0]) === false) ? $star[0] : null;
        $product->code = (empty($code[0]) === false) ? $code[0] : null;
        $product->info_tips = (empty($info_tips[0]) === false) ? $info_tips[0] : null;
        $product->current_price = (empty($current_price[0]) === false) ? $current_price[0] : null;
        $product->time_cout_down = (empty($time_cout_down[0]) === false) ? $time_cout_down[0] : null;
        $product->cost_price = (empty($cost_price[0]) === false) ? $cost_price[0] : null;
        $product->image_url = (empty($image_url[0]) === false) ? $image_url[0] : null;
        $product->description = (empty($description[0]) === false) ? $description[0] : null;
        
        if ($product->save()) {
            if (empty($size) === false) {
                foreach ($size as $item) {
                    $property = new ProductProperty();
                    $property->product_id = $product->id;
                    $property->title = 'size';
                    $property->value = $item;
                    if ($property->save()) {
                        # code...
                    }else{
                        foreach ($property->errors as $error) {
                            $this->error($error[0]);
                        }
                    }
                }
            }
            $this->saveImage($product->image_url,$product->code);    
        }else{
            foreach ($product->errors as $error) {
                $this->error($error[0]);
            }
        }
    }

    
    public function saveImage($url,$code)
    {
        $img = Yii::$app->basePath.DIRECTORY_SEPARATOR.'web'.DIRECTORY_SEPARATOR.'image'.DIRECTORY_SEPARATOR.'product'.DIRECTORY_SEPARATOR.$code.'.jpg';
        file_put_contents($img, file_get_contents($url));
        return;
    }
}
