# 1 泛型

## 1.1 基本概念

泛型提供了编译期的类型检查，但问题远非这么简单

```java
///原生态类型
List list1 = new ArrayList();   ///规避的类型检查
List list1 = new ArrayList<String>();

///参数化类型
List<Object> list2 = new ArrayList<Object>();  //可以放任何类型的对象，没指导意义
List<String> list3 = new ArrayList<String>();

///通配符类型
//无限制通配符类型
List<?> list4 = new ArrayList<Dog>();
//有限制通配符类型
List<? extends Animal> list4 = new ArrayList<Dog>();
List<? extends Animal> list4 = new ArrayList<Cat>();


List<String>里的String是：实际类型参数

在类和方法定义时，引入形式类型参数
public class List<T>{
    T[] data;
}
这里T是形式类型参数
List<T>就叫做：泛型

public class PrimitiveList<E extends Number>{
    E[] data;
}
<E extends Number>是有限制通配符类型


泛型方法
public <E> void do_sth(E e){

}

类型令牌
String.class
List.class
String[].class


```

带泛型的类型可以降级为不带泛型的类型
```java

public static void main(){
    List<String> strings = new ArrayList<String>();
    unsafeAdd(strings, new Integer(42));
    String s = strings.get(0);
}

private static void unsafeAdd(List list, Object o){
    list.add(o);  //unchecked call to add(E) in raw type list
}
这段代码可以通过编译，
但会收到警告，unchecked call to add(E) in raw type list,
而且明显在运行时会收到ClassCastException

这种使用方式并不是一无是处，如果unsafeAdd是一个不关心List具体泛型类型的实现逻辑，
或者没法知道List的泛型类型，或者需要处理各种泛型类型的List，则这种写法是可取的

如果这样，则无法通过编译
private static void unsafeAdd(List<Object> list, Object o){
    list.add(o);  //unchecked call to add(E) in raw type list
}
```

无限制的通配符类型
```
如果一个方法需要传入List，但不关心List的具体泛型类型，则可以使用一个问号代替泛型，
这就是无限制的通配符

static int numElementsInCommon(Set<?> s1, Set<?> s2){
    int result = 0;
    for(Object o1: s1){
        if(s2.contains(o1))){
            result++;
        }
        
    }
    return result;
}

Set<?> set1 = ..;
Set set2 = ..;
set1和set2的区别是什么？
1 这两个都能接受所有泛型类型的Set
2 但是set1只能add一个类型的对象，set2可以add任何类型的对象
```

有限制的通配符类型
```java
Set<? extends Aminal> set = ..;


对于Stack<E>，如果提供如下方法
public void pushAll(Iterable<? exnteds E> src){
    for(E e: src){
        push(e);
    }
}
试想，如果参数不用<? exnteds E>，怎么样才合适呢
<? exnteds E>在这里就是最好的选择


对于Stack<E>，如果提供如下方法
public void popAll(Collection<E> dst){
    while(!isEmpty()) dst.add(pop());
}
如果是Stack<Dog>，本来你用List<Dog>作为参数传入，可以
但如果你要用List<Animal>传入，就不行了，但逻辑上这么做是没问题的，所以方法一改：
public void popAll(Collection<? super E> dst){

}
意思是Collection的泛型类型是E的某个超类


所以对于泛型类型对应的对象E e
如果你要生产（put），即给e值，则E和E的子类都可以给，<? extends E>
如果你要消费（get），即x = e，则x即可以是E，也可以是E的基类或接口，<? super E>
这就是PECS原则，Producer extends，Consumer super
```



## 1.2 关于class对象：  
List.class  
String[].class  
int.class  
都是合法的

但是List<String>.class不合法， List<?>.class不合法

注意：泛型是编译时信息，运行时会被擦除，而class是运行时信息

```java

if(o instanceof Set){
    Set<?> m = (Set<?>)o;
}

但你不能 o instanceof Set<String>

```

## 1.3 消除非受检警告：

情况1：
```java

Set<Animal> animals = new HashSet();
这种情况可以很容易的得到编译器提醒unchecked

```

情况2：  
如果你确定代码是类型安全的，可以@SuppressWarnings("unchecked")



## 1.4 数组和集合：  

```java

数组是协变的
Animal[] animals;
Dog[] dogs = new Dog[2];
animals = dogs;  //对的
animals[0] = new Cat(); //错的，ArrayStoreException

泛型是不可变的
List<Animal> animals;
List<Dog> dogs = new ArrayList<Dog>();
animals = dogs;  //错误的，编译报类型不匹配

数组是具体化的，reified，所以数组会在运行时才知道并检查他们的元素类型约束
泛型是通过擦除的，erasure，只能在编译时强化其类型信息，运行时丢弃

有泛型信息时，不能创建数组，以下都非法
List<E>[]
new List<String>[]
new E[]
为什么不可以这么创建数组呢，看下面的例子，如果可以这么创建数组的话：
List<String>[] stringLists = new List<String>[1];
List<Integer> intList = Arrays.asList(42);
Object[] objects = stringLists;
Object[0] = intList;
String s = stringLists[0].get(0);
上述代码的错误如果放到运行时，就是ClassCast错误，所以编译时第一行就会报错

E, List<E>, List<String>
这些都是不可具体化的类型，其运行时表示法包含的信息，要少于其编译时表示法包含的信息

注意：创建List<?>[] arr是合法的

```


## 1.5 泛型的类型推导

很复杂，规则很多，据说光文档就有16页

Lang.<String>isEmpty(s)这种形式叫做显式类型参数



## 1.6 运行时获取泛型的具体类型：

参考TypeToken
```java
import java.lang.reflect.ParameterizedType;
import java.lang.reflect.Type;
import java.util.List;

public class TypeToken<T> {

    private final Type type;

    protected TypeToken(){
        Type superClass = getClass().getGenericSuperclass();

        type = ((ParameterizedType) superClass).getActualTypeArguments()[0];
    }

    public Type getType() {
        return type;
    }

    public final static Type LIST_STRING = new TypeToken<List<String>>() {}.getType();



}


System.out.println(new TypeToken<List<Animal>>(){}.getType());
打印：
java.util.List<com.cowthan.Animal>
```

# 2 注解

注解是元数据

## 2.1 内置注解

提醒编译器的  
@Override  
@Deprecated  
@SuppressWarnings 关闭警告  

## 2.2 自定义注解

`四大元注解`：

* @Target：注解能用在哪儿
    * @Target(ElementType.METHOD)
    * ElementType可能的值：
        * TYPE          用于class定义
        * CONSTRUCTOR   用于构造方法
        * METHOD        用于方法
        * FIELD         用于成员变量
        * LOCAL_VARIABLE 局部变量声明
        * PACKAGE       包声明
        * PARAMETER    方法参数声明
        * ANNOTATION_TYPE
        * TYPE_PARAMETER
        * TYPE_USE
* @Retention：注解信息在哪个阶段有效
    * @Retention(RetentionPolicy.RUNTIME)
    * RetentionPolicy可能的值：
        * SOURCE：源码阶段，编译时，一般是用来告诉编译器一些信息，将被编译器丢弃
        * CLASS：注解在class文件中可用，会被VM丢弃
        * RUNTIME：运行时，VM在运行时也保留注解，这个才能通过反射获取到
* Documented
	* 将此注解包含在JavaDoc中
* Inherited
	* 允许子类继承父类中的注解


`注解 处理器：通过反射拿到注解`

## 2.3 自定义注解实例

简单的例子，只展示了如何定义和使用注解，没有阐明如何处理注解

```java
/**
 * 描述一个测试用例
 *
 */
@Target(ElementType.METHOD)
@Retention(RetentionPolicy.RUNTIME)
public @interface UseCase {
	
	public int id();
	public String description() default "no description";
	
}
```

```java
public class UseCaseDemo {
	
	@UseCase(id = 1, description = "密码的格式必须规范")
	public boolean testPassword(String password){
		return false;
	}
	
	@UseCase(id = 2, description = "用户名的格式必须规范")
	public boolean testUsername(String username){
		return false;
	}
	
	@UseCase(id = 3, description = "新密码不能等于旧密码")
	public boolean testNewPwd(String password){
		return false;
	}
}

```

## 2.4 处理注解：运行时

下面是一个简单的序列化框架，使用了注解@Seriable的字段可以被序列化为json

```java
@Target({ ElementType.FIELD, ElementType.TYPE })  
@Retention(RetentionPolicy.RUNTIME)  
public @interface Seriable  
{  
      
}  


public class User  
{  
    @Seriable  
    private String username;  
    @Seriable  
    private String password;  
  
    private String three;  
    private String four;  
}  

public boolean isSeriable(Field f){
	f.setAccessible(true);
	Seriable annotation = f.getAnnotation(Seriable.class);
	if(id == null){
		return false;
	}else{
		return true;
	}
}


```

## 2.5 处理注解：编译期

ButterKnife能根据注解生成代码，怎么做到的呢，涉及到apt和编译期处理器

http://www.cnblogs.com/avenwu/p/4173899.html

http://www.bubuko.com/infodetail-826234.html  这篇讲的挺好

http://www.jianshu.com/p/1910762593be 安卓版

http://blog.csdn.net/lmj623565791/article/details/43452969

* 重点有三个
    * 定义注解和注解处理器
    * 打成jar包
    * 在任何项目里使用注解，编译时触发注解处理器
    

```java

import javax.annotation.processing.AbstractProcessor;
import javax.annotation.processing.Messager;
import javax.annotation.processing.ProcessingEnvironment;
import javax.annotation.processing.RoundEnvironment;
import javax.annotation.processing.SupportedAnnotationTypes;
import javax.lang.model.SourceVersion;
import javax.lang.model.element.Element;
import javax.lang.model.element.Name;
import javax.lang.model.element.TypeElement;
import javax.lang.model.element.VariableElement;
import javax.lang.model.util.Elements;

@SupportedAnnotationTypes({"com.avenwu.annotation.PrintMe"})
public class BeanProcessor extends AbstractProcessor {

	// 元素操作的辅助类
	Elements elementUtils;

	@Override
	public synchronized void init(ProcessingEnvironment processingEnv) {
		super.init(processingEnv);
		// 元素操作的辅助类
		elementUtils = processingEnv.getElementUtils();
	}

	
	@Override
	public boolean process(Set<? extends TypeElement> annotations,
			RoundEnvironment roundEnv) {

		for (TypeElement currentAnnotation : annotations) {
            Name qualifiedName = currentAnnotation.getQualifiedName();
            if (qualifiedName.contentEquals("com.avenwu.annotation.PrintMe")){
                Set<? extends Element> annotatedElements = roundEnv.getElementsAnnotatedWith(currentAnnotation);
                for (Element element : annotatedElements) {
                	Version v = element.getAnnotation(Version.class);
                    int major = v.major();
                    int minor = v.minor();
                    if(major < 0 || minor < 0) {
                        String errMsg = "Version cannot be negative. major = " + major + " minor = " + minor;
                        Messager messager = this.processingEnv.getMessager();
                        messager.printMessage(javax.tools.Diagnostic.Kind.ERROR,errMsg,element);
}
                }
            }
        }
		return true;
	}
	
	@Override
    public SourceVersion getSupportedSourceVersion() {
        return SourceVersion.latestSupported();
    }

}
```


# 3 反射

反射等以后吧，安卓里面反射用的也不多，EventBus2就是基于反射，在EventBus3里已经
改成了预处理注解的方式