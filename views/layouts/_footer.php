<?php

declare(strict_types=1);

/** @var yii\web\View $this */

use yii\helpers\Html;

?>
<footer class="site-footer">
    <div class="site-footer__inner">
        <p>
            &copy; <?= date('Y') ?> dejavu memory benchmark &middot; precision over recall.
        </p>
        <nav>
            <?= Html::a('Benchmark', 'https://github.com/carono/dejavu-memory-benchmark', ['rel' => 'external']) ?>
            <?= Html::a('Spec', 'https://github.com/carono/dejavu-spec', ['rel' => 'external']) ?>
            <?= Html::a('API', ['/site/submit']) ?>
        </nav>
    </div>
</footer>
