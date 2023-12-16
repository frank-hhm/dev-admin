<?php
namespace app;
use Spatie\Macroable\Macroable;
// 应用请求对象类
class Request extends \think\Request
{

    use Macroable;

    ## 不过滤变量名
    protected array $except = [];

    /**
     * 当前访问插件
     * @var string
     */
    protected string $addon;

    /**
     * 当前访问应用（模块）
     * @var string
     */
    protected string $module;

    /**
     * 注解
     * @var array
     */
    protected array $annotate;

    /**
     * 当前访问插件
     * @param string $addon
     * @return string
     */
    public function addon(string $addon = ''): string
    {
        if(!empty($addon))
        {
            $GLOBALS["REQUEST_ADDON"] = $addon;
        }
        return $GLOBALS["REQUEST_ADDON"] ?? '';
    }

    /**
     * 当前访问模块
     * @param string $module
     */
    public function module(string $module = '')
    {
        if(!empty($module))
        {
            $GLOBALS["REQUEST_MODULE"] = $module;
        }
        return $GLOBALS["REQUEST_MODULE"] ?? '';
    }

    ## 获取请求的数据
    public function more(array $params, bool $suffix = false, bool $filter = true): array
    {
        $p = [];
        $i = 0;
        foreach ($params as $param) {
            if (!is_array($param)) {
                $p[$suffix ? $i++ : $param] = $this->filterWord(is_string($this->param($param)) ? trim($this->param($param)) : $this->param($param), $filter && !in_array($param, $this->except));
            } else {
                if (!isset($param[1])) $param[1] = null;
                if (!isset($param[2])) $param[2] = '';
                if (is_array($param[0])) {
                    $name = is_array($param[1]) ? $param[0][0] . '/a' : $param[0][0] . '/' . $param[0][1];
                    $keyName = $param[0][0];
                } else {
                    $name = is_array($param[1]) ? $param[0] . '/a' : $param[0];
                    $keyName = $param[0];
                }
                $p[$suffix ? $i++ : ($param[3] ?? $keyName)] = $this->filterWord(is_string($this->param($name, $param[1], $param[2])) ? trim($this->param($name, $param[1], $param[2])) : $this->param($name, $param[1], $param[2]), $filter && !in_array($keyName, $this->except));
            }
        }
        return $p;
    }

    ## 过滤接受的参数
    public function filterWord($str, bool $filter = true)
    {
        if (!$str || !$filter) return $str;
        # 把数据过滤
        $farr = [
            "/<(\\/?)(script|i?frame|html|body|title|link|meta|object|\\?|\\%)([^>]*?)>/isU",
            "/(<[^>]*)on[a-zA-Z]+\s*=([^>]*>)/isU",
            "/join|where|drop|like|modify|rename|insert|database|alter|truncate|\'|\/\*|\.\.\/|\.\/|union|into|load_file|outfile/is"
        ];
        if (is_array($str)) {
            foreach ($str as &$v) {
                if (is_array($v)) {
                    foreach ($v as &$vv) {
                        if (!is_array($vv)) $vv = preg_replace($farr, '', $vv);
                    }
                } else {
                    $v = preg_replace($farr, '', $v);
                }
            }
        } else {
            $str = preg_replace($farr, '', $str);
        }
        return $str;
    }

    ## 获取get参数
    public function getMore(array $params, bool $suffix = false, bool $filter = true): array
    {
        return $this->more($params, $suffix, $filter);
    }

    ## 获取post参数
    public function postMore(array $params, bool $suffix = false, bool $filter = true): array
    {
        return $this->more($params, $suffix, $filter);
    }

    public function annotate($data = []){
        if(!empty($data))
        {
            $GLOBALS["REQUEST_ANNOTATE"] = $data;
        }
        return $GLOBALS["REQUEST_ANNOTATE"] ?? [];
    }
}
