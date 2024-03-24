<?php

use think\facade\Db;
use think\migration\Migrator;
use think\migration\db\Column;

class CreateSystemMenus extends Migrator
{
    public function up()
    {
        $table = $this->table('system_menus');
        $table
            ->addColumn('pid', 'integer', array('limit' => 11, 'default' => 0, 'comment' => '父级id'))
            ->addColumn(Column::integer('module')->setLimit(3)->setDefault(1)->setComment('模块'))
            ->addColumn('icon', 'string', array('limit' => 30, 'default' => '', 'comment' => '图标'))
            ->addColumn('menu_name', 'string', array('limit' => 60, 'default' => '', 'comment' => '菜单名称'))
            ->addColumn('menu_path', 'string', array('limit' => 128, 'default' => '', 'comment' => '路由地址'))
            ->addColumn('menu_node', 'string', array('limit' => 128, 'default' => '', 'comment' => '权限标识'))
            ->addColumn('api_rule', 'string', array('limit' => 150, 'default' => '', 'comment' => '接口'))
            ->addColumn('params', 'string', array('limit' => 255, 'default' => '', 'comment' => '参数'))
            ->addColumn('sort', 'integer', array('limit' => 11, 'default' => 0, 'comment' => '排序'))
            ->addColumn(Column::integer('type')->setLimit(3)->setDefault(1)->setComment('1菜单，2操作'))
            ->addColumn(Column::integer('status')->setLimit(3)->setDefault(1)->setComment('状态'))
            ->addColumn('create_time', 'integer', array('limit' => 11, 'default' => 0, 'comment' => '创建时间'))
            ->addColumn('update_time', 'integer', array('limit' => 11, 'default' => 0, 'comment' => '更新时间'))
            ->addColumn('delete_time', 'integer', array('limit' => 11, 'default' => 0, 'comment' => '删除时间'))
            ->setPrimaryKey('id')
            ->addIndex(['pid', 'module', 'type', 'menu_path'])
            ->setComment('菜单表')
            ->create();

    }

    public function down()
    {
        $table = $this->table('system_menus');

    }
}
