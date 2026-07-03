<?php

/** @var yii\web\View $this */
/** @var app\models\Submission[] $submissions */
/** @var float $maxScore */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Dejavu Memory Benchmark — Leaderboard';
$this->params['meta_description'] = 'Public leaderboard for the dejavu push-memory benchmark: '
    . 'how well agents recall the right fact at the right moment, ranked by total score.';

$medals = [1 => '🥇', 2 => '🥈', 3 => '🥉'];
$count = count($submissions);
?>
<section class="hero">
    <div class="container">
        <span class="hero__eyebrow rise"><span class="dot"></span> push-memory benchmark</span>
        <h1 class="rise rise-2">The leaderboard for memory that reads itself.</h1>
        <p class="hero__lead rise rise-2">
            Most memory benchmarks grade retrieval when the agent decides to search.
            <strong>dejavu</strong> grades the other path: the environment pushes the right fact
            when a cue fires. Below, engines ranked by total score across ten situations where
            push either shines or quietly fails.
        </p>
        <div class="hero__actions rise rise-3">
            <?= Html::a('Submit a run', ['/site/submit'], ['class' => 'btn btn--primary']) ?>
            <?= Html::a('Read the ten cases', ['/site/cases'], ['class' => 'btn']) ?>
        </div>
    </div>
</section>

<section class="container">
    <div class="section-head">
        <div>
            <h2>Standings</h2>
            <p>Ranked by total score, then by earliest submission. Top three are highlighted.</p>
        </div>
    </div>

    <?php if ($count === 0): ?>
        <div class="board-wrap">
            <div class="board-empty">
                <h3>No runs yet</h3>
                <p>
                    The board is empty. Be the first to post a result, see
                    <?= Html::a('how to submit', ['/site/submit']) ?>.
                </p>
            </div>
        </div>
    <?php else: ?>
        <div class="toolbar">
            <label class="search">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3" stroke-linecap="round"/>
                </svg>
                <input type="search" placeholder="Filter by model or submitter…"
                       data-board-search aria-label="Filter the leaderboard">
            </label>
            <span class="toolbar__count" data-board-count>
                <?= $count ?><?= $count === 1 ? ' entry' : ' entries' ?>
            </span>
        </div>

        <div class="board-wrap">
            <div class="board-scroll">
                <table class="board" data-board>
                    <thead>
                        <tr>
                            <th style="width:64px">Rank</th>
                            <th>Model</th>
                            <th style="width:140px">Version</th>
                            <th class="num" style="width:190px">Total score</th>
                            <th class="num" style="width:150px">Submitted</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($submissions as $i => $s): ?>
                            <?php
                            $rank = $i + 1;
                            $score = (float) $s->score_total;
                            $pct = $maxScore > 0 ? max(3, round($score / $maxScore * 100)) : 0;
                            $rowClass = $rank <= 3 ? 'top' . $rank : '';
                            $needle = mb_strtolower(
                                $s->submitter_name . ' ' . $s->model_name . ' ' . (string) $s->model_version,
                            );
                            ?>
                            <tr class="<?= $rowClass ?>" data-search="<?= Html::encode($needle) ?>">
                                <td data-label="Rank">
                                    <?php if (isset($medals[$rank])): ?>
                                        <span class="rank rank--medal" title="Rank <?= $rank ?>"><?= $medals[$rank] ?></span>
                                    <?php else: ?>
                                        <span class="rank"><?= $rank ?></span>
                                    <?php endif ?>
                                </td>
                                <td data-label="Model">
                                    <div class="entrant">
                                        <span class="entrant__model"><?= Html::encode($s->model_name) ?></span>
                                        <span class="entrant__by">by <?= Html::encode($s->submitter_name) ?></span>
                                    </div>
                                </td>
                                <td data-label="Version">
                                    <?php if ($s->model_version): ?>
                                        <span class="ver"><?= Html::encode($s->model_version) ?></span>
                                    <?php else: ?>
                                        <span class="ver ver--none">—</span>
                                    <?php endif ?>
                                </td>
                                <td class="num" data-label="Score">
                                    <span class="score">
                                        <span class="score__bar">
                                            <span class="score__fill" style="width: <?= $pct ?>%"></span>
                                        </span>
                                        <span class="score__val"><?= Yii::$app->formatter->asDecimal($score, 3) ?></span>
                                    </span>
                                </td>
                                <td class="num" data-label="Submitted">
                                    <span class="date"><?= Yii::$app->formatter->asDate($s->submitted_at, 'medium') ?></span>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
                <div class="no-results" data-no-results>No models match that filter.</div>
            </div>
        </div>

        <p class="text-muted mt-3" style="font-size:.85rem;color:var(--faint)">
            Scores range 0–1 (share of situations passed). Curious how they are computed?
            See the <?= Html::a('ten test cases', ['/site/cases']) ?>.
        </p>
    <?php endif ?>
</section>
