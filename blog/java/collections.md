# 集合


# 1 集合关系网


* 关系网：
    * 数组  Arrays.asList就成了List
	* 接口Collection
	    * 3个分支：List，Set，Queue
	    * List两个分支：Sequential（代表作是LinkedList）和RandomAccess（ArrayList，Vector，Stack）
	        * 用时要考虑是频繁插入，还是频繁访问
	        * LinkedList可以快速插入删除，因为只是节点的操作，但下标访问（随机访问）比较慢，它的方法也更多
	            * 提供了支持栈，队列，双端队列的方法
	        * RandomAccess分支可以快速根据下标访问，但长度变化代价比较昂贵，往中间插入，移除也比较费时
	    * Set两个分支：排序的（代表作TreeSet，LinkedHashSet）和无序的（HashSet）
	        * 不允许重复
	        * 能够迅速判断是否包含某元素
	        * 元素类型必须考虑hash，虽然可能不需要你写什么
	        * HashSet底层是HashMap（android-23），value全是HashSet本身
	        * LinkedHashSet：`按照被添加的顺序保存对象`，继承自HashSet，底层是LinkedHashMap，而LinkedHashMap也是继承自HashMap，并配合一个双端链表解决有序问题，所以Hash相关的操作其实都在HashMap里
	        * TreeSet：`按照比较结果来升序保存对象，所以元素必须实现了Comparable`，底层是TreeMap
	      
	    * Queue两个分支：LinkedList实现了一般化的队列接口，先进者先出，PriorityQueue基于Comparator等进行优先级排序，优先级高者先出
	    * CopyOnWrite, Concurrent支持
    * 接口Map
        * 两个分支：SortedMap（代表作TreeMap，LinkedHashMap）和无序的（HashMap，IdentityHashMap，WeakHashMap）
        * 键不能重复，键还得能算出hash值
        * Tree相关是基于二叉查找树，TreeMap基于红黑树
        * Hash相关是基于散列，包括LinkedHashMap，其实散列操作也是继承自HashMap，顺序或者LRU序由双端链表来实现
        * LinkedHashMap有点特殊，可以用来实现Lru缓存
    * 同步支持：
        * CopyOnWrite的List和Set
        * ConcurrentMap
        * 阻塞队列
    * 涉及到的其他东西
        * 泛型
        * 同步的和非同步的
        * 排序，Comparable和Comparator
        * Arrays, Arrays2, Collections, Collections2
        * Map的底层实现，Hash的原理
    * 扩展：
        * Guava的集合库


* 综合：
    * LinkedList，ArrayList，Vector
    * Stack，Queue，Dequeue
    * HashSet，LinkedHashSet，TreeSet
    * HashMap，LinkedHashMap，TreeMap，IdentityHashMao，WeakHashMap
    * Vector和HashTable是线程同步的
	* 新代码中不应该使用过时的Vector，HashTable，Stack
    
# 2 关于ArrayList

构造Collection：
```java
Collection<Integer> collection = new ArrayList<>(10);  //参数是capacity，不是size
Collection<Integer> collection2 = new ArrayList<>();  //参数默认值就是10
Collection<Integer> collection3 = new ArrayList<>(collection);		 //类型是java.util.ArrayList
Collection<Integer> collection4 = Arrays.asList(1,2,3,4,5);
Collection<Integer> collection5 = Arrays.<Integer>asList(1,2,3,4,5);
Collection<Integer> collection6 = Arrays.asList(new Integer[]{5,6,7,8});  //类型是：java.util.Arrays$ArrayList，所以一般是将这个返回作为new ArrayList的参数

List<Integer> list = new ArrayList<Integer>(10);
List<Integer> list = new LinkedList<Integer>();   //没法带capacity参数，因为根本不用事先分配长度
 
Set<Integer> set = new HashSet<Integer>();
Set<Integer> set = new TreeSet<Integer>();
Set<Integer> set = new LinkedHashSet<Integer>();

Map<String, String> map = new HashMap<String, String>();
Map<String, String> map = new TreeMap<String, String>();
Map<String, String> map = new LinkedHashMap<String, String>();
```
* 需要注意的是:Arrays.asList
    * 返回的其实是java.util.Arrays$ArrayList类型，是一个受限的类型，底层是数组，不支持delete等操作，所以尽量别用
    * Arrays.<Integer>asList(1,2,3,4,5); 这种语法叫显式类型说明

长度：
```
size()
isEmpty()
clear()
capacity和size
```

添加：
```
add 
addAll
```

查找：
```
contains
containsAll
indexOf
```

删除
```
remove(T)，基于equals
remove(index)
removeAll()，基于equals
```

截取
```
subList(from, to_exclude)，截取出来的list，肯定满足containsAll，并且不受sort和shuffle影响
```

排序，打乱
```
Collections.sort(List, Comparator)
Collection.shuffle()
```

交集
```
retainAll(list1, list2)，取交集，依赖于equals方法
```

遍历
```
//Iterator只能前向移动
Iterator<Integer> it = arraylist.iterator();
while(it.hasNext()){
	Integer i = it.next();
	System.out.println(i);
}

//ListIterator可以双向移动
ListIterator<Integer> it2 = arraylist.listIterator();
while(it2.hasNext()){
	Integer i = it2.next();
	int prevIndex = it2.previousIndex();
	Integer prev = it2.previous();
	int nextIndex = it2.nextIndex();
	Integer next = it2.next();
	it2.hasPrevious();
	it2.hasNext();
	it2.remove();
	System.out.println(i);
}

//遍历A
for(Integer i: arraylist){
	System.out.println(i);
}

//遍历B
for(int i = 0; i < arraylist.size(); i++){
	System.out.println(arraylist.get(i));
}

A：需要耗费一个迭代器 的开销，但获取元素的时间复杂度是O(1)，速度快，但耗内存
B：不需要额外的空间，但get(i)时间复杂度是O(n)，速度慢（除非是ArrayList，get(i)就是O(1)

所以：
ArrayList：推荐方式B
LinkedList：推荐方式A
```



# 3 关于LinkedList


提供了支持栈，队列，双端队列的方法

把这些方法分类列出来吧，好找
```
getFirst

remove
removeFirst
removeLast

peek()  
poll()
offer()

element()

add
addLast
```

# 4 关于Stack

有个java.util.Stack，除了提供基本的：
push，peek，pop，empty等操作，还提供了随机访问

这里自己基于LinkdedList写一个，只提供栈的最小可用子集：
按照编程思想里所说的，java.util.Stack设计欠缺，基于LinkedList能产生更好的Stack，所以我们这个更好
```
public class Stack<T> {
	
	private LinkedList<T> storage = new LinkedList<>();
	
	public void push(T v){
		storage.addFirst(v);
	}
	
	public T peek(){
		return storage.getFirst();
	}
	
	public T pop(){
		return storage.removeFirst();
	}
	
	public boolean empty(){
		return storage.isEmpty();
	}
	
	public String toString(){
		return storage.toString();
	}

	public static void main(String[] args) {
		
		Stack<String> stack = new Stack<String>();
		for(String s: "My dog has fileas".split(" ")){
			stack.push(s);
		}
		
		while(!stack.empty()){
			System.out.println(stack.pop() + " ");
		}
		
	}
}
```

# 5 关于Set

* 特性：
    * 不允许重复对象
    * 可以快速查询某个对象是否存在，专门对快速查找做了优化，set.contains(t)
    * 常用HashSet，HashSet没顺序，TreeSet有顺序，按照对象的compare来
    * TreeSet元素类型必须实现了Comparable，构造方法也可以传入个Comparator，如元素类型是String，但不想用默认的字典序来排序
    	* Set<String> wors = new TreeSet<>(String.CASE_INSENSITIVE_ORDER);	
```
set.contains(t)
set.containsAll(another set)
set.remove(t)
set.removeAl(another set)
```
可以用foreach遍历

# 6 关于Map



* 概论
    * HashMap：基于hashCode方法，这个是最纯的map，不考虑其他特性，所以最快
        * 插入和查询键值对的开销都是固定的
        * 优化措施：可以通过构造器设置容量和负载因子
            * 一个HashMap 实例有两个影响它性能的因素：初始大小和加载因子（load factor）
            * 当哈希表的大小达到初始大小和加载因子的乘积的时候，哈希表会进行 rehash操作
            * 如果在一个HashMap 实例里面要存储多个映射关系时，我们需要设置足够大的初始化大小以便更有效地存储映射关系而不是让哈希表自动增长让后rehash，造成性能瓶颈
            * 如果你知道有N个键值对，则可以：map = new HashMap<String, Foo>((int)Math.ceil(N / 0.7))
            * 这里加载因子默认值是0.7
            * 安卓里的HashMap的装填因子DEFAULT_LOAD_FACTOR = .75F，默认值，sdk-24
            * threshold = (int)Math.min(newCapacity * loadFactor, MAXIMUM_CAPACITY + 1);
            * 何时rehash？其实是resize
            * if ((size >= threshold) && (null != table[bucketIndex])) { resize(2 * table.length);... }
            * 也就是说如果HashMap的capacity是100，那你put了75个元素之后，就会重新分配底层数组，newCapacity=200
    * LinkedHashMap：遍历时，顺序是插入次序，或者是LRU次序（最近最少使用），可在构造器中选择
    	* 继承HashMap，所以还是用HashMap那一套来实现散列
    	* 内部有一个双向链表，初始化LinedhashMap时，可以指定按照什么顺序遍历
    	* 如果是插入顺序，则双端链表存的就是插入顺序，迭代器会按插入顺序返回
    	* 如果是Lru顺序，则get方法会导致元素移到header
        * 如果需要对Map频繁遍历，而查找操作较少，推荐这个
        * linkedMap = new LinkedHashMap<Integer, String>(16, 0.75, true)，以LRU排序
        * 最少使用的会出现在队列前面，便于定期清理元素
    * TreeMap：排序的，基于Comparable
        * 基于红黑树
        * 带有subMap方法，返回一个子树
	* WeakHashMap:对象保存周期
	* ConcurrentHashMap：多线程，避免加锁
	* IdentityHashmap：判定键的等价策略
	    * 使用==进行键的比较，而不是比较散列映射

Map又叫关联数组，字典，在其他语言里看到AssociateArray，Dict，都是一个东西

先自己写一个Dict，实现键值对的存储和查找，参考Dict类

```
import org.ayo.lang.JsonUtilsUseFast;

public class Dict<K, V> {

	public static class Entry<K, V>{
		public K k;
		public V v;
		
		public Entry(){
			
		}
		
		public Entry(K kk, V vv){
			k = kk;
			v = vv;
		}
	}
	
	private Entry<K, V>[] pairs;  //键值对作为一个object[2]存储，所有键值对就是个
	private int index;
	
	public Dict(int length){
		pairs = new Entry[length];
	}
	
	public void put(K key, V value){
		if(index >= pairs.length){
			throw new ArrayIndexOutOfBoundsException();
		}
		pairs[index++] = new Entry(key, value);
	}
	
	public V get(K key){
		for(int i = 0; i < index; i++){
			if(key.equals(pairs[i].k)){
				return pairs[i].v;
			}
		}
		return null;
	}
	
	public int size(){
		return index;
	}
	
	@Override
	public String toString() {
		return JsonUtilsUseFast.toJson(pairs, true);
	}
	
	
	public static void main(String[] args) {
		
		Dict<String, String> map = new Dict<String, String>(10);
		map.put("1", "一");
		map.put("2", "贰");
		map.put("3", "叁");
		map.put("4", "肆");
		map.put("5", "伍");
		System.out.println(map.size());
		System.out.println(map.toString());
		System.out.println(map.get("4"));
		
	}
}
```
这里只做个说明，完全没考虑查找效率，长度扩展，键重复问题


散列


Dict中，get方法是在数组中遍历，线性查找，HashMap用的是散列码，哈希

* 关于散列
    * 散列码是相对唯一的，用来代表对象的int值
    * 是根据对象的某些信息进行转换生成的
    * java中的支持就是hashCode()方法
    * 所以Dict需要考虑给key生成散列码之后，怎么根据散列存储和快速访问

一个可以作为key的类型定义，需要考虑hashCode()和equals()两个方法
参考hash.Groundhog这个类

先得考虑equals，然后equals相等的两个对象，hashCode()也应该相等
Map里会根据equals来确保键不重复


Object默认的equals比较的是对象地址
Object默认的hashCode也是基于对象地址

* equals方法要满足的5个条件：
    * 自反性：x.equals(x)，一定返回true
    * 对称性：x.equals(y)的结果一定等于y.eqauls(x)
    * 传递性：x.equals(y)为true，y.equals(z)为true，则x.equals(z)一定为true
    * 一致性：多次调用，只要等价信息没变，返回结果一致
    * 有一个是null，结果就是false

* hashCode方法：
    * 并不需要总是能够返回唯一的标识码，这个可能不好理解
    * 首先要知道，哈希值应该依赖于对象的标识性字段，一般情况下，不能依赖于可变字段
        * 这里你要考虑的问题就是：如果一个字段值变了，你希望哈希值也跟着变吗
    * 生成哈希只并不追求唯一性，应该更追求速度
    * 哈希值如果不唯一，注意，有个桶位的概念，即哈希值对应的下标处，存的其实是个List，这个就是桶位
        * 至于是什么类型的List或者数组，后面看源码
        * List的元素类型，其实是entry
        * 桶位在这就暂时叫做BucketList吧，对应的是一个哈希作为下标取出的几个值
    * 即使哈希值唯一，放到Map里时，Map的底层数组大小也是有限定的，所以需要对哈希再处理
        * 例如Map底层数组设置为100个，你的哈希值是200，就得把200映射到100范围之内，
        * 最直观的方法是取余，但取余也是个耗时操作
    * 通过哈希值，拿到BucketList之后，还会通过key的equals方法找到最终确定的Value

* 结论：
    * hashCode要的是速度，不是唯一性，不过hashCode的值应该是均匀的，避免值都集中在一个区域内
    * 当然既有速度，又有唯一性，是很完美的，如果值的个数固定，map底层数组大小也就固定，完美哈希是有可能的
    * EnumSet和EnumMap就实现了完美哈希
    * Key的类型必须仔细定义hashCode和equals方法，使二者能唯一确定一个对象
    * 所以这又回到那个话题：空间换时间
    * 如果知道Value就是100个，怎么办呢，更一般的，要把一个List放到Map里，map可以怎么优化？
        * 参考linkedIn的优化：new HashMap(Math.ceiling(list.size() 乘 0.7))
        * 0.7在这里是负载因子

* 经验
    * equals中用到字段，一般也应该用于生成hashCode
    * 生成hashCode的一个公式，引自effecttive java
        * 定义个int result = 17
        * 对于每一个有意义的字段，计算出一个int c
        * 对result和每一个c：result = 37 x result + c
        * 各种字段f对应的c怎么计算：
            * boolean：c = f ? 1 : 0
            * byte char short int：c = (int)f
            * long：c = (int)(f^(f>>>32))
            * float：c =  Float.floatToIntBits(f)
            * double：long l = Double.doubleToLongBits(f)，回到long
            * Object：c = f.hashCode()
            * 数组：对每个元素应用此规则
```
public class Student{
	public int id;
	public String name;
	
	public int hashCode(){
		int result = 17;
		result = 37*result + name.hashCode();
		result = 37*result + id;
		return result;
	}

}
```


# 7 关于Queue

## 7.1 先进先出：典型队列

Queue接口：LinkedList实现了Queue接口，主要是offer，peek，poll，element

offer：插入队尾，失败返回false，好像还和capacity-restrick有关，这个不会改变capacity大小  
add：插入队尾，但是可以改变capacity

element：拿到队头，但不remove，无则抛异常  
remove：拿到队头，同时remove，无则抛异常   NoSuchElementException

peek：拿到队头，但不remove，无则返回null  
poll：拿到队头，同时 remove，无则null

常用：  
Queue<String> queue = new LinkedList<String>();  
将LinkedList窄化为Queue接口


## 7.2 优先级队列：基于排序

* 简介：
    * 优先级最高先出，PriorityQueue的排序基于Comparator，或者默认的自然排序
    * 内部维护了一个堆，在插入时，会排序
    * 允许重复


## 7.3 Deque（双向队列）

LinkedList支持双端队列的方法，但java里没有显式的定义这么个接口，因为不太常用，一般不会在两端都放元素，然后又需要从两端获取元素

自己定义：
```
public class Deque<T> {
	
	private LinkedList<T> deque = new LinkedList<T>();
	public void addFirst(T e){
		deque.addFirst(e);
	}
	
	public void addLast(T e){
		deque.addLast(e);
	}
	
	public T getFirst(){
		return deque.getFirst();
	}
	
	public T getLast(){
		return deque.getLast();
	}
	
	public T removeFirst(){
		return deque.removeFirst();
	}
	
	public T removeLast(){
		return deque.removeLast();
	}
	
	public int size(){
		return deque.size();
	}
	
	public String toString(){
		return deque.toString();
	}

}
```

# 8 Iterable接口：foreach基于此接口

所有Collection都可以foreach  
Map怎么foreach才最合适？？
```
for(Map.Entry entry: map.entrySet()){
	entry.getKey(), entry.getValue();
}
```

# 9 Collections提供的便利方法

```java
//Empty系列：内部其实都对应一个private static final的实现类，无法插入数据，因为都是immutalble
List<String> list = Collections.EMPTY_LIST;
Set<String> set = Collections.EMPTY_SET;
Map<String, String> map = Collections.EMPTY_MAP;

list = Collections.emptyList();
set = Collections.emptySet();
map = Collections.emptyMap();
Enumeration<String> enumeration = Collections.emptyEnumeration();
Collections.emptyIterator();
Collections.emptyListIterator();
Collections.emptyNavigableMap();
Collections.emptyNavigableSet();
Collections.emptySortedMap();
Collections.emptySortedSet();

//unmodifiable系列：不可变集合
Collection<String> c = new LinkedList<String>();
Collection<String> c1 = Collections.unmodifiableCollection(c);
list = Collections.unmodifiableList(List);

map = Collections.unmodifiableMap(Map);
NavigableMap<String, String> nmap = Collections.unmodifiableNavigableMap(NavigableMap<K, V>);
SortedMap<String, String> smap = Collections.unmodifiableSortedMap(SortedMap<K, V>);

set = Collections.unmodifiableSet(Set<String>);
SortedSet<String> sset = Collections.unmodifiableSortedSet(SortedSet<T>);
NavigableSet<String> nset = Collections.unmodifiableNavigableSet(NavigableSet<T>);

//synchronized系列：性能没有CopyOnWrite和ConcurrentMap好
c1 = Collections.synchronizedCollection(c);
List<String> list = Collections.synchronizedList(list);

map = Collections.synchronizedMap(Map);
SortedMap<String, String> smap = Collections.synchronizedSortedMap(SortedMap<K, V>);
NavigableMap<String, String> nmap = Collections.synchronizedNavigableMap(NavigableMap<K, V>);

set = Collections.synchronizedSet(Set<T>);
SortedSet<String> sset = Collections.synchronizedSortedSet(SortedSet);
NavigableSet<String> nset = Collections.synchronizedNavigableSet(NavigableSet<T>);

//singleton系列
Set<T> set = Collections.singleton(T t);
List<T> list = Collections.singletonList(T t);
Map<K, V> map = Collections.singletonMap(key, value);

//checked系列，避免List list = new ArrayList<String>(); list.add(1);这种不规范使用
Collections.checkedList(list, type);
```

```
排序
Collections.sort(list);

混排
Collections.shuffle(list)

反转
Collections.reverse(list)

用指定元素替换
Collections.fill(li,"aaa")

拷贝
如果目标list长度大，则剩余元素不会被覆盖
Collections.copy(list_src, list_dest)

最大最小
Collections.min(list) 
Collections.max(list)

查找子列表: 这里
int locations = Collections.indexOfSubList(list, subList)
int locations = Collections.lastIndexOfSubList (list, subList); 

根据指定的距离循环移动指定列表中的元素 
Collections.rotate(list,-1); 
 {112, 111, 23, 456, 231 }按-1旋转之后是：111,23,456,231,112
```

# 10 数组

java有三种类型：  
基本数据类型8种  
普通对象  
数组对象

初始化时必须知道大小

Arrays里的操作，个人认为Arrays最重要的作用是提供了对数组的反射

```java

//填充数组
Arrays.fill(array, 5);

//将数组的第2和第3个元素赋值为8
Arrays.fill(array, 2, 4, 8);

//对数组的第2个到第6个进行排序进行排序
Arrays.sort(array,2,7);

//比较数组元素是否相等
Arrays.equals(array1, array2)

//查找
Arrays.binarySearch(array1, 9)
```


# 11 多线程支持


### List支持：CopyOnWriteArrayList

Vector被废弃了，你应该使用CopyOnWriteArrayList  

> CopyOnWrite容器即写时复制的容器。通俗的理解是当我们往一个容器添加元素的时候，不直接往当前容器添加，而是先将当前容器进行Copy，复制出一个新的容器，然后新的容器里添加元素，添加完元素之后，再将原容器的引用指向新的容器。这样做的好处是我们可以对CopyOnWrite容器进行并发的读，而不需要加锁，因为当前容器不会添加任何元素。所以CopyOnWrite容器也是一种读写分离的思想，读和写不同的容器。


add时，会加锁，即一次就拷贝出一份，写完下一个写  
read时，不用加锁，如果此时有人正写，读到的就是旧数据
```java
public boolean add(E e) {
    final ReentrantLock lock = this.lock;
    lock.lock();
    try {
        Object[] elements = getArray();
        int len = elements.length;
        Object[] newElements = Arrays.copyOf(elements, len + 1);
        newElements[len] = e;
        setArray(newElements);
        return true;
    } finally {
        lock.unlock();
    }
}

public E get(int index) {
    return get(getArray(), index);
}
```

* 缺点：
    * 写时，会同时存在两个底层容器对象，GC时间过长，这时，应该考虑使用ConcurrentHashMap
    * 数据不是实时一致的，如果需要实时一致，写入马上就能读到，就不要用CopyOnWrite了。

* 具体参考：
    * http://www.cnblogs.com/dolphin0520/p/3938914.html
    * http://blog.csdn.net/wind5shy/article/details/5396887
    * http://blog.csdn.net/imzoer/article/details/9751591


### Set支持：CopuOnWriteArraySet

和CopyOnWriteArrayList一个道理


### Map支持：

HashTable被废弃了

现在应该使用ConcurrentHashMap和ConcurrentSkipListMap


ConcurrentHashMap是HashMap的线程安全版本，ConcurrentSkipListMap是TreeMap的线程安全版本

下面引自：http://www.infoq.com/cn/articles/ConcurrentHashMap

`为什么HashMap线程不安全`

因为多线程环境下，使用HashMap进行put操作会引起死循环，导致CPU利用率接近100%，所以在并发情况下不能使用HashMap，如以下代码

```java
final HashMap<String, String> map = new HashMap<String, String>(2);
Thread t = new Thread(new Runnable() {
    @Override
    public void run() {
        for (int i = 0; i < 10000; i++) {
            new Thread(new Runnable() {
                @Override
                public void run() {
                    map.put(UUID.randomUUID().toString(), "");
                }
            }, "ftf" + i).start();
        }
    }
}, "ftf");
t.start();
t.join();

HashMap使用链表解决冲突，put时也会遍历这个链表，这个线程遍历着，那个线程
往里插入，可能造成死循环，个人理解

```

`为什么HashTable不行了？`
HashTable容器使用synchronized来保证线程安全，但在线程竞争激烈的情况下HashTable的效率非常低下。因为当一个线程访问HashTable的同步方法时，其他线程访问HashTable的同步方法时，可能会进入阻塞或轮询状态。如线程1使用put进行添加元素，线程2不但不能使用put方法添加元素，并且也不能使用get方法来获取元素，所以竞争越激烈效率越低。


`ConcurrentHashMap用的什么技术？锁分段`
HashTable容器在竞争激烈的并发环境下表现出效率低下的原因是所有访问HashTable的线程都必须竞争同一把锁，那假如容器里有多把锁，每一把锁用于锁容器其中一部分数据，那么当多线程访问容器里不同数据段的数据时，线程间就不会存在锁竞争，从而可以有效的提高并发访问效率，这就是ConcurrentHashMap所使用的锁分段技术，首先将数据分成一段一段的存储，然后给每一段数据配一把锁，当一个线程占用锁访问其中一个段数据的时候，其他段的数据也能被其他线程访问。


`用法`
V putIfAbsent(K key,V value)
如果不存在key对应的值，则将value以key加入Map，否则返回key对应的旧值
等价于：
```java
if (!map.containsKey(key)) 
   return map.put(key, value);
else
   return map.get(key);
```

boolean remove(Object key,Object value)
只有目前将键的条目映射到给定值时，才移除该键的条目。这等价于清单3 的操作
等价于：
```java
if (map.containsKey(key) && map.get(key).equals(value)) {
   map.remove(key);
   return true;
}
return false;
```

boolean replace(K key,V oldValue,V newValue)
只有目前将键的条目映射到给定值时，才替换该键的条目
等价于：
```java
if (map.containsKey(key) && map.get(key).equals(oldValue)) {
   map.put(key, newValue);
   return true;
}
return false;
```

V replace(K key,V value)
等价于：
```java
if (map.containsKey(key)) {
   return map.put(key, value);
}
return null;
```

2 我们也可以自己尝试实现一个CopyOnWriteMap

```java
import java.util.Collection;
import java.util.Map;
import java.util.Set;
 
public class CopyOnWriteMap<K, V> implements Map<K, V>, Cloneable {
    private volatile Map<K, V> internalMap;
 
    public CopyOnWriteMap() {
        internalMap = new HashMap<K, V>();
    }
 
    public V put(K key, V value) {
 
        synchronized (this) {
            Map<K, V> newMap = new HashMap<K, V>(internalMap);
            V val = newMap.put(key, value);
            internalMap = newMap;
            return val;
        }
    }
 
    public V get(Object key) {
        return internalMap.get(key);
    }
 
    public void putAll(Map<? extends K, ? extends V> newData) {
        synchronized (this) {
            Map<K, V> newMap = new HashMap<K, V>(internalMap);
            newMap.putAll(newData);
            internalMap = newMap;
        }
    }
}
```

写时，加锁，并新建底层容器，然后重新指向这个新容器  
读时，不加锁，如果正在写，读到的就是旧数据

懂了这个道理，实现CopyOnWriteLinkedList也就简单了


### Queue支持：BlockingQueue

http://www.infoq.com/cn/articles/java-blocking-queue/

```
插入：
add(e)： 抛出异常  IllegalStateException("Queue full")  	
offer(e)： 不抛异常，返回false
put(e)	： 阻塞
offer(e,time,unit)：超时则退出

移除：
remove()	  抛出异常  NoSuchElementException
poll()	 不抛异常，返回null
take()	 阻塞
poll(time,unit) 超时则退出


检查方法：
element()	 抛出异常
peek()     不抛异常，返回特殊值
```

* 七种阻塞队列
    * ArrayBlockingQueue ：一个由数组结构组成的有界阻塞队列
    * LinkedBlockingQueue ：一个由链表结构组成的有界阻塞队列
    * PriorityBlockingQueue ：一个支持优先级排序的无界阻塞队列
    * DelayQueue：一个使用优先级队列实现的无界阻塞队列
    * SynchronousQueue：一个不存储元素的阻塞队列
    * LinkedTransferQueue：一个由链表结构组成的无界阻塞队列
    * LinkedBlockingDeque：一个由链表结构组成的双向阻塞队列




