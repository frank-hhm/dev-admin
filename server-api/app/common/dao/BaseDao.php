<?php
/**
 * @Author: Frank
 * @Email: frank_hhm@163.com
 * @Date: 2023/12/16 12:35
 */

declare(strict_types=1);

namespace app\common\dao;

use app\common\exception\CommonException;
use think\facade\Config;
use think\facade\Db;
use think\helper\Str;

/**
 * Class BaseDao
 * @package app\dao
 */
abstract class BaseDao
{
    public \think\model $model;
    /**
     * 获取当前模型
     * @return string
     */
    abstract protected function setModel(): string;

    /**
     * 获取模型
     */
    public function __construct()
    {
        $this->model = app()->make($this->setModel());
        return $this->model;
    }

    /**
     * 获取主键
     */
    protected function getPk(): array|string
    {
        return $this->model->getPk();
    }

    /**
     * 获取表名称
     */
    protected function getTable()
    {
        return $this->model->getTable();
    }

    /**
     * 获取搜索器和搜索条件key
     */
    private function getSearchData(array $withSearch): array
    {
        $with = [];
        $whereKey = [];
        try {
            $respones = new \ReflectionClass($this->setModel());
            foreach ($withSearch as $fieldName) {
                $method = 'search' . Str::studly($fieldName) . 'Attr';
                if ($respones->hasMethod($method)) {
                    $with[] = $fieldName;
                } else {
                    $whereKey[] = $fieldName;
                }
            }
            return [$with, $whereKey];
        } catch (\Exception $e) {
            throw new CommonException($e->getMessage());
        }
    }

    /**
     * 根据搜索器获取搜索内容
     */
    protected function withSearchSelect(array $withSearch, ?array $data = [])
    {
        [$with] = $this->getSearchData($withSearch);
        return $this->model->withSearch($with, $data);
    }

    /**
     * 搜索
     */
    public function search(array $where = [])
    {
        if ($where) {
            return $this->withSearchSelect(array_keys($where), $where);
        } else {
            return $this->model;
        }
    }

    /**
     * 获取某个字段数组
     */
    public function getColumn(array $where, string $field, string $key = ''): array
    {
        return $this->model->where($where)->column($field, $key);
    }


    /**
     * 获取一条数据
     */
    public function get($id, ?array $field = [], ?array $with = [],$onlyTrashed = false)
    {
        if (is_array($id)) {
            $where = $id;
        } else {
            $where = [$this->getPk() => $id];
        }
        return $this->model->where($where)->when(count($with), function ($query) use ($with) {
            $query->with($with);
        })->when($onlyTrashed, function ($query) {
            $query->onlyTrashed();
        })->field($field ?? ['*'])->find();
    }

    /**
     * 根据条件获取一条数据
     */
    public function getOne(array $where, ?string $field = '*', array $with = [])
    {
        $field = explode(',', $field);
        return $this->get($where, $field, $with);
    }

    /**
     * 获取某些条件数据
     */
    public function getPageList(array $where,$page = 0, $limit = 0,$with = [],$order = ['id DESC'], $isArr = true, $field = '*'): \think\Paginator|array
    {
        if($limit == 0){
            [$page,$limit] = $this->getPageValue();
        }
        $res = $this->model->where($where)->with($with)->field($field)->order($order)->page($page)->paginate($limit);
        if($isArr) return $res->toArray();
        return $res;
    }

    /**
     * 获取某些条件数据
     */
    public function selectList(array $where, $page = 0, $limit = 0, $field = '*'): \think\Collection|array
    {
        return $this->model->where($where)->field($field)
            ->when($page && $limit, function ($query) use ($page, $limit) {
                $query->page($page, $limit);
            })->select();
    }


    /**
     * 数据详细
     */
    public function detail($filter,?array $with = []){
        if(is_array($filter)){
            return $this->model->where($filter)->when(count($with), function ($query) use ($with) {
                $query->with($with);
            })->find();
        }else{
            return $this->model->when(count($with), function ($query) use ($with) {
                $query->with($with);
            })->find((int) $filter);
        }
    }

    /**
     * 软删除
     */
    public function destroy($ids,$isDes = false)
    {
        if (is_string($ids)) {
            $ids = explode(',',$ids);
        }
        return $this->model::destroy($ids,$isDes);
    }


    /**
     * 读取数据条数
     */
    public function count(array $where = []): int
    {
        return $this->model->where($where)->count();
    }

    /**
     * 更新数据
     */
    public function update($id, array $data, ?string $key = null): \think\Model
    {
        if (is_array($id)) {
            $where = $id;
        } else {
            $where = [is_null($key) ? $this->getPk() : $key => $id];
        }
        return $this->model->update($data, $where);
    }

    /**
     * 插入数据
     */
    public function create(array $data): \think\Model
    {
        return $this->model->create($data);
    }

    /**
     * 插入数据
     */
    public function save(array $data): bool
    {
        return $this->model->save($data);
    }

    /**
     * 插入数据
     */
    public function saveAll(array $data): \think\Collection
    {
        return $this->model->saveAll($data);
    }


    /**
     * 删除
     */
    public function delete($id, ?string $key = null)
    {
        if (is_array($id)) {
            $where = $id;
        } else {
            $where = [is_null($key) ? $this->getPk() : $key => $id];
        }
        return $this->model->where($where)->delete();
    }


    /**
     * 获取分页配置
     */
    public function getPageValue(bool $isPage = true, bool $isRelieve = false)
    {
        $page = $limit = 0;
        if ($isPage) {
            $page = app()->request->param(Config::get('database.page.pageKey', 'page') . '/d', 0);
            $limit = app()->request->param(Config::get('database.page.limitKey', 'limit') . '/d', 0);
        }
        $limitMax = Config::get('database.page.limitMax');
        $defaultLimit = Config::get('database.page.defaultLimit', 10);
        if ($limit > $limitMax && $isRelieve) {
            $limit = $limitMax;
        }
        if($limit == 0){
            $limit = $defaultLimit;
        }
        return [(int)$page, (int)$limit, (int)$defaultLimit];
    }


    /**
     * 密码hash加密
     */
    public function passwordHash(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    /**
     * 数据库事务操作
     */
    public function transaction(callable $closure, bool $isTran = true)
    {
        return $isTran ? Db::transaction($closure) : $closure();
    }
}