<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\helpers\Console;

use Goutte\Client;

/**
 * ContactForm is the model behind the contact form.
 */
class Ask extends Model
{
    public $phrase;
    public $url;
    public $client;

    public function __construct($config = [])
    {
        $this->scenario = (empty($config['scenario']) === false) ? $config['scenario'] : 'default';
        $this->client = new Client();
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

        $crawler = $this->client->request('GET', 'http://www.ask.com/');
        
        $form = $crawler->filter('form#PartialHome-form')->form();
        $form['q'] = $this->phrase;
        
        $crawler = $this->client->submit($form);

        

        $title = $crawler->filter('div.PartialSearchResults-item a.PartialSearchResults-item-title-link')->each(function ($node) {
            return $node->text();          
        });

        $url = $crawler->filter('div.PartialSearchResults-item a.PartialSearchResults-item-title-link')->extract(array('href'));
        
        $snippet = $crawler->filter('div.PartialSearchResults-item p.PartialSearchResults-item-abstract')->each(function ($node) {
            return $node->html();          
        });
        

        foreach ($title as $n => $item) {
            $response[$n]['title'] = trim($item);
            $response[$n]['url'] = (empty($url[$n]) === false) ? $url[$n] : null;
            $response[$n]['snippet'] = (empty($snippet[$n]) === false) ? $snippet[$n] : null;
        }
        
        return $response;   
    }

    
}
