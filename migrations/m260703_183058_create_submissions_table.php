<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%submissions}}`.
 *
 * Stores benchmark results submitted by participants of the
 * dejavu-memory-benchmark.
 */
class m260703_183058_create_submissions_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%submissions}}', [
            'id' => $this->primaryKey(),
            'submitter_name' => $this->string(128)->notNull(),
            'model_name' => $this->string(128)->notNull(),
            'model_version' => $this->string(64)->null(),
            'score_total' => $this->decimal(10, 4)->notNull()->defaultValue(0),
            'score_per_case' => $this->json()->null(),
            'submitted_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'notes' => $this->text()->null(),
        ], $tableOptions);

        $this->createIndex('idx-submissions-score_total', '{{%submissions}}', 'score_total');
        $this->createIndex('idx-submissions-model_name', '{{%submissions}}', 'model_name');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%submissions}}');
    }
}
