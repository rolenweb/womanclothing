<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\data\Sort;
use yii\data\Pagination;
use yii\helpers\Url;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Category;
use app\models\Product;
use app\models\Seo;

use yii\web\NotFoundHttpException;

class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex($cat1 = null,$cat2 = null)
    {
        $params = [
            'cat1' => (empty($cat1) === false) ? $cat1 : null,
            'cat2' => (empty($cat2) === false) ? $cat2 : null,
            'cat3' => (empty($cat3) === false) ? $cat3 : null,
            'product' => (empty($product) === false) ? $product : null,
        ];

        $category = (new Category())->createStructure();



        if (empty($params['cat1']) === false && empty($params['cat2']) === false) {
            $category->getCurrent($params['cat2']);
            if (empty($category->current)) {
                throw new NotFoundHttpException('Sorry, this page is not found');
            }
            $category->seo();
            $query = Product::find()
                ->joinWith(
                        [
                            'category cat' => function($q) {
                                $q->joinWith(
                                        [
                                            'parent p1'
                                        ]
                                    );
                            }
                        ]
                  )
                ->where(
                    [
                        'and',
                            [
                                'cat.slug' => $params['cat2']
                            ],
                            [
                                'p1.slug' => $params['cat1']
                            ]
                    ]
                );

        }else if (empty($params['cat1']) === false) {
            $category->getCurrent($params['cat1']);

            if (empty($category->current)) {
                throw new NotFoundHttpException('Sorry, this page is not found');
            }

            $category->seo();

            $query = Product::find()
                ->joinWith(
                        [
                            'category cat' => function($q) {
                                $q->joinWith(
                                        [
                                            'parent p1'
                                        ]
                                    );
                            }
                        ]
                )
                ->where(
                    [
                        'or',
                            [
                                'p1.slug' => $params['cat1']
                            ],
                            [
                                'cat.slug' => $params['cat1']
                            ]
                    ]
                );
        }else{
            $query = Product::find();
            (new Seo())->metaTagIndex();
        }

        
        $sort = new Sort([
            'attributes' => [
                'createdate' =>[
                    'label' => 'Date'
                ],
                
            ],
            /*'defaultOrder' => [
                'product.id' => SORT_ASC,
            ]*/
        ]);

        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count(),'pageSize'=> 40]);
        $products = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->orderBy($sort->orders)
            ->all();



        return $this->render('index',
            [
                'category' => $category,
                'params' => $params,
                'products' => $products,
                'pages' => $pages,
            ]
        );
    }

    public function actionProduct($slug)
    {
        $product = Product::find()->with(['category.parent'])->where(['slug' => $slug])->limit(1)->one();
        if (empty($product)) {
            throw new NotFoundHttpException('Sorry, this page is not found');
        }
        $product->seo();

        if (empty($product)) {
            throw new NotFoundHttpException();
        }
        return $this->render('product',
            [
                'product' => $product,
                'similars' => $product->similar(),
                'searchData' => $product->searchData
            ]
        );
    }

    public function actionSitemapPage($slug)
    {
        $products = Product::find()
                ->joinWith(
                        [
                            'category cat' => function($q) {
                                $q->joinWith(
                                        [
                                            'parent p1'
                                        ]
                                    );
                            }
                        ]
                )
                ->where(
                    [
                        'or',
                            [
                                'p1.slug' => $slug
                            ],
                            [
                                'cat.slug' => $slug
                            ]
                            
                    ]
                )->select(['product.slug','product.updated_at','product.category_id'])->asArray()->all();

        if (empty($products)) {
            return;
        }

        $urlSet = new \Thepixeldeveloper\Sitemap\Urlset(); 

        foreach ($products as $product) {
            $url = (new \Thepixeldeveloper\Sitemap\Url(Url::to(['site/product','slug' => $product['slug']],true)))
                        ->setLastMod(date("Y-m-d H:i",$product['updated_at']))
                        ->setChangeFreq('monthly')
                        ->setPriority('0.8');

                    $urlSet->addUrl($url);
        }

        echo (new \Thepixeldeveloper\Sitemap\Output())->getOutput($urlSet);
    }

    /**
     * Displays sitemap-index page.
     *
     * @return string
     */
    public function actionSitemap()
    {
        $lastMod = Product::find()->orderBy(['updated_at' => SORT_DESC])->limit(1)->one();
        $pages = 
        [
            [
                'url' => Url::to(['site/sitemap-static-page'],true),
                'lastMod' =>  date("Y-m-d H:i",$lastMod->updated_at)

            ],
            [
                'url' => Url::to(['site/sitemap-page','slug' => 'activewear'],true),
                'lastMod' =>  date("Y-m-d H:i",$lastMod->updated_at)

            ],
            [
                'url' => Url::to(['site/sitemap-page','slug' => 'bottoms'],true),
                'lastMod' =>  date("Y-m-d H:i",$lastMod->updated_at)

            ],
            [
                'url' => Url::to(['site/sitemap-page','slug' => 'dance-costumes'],true),
                'lastMod' =>  date("Y-m-d H:i",$lastMod->updated_at)

            ],
            [
                'url' => Url::to(['site/sitemap-page','slug' => 'denim'],true),
                'lastMod' =>  date("Y-m-d H:i",$lastMod->updated_at)

            ],
            [
                'url' => Url::to(['site/sitemap-page','slug' => 'dresses'],true),
                'lastMod' =>  date("Y-m-d H:i",$lastMod->updated_at)

            ],
            [
                'url' => Url::to(['site/sitemap-page','slug' => 'intimates-lingerie'],true),
                'lastMod' =>  date("Y-m-d H:i",$lastMod->updated_at)

            ],
            [
                'url' => Url::to(['site/sitemap-page','slug' => 'jumpsuits-rompers'],true),
                'lastMod' =>  date("Y-m-d H:i",$lastMod->updated_at)

            ],
            [
                'url' => Url::to(['site/sitemap-page','slug' => 'outerwear'],true),
                'lastMod' =>  date("Y-m-d H:i",$lastMod->updated_at)

            ],
            [
                'url' => Url::to(['site/sitemap-page','slug' => 'plus-size'],true),
                'lastMod' =>  date("Y-m-d H:i",$lastMod->updated_at)

            ],
            [
                'url' => Url::to(['site/sitemap-page','slug' => 'sweaters-cardigans'],true),
                'lastMod' =>  date("Y-m-d H:i",$lastMod->updated_at)

            ],
            [
                'url' => Url::to(['site/sitemap-page','slug' => 'swimwear'],true),
                'lastMod' =>  date("Y-m-d H:i",$lastMod->updated_at)

            ],
            [
                'url' => Url::to(['site/sitemap-page','slug' => 'tops'],true),
                'lastMod' =>  date("Y-m-d H:i",$lastMod->updated_at)

            ],
            
        ];
        

        $sitemapIndex = new \Thepixeldeveloper\Sitemap\SitemapIndex(); 

        foreach ($pages as $page) {
            $url = (new \Thepixeldeveloper\Sitemap\Sitemap($page['url']))
                ->setLastMod($page['lastMod']);

            $sitemapIndex->addSitemap($url);       

        }

        
        echo (new \Thepixeldeveloper\Sitemap\Output())->getOutput($sitemapIndex);
    }

    public function actionSitemapStaticPage()
    {
        $pages = 
        [
            Url::to(['site/index'],true),
            Url::to(['site/contact'],true),
            
        ];
        

        $urlSet = new \Thepixeldeveloper\Sitemap\Urlset(); 

        foreach ($pages as $page) {
            $url = (new \Thepixeldeveloper\Sitemap\Url($page))
                        ->setChangeFreq('monthly')
                        ->setPriority('0.5');

                    $urlSet->addUrl($url);        

        }

        
        echo (new \Thepixeldeveloper\Sitemap\Output())->getOutput($urlSet);
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    
}
