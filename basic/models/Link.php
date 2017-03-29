<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "link".
 *
 * @property integer $id
 * @property string $url
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class Link extends \yii\db\ActiveRecord
{
    const STATUS_WATING = 1;
    const STATUS_PARSED = 2;

    public $host;
    public $black_url;
    public $summary_error;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'link';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['url'], 'string', 'max' => 255],
            [['url'], 'unique'],
            [['url'],'checkBlack','on' => 'parse'],
            [['black_url','summary_error'],'checkIsArray','on' => 'parse'],
            [['host'],'required','on' => 'parse'],
            [['summary_error'],'summaryError','on' => 'parse'],
            
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'url' => 'Url',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['parse'] = ['url'];
        
        return $scenarios;
    }

    public function summaryError($attribute, $params)
    {
        if (empty($this->$attribute) === false) {
            foreach ($this->$attribute as $key => $value) {
                $this->addError($key,$value);
            }
        }
        
    }

    public function checkIsArray($attribute, $params)
    {
        if (!is_array($this->$attribute)) {
            $this->addError($attribute, $attribute.' is not array.');
        }
    }

    public function checkBlack($attribute, $params)
    {
        if (is_array($this->$attribute)) {
            $this->addError($attribute, $attribute.' is in black list.');
        }
    }

    /**
     * [setBlackUrl set array of black urls ]
     * @param array $urls [description]
     */
    public function setBlackUrl($urls = [])
    {
        $this->black_url = $urls;
    }

    /**
     * [setHost set host]
     * @param [type] $url domain.com
     */
    public function setHost($url)
    {
        $this->host = $url;
    }

    public static function filterHost($url)
    {
        $parsUrl = parse_url($url);
        if ($parsUrl['host'] === $this->host) {
            return true;
        }
        return false;
    }

    public function statusName()
    {
        if ($this->status === 1) {
            return 'Wating';
        }
        if ($this->status === 2) {
            return 'Parsed';
        }
    }

    public function parsed()
    {
        $this->status = self::STATUS_PARSED;
        $this->save();
    }
}
