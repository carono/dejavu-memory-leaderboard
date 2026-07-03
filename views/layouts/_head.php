<?php

declare(strict_types=1);

/** @var yii\web\View $this */

use app\assets\AppAsset;
use yii\web\View;

AppAsset::register($this);

// Web fonts: Inter (UI/body) + JetBrains Mono (code/data). Registered before
// the app bundle so the design system's --font-* tokens resolve on first paint.
$this->registerLinkTag(['rel' => 'preconnect', 'href' => 'https://fonts.googleapis.com'], 'font-pre-1');
$this->registerLinkTag(
    ['rel' => 'preconnect', 'href' => 'https://fonts.gstatic.com', 'crossorigin' => ''],
    'font-pre-2',
);
$this->registerCssFile(
    'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700'
        . '&family=JetBrains+Mono:wght@400;500;600&display=swap',
    ['position' => View::POS_HEAD],
    'google-fonts',
);

$this->registerCsrfMetaTags();
$this->registerMetaTag(
    ['charset' => Yii::$app->charset],
    'charset',
);
$this->registerMetaTag(
    [
        'name' => 'viewport',
        'content' => 'width=device-width, initial-scale=1',
    ],
);
if (!empty($this->params['meta_description'])) {
    $this->registerMetaTag(
        [
            'name' => 'description',
            'content' => $this->params['meta_description'],
        ],
    );
}
if (!empty($this->params['meta_keywords'])) {
    $this->registerMetaTag(
        [
            'name' => 'keywords',
            'content' => $this->params['meta_keywords'],
        ],
    );
}
$this->registerLinkTag(
    [
        'rel' => 'icon',
        'type' => 'image/x-icon',
        'href' => Yii::getAlias('@web/favicon.ico'),
    ],
);
