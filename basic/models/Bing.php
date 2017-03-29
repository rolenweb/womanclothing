<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\helpers\Console;

use app\commands\tools\CurlClient;

/**
 * ContactForm is the model behind the contact form.
 */
class Bing extends Model
{
    public $phrase;
    public $url;
    public $client;

    public function __construct($config = [])
    {
        $this->url = 'http://www.bing.com/search?q=';
        $this->scenario = (empty($config['scenario']) === false) ? $config['scenario'] : 'default';
        $this->client = new CurlClient();
    }
    
    public function rules()
    {
        return [
            [['phrase'], 'string'],
            [['phrase'], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'phrase' => 'Phrase',
            
        ];
    }

    
    
    public function parse($phrase)
    {
        $response = [];

        $this->phrase = $phrase;

        $parseUrl = $this->url.urlencode($this->phrase);

        $content = $this->client->parsePage2($parseUrl);

        $title = $this->client->parseProperty($content,'string','li.b_algo h2 a',null,null);

        $url = $this->client->parseProperty($content,'attribute','li.b_algo h2 a',null,'href');

        $snippet = $this->client->parseProperty($content,'html','li.b_algo div.b_caption p',null,null);

        if (empty($title) || empty($url) || empty($snippet)) {
            return;
        }

        foreach ($title as $n => $item) {
            $response[$n]['title'] = trim($item);
            $response[$n]['url'] = (empty($url[$n]) === false) ? $url[$n] : null;
            $response[$n]['snippet'] = (empty($snippet[$n]) === false) ? $snippet[$n] : null;
            //$this->clearContent($snippet[$n]);

        }
        
        return $response;   
    }

    public function clearContent($content)
    {
        
        $patterns = ['span.mb_doct_txt','span.news_dt'];
        $badcode = [];
        foreach ($patterns as $pattern) {
            $badcode[] = $this->client->parseProperty($content,'html',$pattern,null,null);
        }
        
    }

    
}
