<?php
/**
 * @Author: Frank
 * @Email: frank_hhm@163.com
 * @Date: 2023/12/16 13:29
 */
declare(strict_types=1);

namespace app\common\services\common;

use app\common\services\BaseService;
use app\common\helper\StringHelper;
use ReflectionException;

/**
 * 应用节点服务管理
 * Class NodeService
 * @package frank\services
 */
class NodeService extends BaseService
{
    /**
     * 获取当前节点内容
     * @param string $type
     * @return string
     */
    public function getCurrent($type = ''): string
    {
        $prefix = $this->app->getNamespace();
        $middle = '\\' . StringHelper::nameTolower($this->app->request->controller());
        $suffix = ($type === 'controller') ? '' : ('\\' . $this->app->request->action());
        return strtr(substr($prefix, stripos($prefix, '\\') + 1) . $middle . $suffix, '\\', '/');
    }

    /**
     * 检查并完整节点内容
     * @param string $node
     * @return string
     */
    public function fullnode($node): string
    {
        if (empty($node)) return $this->getCurrent();
        if (count($attrs = explode('/', $node)) === 1) {
            return $this->getCurrent('controller') . "/{$node}";
        } else {
            $attrs[1] = StringHelper::nameTolower($attrs[1]);
            return join('/', $attrs);
        }
    }

    /**
     * 获取应用列表
     * @param array $data
     * @return array
     */
    public function getModules($data = []): array
    {
        if ($handle = opendir($this->app->getBasePath())) {
            while (false !== ($file = readdir($handle))) if ($file !== "." && $file !== "..") {
                if (is_dir($this->app->getBasePath() . $file)) $data[] = $file;
            }
            closedir($handle);
        }
        return $data;
    }

    /**
     * 获取所有控制器入口
     * @param boolean $force
     * @return array
     * @throws ReflectionException
     */
    public function getMethods(bool $force = false): array
    {
        static $data = [];
        if (empty($force) && count($data) > 0) {
            return $data;
            // $data = $this->app->cache->get('AuthNode', []);
        } else {
            $data = [];
        }
        $ignores = get_class_methods('\app\BaseController');

        foreach ($this->_scanDirectory($this->app->getBasePath()) as $file) {
            if (preg_match("|/(\w+)/(\w+)/controller/(.+)\.php$|i", $file, $matches)) {
                list( ,$namespace, $appname, $classname) = $matches;
                $class = new \ReflectionClass(strtr("{$namespace}/{$appname}/controller/{$classname}", '/', '\\'));
                $classnameString = StringHelper::nameTolower($classname);
                $prefix = strtr("{$appname}/{$classnameString}", '\\', '/');
                // $data[$prefix] = $this->_parseComment($class->getDocComment(), $classname);
                foreach ($class->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
                    if (in_array($metname = $method->getName(), $ignores)) continue;
                    $data["{$prefix}/{$metname}"] = $this->_parseComment($method->getDocComment(), $metname);
                }

            }
        }
        // $data = array_change_key_case($data, CASE_LOWER);
        $this->app->cache->set('AuthNode', $data);
        return $data;
    }

    /**
     * 获取授权节点列表
     * @return array
     * @throws ReflectionException
     */
    public function getList(): array
    {
        list($nodes, $pnodes, $methods) = [[], [], array_reverse($this->getMethods())];
        foreach ($methods as $rule => $item) {
            $nodes[] = ['rule' => $rule, 'title' => $item['title'], 'method' =>  $item['method']];
        }
        return array_reverse($nodes);
    }


    /**
     * 解析硬节点属性
     * @param mixed $comment 备注内容
     * @param string $default 默认标题
     * @return array
     */
    private function _parseComment(mixed $comment, string $default = ''): array
    {
        $text = strtr((string)$comment, "\n", ' ');
        $title = preg_replace('/^\/\*\s*\*\s*\*\s*(.*?)\s*\*.*?$/', '$1', $text);
        foreach (['@method'] as $find) if (stripos($title, $find) === 0) {
            $title = $default;
        }
        return [
            'title'   => $title ?: $default,
            'method'  => StringHelper::getStringBetween($text,'(',')') ?: ''
        ];
    }

    /**
     * 获取所有PHP文件列表
     * @param string $path 扫描目录
     * @param array $data 额外数据
     * @param string $ext 有文件后缀
     * @return array
     */
    private function _scanDirectory(string $path, array $data = [], string $ext = 'php'): array
    {
        foreach (glob("{$path}*") as $item) {
            if (is_dir($item)) {
                $data = array_merge($data, $this->_scanDirectory("{$item}" . DIRECTORY_SEPARATOR));
            } elseif (is_file($item) && pathinfo($item, PATHINFO_EXTENSION) === $ext) {
                $data[] = strtr($item, '\\', '/');
            }
        }
        return $data;
    }
}
