<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\helpers\Console;

use Goutte\Client;

/**
 * ContactForm is the model behind the contact form.
 */
class Aol extends Model
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

        $crawler = $this->client->request('GET', 'https://www.aol.com/');
        
        $form = $crawler->filter('form.m-form')->form();
        $form['q'] = $this->phrase;
        
        $crawler = $this->client->submit($form);



        $title = $crawler->filter('div.ALGO h3.hac a.find')->each(function ($node) {
            return $node->text();          
        });

        $url = $crawler->filter('div.ALGO h3.hac a.find')->extract(array('href'));
        
        $snippet = $crawler->filter('div.ALGO p[property = "f:desc"]')->each(function ($node) {
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
