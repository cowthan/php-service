PHP简陋查询手册--适用于新手参考
========

1 基本语法
--------------------

```
<?php
    echo "hello";

    //---退出当前脚本
    //die("可以带一段输出到网页的提示文本");
    //exit(-1);

?>
```
* 其他：
    * 花括号分块
    * 分号结尾
    * 注释//   /* */ /**  */
    * 双引号字符串，可解析$a变量
    * 单引号字符串，不能解析$a变量
    * 字符串拼接：$a . "str"
    * 变量定义：$a = 1;
    * 数组定义：$arr = array(1, 2, 3, 4)
    * 基本类型
        * 整型 int   1
        * 浮点型 float  1e12, 1.0     double，float都有，但都是同一个东西，double是历史遗留
        * 布尔型 boolean   true和false
        * 字符串 string
            * "sssss"
            * 'sssss'
            * HereDoc
            * NowDoc
    * 其他类型
        * 数组  array
        * 对象 object
        * null
        * 资源
    * 伪类型
        * mixed
        * number
        * callback
    * 变量定义
        * $x = 1;
        * echo $x;
    * 比较
        * $a="7"; $b=7.00;  $a==$b返回true，问题是只比较值，不比较类型
        * $a === $b，才返回false，三个等于号比较的是类型和值
        * 对于对象：
            * $x == $y，要求类型和值都相同(对象的类型也是值的一部分）
            * $x === $y，要求是同一个对象
            * $x->equals($y)，可以自已定义这么个函数


2 输出到网页
--------------------

### 1 echo

- 只接受字符串类型，所有其他类型都会被强转为字符串

```
<?php
    define("BR", "<br/>");
    $i = 123;
    $d = 1e2;
    $b = true;
    $s = "-则不达123-";
    $arr = array(1,2,3,4);
    $dict = array("a"=>1, "b"=>2);

    echo "\$i = $i" . BR;
    echo "\$d = $d" . BR;
    echo "\$b = $b" . BR;
    echo "\$s = $s" . BR;
    echo "\$arr = $arr" . BR;
    echo "\$dict = $dict" . BR;

```

### 2 print

```
<?php

    define("BR", "<br/>");
    $i = 123;
    $d = 1e2;
    $b = true;
    $s = "-则不达123-";
    $arr = array(1,2,3,4);
    $dict = array("a"=>1, "b"=>2);

    print "\$i = $i" . BR;
    print "\$d = $d" . BR;
    print "\$b = $b" . BR;
    print "\$s = $s" . BR;
    print "\$arr = $arr" . BR;
    print "\$dict = $dict" . BR;

```

### 3 printf

格式化输出

```
$i = 123;
$d = 1.32323e2;
$b = true;
$s = "-则不达123-";
printf('$s = %s, $i = %d, $b = %s, $d = %s', $s, $i, $b, $d);
```

### 4 print_r

```
define("BR", "<br/>");
$i = 123;
$d = 1e2;
$b = true;
$s = "-则不达123-";
$arr = array(1,2,3,4);
$dict = array("a"=>1, "b"=>2);
class A_ClassName{
    public $a = "23234";
    public $b = 1;

    function s(){}
}

print_r($i);
echo BR . "---------" . BR;
print_r($d);
echo BR . "---------" . BR;
print_r($b);
echo BR . "---------" . BR;
print_r($s);
echo BR . "---------" . BR;
print_r($arr);
echo BR . "---------" . BR;
print_r($dict);
echo BR . "---------" . BR;
print_r(new A_ClassName());
echo BR . "---------" . BR;

```

### 5 var_dump

```
define("BR", "<br/>");
$i = 123;
$d = 1e2;
$b = true;
$s = "-则不达123-";
$arr = array(1,2,3,4);
$dict = array("a"=>1, "b"=>2);
class A_ClassName{
    public $a = "23234";
    public $b = 1;

    function s(){}
}

var_dump($i);
echo BR . "---------" . BR;
var_dump($d);
echo BR . "---------" . BR;
var_dump($b);
echo BR . "---------" . BR;
var_dump($s);
echo BR . "---------" . BR;
var_dump($arr);
echo BR . "---------" . BR;
var_dump($dict);
echo BR . "---------" . BR;
var_dump(new A_ClassName());
echo BR . "---------" . BR;

```

3 变量，常量，类型，作用域
------------------

### 1 类型

* 基本类型
    * 整型 int   1
    * 浮点型 float  1e12, 1.0     double，float都有，但都是同一个东西，double是历史遗留
    * 布尔型 boolean   true和false
    * 字符串 string
        * "sssss"
        * 'sssss'
        * HereDoc
        * NowDoc
* 其他类型
    * 数组  array
    * 对象 object
    * null
    * 资源
* 伪类型
    * mixed  bool empty ( mixed var )
    * number
    * callback


### 2 变量，静态变量和常量

```
////全局
$i = 123;
$d = 1e2;
$b = true;
$s = "-则不达123-";
$arr = array(1,2,3,4);
$dict = array("a"=>1, "b"=>2);
class A_ClassName{
    public $a = "23234";
    public $b = 1;

    function s(){}
}
$obj = new A_ClassName();


$a; //类型未定义，值没有，是undefined


define("BR", "<br/>");
if(defined("BR")){

}
echo BR;


////函数作用域
function func($a){
    $x = 1;
}

function static_local() {
    static $local = 0 ;
    $local++;
    echo $local . '<br>';
}

/** static静态全局变量(实际上:全局变量本身就是静态存储方式,所有的全局变量都是静态变量) */
$glo = 1;
function static_global() {
    global $glo; //此处，可以不赋值0，当然赋值0，后每次调用时其值都为0，每次调用函数得到的值都会是1，但是不能想当然的写上"static"加以修饰，那样是错误的.
    $glo++;
    echo $glo . '<br>';
}
echo $glo . '<br>';  ///2


////类和对象作用域
///---const既是static，又是final
class A_ClassName{
    public $a = "23234";
    public $b = 1;

    function s(){
        $x = $this->a;
    }
}
$obj = new A_ClassName();
echo $obj->a;


class MyClass
{
    const constant = 'constant value';

    function showConstant() {
        echo  self::constant . "\n";
    }
}
$obj = new MyClass();
echo MyClass::constant . "\n";

$classname = "MyClass";
echo $classname::constant . "\n"; // 自 5.3.0 起

$class = new MyClass();
$class->showConstant();

///const和继承
//----注意要和get_called_class配合使用
abstract class dbObject
{
    const TABLE_NAME='undefined';

    public static function GetAll()
    {
        $c = get_called_class();
        return "SELECT * FROM `".$c::TABLE_NAME."`";
    }
}

class dbPerson extends dbObject
{
    const TABLE_NAME='persons';
}

class dbAdmin extends dbPerson
{
    const TABLE_NAME='admins';
}

echo dbPerson::GetAll()."<br>";//output: "SELECT * FROM `persons`"
echo dbAdmin::GetAll()."<br>";//output: "SELECT * FROM `admins`"


////late static binding
static声明在父类，子类没法覆盖的问题，例如java，但php解决了这个问题
对于父类来说，方法里的：
- $this：当前对象，随时会和子类对象绑定
- parent：父类对象，子类可以在成员方法中调用parent::func()，相当于java的super
- self：定义此方法的类，注意，方法在哪儿声明的，self就是哪个类，在子类中也是指向父类
- static：调用此方法的类，所以会动态的指向父类或者子类
class A {
    const MY_CONST = false;
    public function my_const_self() {
        return self::MY_CONST;
    }
    public function my_const_static() {
        return static::MY_CONST;
    }
}

class B extends A {
   const MY_CONST = true;
}

$b = new B();
echo $b->my_const_self ? 'yes' : 'no'; // output: no
echo $b->my_const_static ? 'yes' : 'no'; // output: yes

///7.0支持const定义个array
class MyClass
{
    const ABC = array('A', 'B', 'C');
    const A = '1';
    const B = '2';
    const C = '3';
    const NUMBERS = array(
        self::A,
        self::B,
        self::C,
    );
}
var_dump(MyClass::ABC);
var_dump(MyClass::NUMBERS);

内置常量
define("BR", "<br />");
echo "TRUE = " . TRUE . BR;   ///TRUE = 1
echo "FALSE = " . FALSE . BR; ///FALSE =
echo "PHP_VERSION = " . PHP_VERSION . BR ;  ///PHP_VERSION = 5.4.16
echo "PHP_VERSION_ID = " .+ PHP_VERSION_ID . BR;  ////PHP_VERSION_ID = 50416
echo "PHP_OS = " . PHP_OS . BR;  ////PHP_OS = WINNT
echo "__FILE__ = " . __FILE__ . BR;  ////__FILE__ = E:\xampp\htdocs\php-cowthan\cowthan.php
echo "__FUNCTION__ = " . __FUNCTION__ . BR;  ////__FUNCTION__ =
echo "__LINE__ = " . __LINE__ . BR;   ////__LINE__ = 78
echo "__CLASS__";
echo "__METHOD__";

```

### 3 变量展开

* 变量可以在字符串中展开，只限于双引号
    * "$msg"可以展开
    * "${msg}1"可以展开
    * "{$msg}1"可以展开
    * $会在后面寻找一个完整的合法变量名，或者寻找一个{}包的变量名


### 4 类型转换

* 不规范转换
    * $a = "25ddd";  $a+2会转成25
    * $a = "25.4ddd"; $a+2还是会转成25，除非字符串是一个纯double，否则都会转成int
    * $a = "25.4";  $a+2会转成25.4
    * $a = "dfsd"; $a+2没意义，因为a没法转
    * 因为对于字符串的+运算，会是尝试转成数字运算，所以字符串拼接就是用点号

* 没有截断和提升
    * $a = 2;  $a = 2.5; 重新赋值也会改变a的类型（对于java，a就是int了，赋值2.5会导致截断）

* 强制类型转换
    * (int) (integer)
    * (float) (real) (double)
    * (string)
    * (bool) (Boolean)
    * (array)
    * (object)

* 方法转换
    * toString
    * intval()
    * floatval()
    * strval()
    * $isSuccess = settype(变量, 类型)

```
$str="123.9abc";
$int=intval($str);     //转换后数值：123
$float=floatval($str); //转换后数值：123.9
$str=strval($float);   //转换后字符串："123.9"

$num4=12.8;
$flg=settype($num4,"int");
var_dump($flg);  //输出bool(true)
var_dump($num4); //输出int(12)
```

### 5 和变量有关的函数

* isset方法：检测左值
    * 检测变量是否设置 ，只能传入一个变量，即传入的参数必须能作为左值
	* 如果没有定义，或者为NULL，则返回FALSE
	* 否则返回TRUE
	* unset方法：将一个变量变为非isset的

* empty：一个变量isset并且不为空值，不为false
  	* 若变量不存在则返回 TRUE
  	* 若变量存在，但其值为""、0、"0"、NULL、、FALSE、array()、var $var; 以及没有任何属性的对象，则返回 TURE
  	* 否则返回FALSE

* 和成员属性的关系
    * isset和empty不会调起__set和__get，但会调起__isset
    * 也就是说定义了__set和__get，最好也定义一下__isset，否则属性判断会产生歧义

```

unset($a);
unset($b);
$a = 123;
if(isset($a)){
	echo "a是isset的<br/>";  //输出这句
}else{
	echo "a不是isset的<br/>";
}

$a = NULL;
if(isset($a)){
	echo "a是isset的<br/>";
}else{
	echo "a不是isset的<br/>"; //输出这句
}

if(isset($b)){
	echo "b是isset的<br/>";
}else{
	echo "b不是isset的<br/>"; //输出这句
}

echo "==========<br/>";
$a = "dd";
if(empty($a)){
	echo "a没有定义或者值为空（相对于其类型的空，或NULL）<br/>";
}else{
	echo "a已定义，并且有非空值<br/>";  //输出这句
}

$a = array();
if(empty($a)){
	echo "a没有定义或者值为空（相对于其类型的空，或NULL）<br/>";  //输出这句
}else{
	echo "a已定义，并且有非空值<br/>";
}

if(empty($b)){
	echo "b没有定义或者值为空（相对于其类型的空，或NULL）<br/>";   //输出这句
}else{
	echo "b已定义，并且有非空值<br/>";
}
```


### 6 传引用

php其实是通过复制来传递参数的，函数的参数都是传值，=赋值也都是传值，复制了一份
1 对于基本类型：就是创建了一个完全相同的对象实例
2 对于对象类型：也是传值，但是复制的其实是对象的引用，或者说是别名，所以还是指向同一个对象
——这句话深入一点解释就是：既然参数是通过复制传递的，所以你没法改变形参的指向

```
$x = 1;
$y = &$x;
$y++; //连$x也跟着变了
echo $x;

$arr =range(1, 5);
foreach($arr as &$a){
    $a *= 2;  //连数组里的元素值也变了
}
var_dump($arr);

function f(&$x){
    $x += 3;
}
$a = 2;
f($a); //$a变成了5
echo $a;

///&get_x()会导致返回了一个变量（但不能当左值用），get_x()返回的是一个值，注意区别
class A{
    private $x = 10;

    function &get_x(){
        return $this->x;
    }
}
$a = new A();
$x = &$a->get_x(); ///对于返回的变量，还要再取一次引用
$x = 15;   //绕过了private，改变了成员属性的值
echo $a->get_x(); //15

```

4 运算

* 比较
    * $a="7"; $b=7.00;  $a==$b返回true，问题是只比较值，不比较类型
    * $a === $b，才返回false，三个等于号比较的是类型和值
    * 对于对象：
        * $x == $y，要求类型和值都相同(对象的类型也是值的一部分）
        * $x === $y，要求是同一个对象
        * $x->equals($y)，可以自已定义这么个函数

```

```


4 字符串
------------------------

```
///HereDoc  不解析变量
<?php
class foo {
    // 自 PHP 5.3.0 起
    const bar = <<<'EOT'
bar
EOT;
}
?>

```


5 动态特性
-------------------

### 1 eval函数

能执行php函数


### 2 变量

```
$a = 123;
$reflect_a = "a";
echo $$reflect_a;  ///输出 123
```


### 3 函数

```

```

### 4 类名

```

```

### 5 反射一个函数



