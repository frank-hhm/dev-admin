<?php
/**
 * @Author: Frank
 * @Email: frank_hhm@163.com
 * @Date: 2023/12/16 13:28
 */
declare(strict_types=1);

namespace app\common\services\annotation;
use app\common\services\annotation\AnnotationService;
class MethodService extends AnnotationService
{

    protected array $target = [self::ELEMENT_METHOD,self::ELEMENT_TYPE];

    protected array $attributes = ['method','noAuth','force','middleware','noMiddleware'];

    /**
     * 匹配所有方法注释
     * @param callable|null $callable 回调方法
     * @param int $classAccess 类的访问权限类型，在\ReflectionMethod获取
     * @return array
     */
    public function matchAllMethodAnnotation(?callable $callable = null, int $classAccess = \ReflectionMethod::IS_PUBLIC): array
    {
        if ($this->reflection->isAbstract())
            return [];

        $classAnnotatio = [];
        foreach ($this->attributes as $key => $item) {
            $itemAnnotation = $this->matchClassAnnotate($item);
            if (empty($itemAnnotation))
                continue;
            $classAnnotatio = $itemAnnotation;
        }

        // 获取方法注解
        $methods = $this->reflection->getMethods($classAccess);

        $methodAnnotationList = [];
        $class = $this->reflection->getName();
        $classes = explode('\\', $class);
        $classes = array_slice($classes, -2);
        $shortName = $classes[0] . '_' . str_replace('Controller', '', $classes[1]);
        foreach ($methods as $method){
            $annotation = [];
            if ($this->matchMethodAnnotateName == $method->getName()){
                if (!empty($this->ignoreMethodList) && in_array($method->getName(), $this->ignoreMethodList))
                    continue;
                foreach ($this->attributes as $attr) {
                    $itemAnnotation = $this->matchMethodAnnotate($method,$attr);
                    if (empty($itemAnnotation))
                        continue;
                    $annotation[$attr] = $itemAnnotation[$attr];
                }
                if (empty($annotation))
                    continue;

                empty($annotation['method']) && $annotation['method'] = [];
                if(!empty($classAnnotatio['method'])){
                    $annotation['method'] = array_unique(array_merge($classAnnotatio['method'],$annotation['method']));
                }

                empty($annotation['force']) && $annotation['force'] = [];
                if(!empty($classAnnotatio['force'])){
                    $annotation['force'] = array_unique(array_merge($classAnnotatio['force'],$annotation['force']));
                }

                //与类的中间件合并
                if(!empty($classAnnotatio['middleware'])){
                    empty($annotation['middleware']) && $annotation['middleware'] = [];
                    $annotation['middleware'] = array_unique(array_merge($classAnnotatio['middleware'],$annotation['middleware']));
                }
                //与类的中间件合并
                if(!empty($classAnnotatio['noMiddleware'])){
                    empty($annotation['noMiddleware']) && $annotation['noMiddleware'] = [];
                    $annotation['noMiddleware'] = array_unique(array_merge($classAnnotatio['noMiddleware'],$annotation['noMiddleware']));
                }
                $annotation['action'] = $method->name;
                $annotation['name'] = strtolower($shortName . '_' . $method->name);
                if ($callable instanceof \Closure) {
                    $methodAnnotationList[] = $callable($annotation, $method);
                } else {
                    $methodAnnotationList[] = $annotation;
                }
            }
            continue;
        }
        return $methodAnnotationList;
    }
}
