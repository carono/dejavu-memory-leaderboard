<?php

/** @var yii\web\View $this */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Submit a run';
$this->params['meta_description'] = 'How to post a dejavu-memory-benchmark result to the leaderboard: '
    . 'a single JSON POST to /api/submit, with the field schema and a curl example.';

$endpoint = Url::to(['/api/submit'], true);
?>
<section class="hero">
    <div class="container container--tight">
        <span class="hero__eyebrow"><span class="dot"></span> api</span>
        <h1>Post your result in one request.</h1>
        <p class="hero__lead">
            Run the benchmark, then send the totals as a single JSON <code>POST</code>. No account,
            no key: the endpoint validates the payload and adds you to the board.
        </p>
    </div>
</section>

<section class="container container--tight">
    <div class="steps">
        <div class="step">
            <div class="step__n"></div>
            <div>
                <h3>Run the benchmark</h3>
                <p>
                    Clone
                    <?= Html::a('carono/dejavu-memory-benchmark', 'https://github.com/carono/dejavu-memory-benchmark', ['rel' => 'external']) ?>
                    and run <code>php runner/run.php</code> against your engine. It reports a total
                    score (0–1) and a per-case breakdown.
                </p>
            </div>
        </div>
        <div class="step">
            <div class="step__n"></div>
            <div>
                <h3>Build the JSON body</h3>
                <p>
                    Fill the fields below. Only <code>submitter_name</code>, <code>model_name</code>
                    and <code>score_total</code> are required; the rest add context.
                </p>
            </div>
        </div>
        <div class="step">
            <div class="step__n"></div>
            <div>
                <h3>POST it</h3>
                <p>
                    Send it to <code><?= Html::encode($endpoint) ?></code>. A <code>201</code> with
                    your new id means you are on the board.
                </p>
            </div>
        </div>
    </div>

    <h2 class="prose" style="font-size:1.4rem;margin:2.4rem 0 .3rem">Request fields</h2>
    <div class="fields">
        <div class="field">
            <div><code>submitter_name</code> <span class="field__req">required</span></div>
            <p>Who ran it: your name, team, or org. Up to 128 chars.</p>
        </div>
        <div class="field">
            <div><code>model_name</code> <span class="field__req">required</span></div>
            <p>The engine or model under test, e.g. <code>gpt-4o</code>. Up to 128 chars.</p>
        </div>
        <div class="field">
            <div><code>score_total</code> <span class="field__req">required</span></div>
            <p>Overall score from 0 to 1 (share of situations passed).</p>
        </div>
        <div class="field">
            <div><code>model_version</code> <span class="field__opt">optional</span></div>
            <p>A version or snapshot tag, e.g. <code>2026-05</code>. Up to 64 chars.</p>
        </div>
        <div class="field">
            <div><code>score_per_case</code> <span class="field__opt">optional</span></div>
            <p>A JSON object mapping each situation to its score, e.g. <code>{"negative": 1.0}</code>.</p>
        </div>
        <div class="field">
            <div><code>notes</code> <span class="field__opt">optional</span></div>
            <p>Free-form context: hardware, config, caveats.</p>
        </div>
    </div>

    <h2 class="prose" style="font-size:1.4rem;margin:2.4rem 0 .3rem">Example</h2>
    <div class="codeblock">
        <div class="codeblock__bar">
            <span class="codeblock__dot codeblock__dot--r"></span>
            <span class="codeblock__dot codeblock__dot--y"></span>
            <span class="codeblock__dot codeblock__dot--g"></span>
            <span class="codeblock__file">submit.sh</span>
            <button type="button" class="codeblock__copy" data-copy="#curl-example">Copy</button>
        </div>
<pre id="curl-example"><span class="tok-cmd">curl</span> <span class="tok-key">-X</span> POST <span class="tok-str"><?= Html::encode($endpoint) ?></span> <span class="tok-punc">\</span>
  <span class="tok-key">-H</span> <span class="tok-str">"Content-Type: application/json"</span> <span class="tok-punc">\</span>
  <span class="tok-key">-d</span> <span class="tok-str">'{
    "submitter_name": "your-name",
    "model_name": "your-engine",
    "model_version": "2026-07",
    "score_total": 0.87,
    "score_per_case": {
      "L1_keyword": 1.0,
      "L1c_semantic": 0.9,
      "negative": 1.0
    },
    "notes": "single run, default config"
  }'</span></pre>
    </div>

    <div class="callout">
        <svg class="callout__icon" width="20" height="20" viewBox="0 0 24 24" fill="none"
             stroke="currentColor" stroke-width="2">
            <path d="M20 6 9 17l-5-5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        <div>
            On success you get <code>201 Created</code> with <code>{ "success": true, "id": … }</code>.
            A malformed body returns <code>400</code>; a body that fails validation returns
            <code>422</code> with the offending fields.
        </div>
    </div>

    <div class="hero__actions">
        <?= Html::a('Back to the leaderboard', ['/site/index'], ['class' => 'btn btn--primary']) ?>
        <?= Html::a('What is scored', ['/site/cases'], ['class' => 'btn']) ?>
    </div>
</section>
