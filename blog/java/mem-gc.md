# 内存模型和GC


### 11 避免创建不必要的对象

1 String对象

String s = new String("aaa");
String s = "aaa";
第一行代码是不合适的，因为这行代码创建了一个新的String对象。  
参数"aaa"本身就是一个String对象  

2 有静态工厂，就不要new

如Boolean.valueOf(String)应该优先于new Boolean(String)

new必定会创建新对象，而静态工厂没有这个承诺

3 已知的不会修改的字段，做成的静态的

但却不建议延迟初始化，因为会使逻辑变的复杂


4 不太懂，Map.keySet方法返回的Set，其实是一个对象

并且这个Set对象是可变的

5 避免无意识的使用装箱类

装箱类是实实在在的对象，不像基本类型那么轻量级

```java
public static void main(String[] args){
    Long sum = 0L;
    for(long i = 0; i < Integer.MAX_VALUE; i++){
        sum += i;
    }
    sysou(sum);
}
```

这段代码，使用了Long，而不是long，会导致多创建大约2的31次方个Long实例（在sum+=i时）

6 对以上几条原则不要盲目，小对象还是可以随意创建的

小对象的构造器只做少量显式工作
小对象的回收也很廉价
创建附加的小对象，可以提升程序的清晰性，简洁性，功能性，这是好事




### 11 四种引用


### 12 内存模型和垃圾回收



1 清除过期引用


Java语言并不需要你随时将一个引用置位null以释放一个引用，
但下面这种情况，你还是得考虑：

```java
public class Stack{

    private Object[] elements;
    private int size = 0;
    private static final int DEFAULT_INITIAL_CAPACITY = 16;
    
    public Stack(){
        elements = new Object[DEFAULT_INITIAL_CAPACITY];
    }
    
    public void push(Object e){
        ensureCapacity();
        elements[size++] = e;
    }
    
    public Object pop(){
        if(size == 0){
            throw new EmptyStackException();
        }
        
        
        Object result = elements[--size]; 
        
        //问题就出在这里
        elements[size] = null;
        
        return result;
    }
    
    private void ensureCapacity(){
        if(elements.length == size)
            elements = Arrays.copyOf(elements, 2*size + 1);
    }

}

/*

这里，下标小于size的是数组的活动区域，如果活动区域之外还有引用，则就是过期的引用，需要清除，即置为null

*/


```

这里的启示就是：
1 JVM不知道你的对象是否有效
2 你自己才知道哪些个对象是否需要释放
    情况1：引用变量结束了生命周期，则也就自动释放了引用
    情况2: 引用变量还在，但你知道它已经过期了，则手动置null

2 缓存中的引用


情况1：考虑使用WeakHashMap，这种情况没搞太懂
情况2：开一个后台线程，清除过期缓存
情况3：添加新条目时，清除过期缓存，利用LinkedHashMap的removeEldestEntry方法



3 监听器和其他回调导致的内存泄露

注册回调，却没有取消注册
——可以使用弱引用保存回调，如作为WeakHashMap的键


4 大对象的释放

参考Bitmap.recycle()
参考TypeArray.recyle()


5 资源释放

文件
流
MediaPlayer
数据库连接
设备，如相机



6 终结方法：finalize

EJ第7条

