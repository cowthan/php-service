# 活性失败-volatile


线程A对某变量值的修改，可能没有立即在线程B体现出来，称为活性失败。  
注意下面这个例子，在PC和安卓上运行结果可能不一样（安卓可能不需要volatile）


例子
```

public class VolatileTest extends  Thread {

	/**
	 * 使用了volatile，则1秒后，子线程会退出循环，因为在主线程将isRunning置位为false
	 */
	//private volatile boolean isRunning = true;
	
	/**
	 * 不使用volatile，1秒后，主线程置位isRunning为false，但主线程对isRunning的修改对子线程不可见，子线程看见的还是true，循环继续
	 * 
	 * 将setRunning方法设置为synchronized也可以达到volatile的效果，意思就是同步代码块保护的变量修改时也会直接刷新到主存
	 * 
	 */
	private boolean isRunning = true;
	
	public boolean isRunning(){
        return isRunning;
    }
    
	public void setRunning(boolean isRunning){
        this.isRunning= isRunning;
    }
    
	public void run(){
        System.out.println("进入了run...............");
        while (isRunning){}
        System.out.println("isUpdated的值被修改为为false,线程将被停止了");
    }
    public static void main(String[] args) throws InterruptedException {
        VolatileTest volatileThread = new VolatileTest();
        volatileThread.start();
        Thread.sleep(1000);
        volatileThread.setRunning(false);   //停止线程
    }
}
```

Volatile和原子性没什么直接关系  
如果变量被同步代码保护了，就不必考虑volatile

据effective java中描述，这个问题涉及到JVM对while(!flag)这种形式有一个提升的优化，即：
```
while(!flag){}

进行提升优化：

if(flag){
    while(true){}
}
```

怎么引出这个问题呢
```
public abstract class IntGenerator {
  private volatile boolean canceled = false;  
  public abstract int next();
  // Allow this to be canceled:
  public void cancel() { canceled = true; }
  public boolean isCanceled() { return canceled; }
} 
```
* 分析
    * 看这个类的canceled字段，在这里一个IntGenerator对象可以被多个EventChecker对象调用cancel()
    * 这样就在每个EventChecker的线程里，保留了一份对canceled的本地缓存，这个本地缓存可能是每个CPU一个
    * 在每个线程里调用修改cenceled的值，首先会保存到本地缓存，然后也会同步到主存里，据说这是规定，必须的
    * 但是其他线程通过isCanceled()读取它的值，是从本地缓存读，没被改变，即不可见，它已经看不见主存里的值了
    * 所以用volatile来修饰，保证每次对它的修改，都会同步到主存的同时，也会对所有其他线程的内存可见，或者就是保证对于volatile变量，不会在工作内存中拷贝一份，都是在主存中读写 
    * 整了半天，还是挺麻烦，推荐首选用同步来解决问题，volatile适用于只有一个字段可变的情况


下面的内容来自网页：http://www.cnblogs.com/MOBIN/p/5407965.html?hmsr=toutiao.io&utm_medium=toutiao.io&utm_source=toutiao.io


* 摘要
    * Volatile是Java提供的一种弱同步机制，当一个变量被声明成volatile类型后编译器不会将该变量的操作与其他内存操作进行重排序。
    * 在某些场景下使用volatile代替锁可以减少代码量和使代码更易阅读

* Volatile的特性
    * 可见性：当一条线程对volatile变量进行了修改操作时，其他线程能立即知道修改的值，即当读取一个volatile变量时总是返回最近一次写入的值
    * 原子性：对于单个voatile变量其具有原子性(能保证long double类型的变量具有原子性)，但对于i ++ 这类复合操作其不具有原子性(见下面分析)

* Volatile使用的前提
    * 对变量的写入操作不依赖变量的当前值，或者能够确保只有单一的线程修改变量的值
    * 该变量不会与其他状态变量一起纳入不变性条件中
    * 在访问变量时不需要加锁

原理：  
原因：Java内存模型(JMM)规定了所有的变量都存储在主内存中，主内存中的变量为共享变量，  
而每条线程都有自己的工作内存，线程的工作内存保存了从主内存拷贝的变量，  
所有对变量的操作都在自己的工作内存中进行，完成后再刷新到主内存中，  
回到例1，第18行号代码主线程(线程main)虽然对isRunning的变量进行了修改且有刷新  
回主内存中（`《深入理解java虚拟机》中关于主内存与工作内存的交互协议提到变量在工作  
内存中改变后必须将该变化同步回主内存`），但volatileThread线程读的仍是自己工作内存  
的旧值导致出现多线程的可见性问题，解决办法就是给isRunning变量加上volatile关键字。  

* volatile内存语义总结如下
    * 当线程对volatile变量进行写操作时，会将修改后的值刷新回主内存
    * 当线程对volatile变量进行读操作时，会先将自己工作内存中的变量置为无效，之后再通过主内存拷贝新值到工作内存中使用。



* Synchronized与volatile区别 
    * volatile只能修饰变量，而synchronized可以修改变量，方法以及代码块
    * volatile在多线程中不会存在阻塞问题，synchronized会存在阻塞问题
    * volatile能保证数据的可见性，但不能完全保证数据的原子性，synchronized即保证了数据的可见性也保证了原子性
    * volatile解决的是变量在多个线程之间的可见性，而sychroized解决的是多个线程之间访问资源的同步性

