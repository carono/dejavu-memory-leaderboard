<?php

declare(strict_types=1);

namespace app\models;

/**
 * Static catalogue of the ten canonical dejavu-memory-benchmark situations.
 *
 * The source of truth is the JSON test set in the sibling
 * `dejavu-memory-benchmark` repository (cases/*.json); this mirror lets the
 * public site describe each situation without shipping the runner.
 *
 * @see https://github.com/carono/dejavu-memory-benchmark/tree/main/cases
 */
final class BenchmarkCases
{
    /**
     * @return array<int, array{
     *     no: string,
     *     situation: string,
     *     title: string,
     *     proves: string,
     *     detail: string,
     *     seeds: int,
     *     turns: int
     * }>
     */
    public static function all(): array
    {
        return [
            [
                'no' => '01',
                'situation' => 'L1_keyword_recall',
                'title' => 'Keyword recall',
                'proves' => 'An exact or substring cue hits the right fact; unrelated facts stay silent.',
                'detail' => 'Baseline: a keyword ("nginx") and a command cue both point at one fact. '
                    . 'Redis and an unrelated Yii2 rule must not surface.',
                'seeds' => 3,
                'turns' => 1,
            ],
            [
                'no' => '02',
                'situation' => 'L1c_semantic_recall',
                'title' => 'Semantic recall',
                'proves' => 'No literal overlap ("what is my name?" → "name is Ivan"); recovered via a meta cue.',
                'detail' => 'The stored statement shares no token with the question beyond the data-type '
                    . 'meta-cue "name". Projection must emit those cues, or only the expensive semantic '
                    . 'channel could recover it.',
                'seeds' => 1,
                'turns' => 2,
            ],
            [
                'no' => '03',
                'situation' => 'L1b_spreading',
                'title' => 'Spreading activation',
                'proves' => 'The prompt hits fact A; the needed fact B is one graph hop away.',
                'detail' => 'The prompt only names "nginx". "php-fpm" has no direct cue hit but is one '
                    . 'relates-link away (weight 0.7 × decay 0.5 ≥ 0.3 threshold), so it surfaces. An '
                    . 'unlinked fact stays quiet.',
                'seeds' => 3,
                'turns' => 1,
            ],
            [
                'no' => '04',
                'situation' => 'STM_vs_LTM',
                'title' => 'STM overrides LTM',
                'proves' => 'A fresh-session correction overrides and evicts the stale long-term value.',
                'detail' => 'Both facts match the same cues. The session fact declares it supersedes the '
                    . 'old one, so the stale port is evicted rather than surfaced alongside the new one.',
                'seeds' => 2,
                'turns' => 1,
            ],
            [
                'no' => '05',
                'situation' => 'domain_conflict',
                'title' => 'Domain conflict',
                'proves' => 'One ambiguous signal, two domains — the active domain wins.',
                'detail' => '"Run the app" matches both facts with equal salience. The active domain from '
                    . 'harness context disambiguates; the wrong-domain fact is a false positive.',
                'seeds' => 2,
                'turns' => 2,
            ],
            [
                'no' => '06',
                'situation' => 'staleness',
                'title' => 'Staleness',
                'proves' => 'A fact marked stale or archived never surfaces, even at high salience.',
                'detail' => 'Both facts match "deploy" and the stale one has higher salience, but its '
                    . 'status removes it at the gate. Only the confirmed replacement is delivered.',
                'seeds' => 2,
                'turns' => 1,
            ],
            [
                'no' => '07',
                'situation' => 'habituation',
                'title' => 'Habituation',
                'proves' => 'A fact already surfaced this session is suppressed on the next turn.',
                'detail' => 'The same "docker" cue fires twice. After turn 1 delivers the hint it is '
                    . 'habituated; turn 2 stays silent unless activation is materially stronger.',
                'seeds' => 1,
                'turns' => 2,
            ],
            [
                'no' => '08',
                'situation' => 'multi_project',
                'title' => 'Multi-project isolation',
                'proves' => 'A rule from project A does not leak into project B.',
                'detail' => 'Both rules share the cue "push" but carry conflicting policy. A project-tagged '
                    . 'fact only fires when its project is the active context.',
                'seeds' => 2,
                'turns' => 2,
            ],
            [
                'no' => '09',
                'situation' => 'negative',
                'title' => 'Negative / fail-open',
                'proves' => 'Nothing matches, so nothing is injected. Precision over recall.',
                'detail' => 'Nothing in the store relates to the prompt. The gate injects nothing rather '
                    . 'than force the nearest weak match; a false positive costs every turn.',
                'seeds' => 2,
                'turns' => 1,
            ],
            [
                'no' => '10',
                'situation' => 'personal_meta',
                'title' => 'Personal facts + meta-cues',
                'proves' => 'Name, age, profession recalled by data-type cues, the value absent from the prompt.',
                'detail' => 'The prompts never contain "Ivan", "35" or "architect". Recall works because '
                    . 'consolidation attached data-type meta-cues, each resolving to exactly its own fact.',
                'seeds' => 3,
                'turns' => 3,
            ],
        ];
    }
}
