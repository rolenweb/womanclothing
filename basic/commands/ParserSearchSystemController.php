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

use app\models\Bing;
use app\models\Yahoo;
use app\models\Yandex;
use app\models\Aol;
use app\models\Ecosia;
use app\models\Ixquick;
use app\models\Ask;
use app\models\ScheduleParseSearch as Schedule;
use app\models\SearchData;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class ParserSearchSystemController extends BaseCommand
{
    public $system_name;
    public $system;
    public $result;
    
    public function actionIndex()
    {
        for (;;) { 
            $this->system_name = $this->ss()[rand(0,count($this->ss())-1)];

            $this->whisper('Parse SS: '.$this->system_name);

            $schedule = Schedule::current();

            $this->whisper('Parse Title: '.$schedule->product->title);

            switch ($this->system_name) {
                case 'bing':
                    $this->system = new Bing();
                    $this->result = $this->system->parse(strtolower($schedule->product->title));
                    break;

                case 'yahoo':
                    $this->system = new Yahoo();
                    $this->result = $this->system->parse(strtolower($schedule->product->title));
                    break;

                case 'yandex':
                    $this->system = new Yandex();
                    $this->result = $this->system->parse(strtolower($schedule->product->title));
                    break;

                case 'aol':
                    $this->system = new Aol();
                    $this->result = $this->system->parse(strtolower($schedule->product->title));
                    break;

                case 'ecosia':
                    $this->system = new Ecosia();
                    $this->result = $this->system->parse(strtolower($schedule->product->title));
                    break;

                case 'ixquick':
                    $this->system = new Ixquick();
                    $this->result = $this->system->parse(strtolower($schedule->product->title));
                    break;

                case 'ask':
                    $this->system = new Ask();
                    $this->result = $this->system->parse(strtolower($schedule->product->title));
                    break;
                
                default:
                    # code...
                    break;
            }
            if (empty($this->result) === false) {
                if (empty($schedule->product->searchData) === false) {
                    $this->whisper('Delete old search data');
                    $schedule->product->deleteSearchData();
                }
                foreach ($this->result as $item) {
                    if (empty($item['title']) === false && empty($item['url']) === false && empty($item['snippet']) === false) {
                        $data = new SearchData();    
                        $data->product_id = $schedule->product->id;
                        $data->name_ss = $this->system_name;
                        $data->title = $item['title'];
                        $data->url = $item['url'];
                        $data->snippet = $item['snippet'];
                        
                        if ($data->save()) {
                            # code...
                        }else{
                            foreach ($data->errors as $error) {
                                $this->error($error[0]);
                            }
                        }
                    }
                }
                $schedule->next();
            }else{
                $this->error('Result is null');
            }
            $sleep = rand(50,70);
            $this->whisper($sleep.' secs');
            sleep($sleep);
        }
    	
        

    }

    public function ss()
    {
        return ['bing','yahoo','yandex','aol','ecosia','ixquick','ask'];
    }

    
    
}
