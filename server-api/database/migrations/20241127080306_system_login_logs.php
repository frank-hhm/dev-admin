<?php

use think\migration\Migrator;
use think\migration\db\Column;

class SystemLoginLogs extends Migrator
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
        $table = $this->table('system_login_logs');
        $table
            ->addColumn(Column::integer('admin_id')->setLimit(11)->setDefault(0)->setComment('关联后台账号'))
            ->addColumn(Column::string('source')->setDefault('admin')->setComment('来源'))
            ->addColumn(Column::text('param_data')->setComment('提交数据'))
            ->addColumn(Column::string('message')->setLimit(255)->setDefault('')->setComment('提示'))
            ->addColumn(Column::text('content')->setComment('内容'))
            ->addColumn(Column::string('ip')->setLimit(60)->setDefault('')->setComment('操作ip'))
            ->addColumn(Column::text('user_agent')->setComment('用户代理'))
            ->addColumn(Column::enum('status',[0,1])->setDefault('0')->setComment('状态'))
            ->addColumn(Column::integer('create_time')->setLimit(11)->setDefault(0)->setComment('新增时间'))
            ->addColumn(Column::integer('update_time')->setLimit(11)->setDefault(0)->setComment('更新时间'))
            ->addColumn(Column::integer('delete_time')->setLimit(11)->setDefault(0)->setComment('删除时间'))
            ->setPrimaryKey('id')
            ->addIndex(['id','admin_id','source','ip','status'])
            ->setComment('后台登录记录')
            ->create();

    }
}
