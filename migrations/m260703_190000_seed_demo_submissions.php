<?php

use yii\db\Migration;

/**
 * Seeds the leaderboard with a handful of illustrative submissions so the
 * public page has content to render. Every row is clearly demo data and the
 * migration is fully reversible (safeDown removes exactly what it inserted).
 */
class m260703_190000_seed_demo_submissions extends Migration
{
    /**
     * Marker stored in `notes` so safeDown can find and remove only these rows.
     */
    private const DEMO_MARKER = '[demo-seed]';

    /**
     * Per-case keys mirror the ten canonical situations of the benchmark.
     */
    private function rows(): array
    {
        // submitter, model, version, per-case score map (0..1)
        return [
            ['dejavu core', 'reference-engine', 'v0.4', [
                'L1_keyword' => 1.0, 'L1c_semantic' => 1.0, 'L1b_spreading' => 1.0,
                'STM_vs_LTM' => 1.0, 'domain_conflict' => 1.0, 'staleness' => 1.0,
                'habituation' => 1.0, 'multi_project' => 1.0, 'negative' => 1.0,
                'personal_meta' => 0.90,
            ]],
            ['Anthropic', 'claude-opus-4-8', '2026-05', [
                'L1_keyword' => 1.0, 'L1c_semantic' => 1.0, 'L1b_spreading' => 0.90,
                'STM_vs_LTM' => 1.0, 'domain_conflict' => 1.0, 'staleness' => 1.0,
                'habituation' => 0.80, 'multi_project' => 1.0, 'negative' => 1.0,
                'personal_meta' => 1.0,
            ]],
            ['OpenAI', 'gpt-4o', '2024-11', [
                'L1_keyword' => 1.0, 'L1c_semantic' => 0.90, 'L1b_spreading' => 0.70,
                'STM_vs_LTM' => 0.80, 'domain_conflict' => 0.90, 'staleness' => 1.0,
                'habituation' => 0.60, 'multi_project' => 0.80, 'negative' => 1.0,
                'personal_meta' => 0.85,
            ]],
            ['Google', 'gemini-2.0-pro', 'exp', [
                'L1_keyword' => 1.0, 'L1c_semantic' => 0.85, 'L1b_spreading' => 0.65,
                'STM_vs_LTM' => 0.75, 'domain_conflict' => 0.80, 'staleness' => 0.90,
                'habituation' => 0.60, 'multi_project' => 0.75, 'negative' => 0.90,
                'personal_meta' => 0.80,
            ]],
            ['mem0', 'mem0-oss', '0.1.x', [
                'L1_keyword' => 0.90, 'L1c_semantic' => 0.60, 'L1b_spreading' => 0.40,
                'STM_vs_LTM' => 0.60, 'domain_conflict' => 0.50, 'staleness' => 0.70,
                'habituation' => 0.40, 'multi_project' => 0.55, 'negative' => 0.80,
                'personal_meta' => 0.65,
            ]],
            ['community', 'llama-3.3-70b', 'instruct', [
                'L1_keyword' => 0.90, 'L1c_semantic' => 0.55, 'L1b_spreading' => 0.35,
                'STM_vs_LTM' => 0.55, 'domain_conflict' => 0.45, 'staleness' => 0.70,
                'habituation' => 0.35, 'multi_project' => 0.50, 'negative' => 0.75,
                'personal_meta' => 0.60,
            ]],
            ['community', 'mistral-large', '2411', [
                'L1_keyword' => 0.85, 'L1c_semantic' => 0.50, 'L1b_spreading' => 0.30,
                'STM_vs_LTM' => 0.50, 'domain_conflict' => 0.40, 'staleness' => 0.60,
                'habituation' => 0.30, 'multi_project' => 0.45, 'negative' => 0.70,
                'personal_meta' => 0.55,
            ]],
            ['baseline', 'naive-rag', 'top-k=4', [
                'L1_keyword' => 0.70, 'L1c_semantic' => 0.30, 'L1b_spreading' => 0.10,
                'STM_vs_LTM' => 0.20, 'domain_conflict' => 0.20, 'staleness' => 0.30,
                'habituation' => 0.10, 'multi_project' => 0.25, 'negative' => 0.40,
                'personal_meta' => 0.35,
            ]],
            ['baseline', 'no-memory', 'control', [
                'L1_keyword' => 0.0, 'L1c_semantic' => 0.0, 'L1b_spreading' => 0.0,
                'STM_vs_LTM' => 0.0, 'domain_conflict' => 0.0, 'staleness' => 0.50,
                'habituation' => 0.50, 'multi_project' => 0.0, 'negative' => 1.0,
                'personal_meta' => 0.0,
            ]],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $batch = [];
        // Space the timestamps out so ordering by date is meaningful.
        $base = strtotime('2026-06-01 09:00:00');
        foreach ($this->rows() as $i => [$submitter, $model, $version, $perCase]) {
            $total = array_sum($perCase) / count($perCase);
            $batch[] = [
                'submitter_name' => $submitter,
                'model_name' => $model,
                'model_version' => $version,
                'score_total' => round($total, 4),
                'score_per_case' => json_encode($perCase),
                'submitted_at' => date('Y-m-d H:i:s', $base + $i * 86400 * 3),
                'notes' => self::DEMO_MARKER,
            ];
        }

        $this->batchInsert('{{%submissions}}', [
            'submitter_name',
            'model_name',
            'model_version',
            'score_total',
            'score_per_case',
            'submitted_at',
            'notes',
        ], $batch);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('{{%submissions}}', ['notes' => self::DEMO_MARKER]);
    }
}
