<?php
/**
 * @Author: Frank
 * @Email: frank_hhm@163.com
 * @Date: 2023/12/16 13:28
 */
declare(strict_types=1);

namespace app\common\services\annotation;
use app\common\services\annotation\AnnotationService;
class MiddlewareService extends AnnotationService
{

    protected array $target = [self::ELEMENT_METHOD];

    protected array $attributes = ['method'];
}