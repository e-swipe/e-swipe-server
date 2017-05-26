<?php
use Migrations\AbstractMigration;

class SessionsUpdate extends AbstractMigration
{

    public function up()
    {

        $this->table('sessions', ['id' => false, 'primary_key' => ['uuid']])
            ->addColumn('uuid', 'binary', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('user_id', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addIndex(
                [
                    'user_id',
                ]
            )
            ->create();

        $this->table('sessions')
            ->addForeignKey(
                'user_id',
                'users',
                'id',
                [
                    'update' => 'RESTRICT',
                    'delete' => 'RESTRICT'
                ]
            )
            ->update();
    }

    public function down()
    {
        $this->table('sessions')
            ->dropForeignKey(
                'user_id'
            );

        $this->dropTable('sessions');
    }
}

