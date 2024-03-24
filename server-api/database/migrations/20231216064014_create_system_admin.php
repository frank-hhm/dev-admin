<?php

use think\facade\Db;
use think\migration\Migrator;
use think\migration\db\Column;

class CreateSystemAdmin extends Migrator
{
    public function up()
    {
        $table = $this->table('system_admin');
        $table
            ->addColumn('account', 'string', array('limit' => 60, 'default' => '', 'comment' => '后台管理员账号'))
            ->addColumn('avatar', 'string', array('limit' => 255, 'default' => '', 'comment' => '头像'))
            ->addColumn('pwd', 'string', array('limit' => 100, 'default' => '', 'comment' => '后台管理员密码'))
            ->addColumn('real_name', 'string', array('limit' => 60, 'default' => '', 'comment' => '后台管理员姓名'))
            ->addColumn('roles', 'string', array('limit' => 128, 'default' => '', 'comment' => '后台管理员权限'))
            ->addColumn('last_ip', 'string', array('limit' => 16, 'default' => '', 'comment' => '后台管理员最后一次登录ip'))
            ->addColumn('last_time', 'integer', array('limit' => 11, 'default' =>0, 'comment' => '后台管理员最后一次登录时间'))
            ->addColumn('login_count', 'integer', array('limit' => 11, 'default' =>0, 'comment' => '登录次数'))
            ->addColumn(Column::tinyInteger('level')->setLimit(3)->setDefault(1)->setComment('后台管理员级别 0为开发者，1为超级管理员，2为管理员'))
            ->addColumn(Column::tinyInteger('status')->setLimit(3)->setDefault(1)->setComment('状态，1启用，0禁用'))
            ->addColumn('create_time', 'integer', array('limit' => 11, 'default' => 0, 'comment' => '创建时间'))
            ->addColumn('update_time', 'integer', array('limit' => 11, 'default' => 0, 'comment' => '更新时间'))
            ->addColumn('delete_time', 'integer', array('limit' => 11, 'default' => 0, 'comment' => '删除时间'))
            ->setPrimaryKey('id')
            ->addIndex(['account'], ['unique' => true])
            ->addIndex(['status'])
            ->setComment('后台管理员表')
            ->create();
        Db::name('system_admin')->insertAll([
            [
                'account' => 'frank',
                'pwd' => '$2y$10$/xUS/4TZC99TfixyR5ZeI.9xJHvQkSxtrylcXdqilDCSorJHZVVpe',
                'real_name' => '小明',
                'level' => -1,
                'status' => 1,
            ]
        ]);
        Db::name('system_admin')->insertAll([
            [
                'account' => 'admin',
                'pwd' => '$2y$10$/xUS/4TZC99TfixyR5ZeI.9xJHvQkSxtrylcXdqilDCSorJHZVVpe',
                'real_name' => '管理员',
                'level' => 0,
                'status' => 1,
            ]
        ]);
    }

    public function down()
    {
        $table = $this->table('system_admin');
    }
}
