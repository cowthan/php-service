# 安全性失败-锁


首先需要注意的是锁会让并行变为串行，但安全和效率，这两个之间没有折衷，安全第一，但我们要追求的必须是高效安全

* 本文涉及到：
    * 原子操作
    * 临界区
    * 锁
    * 信号量


## 1 共享受限资源

什么时候会出现共享受限资源的冲突？  
有一份数据摆在这里，多个worker线程都对其进行修改，状态就可能会乱了  

总之，每次访问一个资源时，从进去到出来，都要保证数据的一致性

基本上所有保护共享受限资源的方法，都是序列化对受限资源的访问（同步化），也就是程序到这里就变成串行了，加个锁保证同时只有一个线程访问，这种机制就叫互斥量

如果你改变一个对象的状态是一个复杂的过程：
* 这期间你最好保证不要出现任何被打断的可能


## 2 原子类

原子操作被用来写无锁的代码，避免同步

原子操作不是同步化的，而是避免了同步化：  
——普通的运算操作，如果要依赖原子性，要谨慎使用，至少编程思想里不推荐的，除非非常懂JVM，能编写JVM，编程思想就是这个意思  
——但是可以使用Atom系列类来保证安全

有两部分内容：  
——普通的运算操作的原子性，如加减乘除，这个很难搞懂，你知道a+b是不是原子操作？  
——Atom系列类，提供了一套原子操作，基本还是有保障的

### 2.1 原子操作

普通运算的原子性：暂时不做研究了  
a++不是原子操作  
+-*/也不是原子操作  
x = x + 1  =也不是原子操作  

想了解更多，再看一遍编程思想

### 2.2 原子类

原子类是可以信赖的，可以用来做性能调优，避免写同步代码，避免序列化访问资源


```
public class EvenGenerator extends IntGenerator {
  private int currentEvenValue = 0;
  public int next() {
    ++currentEvenValue; // Danger point here!
    ++currentEvenValue;
    return currentEvenValue;
  }
  public static void main(String[] args) {
    EvenChecker.test(new EvenGenerator());
  }
}
```

原子操作就是一步能完成的操作：
```
AtomicInteger currentEvenValue = new AtomicInteger(0);
return currentEvenValue.addAndGet(2);  //这里给value增加了2，并返回其值
```
* 注意：
    * 说是原子操作被用来构建Concurrent包，不建议你用
    * 用了原子操作，就省了很多加锁操作

* 都有什么
	* AtomicInteger
	* AtomicLong
	* AtomicReference


## 3 Synchronized临界区

例子： 
```
public class SynchronizedEventGenerator extends IntGenerator {
	private int currentEvenValue = 0;

	public int next() {
		synchronized (this) {
			++currentEvenValue; 
			++currentEvenValue;
			return currentEvenValue;
		}
	}

	public static void main(String[] args) {
		EvenChecker.test(new SynchronizedEventGenerator());
	}
}
```
```
public synchronized int next() {
	++currentEvenValue; // Danger point here!
	++currentEvenValue;
	return currentEvenValue;
}

相当于：
public int next() {
	synchronized (this) {
		++currentEvenValue; 
		++currentEvenValue;
		return currentEvenValue;
	}
}

```
* synchronized的锁始终是加在一个对象上
    * 直接修饰一个方法时，就是this
    * 如果多个对象访问同一资源，锁就得加到一个外部的静态对象上
    * 作用于静态方法/属性时，锁住的是存在于永久的Class对象

* synchronized的原理：
    * 每个object对象都有一个内置的锁
    * 所有对象都自动含有单一的锁，JVM负责跟踪对象被加锁的次数
    * 在任务（线程）第一次给对象加锁的时候， 计数变为1
    * 每当这个相同的任务（线程）在此对象上获得锁时，计数会递增
    * 只有首先获得锁的任务（线程）才能继续获取该对象上的多个锁
    * 每当任务离开时，计数递减，当计数为0的时候，锁被完全释放
    * 在HotSpot中JVM实现中，锁有个专门的名字：对象监视器
    * 更深入的讲：
    * 当多个线程同时请求某个对象监视器时，对象监视器会设置几种状态用来区分请求的线程
    * Contention List：所有请求锁的线程将被首先放置到该竞争队列，是个虚拟队列，不是实际的Queue的数据结构
    * Entry List：EntryList与ContentionList逻辑上同属等待队列，ContentionList会被线程并发访问，为了降低对 ContentionList队尾的争用，而建立EntryList
    * Contention List中那些有资格成为候选人的线程被移到Entry List 
    * Wait Set：那些调用wait方法被阻塞的线程被放置到Wait Set
    * OnDeck：任何时刻最多只能有一个线程正在竞争锁，该线程称为OnDeck
  
  

注意：
wait,notify和synchronized的用法
    

## 4 锁

```
import java.util.concurrent.locks.*;

public class MutexEvenGenerator extends IntGenerator {
	private int currentEvenValue = 0;
	private Lock lock = new ReentrantLock();

	public int next() {
		lock.lock();
		try {
			++currentEvenValue;
			Thread.yield(); // Cause failure faster
			++currentEvenValue;
			return currentEvenValue;
		} finally {
			lock.unlock();
		}
	}

	public static void main(String[] args) {
		EvenChecker.test(new MutexEvenGenerator());
	}
} 
```


比synchronized多了什么特性：  
——可以尝试获取锁，不必非得阻塞在这  
——提供了比synchronized更细粒度的控制  
——在实现链表遍历节点时，有个节点传递的加锁机制（锁耦合），在释放这个节点的锁之前，必须捕获下个节点的锁  
——synchronized引起的阻塞无法被interrupt方法中断，但ReentrantLock提供了可以被中断的机制  
——ReentrantLock.lockInterruptly()：如果得不到锁（被其他地方占用），就会阻塞，但是这个阻塞可以被interrupt()  

例子：
```
import java.util.concurrent.*;
import java.util.concurrent.locks.*;

public class AttemptLocking {
	private ReentrantLock lock = new ReentrantLock();

	public void untimed() {
		boolean captured = lock.tryLock();
		try {
			System.out.println("tryLock(): " + captured);
		} finally {
			if (captured)
				lock.unlock();
		}
	}

	public void timed() {
		boolean captured = false;
		try {
			captured = lock.tryLock(2, TimeUnit.SECONDS);
		} catch (InterruptedException e) {
			throw new RuntimeException(e);
		}
		try {
			System.out.println("tryLock(2, TimeUnit.SECONDS): " + captured);
		} finally {
			if (captured)
				lock.unlock();
		}
	}

	public static void main(String[] args) {
		final AttemptLocking al = new AttemptLocking();
		al.untimed(); // True -- lock is available
		al.timed(); // True -- lock is available
		// Now create a separate task to grab the lock:
		new Thread() {
			{
				setDaemon(true);
			}

			public void run() {
				al.lock.lock();
				System.out.println("acquired");
			}
		}.start();
		Thread.yield(); // Give the 2nd task a chance
		
		try {
			Thread.sleep(1000);
		} catch (InterruptedException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		al.untimed(); // False -- lock grabbed by task
		al.timed(); // False -- lock grabbed by task
	}
} /*
 * Output: tryLock(): true tryLock(2, TimeUnit.SECONDS): true acquired
 * tryLock(): false tryLock(2, TimeUnit.SECONDS): false
 */// :~
```
```
boolean captured = lock.tryLock();//不会阻塞，不管有没有得到锁，都往下执行
captured = lock.tryLock(2, TimeUnit.SECONDS); //会阻塞2秒，然后不管有没有得到锁，都往下执行
```

## 其他锁

其他高级锁，不好意思，我还没研究过

## 4 信号（Semaphore）

这个，也不好意思，我也没研究过
