<?php

use common\models\Ad;
use common\models\Order;
use yii\bootstrap\Nav;
use yii\widgets\Breadcrumbs;
use yii\helpers\Url;

$ads = count(Ad::findAll(['status' => 2]));
$orders = count(Order::findAll(['status' => Order::STATUS_PROCESS]));

$menuItems = [
    [
        'label' => (@($ads != 0) ? '<i class="ic ic-bullhorn"></i><i class="count">' . $ads . '</i>' : '<i class="ic ic-bullhorn"></i>') . ' <span class="sm-hidden">ОБЪЯВЛЕНИЯ</span>',
        'encode' => false,
        'url' => '/ad',
        'active' => Yii::$app->controller->module->id == "ad",
        'visible' => Yii::$app->user->can('backend.ads'),
    ],
    [
        'label' => (@($orders != 0) ? '<i class="ic ic-briefcase"></i><i class="count">' . $orders . '</i>' : '<i class="ic ic-briefcase"></i>') . ' <span class="sm-hidden">ЗАКАЗЫ</span>',
        'encode' => false,
        'url' => '/orders',
        'active' => Yii::$app->controller->module->id == "orders",
        'visible' => Yii::$app->user->can('backend.orders.order') || Yii::$app->user->can('backend.orders.publication-orders'),
    ],
    [
        'label' => '<i class="ic ic-more-items"></i><span class="sm-hidden">КОНТЕНТ</span>',
        'encode' => false,
        'url' => '/content',
        'active' => Yii::$app->controller->module->id == "content" && Yii::$app->controller->id !== "order",
        'visible' => Yii::$app->user->can('backend.content.page')
            || Yii::$app->user->can('backend.content.questions')
            || Yii::$app->user->can('backend.content.legal'),
    ],
    [
        'label' => '<i class="ic ic-newspaper"></i><span class="sm-hidden">НОВОСТИ</span>',
        'encode' => false,
        'url' => '/news',
        'active' => Yii::$app->controller->module->id == "news"
            && Yii::$app->controller->id !== "order",
    ],
    [
        'label' => '<i class="ic ic-factory"></i><span class="sm-hidden">КОМПАНИИ</span>',
        'encode' => false,
        'url' => '/company',
        'active' => Yii::$app->controller->module->id == "company",
        'visible' => Yii::$app->user->can('backend.company')
    ],
    [
        'label' => '<i class="ic ic-parents"></i><span class="sm-hidden">ПОЛЬЗОВАТЕЛИ</span>',
        'encode' => false,
        'url' => '/user',
        'active' => Yii::$app->controller->module->id == "user",
        'visible' => Yii::$app->user->can('backend.user'),
    ],
];

?>

<?php $this->beginContent('@app/views/layouts/main.php'); ?>
<nav class="navbar navbar-inverse visible-xs">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Основное меню</a>
        </div>

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <?php
                echo Nav::widget([
                    'options' => ['class' => 'nav-pills nav-stacked'],
                    'items' => $menuItems,
                ]);
                ?>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>

<div class="leftColumnCover hidden-xs">
    <div class="leftColumnContent sm-hidden">
        <div id="aside" class="columnSidebar">
            <?php
            echo Nav::widget([
                'options' => ['class' => 'nav-pills nav-stacked'],
                'items' => $menuItems,
            ]);
            ?>
        </div>
    </div>
</div>

<div id="article" class="rightColumnContent content-width">
    <?= Breadcrumbs::widget([
        'homeLink' => [
            'label' => Yii::$app->name,
            'url' => Yii::$app->homeUrl,
        ],
        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]) ?>

    <?php
    $menu = [];

    if (method_exists(Yii::$app->controller->module, 'menu'))
        foreach (Yii::$app->controller->module->menu() as $label => $url)
            $menu[] = [
                'label' => $label,
                'url' => is_array($url) ? reset($url) : $url,
                'active' => is_array($url) ? in_array(Url::to(), $url) : Url::to() == $url,
            ];

    if (!empty($menu) && !(Yii::$app->controller->id == 'main' && Yii::$app->controller->action->id == 'index')) {
        echo Nav::widget([
            'options' => ['class' => 'nav-tabs'],
            'items' => $menu,
        ]);
    }
    ?>
    <?= $content ?>
</div>
<?php $this->endContent(); ?>

<script>
    (function () {
        let a = document.querySelector('#aside'), b = null, P = 0;
        window.addEventListener('scroll', Ascroll, false);
        document.body.addEventListener('scroll', Ascroll, false);

        function Ascroll() {
            if (b === null) {
                let Sa = getComputedStyle(a, ''), s = '';
                /*for (let i = 0; i < Sa.length; i++) {
                    if (Sa[i].indexOf('overflow') == 0 || Sa[i].indexOf('padding') == 0 || Sa[i].indexOf('border') == 0 || Sa[i].indexOf('outline') == 0 || Sa[i].indexOf('box-shadow') == 0 || Sa[i].indexOf('background') == 0) {
                        s += Sa[i] + ': ' +Sa.getPropertyValue(Sa[i]) + '; '
                    }
                }*/
                b = document.createElement('div');
                b.style.cssText = s + ' box-sizing: border-box; width: ' + a.offsetWidth + 'px;';
                a.insertBefore(b, a.firstChild);
                let l = a.childNodes.length;
                for (let i = 1; i < l; i++) {
                    b.appendChild(a.childNodes[1]);
                }
                a.style.height = b.getBoundingClientRect().height + 'px';
                a.style.padding = '0';
                a.style.border = '0';
            }
            let Ra = a.getBoundingClientRect(),
                R = Math.round(Ra.top + b.getBoundingClientRect().height - document.querySelector('#article').getBoundingClientRect().bottom);
            if ((Ra.top - P) <= 0) {
                if ((Ra.top - P) <= R) {
                    b.className = 'stop';
                    b.style.top = -R + 'px';
                } else {
                    b.className = 'sticky';
                    b.style.top = P + 'px';
                }
            } else {
                b.className = '';
                b.style.top = '';
            }
            window.addEventListener('resize', function () {
                a.children[0].style.width = getComputedStyle(a, '').width
            }, false);
        }
    })()
</script>
