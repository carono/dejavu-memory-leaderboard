<?php

/** @var yii\web\View $this */

use yii\helpers\Html;

$this->title = 'About the benchmark';
$this->params['meta_description'] = 'What the dejavu memory benchmark measures: the push read-path '
    . '(cue → activation → gate → delivery) that injects a fact when the environment fires a cue.';

$features = [
    ['icon' => '🎯', 'title' => 'Cue-triggered', 'text' => 'The environment injects a fact when a cue fires. The agent never has to ask.'],
    ['icon' => '🕸️', 'title' => 'Spreading activation', 'text' => 'A hit on one fact can surface a linked fact one graph hop away.'],
    ['icon' => '🔇', 'title' => 'Precision over recall', 'text' => 'Nothing matches means nothing is injected. A false positive costs every turn.'],
    ['icon' => '🧩', 'title' => 'Self-contained cases', 'text' => 'Each case ships its own seed facts and turns. No live store required to grade.'],
];
?>
<section class="hero">
    <div class="container container--tight">
        <span class="hero__eyebrow"><span class="dot"></span> about</span>
        <h1>A behavioural benchmark for push-memory agents.</h1>
        <p class="hero__lead">
            Existing memory benchmarks (LongMemEval, LoCoMo) grade <strong>pull-RAG</strong> over
            long transcripts: does the model retrieve the right passage when it decides to search?
            dejavu is a different mechanism, and this benchmark grades that.
        </p>
    </div>
</section>

<section class="container container--tight">
    <div class="prose">
        <h2>What it measures</h2>
        <p>
            In the <strong>dejavu</strong> pattern the environment injects a fact when a cue fires,
            without the agent asking. The benchmark grades that <strong>push read-path</strong>:
            cue → activation → gate → delivery, across the ten situations where push either shines
            or quietly fails.
        </p>
        <p>
            Each case is a tiny, self-contained session. It ships its own seed facts (a statement
            plus projected cues and graph links) and a list of turns (a prompt plus what the memory
            channel is expected to do). The runner feeds every turn to an engine and checks the
            delivered set against the assertions. No live store required: a case carries everything
            it needs.
        </p>

        <div class="feature-grid">
            <?php foreach ($features as $f): ?>
                <div class="feature">
                    <div class="feature__icon" aria-hidden="true"><?= $f['icon'] ?></div>
                    <h3><?= Html::encode($f['title']) ?></h3>
                    <p><?= Html::encode($f['text']) ?></p>
                </div>
            <?php endforeach ?>
        </div>

        <h2>Why not a chat benchmark</h2>
        <p>
            These ten situations isolate the mechanics unique to push: the symbolic cue-index, the
            STM/LTM interplay, cue disambiguation, domain and project isolation, habituation, and
            the discipline of staying silent. A false positive here costs every turn, so the bar is
            <strong>precision over recall</strong>.
        </p>

        <h2>The dejavu pattern</h2>
        <p>
            dejavu is an associative push-memory layer for agents, built on two stores: a cheap
            <strong>cue memory</strong> distilled from triggers that raises a familiarity signal,
            and a persistent <strong>fact store</strong> of curated facts. Cues match the context,
            the matching facts are gated and delivered. Memory becomes push precisely because the
            cue index does the noticing.
        </p>

        <div class="callout">
            <svg class="callout__icon" width="20" height="20" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2">
                <path d="M12 16v-4M12 8h.01" stroke-linecap="round"/>
                <circle cx="12" cy="12" r="9"/>
            </svg>
            <div>
                The full specification lives in
                <?= Html::a('github.com/carono/dejavu-spec', 'https://github.com/carono/dejavu-spec', ['rel' => 'external']) ?>.
                The benchmark and its runner are at
                <?= Html::a('carono/dejavu-memory-benchmark', 'https://github.com/carono/dejavu-memory-benchmark', ['rel' => 'external']) ?>.
            </div>
        </div>

        <div class="hero__actions">
            <?= Html::a('Browse the ten cases', ['/site/cases'], ['class' => 'btn btn--primary']) ?>
            <?= Html::a('Read the spec', 'https://github.com/carono/dejavu-spec', ['class' => 'btn', 'rel' => 'external']) ?>
        </div>
    </div>
</section>
