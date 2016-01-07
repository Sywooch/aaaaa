<?php

use yii\db\Migration;
use yii\db\mysql\Schema;

class m160107_080402_init extends Migration
{
    public function up()
    {
//        $this->createTable('post_type', [
//            'id' => Schema::TYPE_PK,
//            'name' => Schema::TYPE_STRING . ' not null',
//        ]);
//        $this->insert('post_type', ['name' => 'Пост']);
//        $this->insert('post_type', ['name' => 'Фото']);
//        $this->insert('post_type', ['name' => 'Видео']);
//        $this->insert('post_type', ['name' => 'Ссылка']);

        $this->createTable('moderation', [
            'id' => Schema::TYPE_PK,
            'text' => Schema::TYPE_TEXT . ' not null',
            'hash' => 'varchar(32) not null',
            'ip' => Schema::TYPE_STRING . ' not null',
            'user_agent' => Schema::TYPE_STRING . ' not null',
            'created' => Schema::TYPE_DATETIME . ' not null',
        ]);
        $this->createIndex('hash_index', 'moderation', 'hash', true);

        $this->createTable('post', [
            'id' => Schema::TYPE_PK,
            'text' => Schema::TYPE_TEXT . ' not null',
            'hash' => 'varchar(32) not null',
            'visible' => Schema::TYPE_BOOLEAN . ' not null default 0',
            'ip' => Schema::TYPE_STRING . ' not null',
            'user_agent' => Schema::TYPE_STRING . ' not null',
            'created' => Schema::TYPE_DATETIME . ' not null',
        ]);
        $this->createIndex('hash_index', 'post', 'hash');
        $this->createIndex('visible_index', 'post', 'visible');

        $this->createTable('vote', [
            'id' => Schema::TYPE_PK,
            'post_id' => Schema::TYPE_INTEGER . ' not null',
            'rating' => Schema::TYPE_INTEGER . ' not null default 0',
            'ip' => Schema::TYPE_STRING . ' not null',
            'user_agent' => Schema::TYPE_STRING . ' not null',
            'created' => Schema::TYPE_DATETIME . ' not null',
        ]);
        $this->createIndex('unique_vote', 'vote', ['post_id', 'ip', 'user_agent'], true);
        $this->createIndex('post_index', 'vote', 'post_id');
        $this->createIndex('ip_index', 'vote', 'ip');
        $this->createIndex('user_agent_index', 'vote', 'user_agent');
        $this->addForeignKey(
            'fk__vote__post_id__post',
            'vote', 'post_id',
            'post', 'id',
            'cascade', 'cascade'
        );


        $this->createTable('tag', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . ' not null',
        ]);

        $this->createTable('tag4post', [
            'post_id' => Schema::TYPE_INTEGER . ' not null',
            'tag_id' => Schema::TYPE_INTEGER . ' not null',
        ]);
        $this->addForeignKey(
            'fk__tag4post__post_id__post',
            'tag4post', 'post_id',
            'post', 'id',
            'cascade', 'cascade'
        );
        $this->addForeignKey(
            'fk__tag4post__tag_id__tag',
            'tag4post', 'tag_id',
            'tag', 'id',
            'cascade', 'cascade'
        );
    }

    public function down()
    {
        $this->dropIndex('hash_index', 'moderation');
        $this->dropTable('moderation');

        $this->dropForeignKey('fk__tag4post__post_id__post', 'tag4post');
        $this->dropForeignKey('fk__tag4post__tag_id__tag', 'tag4post');
        $this->dropTable('tag4post');
        $this->dropTable('tag');

        $this->dropForeignKey('fk__vote__post_id__post', 'vote');
        $this->dropIndex('unique_vote', 'vote');
        $this->dropIndex('post_index', 'vote');
        $this->dropIndex('ip_index', 'vote');
        $this->dropIndex('user_agent_index', 'vote');
        $this->dropTable('vote');

        $this->dropTable('post');
    }
}
