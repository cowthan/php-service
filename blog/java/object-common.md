# 对象通用行为


本文说的是类的通用行为，要定义一个类，首先考虑本文提到的几个问题



## 0 前言

* 类的分类：
    * 活动实体，如Thread，安卓的Activity
        * 一个线程就是一个Thread对象，两个线程要相等，就得是同一个对象
        * 一个安卓界面就是一个Activity对象，两个界面要相等，就得是同一个对象
    * 值类，如Person
        * 一个学生可能对应好几个对象，要判断相等，可不能依赖于两个是不是同一个对象

* Java的对象
    * 普通对象
    * 数组对象




## 1 equals方法

判断两个对象是否相等，有几种情况：
* 1 活动实体
* 2 值类
* 3 Random类似的类，相等的逻辑是什么？能否产生同样的随机数序列？
* 4 Set，Map，List的相等逻辑
* 5 私有的类，包级私有的类：equals应该抛出异常，因为equals方法永远不应该调用


>值类一般需要覆盖equals方法，以实现自己的逻辑相等的概念

* equals的5个原则：
    * `自反性reflexive`：x.equils(x)必须返回true
    * `对称性symmetric`：x.equals(y)为true，y.equals(x)必为true
    * `传递性transitive`：x.equals(y)为true，y.equals(z)为true，则x.equals(z)必为true
    * `一致性consistent`：只要比较字段没有修改，x.equals(y)不管调多少次，都一致返回true或false
    * x.equals(null)必为false

EffectiveJava的作者确实给出了几个违反这几个原则的例子，此处不做过多描述


一个通用的的equals实现：

```java

//对于class T
@Override
public boolean equals(Object o){
    
    if(this == o) return true;
    if(!(o instanceof T)) return false;
    T t = (T)o;
    
    //对每个关键域进行检查，并要避免null
    if(!(field == t.field || (field != null && field.equals(t.field)))){
        return false;
    }
    
    //继续下一个field：任何一个不equals，都返回false
    //...
    
    //通过所有检查，返回true
    return true;
    
    
    //覆盖了equals方法，总是需要覆盖hashCode
}

```



## 2 hashCode方法

equals判断相等的两个对象，hashCode也应该一样

如果覆盖了equals而不覆盖hashCode，会发生什么？
例如对于Person类，equals被覆盖，同一个id的学生，equals为true，但hashCode没覆盖

Person p1 = new Person(1);
Person p2 = new Person(1);

p1.equals(ps)返回true

Map<Person, Course> map;

map.put(p1, course1);
map.put(p2, course2);

现在，map里有两个entry了，p2并不能覆盖p1，因为hashCode不一样

map.get(p2)会返回course1还是2呢，course2

map.get(new person(1))会返回什么？null，但你期望他返回什么呢


hashCode的通用实现：

* 注意：
    * 如果hash计算开销较大，应该缓存起来

```java

//注意，用于生成hashCode的字段，应该是所有用于equals比较的字段
//没有用于equals的，不要用于hashCode
//如果一个域的值是可以通过其他域计算出来的，就是冗余域，不要参与hash


@Override
public int hashCode(){
    int result = 17;
    
    result = 31 * result + int;  //byte, short, char, int都转为int
    result = 31 * result + (boolean ? 1 : 0);
    result = 31 * result + (int)(long ^ (long >>> 32));
    result = 31 * result + Float.floatToIntBits(float);
    
    //double
    long ld = Double.doubleToLongBits(f);
    result = 31 * result + (int)(ld ^ (ld >>> 32));
    
    //引用
    result = 31 * result + (ref == null ? 0 : ref.hashCode());
    
    //数组
    if(arr == null || arr.length == 0){
        result = 31 * result + 0;
    }else{
        取出重要元素，计算hash，或者如果每个元素都一样重要，则：
        result = 31 * result + Arrays.hashCode(arr);
    }
    
    return result;
}

```


## 3 toString

toString就是为了更好的打印，这里没什么需要多说的

## 4 Comparable接口

Comparable是类需要实现的接口  
Comparator可以作为一个策略，一个函数对象传入方法内  




## 5 clone方法

参考对象管理的第10节
