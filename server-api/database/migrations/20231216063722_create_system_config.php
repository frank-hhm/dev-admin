<?php

use think\facade\Db;
use think\migration\Migrator;
use think\migration\db\Column;

class CreateSystemConfig extends Migrator
{
    public function up()
    {
        $table = $this->table('system_config');
        $table
            ->addColumn('sys_name', 'string', array('limit' => 255, 'default' => '', 'comment' => '配置名称'))
            ->addColumn(Column::longText('sys_value')->setComment('配置值'))
            ->setPrimaryKey('id')
            ->addIndex(['sys_name'], ['unique' => true])
            ->setComment('配置表')
            ->create();
        Db::name('system_config')->insertAll([
            [
                'sys_name' => 'system_name',
                'sys_value' => 'dev-admin',
            ],[
                'sys_name' => 'system_version',
                'sys_value' => '1.0',
            ],[
                'sys_name' => 'system_icon',
                'sys_value' => '',
            ],[
                'sys_name' => 'system_logo',
                'sys_value' => '',
            ],[
                'sys_name' => 'copyright',
                'sys_value' => '',
            ],[
                'sys_name' => 'web_domain',
                'sys_value' => 'http://127.0.0.1',
            ]
        ]);
    }

    public function down()
    {
        $this->table('system_config');
    }
}
