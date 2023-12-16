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
            ->addColumn(Column::integer('type')->setLimit(3)->setDefault(1)->setComment('1菜单，2应用，3操作'))
            ->addColumn(Column::integer('status')->setLimit(3)->setDefault(1)->setComment('状态'))
            ->addColumn('create_time', 'integer', array('limit' => 11, 'default' => 0, 'comment' => '创建时间'))
            ->addColumn('update_time', 'integer', array('limit' => 11, 'default' => 0, 'comment' => '更新时间'))
            ->addColumn('delete_time', 'integer', array('limit' => 11, 'default' => 0, 'comment' => '删除时间'))
            ->setPrimaryKey('id')
            ->addIndex(['pid', 'module', 'type', 'menu_path'])
            ->setComment('菜单表')
            ->create();
        Db::name('system_menus')->insertAll([
            [
                'pid' => 0,
                'module' => 1,
                'icon' => 'icon-shezhi',
                'menu_name' => '设置',
                'menu_path' => '/system',
                'menu_node' => '',
                'api_rule' => '',
                'params' => '',
                'sort' => 0,
                'status' => 1,
                'type' => 1,
                'id' => 1
            ], [
                'pid' => 1,
                'module' => 1,
                'icon' => '',
                'menu_name' => '基础设置',
                'menu_path' => '/system/config',
                'menu_node' => 'system-config',
                'api_rule' => 'sys/system.config/save',
                'params' => '',
                'sort' => 99,
                'status' => 1,
                'type' => 1,
                'id' => 2
            ], [
                'pid' => 1,
                'module' => 1,
                'icon' => '',
                'menu_name' => '菜单列表',
                'menu_path' => '/system/menus/list',
                'menu_node' => 'system-menus-list',
                'api_rule' => 'sys/system.menus/list',
                'params' => '',
                'sort' => 0,
                'status' => 1,
                'type' => 1,
                'id' => 3
            ], [
                'pid' => 1,
                'module' => 1,
                'icon' => '',
                'menu_name' => '账号管理',
                'menu_path' => '/system/admin/list',
                'menu_node' => 'system-admin-list',
                'api_rule' => 'sys/system.admin/list',
                'params' => '',
                'sort' => 9,
                'status' => 1,
                'type' => 1,
                'id' => 4
            ], [
                'pid' => 1,
                'module' => 1,
                'icon' => '',
                'menu_name' => '权限管理',
                'menu_path' => '/system/role/list',
                'menu_node' => 'system-role-list',
                'api_rule' => 'sys/system.role/list',
                'params' => '',
                'sort' => 8,
                'status' => 1,
                'type' => 1,
                'id' => 5
            ], [
                'pid' => 3,
                'module' => 1,
                'icon' => '',
                'menu_name' => '添加菜单',
                'menu_path' => '',
                'menu_node' => 'system-menus-create',
                'api_rule' => 'sys/system.menus/create',
                'params' => '',
                'sort' => 0,
                'status' => 1,
                'type' => 3,
                'id' => 6
            ], [
                'pid' => 3,
                'module' => 1,
                'icon' => '',
                'menu_name' => '编辑菜单',
                'menu_path' => '',
                'menu_node' => 'system-menus-update',
                'api_rule' => 'sys/system.menus/update',
                'params' => '',
                'sort' => 0,
                'status' => 1,
                'type' => 3,
                'id' => 7
            ], [
                'pid' => 3,
                'module' => 1,
                'icon' => '',
                'menu_name' => '删除菜单',
                'menu_path' => '',
                'menu_node' => 'system-menus-delete',
                'api_rule' => 'sys/system.menus/delete',
                'params' => '',
                'sort' => 0,
                'status' => 1,
                'type' => 3,
                'id' => 8
            ], [
                'pid' => 3,
                'module' => 1,
                'icon' => '',
                'menu_name' => '修改菜单状态',
                'menu_path' => '',
                'menu_node' => 'system-menus-status',
                'api_rule' => 'sys/system.menus/status',
                'params' => '',
                'sort' => 0,
                'status' => 1,
                'type' => 3,
                'id' => 9
            ],
            [
                'pid' => 4,
                'module' => 1,
                'icon' => '',
                'menu_name' => '添加账号',
                'menu_path' => '',
                'menu_node' => 'system-admin-create',
                'api_rule' => 'sys/system.admin/create',
                'params' => '',
                'sort' => 0,
                'status' => 1,
                'type' => 3,
                'id' => 10
            ], [
                'pid' => 4,
                'module' => 1,
                'icon' => '',
                'menu_name' => '编辑账号',
                'menu_path' => '',
                'menu_node' => 'system-admin-update',
                'api_rule' => 'sys/system.admin/update',
                'params' => '',
                'sort' => 0,
                'status' => 1,
                'type' => 3,
                'id' => 11
            ], [
                'pid' => 4,
                'module' => 1,
                'icon' => '',
                'menu_name' => '删除账号',
                'menu_path' => '',
                'menu_node' => 'system-admin-delete',
                'api_rule' => 'sys/system.admin/delete',
                'params' => '',
                'sort' => 0,
                'status' => 1,
                'type' => 3,
                'id' => 12
            ], [
                'pid' => 4,
                'module' => 1,
                'icon' => '',
                'menu_name' => '修改账号状态',
                'menu_path' => '',
                'menu_node' => 'system-admin-status',
                'api_rule' => 'sys/system.admin/status',
                'params' => '',
                'sort' => 0,
                'status' => 1,
                'type' => 3,
                'id' => 13
            ],
            [
                'pid' => 5,
                'module' => 1,
                'icon' => '',
                'menu_name' => '权限授权',
                'menu_path' => '/system/role/auth',
                'menu_node' => 'system-role-auth',
                'api_rule' => '',
                'params' => '',
                'sort' => 0,
                'status' => 1,
                'type' => 3,
                'id' => 14
            ],   [
                'pid' => 5,
                'module' => 1,
                'icon' => '',
                'menu_name' => '添加角色',
                'menu_path' => '/system/role/auth',
                'menu_node' => 'system-role-create',
                'api_rule' => 'sys/system.role/create',
                'params' => '',
                'sort' => 0,
                'status' => 1,
                'type' => 3,
                'id' => 15
            ], [
                'pid' => 5,
                'module' => 1,
                'icon' => '',
                'menu_name' => '编辑角色',
                'menu_path' => '/system/role/auth',
                'menu_node' => 'system-role-update',
                'api_rule' => 'sys/system.role/update',
                'params' => '',
                'sort' => 0,
                'status' => 1,
                'type' => 3,
                'id' => 16
            ], [
                'pid' => 5,
                'module' => 1,
                'icon' => '',
                'menu_name' => '删除角色',
                'menu_path' => '/system/role/auth',
                'menu_node' => 'system-role-delete',
                'api_rule' => 'sys/system.role/delete',
                'params' => '',
                'sort' => 0,
                'status' => 1,
                'type' => 3,
                'id' => 17
            ],[
                'pid' => 0,
                'module' => 1,
                'icon' => 'icon-mofa',
                'menu_name' => '素材',
                'menu_path' => '/media/list',
                'menu_node' => 'media-list',
                'api_rule' => 'sys/media/list',
                'params' => '',
                'sort' => 9,
                'status' => 1,
                'type' => 1,
                'id' => 18
            ], [
                'pid' => 18,
                'module' => 1,
                'icon' => '',
                'menu_name' => '添加素材分组',
                'menu_path' => '',
                'menu_node' => 'mate-files_group-create',
                'api_rule' => 'sys/mate.files_group/create',
                'params' => '',
                'sort' => 0,
                'status' => 1,
                'type' => 3,
                'id' => 19
            ], [
                'pid' => 18,
                'module' => 1,
                'icon' => '',
                'menu_name' => '编辑素材分组',
                'menu_path' => '',
                'menu_node' => 'mate-files_group-update',
                'api_rule' => 'sys/mate.files_group/update',
                'params' => '',
                'sort' => 0,
                'status' => 1,
                'type' => 3,
                'id' => 20
            ], [
                'pid' => 18,
                'module' => 1,
                'icon' => '',
                'menu_name' => '删除素材分组',
                'menu_path' => '',
                'menu_node' => 'mate-files_group-delete',
                'api_rule' => 'sys/mate.files_group/delete',
                'params' => '',
                'sort' => 0,
                'status' => 1,
                'type' => 3,
                'id' => 21
            ], [
                'pid' => 18,
                'module' => 1,
                'icon' => '',
                'menu_name' => '编辑素材名称',
                'menu_path' => '',
                'menu_node' => 'mate-files-update-name',
                'api_rule' => 'sys/mate.files/updateName',
                'params' => '',
                'sort' => 0,
                'status' => 1,
                'type' => 3,
                'id' => 22
            ], [
                'pid' => 18,
                'module' => 1,
                'icon' => '',
                'menu_name' => '上传素材',
                'menu_path' => '',
                'menu_node' => 'common-upload-image',
                'api_rule' => 'sys/common/upload',
                'params' => '',
                'sort' => 0,
                'status' => 1,
                'type' => 3,
                'id' => 23
            ], [
                'pid' => 18,
                'module' => 1,
                'icon' => '',
                'menu_name' => '删除素材',
                'menu_path' => '',
                'menu_node' => 'mate-files-delete',
                'api_rule' => 'sys/mate.files/delete',
                'params' => '',
                'sort' => 0,
                'status' => 1,
                'type' => 3,
                'id' => 24
            ], [
                'pid' => 18,
                'module' => 1,
                'icon' => '',
                'menu_name' => '移动素材分组',
                'menu_path' => '',
                'menu_node' => 'mate-files-update-group',
                'api_rule' => 'sys/mate.files/setGroup',
                'params' => '',
                'sort' => 0,
                'status' => 1,
                'type' => 3,
                'id' => 25
            ]
        ]);
    }

    public function down()
    {
        $table = $this->table('system_menus');

    }
}
