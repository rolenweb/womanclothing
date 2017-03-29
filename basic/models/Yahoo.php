<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\helpers\Console;

use Goutte\Client;

/**
 * ContactForm is the model behind the contact form.
 */
class Yahoo extends Model
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

        $crawler = $this->client->request('GET', 'https://search.yahoo.com/');

        $form = $crawler->filter('form#sf')->form();
        $form['p'] = $this->phrase;
        
        $crawler = $this->client->submit($form);

        $title = $crawler->filter('div.compTitle h3.title a')->each(function ($node) {
            return $node->text();          
        });
        
        $url = $crawler->filter('div.compTitle h3.title a')->extract(array('href'));
        
        $snippet = $crawler->filter('div.compText p.lh-16')->each(function ($node) {
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
