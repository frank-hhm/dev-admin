<?php

use think\facade\Db;
use think\migration\Migrator;
use think\migration\db\Column;

class AddMenus extends Migrator
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
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
                'type' => 2,
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
                'type' => 2,
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
                'type' => 2,
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
                'type' => 2,
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
                'type' => 2,
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
                'type' => 2,
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
                'type' => 2,
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
                'type' => 2,
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
                'type' => 2,
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
                'type' => 2,
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
                'type' => 2,
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
                'type' => 2,
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
                'menu_node' => 'media-group-create',
                'api_rule' => 'sys/media_group/create',
                'params' => '',
                'sort' => 0,
                'status' => 1,
                'type' => 2,
                'id' => 19
            ], [
                'pid' => 18,
                'module' => 1,
                'icon' => '',
                'menu_name' => '编辑素材分组',
                'menu_path' => '',
                'menu_node' => 'media-group-update',
                'api_rule' => 'sys/media_group/update',
                'params' => '',
                'sort' => 0,
                'status' => 1,
                'type' => 2,
                'id' => 20
            ], [
                'pid' => 18,
                'module' => 1,
                'icon' => '',
                'menu_name' => '删除素材分组',
                'menu_path' => '',
                'menu_node' => 'media-group-delete',
                'api_rule' => 'sys/media_group/delete',
                'params' => '',
                'sort' => 0,
                'status' => 1,
                'type' => 2,
                'id' => 21
            ], [
                'pid' => 18,
                'module' => 1,
                'icon' => '',
                'menu_name' => '编辑素材名称',
                'menu_path' => '',
                'menu_node' => 'media-update-name',
                'api_rule' => 'sys/media/updateName',
                'params' => '',
                'sort' => 0,
                'status' => 1,
                'type' => 2,
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
                'type' => 2,
                'id' => 23
            ], [
                'pid' => 18,
                'module' => 1,
                'icon' => '',
                'menu_name' => '删除素材',
                'menu_path' => '',
                'menu_node' => 'media-delete',
                'api_rule' => 'sys/media/delete',
                'params' => '',
                'sort' => 0,
                'status' => 1,
                'type' => 2,
                'id' => 24
            ], [
                'pid' => 18,
                'module' => 1,
                'icon' => '',
                'menu_name' => '移动素材分组',
                'menu_path' => '',
                'menu_node' => 'media-update-group',
                'api_rule' => 'sys/media/setGroup',
                'params' => '',
                'sort' => 0,
                'status' => 1,
                'type' => 2,
                'id' => 25
            ], [
                'pid' => 0,
                'module' => 1,
                'icon' => 'icon-wode-2',
                'menu_name' => '个人中心',
                'menu_path' => '/detail',
                'menu_node' => '',
                'api_rule' => '',
                'params' => '',
                'sort' => 0,
                'status' => 1,
                'type' => 1,
                'id' => 26
            ]
        ]);
    }
}
