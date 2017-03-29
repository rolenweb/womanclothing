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
use app\models\Category;
use app\models\Product;


/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class SlugController extends BaseCommand
{
    public function actionIndex($type)
    {
        switch ($type) {
            case 'category':
                $this->category();
                break;

            case 'product':
                $this->product();
                break;
            
            default:
                # code...
                break;
        }
    }

    public function category()
    {
        $query = Category::find();
        $countQuery = clone $query;
        $i = 0;
        $total = $countQuery->count();
        Console::startProgress($i, $total);
        foreach ($query->all() as $category) {
            Console::updateProgress(++$i, $total);
            $category->save();
        }
        Console::startProgress($i, $total);
    }

    public function product()
    {
        $query = Product::find();
        $countQuery = clone $query;
        $i = 0;
        $total = $countQuery->count();
        Console::startProgress($i, $total);
        foreach ($query->each(100) as $product) {
            Console::updateProgress(++$i, $total);
            $product->save();
        }
        Console::startProgress($i, $total);
    }
    
}
