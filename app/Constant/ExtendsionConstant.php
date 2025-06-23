<?php

namespace App\Constant;

use ReflectionClass;

class ExtendsionConstant{
    protected $constant;

    // truyền class chứa các const khai báo sẽ trả về dạng mảng 
    public static function getConstantsAsArray(string $class): array
    {
        if (!class_exists($class)) {
            throw new \InvalidArgumentException("Class $class không tồn tại.");
        }

        $reflection = new ReflectionClass($class);
        return array_values($reflection->getConstants());
    }

    // truyền class và truyền giá trị value để lấy text tương ứng
    public static function getTextByValue(string $class, string $value): ?string
    {
        $constants = self::getConstantsAsArray($class);

        foreach ($constants as $const) {
            if (is_array($const) && isset($const['value']) && $const['value'] === $value) {
                return $const['text'];
            }
        }

        return null; // nếu không tìm thấy
    }
}