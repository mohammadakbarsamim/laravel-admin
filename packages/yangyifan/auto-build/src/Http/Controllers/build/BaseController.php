<?php

// +----------------------------------------------------------------------
// | date: 2016-01-12
// +----------------------------------------------------------------------
// | BaseController.php: 自动构建基础控制器
// +----------------------------------------------------------------------
// | Author: yangyifan <yangyifanphp@gmail.com>
// +----------------------------------------------------------------------

namespace Yangyifan\AutoBuild\Http\Controllers\Build;

use gossi\codegen\generator\CodeFileGenerator;
use gossi\codegen\model\PhpClass;
use gossi\codegen\model\PhpProperty;

class BaseController extends \Yangyifan\AutoBuild\Http\Controllers\BaseController
{
    protected  $code_generator;
    protected  $php_class;
    protected  $php_method;
    protected  $php_parameter;
    protected  $request;

    const CONSTRUCT_FUNCTION_NAME   = '__construct';//构造方法名称
    const CONSTRUCT_FUNCTION_TITLE  = '构造方法';//构造方法名称
    const OUT_PUT_DIR               = '/output/';//输出文件目录
    const EXT                       = '.php';//文件类型
    const AUTHOR_EMILA              = 'yangyifanphp@gmail.com';//作者邮箱
    const AUTHOR_NAME               = 'yangyifan';//作者名称

    /**
     * 构造方法
     *
     * @author yangyifan <yangyifanphp@gmail.com>
     */
    public function __construct()
    {
        parent::__construct();
        $this->code_generator   = new CodeFileGenerator();
        $this->php_class        = new PhpClass();
    }

    /**
     * 析构方法
     *
     * @author yangyifan <yangyifanphp@gmail.com>
     */
    public function __destruct()
    {
        //设置文件说明注释
        $this->setHeaderComment();
        //设置需要继承的类
        $this->setParentClass();

        $file_name  = $this->getFileName();
        $dir_name   = dirname($file_name);

        if (is_dir($dir_name) == false) {
            mkdir($dir_name, 0777, true);
        }

        file_put_contents( dirname(dirname(dirname(__DIR__ ))) . self::OUT_PUT_DIR . $this->request['file_name'] . self::EXT, $this->code_generator->generate($this->php_class));
    }

    /**
     * 文件文件全路径
     *
     * @return string
     * @author yangyifan <yangyifanphp@gmail.com>
     */
    private function getFileName()
    {
        return dirname(dirname(dirname(__DIR__ ))) . self::OUT_PUT_DIR . $this->request['file_name'] . self::EXT;
    }

    /**
     * 设置文件名称(class名称 和 命名空间)
     *
     * @param $file_name
     * @return $this
     * @author yangyifan <yangyifanphp@gmail.com>
     */
    protected function setQualifiedName()
    {
        $file_info = $this->setFile();

        $this->php_class->setName( $file_info['name'] );//设置文件全路径
        $this->php_class->setNamespace( $file_info['namespace'] );//设置文件全路径
        return $this;
    }

    /**
     * 导入 class
     *
     * @param array $use_array
     * @return $this
     * @author yangyifan <yangyifanphp@gmail.com>
     */
    protected function setUse($use_array = [])
    {
        //组合
        $use_array = static::USE_ARRAY ?  : array_merge( $use_array, static::USE_ARRAY );

        if (!empty(($use_array))) {
            foreach ($use_array as $use) {
                $this->php_class->declareUse($use);
            }
        }
        return $this;
    }

    /**
     * 设置需要继承的类
     *
     * @return $this
     * @author yangyifan <yangyifanphp@gmail.com>
     */
    private function setParentClass()
    {
        $parent_class = $this->request['parent_class'] ? : static::DEFAULT_PARENT_CLASS;
        $this->php_class->setParentClassName($parent_class);
        return $this;
    }

    /**
     * 设置文件名称和命名空间
     *
     * @return array
     * @author yangyifan <yangyifanphp@gmail.com>
     */
    protected function setFile()
    {
        if (!empty($this->request['file_name'])) {
            //最后出现的文字
            $end_str = strripos($this->request['file_name'], '/');


            if ( $end_str >= 0  ) {
                return [
                    "namespace" => str_replace("/", "\\", mb_substr($this->request['file_name'], 0, $end_str)),
                    'name'      => mb_substr($this->request['file_name'], $end_str + 1, ( strlen($this->request['file_name']) -1) )
                ];
            }
            return [
                "namespace" => '',
                "name"      => $this->request['file_name'],
            ];
        }
    }

    /**
     * 设置类属性
     *
     * @param $name
     * @param null $value
     * @author yangyifan <yangyifanphp@gmail.com>
     */
    protected function setProperty($name, $value, $description, $type = 'privite')
    {
        $this->php_class->setProperty(
            PhpProperty::create($name)
                ->setVisibility($type)
                ->setType('string')
                ->setDefaultValue($value)
                ->setDescription($description)
        );
        return $this;
    }

    /**
     * 设置文件说明注释
     *
     * @author yangyifan <yangyifanphp@gmail.com>
     */
    private function setHeaderComment()
    {
        $this->php_class->setLongDescription("
+----------------------------------------------------------------------
| date: ".date('Y-m-d H:i:s')."
+----------------------------------------------------------------------
| ".$this->php_class->getName().".php: {$this->request['file_title']}
+----------------------------------------------------------------------
| Author: ".self::AUTHOR_NAME." <".self::AUTHOR_EMILA.">
+----------------------------------------------------------------------
        ");
    }
}