<?php

use yii\db\Migration;
use yii\db\mysql\Schema;

class m160210_085202_grabber extends Migration
{
    public function up()
    {
        $this->createTable('source', [
            'id' => Schema::TYPE_PK,
            'url' => Schema::TYPE_STRING . ' not null',
            'parser' => Schema::TYPE_STRING,
            'enable' => Schema::TYPE_BOOLEAN . " default 1",
        ]);

        $this->createTable('log', [
            'source_id' => Schema::TYPE_INTEGER . ' not null',
            'updated' => Schema::TYPE_DATETIME,
        ]);
        $this->createIndex('index_source_id', 'log', 'source_id', true);
        $this->addForeignKey('fk__log_source_id_source', 'log', 'source_id', 'source', 'id', 'cascade', 'cascade');
    }

    public function down()
    {
        $this->dropForeignKey('fk__log_source_id_source', 'log');
        $this->dropIndex('index_source_id', 'log');
        $this->dropTable('log');
        $this->dropTable('source');
    }
}
