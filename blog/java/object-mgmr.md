# 对象管理


&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;编程是个慢慢积累的过程，则不达的博客，就是我已经有的，和将要有的积累。前十篇博客大部分是Java编程思想和Effective Java的笔记，部分参考HeadFirst设计模式和大话设计模式。

本节重点说对象的创建和管理，如果还有什么这方面的欠缺，欢迎留言。

* 目录
  * 静态工厂
  * 服务提供者框架
  * Builder模式的构建器
  * 单例模式
  * 简单工厂模式
  * 工厂方法
  * 抽象工厂
  * Flyweight：蝇量模式（享元模式）
  * 备忘录模式
  * 原型模式和深拷贝浅拷贝


> Effective Java这本书里，作者一直在申明一条原则：  
对象不能在初始化过程中，还能被访问到，  
必须准备好所有必要字段，再获取对象，  
不要留中间状态。


## 1 静态工厂

这个比较简单，先上代码：
#### 1.1 套路

```java

//常规模式
public static class Boolean{

	//静态工厂方法
	public static Boolean valueOf(boolean b){
		return new Boolean(b); 
	}

	private boolean v;
	
	private Boolean(boolean b){
		v = b;
	}
	
}

//高级模式：限制对象的数目，比如单例，此处的例子是：Boolean基本就只能对应两个值
public static class Boolean{
		
	//预定义的两个对象，某种意义上算是缓存，使用时可以省去创建对象的过程
	public static final Boolean True = new Boolean(true);
	public static final Boolean False = new Boolean(false);
	
	//静态工厂方法
	public static Boolean valueOf(boolean b){
		return b ? True : False;
	}

	private boolean v;
	
	private Boolean(boolean b){
		v = b;
	}
	
}
```

* 静态工厂方法的好处是：
  * 1 方法有名字，构造器没有，所以可以给出不同重载，而方法名赋予其意义，这点就胜于构造器
  * 2 可以在静态工厂里控制返回的对象，不一定非得是new出来的
  * 3 可以返回任意子类型的对象，返回的父类或者接口引用，具体实现类甚至可以对外隐藏，参考Java Collections Framework中集合接口的32个便利实现
    * （1）Collections里有unmodified, empty, checked, synchronized各8个方法，对应8种不同的集合类型
    * 这8种集合类型就是：Collection, List, Map, SortedMap, NavigableMap, Set, SortedSet, NavigableSet，注意这是接口类型，对外的
    * 返回的实际类型是什么呢，都是以private static class的形式实现的，并未对外公开，所以可以jdk随时修改，提升性能或修改实现
    * （2）EnumSet：其静态工厂方法会根据底层枚举类型大小，返回RegalarEnumSet对象或者JumboEnumSet对象，而且这对外部用户是隐藏的
  * 4 简化Map<String, String> m = new HashMap<String, String>()这种繁琐的调用, Map<String, String> m = HashMap.newHashMap();

* 静态工厂的命名有几个套路，可以让别人一看到方法名，就知道是工厂方法
  * 静态工厂方法终究也只是普通的static方法，文档中并不会特别对待，这是个缺点
  * 所以这里有些命名套路，还是应该遵守的
  * valueOf：类似Boolean.valueOf()，实际是类型转换，参数和返回的实例有相同的值
  * of：valueOf的简洁写法，在EnumSet中流行起来
  * getInstance：参数可选
  * newInstance：隐含的意思是每次都是new一个新的
  * getType, newType
	

## 2 服务提供者框架  

这是从静态工厂好处3里引出来的，对外提供一个接口，客户端依赖于此接口实例，但并不关心具体实现

* 三大组件：以JDBC为例
	* `服务接口`：Service Interface，提供者实现，如Connection
	* `提供者注册API`：Provider Registreation API，用来注册实现，让客户端访问，如DriverManager.registerDriver()
	* `服务访问API`：Service Access API，客户端用来获取服务实例，这里就是灵活的静态工厂，如DriverManager.getConnection()
	* `服务提供者接口`，Service Provider Interface，可选，用来创建服务实例，如果没有这个，就得按照类名注册，并通过反射实例化，如Driver就是这个角色


```java

/**
 * ================服务接口：Service Interface==================
 * 一个对外提供服务的接口，并且不同情况，会产生不同的Service对象，
 * 即通过Service的不同实现，对外提供不同的服务
 *
 */
public interface Service {
	
	void doService();

}


/**
 * ================服务提供者接口==================
 * 用来生成Service对象，注意，如果不使用Provider，则注册到Services的就得是Service实现类的Class对象，
 * newInstance也只能通过反射来了
 * 问题就是Provider实现类应该有几个
 *
 */
public interface Provider {
	Service newService();
}

public class Services {

	private Services(){}
	
	//================提供者注册API==================//
	//这里要么注册provider对象，要么注册Service实现类的Class，你选吧
	private static final Map<String, Provider> providers = new ConcurrentHashMap<>();
	public static final String DEFAULT_PROVIDER_NAME = "<def>";
	
	public static void registerDefaultProvider(Provider p){
		registerProvider(DEFAULT_PROVIDER_NAME, p);
	}

	public static void registerProvider(String defaultProviderName, Provider p) {
		providers.put(defaultProviderName, p);
	}
	
	//================服务访问API==================//
	public static Service newInstance(){
		return newInstance(DEFAULT_PROVIDER_NAME);
	}

	public static Service newInstance(String name) {
		Provider p = providers.get(name);
		if(p == null){
			throw new IllegalArgumentException("No provider registered with name + " + name);
		}
		return p.newService();
	}
}

```

* 使用场景
  * Service提供了某项工作的接口
  * Services是一个平台：
    * 注册接口：用于注册Service的实现
      * 可以注册Provider，如上例，规避调用Class对象和反射
      * 可以注册Service实现类的Class对象
      * 可以注册Service实现类的实例
      * 访问接口：用于获取Service的实现的实例
    * api作者和用户都可以实现Service和Provider，即提供服务
    * JDBC的服务就是连接数据库，不同的Service实现，对应不同的数据库，也使用了不同的驱动


## 3 Builder模式的构建器

* 使用场景：参数个数多
    * 原始模式：构造器或者静态工厂形式太多，参数多
    * JavaBeans模式：new一个空对象然后set各种参数，代码不宜管理，set过程中，对象也可能处于不一致状态
    * 把JavaBeans的一组set封装起来，就是一个建造者模式的原始形态，但里面依旧需要对对象的一致性负责
    	* 这里说的一致性问题，意思是new完对象，还需要一组set之后，对象才能正常工作，
    	* 但set期间，对象已经有了，却不能正常工作，这就是一个危险的状态
    	* Builder模式，就不存在这个不一致的状态，因为对象最终还是通过一个构造器出来后就已经可以正常工作了
    * 所以这时使用Builder模式，既能保证JavaBeans的可读性，又能保证原始模式的安全性
    * ImageLoader的初始化，AlertDialog的初始化，都使用了这种模式，参数很多，有些必填（放到Buidler的构造方法），有些选填（作为单独方法）

### 3.1 简单模式


```java
public class NutritionFacts {

	private final int servingSize;
	private final int servings;
	private final int calories;
	private final int fat;
	private final int sodium;
	private final int carbohydrate;
	
	
	public NutritionFacts(Builder builder) {
		servingSize = builder.servingSize;
		servings = builder.servings;
		calories = builder.calories;
		fat = builder.fat;
		sodium = builder.sodium;
		carbohydrate = builder.carbohydrate;
	}
	
	public static class Builder{
		
		//必填的参数，无默认值
		private final int servingSize;
		private final int servings;
		
		//选填的参数，有默认值
		private int calories = 0;
		private int fat      = 0;
		private int carbohydrate = 0;
		private int sodium = 0;
		
		public Builder(int servingSize, int servings){
			this.servingSize = servingSize;
			this.servings = servings;
		}
		
		public Builder calories(int val){ calories = val; return this; }
		public Builder fat(int val){ fat = val; return this; }
		public Builder carbohydrate(int val){ carbohydrate = val; return this;}
		public Builder sodium(int val){ sodium = val; return this; }
	
		public NutritionFacts build(){
			return new NutritionFacts(this);
		}
		
	}
	
}

public static void main(String[] args) {
	NutritionFacts cocacola = new NutritionFacts.Builder(240,  8)
			.calories(100)
			.sodium(35)
			.carbohydrate(27)
			.build();
}


```

### 3.2 Builder接口模式

将Builder抽取出来单独做一个接口，这个接口的对象可以：
* 创建任意多的对象
* 其功能就类似于直接传Class对象
* 但比Class对象的newInstance方法多了类型检查，构造方法保证等
* 缺点就是要创建N多个Builder类


```java
public interface Builder<T> {
	public T build();
}


public class NutritionFacts2 {

	private final int servingSize;
	private final int servings;
	private final int calories;
	private final int fat;
	private final int sodium;
	private final int carbohydrate;
	
	
	public NutritionFacts2(MyBuilder builder) {
		servingSize = builder.servingSize;
		servings = builder.servings;
		calories = builder.calories;
		fat = builder.fat;
		sodium = builder.sodium;
		carbohydrate = builder.carbohydrate;
	}
	
	public static class MyBuilder implements Builder<NutritionFacts2>{
		
		//必填的参数，无默认值
		private final int servingSize;
		private final int servings;
		
		//选填的参数，有默认值
		private int calories = 0;
		private int fat      = 0;
		private int carbohydrate = 0;
		private int sodium = 0;
		
		public MyBuilder(int servingSize, int servings){
			this.servingSize = servingSize;
			this.servings = servings;
		}
		
		public MyBuilder calories(int val){ calories = val; return this; }
		public MyBuilder fat(int val){ fat = val; return this; }
		public MyBuilder carbohydrate(int val){ carbohydrate = val; return this;}
		public MyBuilder sodium(int val){ sodium = val; return this; }
	
		public NutritionFacts2 build(){
			return new NutritionFacts2(this);
		}
		
	}
	
	public static void main(String[] args) {
		Builder<NutritionFacts2> builder = new NutritionFacts2.MyBuilder(240,  8)
				.calories(100)
				.sodium(35)
				.carbohydrate(27);
		
		NutritionFacts2 cocacola = builder.build();
	}
}

/*
这里的Builder<NutritionFacts2> builder对象，可以传给任意的抽象工厂方法
*/

```

## 4 单例模式

* 怎么能破坏单例的限制
  * 反射：反射出私有构造方法，Enum可自然规避此问题，其他方式得强写检查代码
  * 序列化：将单例序列化，再反序列化，出来就是一个新对象，Enum可自然解决，其他方式使用readResolve方法
  * 安卓里的多进程，会产生多个Application


### 4.1 饿汉：公有域，或静态工厂

```java
public class Singleton {
	
	public static final Singleton INSTANCE = new Singleton();
	private Singleton(){}
	private Object readResolve(){ return INSTANCE; }
	
	public void provideService(){
		
	}

}

//访问
Singleton.INSTANCE.provideService();
```

```java
public class Singleton {
	
	private static final Singleton INSTANCE = new Singleton();
	private Singleton(){}
	public static Singleton getInstance(){ return INSTANCE; }
	private Object readResolve(){ return INSTANCE; }
	
	public void provideService(){
		
	}

}

//访问
Singleton.getInstance().provideService();
```

### 4.2 懒汉：双保险模式

这里注意延迟加载和volatile的使用

```java
public class Singleton{
   private volatile static Singleton instance = null;
   private Singleton(){}
   public static Singleton getInstance(){
     if(instance == null){
        synchronized(Singleton.class){
            if(instance == null){ instance = new Singleton(); }
        }
     }
     return instance;
   }
}
```


这双重检查的概念可以推广到所有需要延迟加载的地方，如果一个变量需要延迟加载，
那访问的时候，你就应该这样：

```java

private volatile T field;

T getField(){
	//用到了result这个局部变量，确保field只在已经被初始化的情况下读取一次，可以提升性能（非必须）
	//据说 速度比不用局部变量快了25%
	T result = field;  
	if(result == null){
		synchronized(this){
			result = field;
			if(result == null){
				field = result = computeFieldValue();
			}
		}
	}
	return result;
}
```

### 4.3 懒汉：内部类模式

这里的说法是：    
SingletonHolder作为一个内部类，会在访问时被加载，所以这里实现了延迟加载，并且内部类可以从语言层面上防止多线程的问题，比双重锁模式优雅的多。

```java
public class Singleton{
   private static class SingletonHolder{
       private static final Singleton INSTANCE = new Singleton();
   }
   private Singleton(){}
   public static Singleton getInstance(){
       return SingletonHolder.INSTANCE;
   }
}
```

访问Singleton这个类时，才加载Inner，单例才被初始化  
而且一个Inner保证了有一个singleton静态实例  
那能保证Inner只有一个吗？能啊，ClassLoader会保证Inner的Class就一个  

### 4.4 枚举

按照Effective Java书里说法，这个方法虽然没流行起来，但最符合单例的需求

```java
//直接就能防止反射，防止序列化时生成新类
public enum Singleton {
	
	INSTANCE;
	
	public void provideService(){
		
	}

}
```

### 4.5 暴力反射版的单例：趣味探索

```java
public class Singleton {
	private Singleton(){}
	public void doSth(){
		System.out.println("做点什么");
	}
}
```

```java
public class SingletonFactory {
	private static Singleton singleton;
	//===只实例化一次，使用暴力反射
	static{
		try {
			Class cls = Class.forName(Singleton.class.getName());
			Constructor cons = cls.getDeclaredConstructor();
			cons.setAccessible(true);
			singleton = (Singleton) cons.newInstance();
		} catch (Exception e) {
			e.printStackTrace();
		}
	}
	
	public static Singleton getSingleton(){
		return singleton;
	}
	
	/**
	 * 扩展：一个项目可以有一个单例构造器，负责生成所有单例对象，只需要传入类型，
	 * 但是需要事先知道有几个单例类型
	 */
}
```


## 5 简单工厂模式

场景：披萨店里生产各种披萨，需要你实现一个用户点餐的接口，用户可以选择披萨类型

```java
先给出Pizza的类体系

public abstract class Pizza{

}

public class CheesePizza extends Pizza{

}

public class PepperoniPizza extends Pizza{

}

public class ClamPizza extends Pizza{

}

public class VeggiPizza extends Pizza{

}
```

```java
//------------------------------------------------------------
//第一个例子，不用工厂模式
//------------------------------------------------------------
Pizza orderPizza(String type){
	Pizza pizza;
	if(type.equals("cheese")){
		pizza = new CheesePizza();
	}else if(type.equals("pepperoni")){
		pizza = new PepperoniPizza();
	}else if(type.equals("clam")){
		pizza = new ClamPizza();
	}else if(type.equals("veggie")){
		pizza = new VeggiPizza();
	}
	pizza.prepare();
	pizza.bake();
	pizza.cut();
	pizza.box();
	return pizza;
}
//如果需要增删pizza的种类，则这个方法的代码需要修改
//也就是需要修改已经编译好的
//不符合对修改关闭，对扩展开放的原则

//------------------------------------------------------------
//第二个例子，使用简单工厂模式
//------------------------------------------------------------
public class SimplePizzaFactory{
	
        ///方式1：传入字符串类型的type，或者int类型的type，依然需要修改，但至少代码已经都归到一个地方了
	public Pizza createPizza(String type){
		Pizza pizza;
		if(type.equals("cheese")){
			pizza = new CheesePizza();
		}else if(type.equals("pepperoni")){
			pizza = new PepperoniPizza();
		}else if(type.equals("clam")){
			pizza = new ClamPizza();
		}else if(type.equals("veggie")){
			pizza = new VeggiPizza();
		}
		return pizza;
	}
        
        ///方式2：再增加新的pizza类型，这个方法也不需要修改了
        public <T extends Pizza> T createPizza(Class<T> clazz){
		Pizza pizza;
		try{
			pizza = clazz.newInstance();
		}catch(Exception e){
			e.printStackTrace();
			System.out.println("生成错误");
		}
		return (T)pizza;
	}

       ///方式3：参数也可以传入Builder，Provider等
    
}

public class PizzaStore{
	
	SimplePizzaFactory factory;
	public PizzaStore(SimplePizzaFactory fac){
		this.factory = fac;
	}
	
	
	public Pizza orderPizza(String type){
		Pizza pizza;
		pizza = factory.createPizza(type);
		pizza.prepare();
		pizza.bake();
		pizza.cut();
		pizza.box();
		return pizza;
	}
}
///

```

## 6 工厂方法

需要注意的是，工厂方法不是简单工厂的升级，  
而是简单工厂所对应的需求升级了，这里的升级就是，出现了不同的产品等级  
Pizza店生意好了，采用了加盟模式，NewYork, Chicago都有加盟店了，  
但是这两个地方的Pizza风味有所不同，虽然同是Chesse或者Pepper，但是  
还都有各自的风味，如饼的厚薄，饼的大小，传入同样的type，但返回的对象不一样了

> 工厂方法提供一个工厂接口或基类，由子类完成具体的创建动作，
所以工厂基类中，对对象的处理，就和子类的创建动作解耦了

工厂方法，能让各地的Pizza店还保持一样的制作流程，但各自在创建时，允许发挥个性

```java

public abstract class PizzaStore{
        public Pizza orderPizza(String type){
		Pizza pizza;
		pizza = createPizza(type);
		pizza.prepare();
		pizza.bake();
		pizza.cut();
		pizza.box();
		return pizza;
	}
	abstract Pizza createPizza(String type);
}
public class NewYorkPizzaStore extends PizzaStore{
	
	public Pizza createPizza(String type){
		Pizza pizza;
		if(type.equals("cheese")){
			pizza = new NYCheesePizza();
		}else if(type.equals("pepperoni")){
			pizza = new NYPepperoniPizza();
		}else if(type.equals("clam")){
			pizza = new NYClamPizza();
		}else if(type.equals("veggie")){
			pizza = new NYVeggiPizza();
		}
		return pizza;
	}
	
}
public class ChicogoPizzaStore extends PizzaStore{
	
	public Pizza createPizza(String type){
		Pizza pizza;
		if(type.equals("cheese")){
			pizza = new CGCheesePizza();
		}else if(type.equals("pepperoni")){
			pizza = new CGPepperoniPizza();
		}else if(type.equals("clam")){
			pizza = new CGClamPizza();
		}else if(type.equals("veggie")){
			pizza = new CGVeggiPizza();
		}
		return pizza;
	}
	
}

```

```java
Pizza的类体系还是差不多

public abstract class Pizza{

}

public class NYCheesePizza extends Pizza{

}

public class NYPepperoniPizza extends Pizza{

}

public class CGCheesePizza extends Pizza{

}

public class CGPepperoniPizza extends Pizza{

}
```

* 分析
  * 一样的type，但是不同的工厂子类返回了不同的类型
  * 比如说螺丝分为大小螺丝，可以有大螺丝工厂，小螺丝工厂，大和小就是产品等级
  * 而螺丝，螺帽系列，就是一个产品族，对应到pizza上，就是pizza，辣椒，调料就是一个产品族
  * 涉及到产品族，就需要用到抽象工厂了

```java
这里给出一段代码，还是要解决上面的需求，但不使用工厂方法，只用暴力方案

public class DependentPizzaStore{
	
	public Pizza createPizza(String style, String type){
		Pizza pizza = null;
		if(style.equals("NewYork")){
			if(type.equals("cheese")){
				return NYCheesePizza();
			}else if(type.equals("veggi")){
				return NYVeggiPizza();
			}
		}else if(style.equals("Chicago")){
			if(type.equals("cheese")){
				return CGCheesePizza();
			}else if(type.equals("veggi")){
				return CGVeggiPizza();
			}
		}
		return pizza;
	}
	
}

这段代码很重要，很直观的告诉你简单工厂和工厂方法解决的问题

createPizza(String style, String type)两个参数时，style就变成了工厂方法里的类体系结构，每个工厂子类其实就是不同的style

createPizza(String type)一个参数时，使用简单工厂就可以了

如果出现了CreatePepper， CreateSauce，需要你创建辣椒和酱料，和Pizza一起作为一个产品族，就用到抽象工厂了

```

## 7 抽象工厂

看到这里时，你应该已经了解了简单工厂和工厂方法
* 你应该已经知道了
  * 同一个东西的不同风格，是产品等级不同，如大小螺丝，需要不同风格的工厂--工厂方法
  * 不同的东西形成一个系列，如螺丝和螺帽，是一个产品族，在一个工厂里生产

```java
现在假设不同地域的pizza店，得就近生产自己地区的酱料和奶酪，以保持新鲜


public interface AbstractFactory{
	public Cheese createCheese();
	public Sauce createSauce();
}

public class NewYorkFactory extends AbstractFactory{
	public Cheese createCheese(){
		return NYCheese();
	}
	public Sauce createSauce(){
		return NYSauce();
	}
}
public class CGFactory extends AbstractFactory{
	public Cheese createCheese(){
		return CGCheese();
	}
	public Sauce createSauce(){
		return CGSauce();
	}
}


这俩工厂用在哪儿呢？注意，酱料和奶酪作为一个产品族，他们组装出来的产品就是：Pizza

所以这里的工厂，应该用在组装Pizza的地方
public class ChicogoPizzaFactory extends PizzaFactory{
	
	public Pizza createPizza(String type){
		Pizza pizza;
                AbstractFactory fac = new CGFactory();
		if(type.equals("cheese")){
			pizza = new CGCheesePizza(fac);
		}else if(type.equals("pepperoni")){
			pizza = new CGPepperoniPizza(fac);
		}else if(type.equals("clam")){
			pizza = new CGClamPizza(fac);
		}else if(type.equals("veggie")){
			pizza = new CGVeggiPizza(fac);
		}
		return pizza;
	}
	
}
```

* 三个工厂模式的总结：
  * 简单工厂：解决了单个店面的选择不同类型pizza的问题
  * 工厂方法：解决了不同地域Pizza店的口味问题，同一类型的pizza可以做出不同风格
  * 抽象工厂：解决了不同地域的Pizza原料生产问题，原料作为产品族
  * 这里Pizza的例子扩展到女娲造人的场景里
    * 人的类型：男人，女人---由简单工厂解决，SimpleFactory.createHuman(type)
    * 人的不同风格：黄色，黑色，白色--由工厂方法解决
      * IHumanFactory.createHuman(type)
      * 分为YellowFactory，BlackFactory，WhiteFactory
    * 人的零件问题：胳膊，腿，脑袋
      * AbstractFactory.create胳膊，create腿，create脑袋
      * Yellow零件Factory，Black零件Factory，White零件Factory
      * 而抽象工厂，需要设置给上面不同肤色的工厂方法使用，来组装成不同肤色的人

## 8 Flyweight：蝇量模式（享元模式）

* 场景：
  * 一个森林里有很多树，树有千万棵，但一共就三种（柳树，槐树，杨树），然后就是树的位置不同，树的样子是外部状态，树的位置是内部状态
  * 一个年级很多Student，各个Student保存一个Class班级信息，其实1000个学生，就10个Class，Class是外部状态，其他如姓名，生日，成绩，是内部状态
  * 一个对象很多字段，但有一组字段是所有对象都一样的，1000个对象，可能这一组字段就几种情况，这一组字段，就是外部状态，其他的字段，是内部状态

> Flyweight模式就是解决外部状态的问题，用一个对象，提供很多个虚拟对象

```java

//树的对象千千万，但不怕，这里走了个极端，树成了无状态对象，其实可以有内部状态，
//如位置xy和树龄age，都可以作为内部状态使用，而非方法参数
public class Tree{
    RealTree realTree;
    public void display(int x, int y, int age){

    }
}

///树的外部状态，其实就三个对象，柳树，杨树，槐树
//这里这几个字段说是固定不变的，可能有点牵强，不要太纠结
public class RealTree{
    public Leaf leaf;
    public Type type;
    public int 硬度;
    public int 果实;
}


public class TreeManager{
    public HashMap<String, RealTree> flyweights = ...;
    static{
      flyweights.put("杨树", new RealTree());
      ....
    }
    public RealTree getRealTree(String key){...}
    
}
```


## 9 备忘录模式

对象快照，例如在命令模式的撤销功能中，需要记住对象之前的状态，或者游戏的进度保存  

备忘录模式就是由对象自己管理自己的save和restore

```java
public class GameRole{
    
	private String currentEnemy;
	private String currentFriend;
	private int currentX, currentY;
	private int currentAttachPower;
	
	public Memento createMemento(){
		return new Memento(currentX, currentY);
	}
	
	public void setMemento(Memento memento){
		this.currentX = memento.currentX;
		this.currentY = memento.currentY;
	}
}

public static class Memento{
	private int currentX, currentY; //当前位置坐标，需要保存和还原
}

```


## 10 原型模式和深拷贝浅拷贝

深拷贝与浅拷贝问题中，会发生深拷贝的有java中的8种基本类型以及他们的装箱类型，另外还有String类型。其余的都是浅拷贝

```java
public class Prototype implements Cloneable {  
    private ArrayList list = new ArrayList();  
    public Prototype clone(){  
        Prototype prototype = null;  
        try{  
            prototype = (Prototype)super.clone();  
            prototype.list = (ArrayList) this.list.clone();  
        }catch(CloneNotSupportedException e){  
            e.printStackTrace();  
        }  
        return prototype;   
    }  
}
```

* 你需要弄清楚的问题：
  * 你的对象到底要对任何一个域拷贝到什么程度
  * 域的类型是否实现了clone，无论是你实现还是JDK实现，怎么实现的，你得知道
  * 就上例而言：由于ArrayList不是基本类型，所以成员变量list，不会被拷贝，需要我们自己实现深拷贝，幸运的是java提供的大部分的容器类都实现了Cloneable接口。所以实现深拷贝并不是特别困难。