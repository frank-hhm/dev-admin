<?php

use think\facade\Db;
use think\migration\Migrator;
use think\migration\db\Column;

class CreateSystemRole extends Migrator
{

    public function up()
    {
        $table = $this->table('system_role');
        $table
            ->addColumn('role_name', 'string', array('limit' => 150, 'default' => '', 'comment' => '身份管理名称'))
            ->addColumn('remarks', 'string', array('limit' => 255, 'default' => '', 'comment' => '备注'))
            ->addColumn(Column::text('rules')->setComment('身份管理权限'))
            ->addColumn(Column::tinyInteger('status')->setLimit(3)->setDefault(1)->setComment('状态，1启用，0禁用'))
            ->addColumn('create_time', 'integer', array('limit' => 11, 'default' => 0, 'comment' => '创建时间'))
            ->addColumn('update_time', 'integer', array('limit' => 11, 'default' => 0, 'comment' => '更新时间'))
            ->setPrimaryKey('id')
            ->setComment('身份管理表')
            ->create();
        Db::name('system_role')->insertAll([
            [
                'id'=>1,
                'role_name' => '管理员',
                'remarks' => '管理员',
                'rules' => '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25',
                'create_time' => time(),
            ]
        ]);
    }

    public function down()
    {
        $table = $this->table('system_role');
    }
}
