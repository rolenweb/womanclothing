<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;
use yii\helpers\Console;

use app\models\Bing;
use app\models\Yahoo;
use app\models\Yandex;
use app\models\Aol;
use app\models\Ecosia;
use app\models\Ixquick;
use app\models\Ask;
use app\models\Google2;


/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class SpiderController extends BaseCommand
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     */
    public function actionIndex()
    {
    	for (;;) { 
    		$start = time();
            
            //$bing = new Bing();
            //$result = $bing->parse('Convert cad to aud');
            //$yahoo = new Yahoo();
            //$result = $yahoo->parse('Convert cad to aud');
            //$yandex = new Yandex();
            //$result = $yandex->parse('Convert cad to aud');
            //$aol = new Aol();
            //$result = $duckduckgo->parse('Convert cad to aud');
            //$ecosia = new Ecosia();
            //$result = $ecosia->parse('Convert cad to aud');
            //$ixquick = new Ixquick();
            //$result = $ixquick->parse('Convert cad to aud');
            //$ask = new Ask();
            //$result = $ask->parse('Convert cad to aud');
            $google = new Google2();
            $result = $google->parse('convert cad to aud');
            var_dump($result);
            die;
            
            $finish = time();
            $dif = $finish-$start;
            $sleep = rand(50,70);
            $this->whisper('Sleep '.$sleep.' sec');
            sleep($sleep);
    	}
        
    }

}
