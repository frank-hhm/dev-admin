<?php

use think\migration\Migrator;
use think\migration\db\Column;

class CreateMedia extends Migrator
{

    public function up()
    {
        $table = $this->table('media');
        $table
            ->addColumn('group_id', 'integer', array('limit' => 11, 'default' => 0, 'comment' => '分组'))
            ->addColumn('source', 'integer', array('limit' => 11, 'default' => 0, 'comment' => '来源：1admin'))
            ->addColumn('source_id', 'integer', array('limit' => 11, 'default' => 0, 'comment' => '来源id'))
            ->addColumn('oss_type', 'string', array('limit' => 30, 'default' => '', 'comment' => '存储方式'))
            ->addColumn('file_domain', 'string', array('limit' => 255, 'default' => '', 'comment' => '存储域名'))
            ->addColumn('file_name', 'string', array('limit' => 255, 'default' => '', 'comment' => '文件名'))
            ->addColumn('former_name', 'string', array('limit' => 255, 'default' => '', 'comment' => '原文件名'))
            ->addColumn('file_path', 'string', array('limit' => 255, 'default' => '', 'comment' => '文件路径'))
            ->addColumn(Column::float('file_size')->setDefault(0)->setComment('文件大小(字节)'))
            ->addColumn('file_type', 'string', array('limit' => 60, 'default' => '', 'comment' => '文件类型'))
            ->addColumn('extension', 'string', array('limit' => 120, 'default' => '', 'comment' => '文件扩展名'))
            ->addColumn('covor', 'string', array('limit' => 255, 'default' => '', 'comment' => '封面'))
            ->addColumn('create_time', 'integer', array('limit' => 11, 'default' => 0, 'comment' => '创建时间'))
            ->addColumn('update_time', 'integer', array('limit' => 11, 'default' => 0, 'comment' => '更新时间'))
            ->setPrimaryKey('id')
            ->addIndex(['group_id','source','file_type','source_id'])
            ->setComment('素材表')
            ->create();
    }

    public function down()
    {
        $table = $this->table('media');
    }
}
