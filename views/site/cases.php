<?php

/** @var yii\web\View $this */
/** @var array<int, array{no:string,situation:string,title:string,proves:string,detail:string,seeds:int,turns:int}> $cases */

use yii\helpers\Html;

$this->title = 'The ten test cases';
$this->params['meta_description'] = 'The ten canonical dejavu situations, from keyword recall and '
    . 'spreading activation to habituation, domain isolation and fail-open silence.';
?>
<section class="hero">
    <div class="container container--tight">
        <span class="hero__eyebrow"><span class="dot"></span> test set</span>
        <h1>Ten situations where push memory is tested.</h1>
        <p class="hero__lead">
            Each situation is one JSON file in the test set. A case ships its own seed facts and
            turns, so it can be graded without a live store. Here is what every one proves.
        </p>
    </div>
</section>

<section class="container container--tight">
    <div class="case-list">
        <?php foreach ($cases as $c): ?>
            <article class="case">
                <div class="case__no" aria-hidden="true"><?= Html::encode($c['no']) ?></div>
                <div>
                    <div class="case__head">
                        <h2 class="case__title"><?= Html::encode($c['title']) ?></h2>
                        <span class="case__sit"><?= Html::encode($c['situation']) ?></span>
                    </div>
                    <p class="case__proves"><?= Html::encode($c['proves']) ?></p>
                    <p class="case__note"><?= Html::encode($c['detail']) ?></p>
                    <div class="case__meta">
                        <span><?= $c['seeds'] ?> seed <?= $c['seeds'] === 1 ? 'fact' : 'facts' ?></span>
                        <span><?= $c['turns'] ?> <?= $c['turns'] === 1 ? 'turn' : 'turns' ?></span>
                    </div>
                </div>
            </article>
        <?php endforeach ?>
    </div>

    <div class="callout">
        <svg class="callout__icon" width="20" height="20" viewBox="0 0 24 24" fill="none"
             stroke="currentColor" stroke-width="2">
            <path d="M12 16v-4M12 8h.01" stroke-linecap="round"/>
            <circle cx="12" cy="12" r="9"/>
        </svg>
        <div>
            The runnable JSON cases and a dependency-free PHP runner are in
            <?= Html::a('carono/dejavu-memory-benchmark', 'https://github.com/carono/dejavu-memory-benchmark', ['rel' => 'external']) ?>.
            Run <code>php runner/run.php</code> on a clean checkout to grade the reference engine.
        </div>
    </div>

    <div class="hero__actions">
        <?= Html::a('See the standings', ['/site/index'], ['class' => 'btn btn--primary']) ?>
        <?= Html::a('How to submit', ['/site/submit'], ['class' => 'btn']) ?>
    </div>
</section>
