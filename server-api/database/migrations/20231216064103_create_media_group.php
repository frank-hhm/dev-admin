<?php

use think\migration\Migrator;
use think\migration\db\Column;

class CreateMediaGroup extends Migrator
{

    public function up()
    {
        $table = $this->table('media_group');
        $table
            ->addColumn('source', 'integer', array('limit' => 11, 'default' => 0, 'comment' => '来源：1admin'))
            ->addColumn('source_id', 'integer', array('limit' => 11, 'default' => 0, 'comment' => '来源id'))
            ->addColumn('group_name', 'string', array('limit' => 150, 'default' => '', 'comment' => '分组名称'))
            ->addColumn('type', 'string', array('limit' => 60, 'default' => 'image', 'comment' => '分组类型'))
            ->addColumn('create_time', 'integer', array('limit' => 11, 'default' => 0, 'comment' => '创建时间'))
            ->addColumn('update_time', 'integer', array('limit' => 11, 'default' => 0, 'comment' => '更新时间'))
            ->setPrimaryKey('id')
            ->addIndex(['source','source_id','group_name','type'])
            ->setComment('素材分组')
            ->create();
    }

    public function down()
    {
        $table = $this->table('media_group');
    }
}
