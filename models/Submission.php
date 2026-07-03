<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * ActiveRecord for a single benchmark submission.
 *
 * @property int $id
 * @property string $submitter_name
 * @property string $model_name
 * @property string|null $model_version
 * @property float $score_total
 * @property array|null $score_per_case
 * @property string $submitted_at
 * @property string|null $notes
 */
class Submission extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%submissions}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['submitter_name', 'model_name', 'score_total'], 'required'],
            [['submitter_name', 'model_name'], 'string', 'max' => 128],
            [['model_version'], 'string', 'max' => 64],
            [['score_total'], 'number'],
            [['score_per_case'], 'validateScorePerCase'],
            [['notes'], 'string'],
            [['submitter_name', 'model_name', 'model_version', 'notes'], 'trim'],
        ];
    }

    /**
     * score_per_case must be an associative structure (map of case => score) or null.
     */
    public function validateScorePerCase($attribute): void
    {
        $value = $this->$attribute;
        if ($value !== null && !is_array($value)) {
            $this->addError($attribute, 'score_per_case must be a JSON object/array.');
        }
    }
}
