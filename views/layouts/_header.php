<?php

declare(strict_types=1);

/** @var yii\web\View $this */

use yii\helpers\Html;
use yii\helpers\Url;

$route = ltrim(Yii::$app->controller->route ?? '', '/');
$isActive = static fn (string $r): string => $route === $r ? ' is-active' : '';

$links = [
    ['label' => 'Leaderboard', 'route' => 'site/index', 'url' => ['/site/index']],
    ['label' => 'About', 'route' => 'site/about', 'url' => ['/site/about']],
    ['label' => 'Test cases', 'route' => 'site/cases', 'url' => ['/site/cases']],
    ['label' => 'Submit', 'route' => 'site/submit', 'url' => ['/site/submit']],
];
?>
<header class="site-nav">
    <div class="site-nav__inner">
        <a class="brand" href="<?= Url::home() ?>">
            <span class="brand__mark" aria-hidden="true">déjà</span>
            <span>dejavu</span>
            <span class="brand__tag">leaderboard</span>
        </a>

        <button type="button" class="nav-toggle" data-nav-toggle
                aria-controls="nav-links" aria-expanded="false" aria-label="Toggle navigation">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M4 6h16M4 12h16M4 18h16" stroke-linecap="round"/>
            </svg>
        </button>

        <ul class="nav-links" id="nav-links">
            <?php foreach ($links as $l): ?>
                <li>
                    <a class="<?= trim($isActive($l['route'])) ?>" href="<?= Url::to($l['url']) ?>">
                        <?= Html::encode($l['label']) ?>
                    </a>
                </li>
            <?php endforeach ?>
            <li class="nav-cta">
                <?= Html::a('Submit a run', ['/site/submit'], ['class' => 'btn btn--primary btn--sm']) ?>
            </li>
        </ul>
    </div>
</header>
