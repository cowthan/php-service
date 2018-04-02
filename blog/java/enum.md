# 枚举

header 1 | header 2
---|---
row 1 col 1 | row 1 col 2
row 2 col 1 | row 2 col 2

![image](http://note.youdao.com/favicon.ico)
```
[link](http://note.youdao.com/)
```

- [x] dsfsdf
- [ ] sfsdfs


```math
E = mc^2
```
~~sdfsdf~~
++sfsdfsdf++
*sfsfsdf*
**sfasfs**

sdfasdf
---


```
gantt
dateFormat YYYY-MM-DD
section S1
T1: 2014-01-01, 9d
section S2
T2: 2014-01-11, 9d
section S3
T3: 2014-01-02, 9d
```



注解

http://blog.csdn.net/lmj623565791/article/details/43452969




## 1 基本使用

```java
package com.cowthan.enum2;

public enum Planet {
	
	MERCURY(3.302e+23, 2.439e6),  //水星
	VENUS(4.869e+24, 6.052e6),  //金星
	EARTH(5.975e+24, 6.378e6),  //
	MARS(6.419e+23, 3.393e6),   //火星
	JUPITER(1.899e+27, 7.149e7), //木星
	SATURN(5.685e+26, 6.027e7), //土星
	URANUS(8.683e+25,  2.556e7), //天王星
	NEPTUNE(1.024e+26, 2.477e7); //海王星
	
	/*
	 
	 枚举天生不可变，因此所有域都应该是final的
	  
	 */
	private final double mass;   //  kg
	private final double radius; //  meter
	private final double surfaceGravity; //    m/s^2
	public boolean fuck = false;
	
	private static final double G = 6.67300E-11;
	
	Planet(double mass, double radius) {
		this.mass = mass;
		this.radius = radius;
		surfaceGravity = G * mass / (radius * radius);
	}
	
	public double mass() {return mass;}
	public double radius() { return radius;}
	public double surfaceGravity() { return surfaceGravity;}
	public double surfaceWeight(double mass){
		return mass * surfaceGravity;
	}
	
	
	public static void main(String[] args) {
		double earthWeight = 100; //kg
		double mass = earthWeight / Planet.EARTH.surfaceGravity();
		for(Planet p: Planet.values()){
			System.out.printf("Weight on %s is %f%n", p, p.surfaceWeight(mass));
		}
	}
}

```

```
public enum Operation_1 {

	PLUS, MINUS, TIMES, DIVIDE;
	
	public double apply(double x, double y){
		
		switch(this){
		case PLUS: return x+y;
		case MINUS: return x-y;
		case TIMES: return x*y;
		case DIVIDE: return x/y;
		}
		throw new AssertionError("unknown op: " + this);  ///没有这句,编译不会通过
	}
	
}

```


## 2 抽象方法

```java
package com.cowthan.enum2;

/**
 * 特定于常量的方法实现
 *
 */
public enum Operation {

	PLUS("+") {
		@Override
		double apply(double x, double y) {
			return x + y;
		}
	}, 
	MINUS("-") {
		@Override
		double apply(double x, double y) {
			return x - y;
		}
	}, 
	TIMES("*") {
		@Override
		double apply(double x, double y) {
			return x * y;
		}
	}, 
	DIVIDE("/") {
		@Override
		double apply(double x, double y) {
			return x / y;
		}
	};
	
	private final String symbol;
	
	private Operation(String symbol) {
		this.symbol = symbol;
	}
	
	abstract double apply(double x, double y);
	
	@Override
	public String toString() {
		return symbol;
	}
}
```


## 3 enum操作

```
enum的静态方法：
Week[] values()：返回所有常量的数组
Week valueOf(String)：根据常量名，获得枚举常量


enum的实例方法：
name()：获取常量名
ordinal()：获取枚举常量在类型中的数字位置，从0开始


特别注意：
ordinal()是内置的，这个是序数，是为EnumSet和EnumMap设计的，程序员不应该依赖这个方法做有关下标的事

你应该用实例域代替序数，例如MONDAY(1)



```


## 4 EnumSet代替位域： 枚举的集合

什么是位域呢，就是常见的flag模式，或者标志位模式
```
public class Text{

	public static final int STYLE_BOLD          = 1 << 0; //1   0001
	public static final int STYLE_ITALIC        = 1 << 1; //2   0010
	public static final int STYLE_UNDERLINE     = 1 << 2; //4   0100
	public static final int STYLE_STRIKETHROUGH = 1 << 3; //8   1000
	
	public void applyStyles(int styles){
	}

}

text.applyStyles(STYLE_BOLD | STYLE_ITALIC);  

OR运算符可以将几个常量合并到一个集合中，
这就叫位域（bit field），可以认为位域是标志位的集合


EnumSet可以用单个long来实现，性能比得上位域
```

改为使用位域：
```
public class Text{
	public enum Style { BOLD, ITALIC, UNDERLINE, STRIKETHROUGH }
	
	//任何Set都可以传进来，但EnumSet是最佳的
	
	public void applyStyles(Set<Style> styles){
	}
}

怎么调用：text.applyStyles(EnumSet.of(Style.BOLD, Style.ITALIC));

```

## 5 EnumMap代替序数索引

避免使用enum.ordinal()作为数组下标，如果想根据enum的序数快速定位枚举，应该用EnumMap

EnumMap：键是枚举的Map
```
Map<Week, Set<Course>> courses = new EnumMap<Week, Set<Course>>(Week.class);

for(Week w: Week.values()){
	courses.put(w, new HashSet<Course>>);
}
```
指定了EnumMap的key类型之后，由于enum的常量个数是固定的，  
所以Enum最多有几个键也是固定的，  
所以可以实现完美哈希


## 6 枚举和String

Enum.valueOf(s)方法，根据常量名的字符串直接得到枚举常量  
如果toString被覆盖（默认返回常量名），则你需要下面这段代码来进行字符串和枚举常量的映射：
```
public enum Week {
	
	Monday;
	
	
	public String toString() {
		if(this == Week.Monday){
			return "周一";
		}
		return "";
	}
	
	private static final Map<String, Week> stringToEnum  = new HashMap<>();
	static{
		for(Week w: Week.values()){
			stringToEnum.put(w.toString(), w);
		}
	}
	
	public static void main(String[] args) {
		
		Week monday = Week.valueOf("Monday");   ///转换枚举常量name
		System.out.println(monday);
		
		Week monday2 = Week.stringToEnum.get("周一");  ///转换自定义字符串
		System.out.println(monday2);
		
	}

}
```


## 7 例子


策略枚举
 
 策略模式的枚举版，普通的策略模式是一个接口，具体实现类来提供具体算法
 
 
 需求：算每天的工资  
 每天上班标准是8小时，工资固定  
 超出部分是加班，工资1.5倍  
 平时超出8小时算加班  
 周末全算加班  
 
 你分析一下这个需求：  
 1 上班的这一天可能是工作日，周末，也有可能是其他节假日，而且加班工资可能也不一样  
 2 所以先定义个工作日类型，也可以看做薪资类型，主要处理加班工资的问题，这里是PayType
 

```
public enum PayRollDay {
	
	MONDAY(PayType.WEEKDAY),
	TUESDAY(PayType.WEEKDAY),
	WEDNESDAY(PayType.WEEKDAY),
	THURSDAY(PayType.WEEKDAY),
	FRIDAY(PayType.WEEKDAY),
	SATURDAY(PayType.WEEKEND),
	SUBDAY(PayType.WEEKEND);
	
	private final PayType payType;
	PayRollDay(PayType payType){
		this.payType = payType;
	}
	
	double pay(double hoursWorked, double payRate){
		return payType.pay(hoursWorked, payRate);
	}
	
	
	
	
	private enum PayType{
		WEEKDAY{
			double overtimePay(double hours, double payRate){
				return hours <= HOURS_PER_SHIFT ? 0 : (hours - HOURS_PER_SHIFT) * payRate / 2;
			}
		},
		
		WEEKEND{
			double overtimePay(double hours, double payRate){
				return (hours) * payRate / 2;
			}
		};
		
		
		private static final int HOURS_PER_SHIFT = 8;
		abstract double overtimePay(double hrs, double parRate);
		
		double pay(double hoursWorked, double payRate){
			double basePay = hoursWorked * payRate;
			return basePay + overtimePay(hoursWorked, payRate);
		}
		
	}
	
	
}
```

