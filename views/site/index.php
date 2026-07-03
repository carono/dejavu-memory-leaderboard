<?php

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

use yii\grid\GridView;
use yii\helpers\Html;

$this->title = 'Dejavu Memory Benchmark — Leaderboard';

// Rank offset accounts for pagination.
$pagination = $dataProvider->getPagination();
$rank = $pagination ? $pagination->getOffset() : 0;
?>
<div class="site-index">

    <div class="p-4 p-md-5 mb-4 rounded bg-body-tertiary">
        <h1 class="display-6"><?= Html::encode('🏆 Dejavu Memory Benchmark') ?></h1>
        <p class="lead mb-0">
            Public leaderboard of <code>dejavu-memory-benchmark</code> runs, ranked by total score.
        </p>
        <p class="text-muted mt-2 mb-0">
            Submit your results via <code>POST /api/submit</code> — see the
            <?= Html::a('README', 'https://github.com/carono/dejavu-memory-leaderboard#readme') ?>
            for the JSON format.
        </p>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['class' => 'table table-striped table-hover align-middle'],
        'layout' => "{items}\n<div class=\"d-flex justify-content-between align-items-center\">{summary}{pager}</div>",
        'emptyText' => 'No submissions yet. Be the first to submit your benchmark results!',
        'columns' => [
            [
                'header' => '#',
                'headerOptions' => ['style' => 'width:60px'],
                'contentOptions' => ['class' => 'fw-bold'],
                'value' => function () use (&$rank) {
                    return ++$rank;
                },
            ],
            [
                'label' => 'Submitter / Model',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::encode($model->submitter_name)
                        . '<br><span class="text-muted small">'
                        . Html::encode($model->model_name)
                        . '</span>';
                },
            ],
            [
                'label' => 'Version',
                'value' => function ($model) {
                    return $model->model_version ?: '—';
                },
            ],
            [
                'label' => 'Total score',
                'headerOptions' => ['class' => 'text-end'],
                'contentOptions' => ['class' => 'text-end fw-semibold'],
                'value' => function ($model) {
                    return Yii::$app->formatter->asDecimal($model->score_total, 4);
                },
            ],
            [
                'label' => 'Date',
                'headerOptions' => ['class' => 'text-end'],
                'contentOptions' => ['class' => 'text-end text-muted'],
                'value' => function ($model) {
                    return Yii::$app->formatter->asDatetime($model->submitted_at, 'medium');
                },
            ],
        ],
    ]) ?>

</div>
