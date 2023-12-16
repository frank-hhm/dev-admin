<?php
/**
 * @Author: Frank
 * @Email: frank_hhm@163.com
 * @Date: 2023/12/16 13:27
 */
declare(strict_types=1);


namespace app\common\services\annotation;
use app\common\exception\CommonException;
use app\common\services\BaseService;
use ReflectionClass;
use ReflectionMethod;

/**
 * Class AnnotationService
 * 自定义异常类的基类
 */
class AnnotationService extends BaseService
{
    /**
     * @var ReflectionClass $reflection 类反射对象
     */
    protected ReflectionClass $reflection;
    /**
     * @var ReflectionMethod $method 方法反射对象
     */
    protected ReflectionMethod $method;

    // 注解在类上
    const ELEMENT_TYPE = 'class';

    // 注解在方法上
    const ELEMENT_METHOD = 'method';

    /**
     * @var array $annotationTypeList 允许注解位置
     */
    protected array $target = [];

    /**
     * @var string $pattern 通用匹配规则模板
     */
    protected string $pattern = '#@({annotateName})\s*(\(\s*(.*)\))?#i';

    /**
     * @var string $annotateName 注解名
     */
    protected string $annotateName;

    /**
     * @var string[] $ignoreMethodList 忽略的公共方法
     */
    protected array $ignoreMethodList = ['__construct','validate','result'];

    /**
     * @var array $classAnnotate 注解解析数据
     */
    protected array $classAnnotate;

    /**
     * @var mixed $matchAnnotateType 匹配到的类注解名
     */
    protected mixed $matchTypeAnnotateName;

    /**
     * @var mixed matchMethodAnnotateName 匹配到的方法注解名
     */
    protected mixed $matchMethodAnnotateName;

    protected mixed $ignorePropertyList = [];


    protected array $attributes = ['method'];

    /**
     * Node constructor.
     * @param string $class
     * @param string $action
     * @param array|null $ignoreMethodList
     * @param array|null $ignorePropertyList
     */
    public function __construct(string $class,string $action = '', array $ignoreMethodList = null, ?array $ignorePropertyList = null)
    {
        try {
            $this->reflection = new ReflectionClass($class);

            $this->ignoreMethodList = !empty($ignoreMethodList) ? array_merge($this->ignoreMethodList, $ignoreMethodList) : $this->ignoreMethodList;
            $this->ignorePropertyList = !empty($ignorePropertyList) ? array_merge($this->ignorePropertyList, $ignorePropertyList) : $this->ignorePropertyList;
            $this->matchMethodAnnotateName = $action;
        }catch (\Exception $e){
            throw new CommonException($e->getMessage());
        }
    }

    /**
     * match all method annotation
     * @param callable|null $callable 回调方法
     * @param int $classAccess
     * @return array
     */
    public function matchAllMethodAnnotation(?callable $callable = null, int $classAccess = ReflectionMethod::IS_PUBLIC): array
    {
        if ($this->reflection->isAbstract())
            return [];
        // 获取类注解
        $this->matchClassAnnotate();
        // 获取方法注解
        $methods = $this->reflection->getMethods($classAccess);
        $methodAnnotationList = [];
        foreach ($methods as $method):
            if (!empty($this->ignoreMethodList) && in_array($method->getName(), $this->ignoreMethodList))
                continue;
            if ($callable instanceof \Closure) {
                if ($callable($method))
                    break;
            } else {
                $annotationList = $this->matchMethodAnnotate($method);
                if (!empty($annotationList))
                    $methodAnnotationList[] = $annotationList;
            }
        endforeach;
        return $methodAnnotationList;
    }

    /**
     * 匹配类注释
     */
    public function matchClassAnnotate($attributes = ''): ?array
    {
        $pattern =  !empty($attributes) ? str_replace('{annotateName}', $attributes, $this->pattern) : $this->pattern;
        if (in_array(self::ELEMENT_TYPE, $this->target)) {
            $docComment = $this->reflection->getDocComment();
            if(!$docComment) return null;
            preg_match($pattern, $docComment, $matches);
            $this->matchTypeAnnotateName = $matches[1] ?? '';
            return !isset($matches[1]) ? null : $this->parseAnnotate($matches[3] ?? '',$attributes);
        }
        return null;
    }

    /**
     * 匹配方法注释
     */
    protected function matchMethodAnnotate(ReflectionMethod $method = null, $attributes = ''): ?array
    {
        $pattern =  !empty($attributes) ? str_replace('{annotateName}', $attributes, $this->pattern) : $this->pattern;
        if (in_array($attributes, $this->attributes)) {
            $method = $method ?: $this->method;
            $methodComment = $method->getDocComment();
            if($methodComment){
                preg_match($pattern, $methodComment, $matches);
                return !isset($matches[1]) ? null : $this->parseAnnotate($matches[3] ?? '',$attributes);
            }
        }
        return null;
    }

    /**
     * 从annotate解析注解信息
     */
    protected function parseAnnotate(string $annotate,$attributes): array
    {
        if (empty($annotate))
            return [];
        // 默认格式 (...a)
        $annotate = str_replace(['"', '\''], '', $annotate);
        $annotateList = explode(',', $annotate);
        $annotateSet[$attributes] = $annotateList;
        return $annotateSet;
    }
}
