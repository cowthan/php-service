# 线程协作


本文重点是线程的协作，如线程并发的执行一个任务，线程A等待线程B完成后再接着执行，线程A和B交替的执行某个任务

## 1 join

线程A等待线程B执行完，在A中调用B.join()  
A中的B.join()可以被中断  
B也可以被中断  
B被中断后，run方法继续执行直到返回，此刻A的join依旧有效
A被中断后，或者join到期后，run方法也继续执行，所以可能需要加条件判断


```

public class C7 {
	
	public static class Sleeper implements Runnable{

		@Override
		public void run() {
			System.out.println("Sleeper：我先睡个5秒...");
			try {
				Thread.sleep(5000);
			} catch (InterruptedException e) {
				//e.printStackTrace();
				System.out.println("Sleeper---谁他妈吵醒我！");
			}
			System.out.println("Sleeper：我醒了!");
		}
	}
	
	public static class Guest implements Runnable{
		
		Thread sleeperThread;
		
		public Guest(Thread sleeperThread){
			this.sleeperThread = sleeperThread;
		}
		
		@Override
		public void run() {
			System.out.println("Guest：我是客人，我在这坐着等Sleeper醒来再说话");
			try {
				sleeperThread.join();
			} catch (InterruptedException e) {
				//e.printStackTrace();
				System.out.println("Guest：别打扰我等待，我得等他睡醒了啊！");
			}
			System.out.println("Guest：这哥终于睡醒了！");
		}
		
	}
	
	public static void main(String[] args) {
		Thread sleeper = new Thread(new Sleeper());
		Thread guest = new Thread(new Guest(sleeper));
		sleeper.start();
		guest.start();
		
		///注掉下面这段，则sleeper会睡5秒，不注掉，3秒后就会打断sleeper的睡眠：被interrupt的是sleep方法
//		ScheduledExecutorService exec = Executors.newScheduledThreadPool(2);
//		exec.schedule(new Runnable() {
//			public void run() {
//				sleeper.interrupt();
//			}
//		}, 3, java.util.concurrent.TimeUnit.SECONDS);
//		
//		///注掉下面这段，则guest会等5秒，不注掉，2秒后就会打断等待：被interrupt的是join方法
//		exec.schedule(new Runnable() {
//			public void run() {
//				guest.interrupt();
//			}
//		}, 2, java.util.concurrent.TimeUnit.SECONDS);
	}
	
}
```

想join到线程t，就得调用t.join()，这个方法类似sleep，会阻塞在这里，也可以interrupt，在这里的语义就是，我要等待线程t执行完再继续执行，在此之前我都等待。

join()：挂起当前线程，等待目标线程， t.join()，这里t是目标线程  
join(millis，nano)：超时参数，如果过了超时时间还是没等到，join就强制返回

sleeper和guest，guest需要在sleeper的线程对象上join，即sleeper.join()  
直到sleeper的run方法返回，线程执行完毕，才会激活join，guest才退出阻塞，继续往下执行    
sleeper结束时，sleeper.isAlive()为false

一个线程可以join到其他多个线程上，等到都结束了才继续执行

在当前线程c调用t.join()表示：
* c等待t执行完毕，期间c和t都可以被中断
* t必须是从c产生的线程


`有类似需求，可以考虑CyclicBarrier，栅栏，可能比join更合适`


## 2 线程通信，线程协作

sleep和yield算是协作，我让你让大家让，但太底层，而且顺序根本不可控，完全不能依赖  
join算是协作，等待嘛  
wait和notify，算是第一次出现的像样的协作  
队列  
Piper  

生产者和消费者模型  
——wait和notify版  
——队列模型

----java.utis.concurrent中的构件----  
CoundDownLatch  
CyclicBarrier  
DelayQueue  
PriorityBlockingQueue  
Exchanger  

-----------------------

程序可能在某个地方必须等待另一个线程完成任务，如果用无限循环来检查，这叫忙等待，很耗CPU


## 3 wait和notify，notifyAll


### 3.1 简介
obj.wait()和obj.notify()的作用：  
——wait释放obj上的锁，所以必须先持有锁了，通过synchronized  
——程序在这里开始阻塞，发出的信息就是：我在obj上等待，释放了obj的锁，并且等待notify  
——别的程序此时可以拿到obj上的锁了  
——notify也会先释放obj的锁，所以也得先拿到锁，obj.notify()会通知在obj上wait的对象  
——此时wait的地方会再拿到锁，继续往下执行  

```
public class Test1 {
	
	
	public static class Waiter implements Runnable{
		
		@Override
		public void run() {
			synchronized (this) {
				System.out.println("我拿到锁了，并且wait了，锁就释放了，并且等待锁");
				try {
					wait(3000);
					System.out.println("wait拿到锁，返回了");
				} catch (InterruptedException e) {
					e.printStackTrace();
				}
			}
		}
		
	}
	
	public static void main(String[] args) throws InterruptedException {
		
		Waiter w = new Waiter();
		ExecutorService exec = Executors.newCachedThreadPool();
		exec.execute(w);
		exec.shutdown();
//		Thread.sleep(2000);
//		synchronized (w) {
//			System.out.println("拿到锁了");
//			Thread.sleep(2000);
//			w.notify();
//			Thread.sleep(2000);
//			System.out.println("notify了，notify不会释放锁，走到同步代码最后才释放锁");
//		}
		
	}
	
}
```

```
public class Test2 {
	
	
	public static class Waiter implements Runnable{
		
		@Override
		public void run() {
			synchronized (this) {
				System.out.println("我拿到锁了，并且wait了，锁就释放了，并且等待锁");
				try {
					wait();
					System.out.println("wait拿到锁，返回了");
				} catch (InterruptedException e) {
					e.printStackTrace();
				}
			}
		}
		
	}
	
	public static void main(String[] args) throws InterruptedException {
		
		Waiter w = new Waiter();
		ExecutorService exec = Executors.newCachedThreadPool();
		exec.execute(w);
		exec.shutdown();
		Thread.sleep(2000);
		synchronized (w) {
			System.out.println("拿到锁了");
			Thread.sleep(2000);
			w.notify();
			Thread.sleep(2000);
			System.out.println("notify了，notify不会释放锁，走到同步代码最后才释放锁");
		}
		
	}
	
}
```

```

public class Test3 {
	
	
	public static class Waiter implements Runnable{
		
		@Override
		public void run() {
			synchronized (this) {
				System.out.println("我拿到锁了，并且wait了，锁就释放了，并且等待锁");
				while(true){
					try {
						wait();
						System.out.println("wait拿到锁，返回了");
					} catch (InterruptedException e) {
						e.printStackTrace();
					}
				}
			}
		}
		
	}
	
	public static void main(String[] args) throws InterruptedException {
		
		Waiter w = new Waiter();
		ExecutorService exec = Executors.newCachedThreadPool();
		exec.execute(w);
		exec.shutdown();
		Thread.sleep(2000);
		synchronized (w) {
			System.out.println("拿到锁了");
			Thread.sleep(2000);
			w.notify();
			Thread.sleep(2000);
			System.out.println("notify了，notify不会释放锁，走到同步代码最后才释放锁");
		}
		
	}
	
}
```
要从wait中恢复，也就是让wait返回，必须满足两个条件：
——有人在同一个对象上notify过  
——同一对象的锁被释放  
——而notify也需要操作锁，所以也必须持有锁，但这个操作不是释放锁，也就是说notify之后，wait返回之前，还可以执行代码，只要在同步块里，这个时机就是锁释放之前  

### 3.2 套路
一般用法是：
```
在一个线程里：
synchronized(obj){
	while(condition不符合){
		obj.wait(); //等到condition符合
	}
	//处理condition符合之后的逻辑
}


注意，这里有个有缺陷的wait的用法  
while(condition不符合){
	//Point-1：在这里，
	）线程可能切换了，切到另一个线程，并且导致了condition符合了（并notify，但此时这里并未，然后切换回来，wait了，就死锁了
	synchronized(obj){
		obj.wait(); 
	}
}



在另一个线程里：
synchronized(obj){
	///处理condition，让其符合条件
	obj.notify();
	//做些wait返回之前可能需要做的事
	//锁在这准备释放了，wait复活的两个条件都满足了
}
```
notify会唤醒最后一个在obj上wait的线程  
notifyAll会唤醒所有在obj上wait的线程


可以接受时间参数的wait：  
——给wait个超时时间


例子：很好的说明了wait和notify怎么用
```

class Car {
	  private boolean waxOn = false;
	  public synchronized void waxed() {
	    waxOn = true; // Ready to buff
	    notifyAll();
	  }
	  public synchronized void buffed() {
	    waxOn = false; // Ready for another coat of wax
	    notifyAll();
	  }
	  public synchronized void waitForWaxing()
	  throws InterruptedException {
	    while(waxOn == false)
	      wait();
	  }
	  public synchronized void waitForBuffing()
	  throws InterruptedException {
	    while(waxOn == true)
	      wait();
	  }
	}

	class WaxOn implements Runnable {
	  private Car car;
	  public WaxOn(Car c) { car = c; }
	  public void run() {
	    try {
	      while(!Thread.interrupted()) {
	        System.out.println("Wax On! ");
	        TimeUnit.MILLISECONDS.sleep(200);
	        car.waxed();
	        car.waitForBuffing();
	      }
	    } catch(InterruptedException e) {
	      System.out.println("Exiting via interrupt");
	    }
	    System.out.println("Ending Wax On task");
	  }
	}

	class WaxOff implements Runnable {
	  private Car car;
	  public WaxOff(Car c) { car = c; }
	  public void run() {
	    try {
	      while(!Thread.interrupted()) {
	        car.waitForWaxing();
	        System.out.println("Wax Off! ");
	        TimeUnit.MILLISECONDS.sleep(200);
	        car.buffed();
	      }
	    } catch(InterruptedException e) {
	      System.out.println("Exiting via interrupt");
	    }
	    System.out.println("Ending Wax Off task");
	  }
	}

	public class WaxOMatic {
	  public static void main(String[] args) throws Exception {
	    Car car = new Car();
	    ExecutorService exec = Executors.newCachedThreadPool();
	    exec.execute(new WaxOff(car));
	    exec.execute(new WaxOn(car));
	    TimeUnit.SECONDS.sleep(5); // Run for a while...
	    exec.shutdownNow(); // Interrupt all tasks
	  }
	} /* Output: (95% match)
	Wax On! Wax Off! Wax On! Wax Off! Wax On! Wax Off! Wax On! Wax Off! Wax On! Wax Off! Wax On! Wax Off! Wax On! Wax Off! Wax On! Wax Off! Wax On! Wax Off! Wax On! Wax Off! Wax On! Wax Off! Wax On! Wax Off! Wax On! Exiting via interrupt
	Ending Wax On task
	Exiting via interrupt
	Ending Wax Off task
	*///:~
```
* 总结：
    * 这个例子有点太场景化，也就是太特殊，如果要用同步队列来实现：
    * 上蜡和抛光可以看做是两条流水线，并且原则是：一条流水线处理一个队列里的消息
    * 等待上蜡的队列，等待抛光的队列，而两条流水线，就变成了在queue.take()或者poll()时阻塞，思路就清楚多了
    * 但是注意，不是所有的业务模型都可以映射到队列模型

### 3.3 为什么要在while(true)里wait
* 再说notifyAll和while(true){wait();}的问题
	* 一个或多个任务在一个对象上等待同一个条件，不管谁被唤醒之后，条件可能已经改变了，所以wait醒来之后还是要对条件进行检查
	* 在一个对象上wait的线程可能有多个，而且可能是针对不同的条件，所以被唤醒之后，需要检查一下是否自己需要的条件
	* 总之，wait醒来之后，需要检查自己需要的条件是否满足，不满足的话还得继续wait
	* 所以，在while(true)中wait通常是比较合理的做法
		* notify只能唤醒一个


### 3.4 notify和notifyAll的区别

在同一个对象上有多个线程wait，notify唤醒最后一个等待的，notifyAll唤醒所有



## 4 Condition，await和signal

```
import java.util.concurrent.*;
import java.util.concurrent.locks.*;



public class WaxOMatic2 {
  public static void main(String[] args) throws Exception {
    Car car = new Car();
    ExecutorService exec = Executors.newCachedThreadPool();
    exec.execute(new WaxOff(car));
    exec.execute(new WaxOn(car));
    TimeUnit.SECONDS.sleep(5);
    exec.shutdownNow();
  }
  
  
  public static class Car {
	  private Lock lock = new ReentrantLock();
	  private Condition condition = lock.newCondition();
	  private boolean waxOn = false;
	  public void waxed() {
	    lock.lock();
	    try {
	      waxOn = true; // Ready to buff
	      condition.signalAll();
	    } finally {
	      lock.unlock();
	    }
	  }
	  public void buffed() {
	    lock.lock();
	    try {
	      waxOn = false; // Ready for another coat of wax
	      condition.signalAll();
	    } finally {
	      lock.unlock();
	    }
	  }
	  public void waitForWaxing() throws InterruptedException {
	    lock.lock();
	    try {
	      while(waxOn == false)
	        condition.await();
	    } finally {
	      lock.unlock();
	    }
	  }
	  public void waitForBuffing() throws InterruptedException{
	    lock.lock();
	    try {
	      while(waxOn == true)
	        condition.await();
	    } finally {
	      lock.unlock();
	    }
	  }
	}

  public static class WaxOn implements Runnable {
	  private Car car;
	  public WaxOn(Car c) { car = c; }
	  public void run() {
	    try {
	      while(!Thread.interrupted()) {
	        System.out.println("Wax On! ");
	        TimeUnit.MILLISECONDS.sleep(200);
	        car.waxed();
	        car.waitForBuffing();
	      }
	    } catch(InterruptedException e) {
	      System.out.println("Exiting via interrupt");
	    }
	    System.out.println("Ending Wax On task");
	  }
	}

  public static class WaxOff implements Runnable {
	  private Car car;
	  public WaxOff(Car c) { car = c; }
	  public void run() {
	    try {
	      while(!Thread.interrupted()) {
	        car.waitForWaxing();
	        System.out.println("Wax Off! ");
	        TimeUnit.MILLISECONDS.sleep(200);
	        car.buffed();
	      }
	    } catch(InterruptedException e) {
	      System.out.println("Exiting via interrupt");
	    }
	    System.out.println("Ending Wax Off task");
	  }
	}
} /* Output: (90% match)
Wax On! Wax Off! Wax On! Wax Off! Wax On! Wax Off! Wax On! Wax Off! Wax On! Wax Off! Wax On! Wax Off! Wax On! Wax Off! Wax On! Wax Off! Wax On! Wax Off! Wax On! Wax Off! Wax On! Wax Off! Wax On! Wax Off! Wax On! Exiting via interrupt
Ending Wax Off task
Exiting via interrupt
Ending Wax On task
*///:~
```


这里要用到的锁就是ReentranLock



## 5 同步队列：接口BlockingQueue


### 5.1  简介

总而言之，同步队列可以使很多业务模型得以简化，处理问题的思路更简单

java提供了大量的BlockingQueue接口的实现，例子参考BlockingQueueTest
```
public interface BlockingQueue<E> extends Queue<E> {
		
	/**
	 * 添加，如果没有空间，会阻塞等待
	 * @param e
	 * @throws InterruptedException
	 */
	void put(E e) throws InterruptedException;
	
	/**
     * 移除并返回，如果empty，则阻塞等待
     */
    E take() throws InterruptedException;
    
    /**
     * 移除并返回，如果empty，会等待指定时间
     * @param timeout
     * @param unit
     * @return
     */
    E poll(long timeout, TimeUnit unit);
    
    /**
     * Returns the number of additional elements that this queue can ideally
     * (in the absence of memory or resource constraints) accept without
     * blocking, or {@code Integer.MAX_VALUE} if there is no intrinsic
     * limit.
     *
     * <p>Note that you <em>cannot</em> always tell if an attempt to insert
     * an element will succeed by inspecting {@code remainingCapacity}
     * because it may be the case that another thread is about to
     * insert or remove an element.
     *
     * @return the remaining capacity
     */
    int remainingCapacity();
    
    public boolean contains(Object o);
    
    /**
     * 把队列里的元素都移到Collection里
     * @param c
     * @return
     */
    int drainTo(Collection<? super E> c);
    int drainTo(Collection<? super E> c, int maxElements);
    
}

java.util.concurrent.BlockingQueue<Message> queue = 
			//new ArrayBlockingQueue<BlockingQueueTest.Message>(10, true); //true是access policy，表示FIFO，先进先出
			//new LinkedBlockingDeque<BlockingQueueTest.Message>(10);
			//new DelayQueue<BlockingQueueTest.Message>();
			//new PriorityBlockingQueue<BlockingQueueTest.Message>();
			//new SynchronousQueue<BlockingQueueTest.Message>(true);
			//new LinkedTransferQueue<BlockingQueueTest.Message>();
```
常规：  			
ArrayBlockingQueue  
LinkedBlockingDeque  

延迟队列：  
DelayQueue  

优先级队列  
PriorityBlockingQueue

不懂的队列：  
SynchronousQueue
LinkedTransferQueue

### 5.2 例子


例子1：PriorityQueue，优先级队列

```
import java.util.ArrayList;
import java.util.List;
import java.util.Queue;
import java.util.Random;
import java.util.concurrent.ExecutorService;
import java.util.concurrent.Executors;
import java.util.concurrent.PriorityBlockingQueue;
import java.util.concurrent.TimeUnit;

class PrioritizedTask implements Runnable, Comparable<PrioritizedTask> {
	private Random rand = new Random(47);
	private static int counter = 0;
	private final int id = counter++;
	private final int priority;
	protected static List<PrioritizedTask> sequence = new ArrayList<PrioritizedTask>();

	public PrioritizedTask(int priority) {
		this.priority = priority;
		sequence.add(this);
	}

	public int compareTo(PrioritizedTask arg) {
		return priority < arg.priority ? 1 : (priority > arg.priority ? -1 : 0);
	}

	public void run() {
		try {
			TimeUnit.MILLISECONDS.sleep(rand.nextInt(250));
		} catch (InterruptedException e) {
			// Acceptable way to exit
		}
		System.out.println(this);
	}

	public String toString() {
		return String.format("[%1$-3d]", priority) + " Task " + id;
	}

	public String summary() {
		return "(" + id + ":" + priority + ")";
	}

	public static class EndSentinel extends PrioritizedTask {
		private ExecutorService exec;

		public EndSentinel(ExecutorService e) {
			super(-1); // Lowest priority in this program
			exec = e;
		}

		public void run() {
			int count = 0;
			for (PrioritizedTask pt : sequence) {
				System.out.println(pt.summary());
				if (++count % 5 == 0)
					System.out.println();
			}
			System.out.println();
			System.out.println(this + " Calling shutdownNow()");
			exec.shutdownNow();
		}
	}
}

class PrioritizedTaskProducer implements Runnable {
	private Random rand = new Random(47);
	private Queue<Runnable> queue;
	private ExecutorService exec;

	public PrioritizedTaskProducer(Queue<Runnable> q, ExecutorService e) {
		queue = q;
		exec = e; // Used for EndSentinel
	}

	public void run() {
		// Unbounded queue; never blocks.
		// Fill it up fast with random priorities:
		for (int i = 0; i < 20; i++) {
			queue.add(new PrioritizedTask(rand.nextInt(10)));
			Thread.yield();
		}
		// Trickle in highest-priority jobs:
		try {
			for (int i = 0; i < 10; i++) {
				TimeUnit.MILLISECONDS.sleep(250);
				queue.add(new PrioritizedTask(10));
			}
			// Add jobs, lowest priority first:
			for (int i = 0; i < 10; i++)
				queue.add(new PrioritizedTask(i));
			// A sentinel to stop all the tasks:
			queue.add(new PrioritizedTask.EndSentinel(exec));
		} catch (InterruptedException e) {
			// Acceptable way to exit
		}
		System.out.println("Finished PrioritizedTaskProducer");
	}
}

class PrioritizedTaskConsumer implements Runnable {
	private PriorityBlockingQueue<Runnable> q;

	public PrioritizedTaskConsumer(PriorityBlockingQueue<Runnable> q) {
		this.q = q;
	}

	public void run() {
		try {
			while (!Thread.interrupted())
				// Use current thread to run the task:
				q.take().run();
		} catch (InterruptedException e) {
			// Acceptable way to exit
		}
		System.out.println("Finished PrioritizedTaskConsumer");
	}
}

public class PriorityBlockingQueueDemo {
	public static void main(String[] args) throws Exception {
		Random rand = new Random(47);
		ExecutorService exec = Executors.newCachedThreadPool();
		PriorityBlockingQueue<Runnable> queue = new PriorityBlockingQueue<Runnable>();
		exec.execute(new PrioritizedTaskProducer(queue, exec));
		exec.execute(new PrioritizedTaskConsumer(queue));
	}
} /* (Execute to see output) */// :~

```

* 简介：
	* 基于优先级，优先级的排序规则，由你来实现，就是实现方法compareTo
	* 用到了add和take方法，和take配对的不是put吗
	* 队列的元素类型Task，必须实现Comparable来对优先级进行排序，以保证按优先级顺序来

PriorityBlockingQueue代码1000多行呢


例子2：DelayQueue，延迟队列

```
import static java.util.concurrent.TimeUnit.MILLISECONDS;
import static java.util.concurrent.TimeUnit.NANOSECONDS;

import java.util.ArrayList;
import java.util.List;
import java.util.Random;
import java.util.concurrent.DelayQueue;
import java.util.concurrent.Delayed;
import java.util.concurrent.ExecutorService;
import java.util.concurrent.Executors;
import java.util.concurrent.TimeUnit;

class DelayedTask implements Runnable, Delayed {
	private static int counter = 0;
	private final int id = counter++;
	private final int delta;
	private final long trigger;
	protected static List<DelayedTask> sequence = new ArrayList<DelayedTask>();

	public DelayedTask(int delayInMilliseconds) {
		delta = delayInMilliseconds;
		trigger = System.nanoTime() + NANOSECONDS.convert(delta, MILLISECONDS);
		sequence.add(this);
	}

	public long getDelay(TimeUnit unit) {
		return unit.convert(trigger - System.nanoTime(), NANOSECONDS);
	}

	public int compareTo(Delayed arg) {
		DelayedTask that = (DelayedTask) arg;
		if (trigger < that.trigger)
			return -1;
		if (trigger > that.trigger)
			return 1;
		return 0;
	}

	public void run() {
		System.out.println(this + " ");
	}

	public String toString() {
		return String.format("[%1$-4d]", delta) + " Task " + id;
	}

	public String summary() {
		return "(" + id + ":" + delta + ")";
	}

	public static class EndSentinel extends DelayedTask {
		private ExecutorService exec;

		public EndSentinel(int delay, ExecutorService e) {
			super(delay);
			exec = e;
		}

		public void run() {
			for (DelayedTask pt : sequence) {
				System.out.println(pt.summary() + " ");
			}
			System.out.println();
			System.out.println(this + " Calling shutdownNow()");
			exec.shutdownNow();
		}
	}
}

class DelayedTaskConsumer implements Runnable {
	private DelayQueue<DelayedTask> q;

	public DelayedTaskConsumer(DelayQueue<DelayedTask> q) {
		this.q = q;
	}

	public void run() {
		try {
			while (!Thread.interrupted())
				q.take().run(); // Run task with the current thread
		} catch (InterruptedException e) {
			// Acceptable way to exit
		}
		System.out.println("Finished DelayedTaskConsumer");
	}
}

public class DelayQueueDemo {
	public static void main(String[] args) {
		Random rand = new Random(47);
		ExecutorService exec = Executors.newCachedThreadPool();
		DelayQueue<DelayedTask> queue = new DelayQueue<DelayedTask>();
		// Fill with tasks that have random delays:
		for (int i = 0; i < 20; i++)
			queue.put(new DelayedTask(rand.nextInt(5000)));
		// Set the stopping point
		queue.add(new DelayedTask.EndSentinel(5000, exec));
		exec.execute(new DelayedTaskConsumer(queue));
	}
} /*
 * Output: [128 ] Task 11 [200 ] Task 7 [429 ] Task 5 [520 ] Task 18 [555 ] Task
 * 1 [961 ] Task 4 [998 ] Task 16 [1207] Task 9 [1693] Task 2 [1809] Task 14
 * [1861] Task 3 [2278] Task 15 [3288] Task 10 [3551] Task 12 [4258] Task 0
 * [4258] Task 19 [4522] Task 8 [4589] Task 13 [4861] Task 17 [4868] Task 6
 * (0:4258) (1:555) (2:1693) (3:1861) (4:961) (5:429) (6:4868) (7:200) (8:4522)
 * (9:1207) (10:3288) (11:128) (12:3551) (13:4589) (14:1809) (15:2278) (16:998)
 * (17:4861) (18:520) (19:4258) (20:5000) [5000] Task 20 Calling shutdownNow()
 * Finished DelayedTaskConsumer
 */// :~
```

* 要点：
	* 队列的元素类型：需要实现Delayed接口，包括getDelay()和compareTo()两个方法
	* getDlay决定了任务是否到时可以运行
	* compareTo决定了队列会排序，并且队列头就是第一个应该被运行的任务
	


* 带着问题看代码：
	* 普通队列在take()这里阻塞，有新任务put进来，则被唤醒，所以只要有任务，就不会阻塞
	* DelayQueue即使有消息，也会阻塞，表示任务都没到时间，这种阻塞，何时被唤醒？
		* DelayQueue内部有个PriorityQueue
		* 参考下面的代码，就是DelayQueue的take方法实现
			* 取出队列头（peek，看看，不删除）
			* 如果是null，说明队列空，则Condition.await()
			* 如果不空，则根据任务的int delay = getDelay()方法，计算出delay时间，即多久之后任务可运行
				* 注意队列是根据你实现的compareTo排好序的，队列头就应该是第一个可以被运行的任务
				* getDelay()方法也是你实现的，在任务内部，你要记录延时时间（时间间隔），触发时间（具体时刻，计算出来并存好），所以随时随刻你都可以在getDelay()方法中计算出任务多久之后应该被触发
				* 如果delay <= 0，就返回(queue.poll())，任务可以执行了
				* 如果delay > 0，说明还没到时，则Condition.awaitNanos(delay)，阻塞指定时间后，再开始新一轮for循环，此时队列头的任务应该到时了
				* leader不知道是干啥的啊，先当成一直是null来理解的
	* ReentrantLock决定了多个线程在队列上take时，同一时刻只有一个线程会进入，所以只会有一个线程在await上阻塞，其他会在ReentrantLock上阻塞

```java
public E take() throws InterruptedException {
    final ReentrantLock lock = this.lock;
    lock.lockInterruptibly();
    try {
        for (;;) {
            E first = q.peek();
            if (first == null)
                available.await();
            else {
                long delay = first.getDelay(NANOSECONDS);
                if (delay <= 0)
                    return q.poll();
                first = null; // don't retain ref while waiting
                if (leader != null)
                    available.await();
                else {
                    Thread thisThread = Thread.currentThread();
                    leader = thisThread;
                    try {
                        available.awaitNanos(delay);
                    } finally {
                        if (leader == thisThread)
                            leader = null;
                    }
                }
            }
        }
    } finally {
        if (leader == null && q.peek() != null)
            available.signal();
        lock.unlock();
    }
    }
```


######
`Thread leader`的作用：没明白
Thread designated to wait for the element at the head of
the queue.  This variant of the Leader-Follower pattern
(http://www.cs.wustl.edu/~schmidt/POSA/POSA2/) serves to
minimize unnecessary timed waiting.  When a thread becomes
the leader, it waits only for the next delay to elapse, but
other threads await indefinitely.  The leader thread must
signal some other thread before returning from take() or
poll(...), unless some other thread becomes leader in the
interim.  Whenever the head of the queue is replaced with
an element with an earlier expiration time, the leader
field is invalidated by being reset to null, and some
waiting thread, but not necessarily the current leader, is
signalled.  So waiting threads must be prepared to acquire
and lose leadership while waiting.


### 5.3更多

* 一些实践中的应用
    * 安卓的Looper和Handler可以作为一个通用的
    * UI框架的大循环，是一个阻塞式的，循环处理的其实是UI上用户操作产生的事件，界面不会频繁刷新
    * 游戏框架的大循环，是一个非阻塞式的，每一轮循环必须完成刷新界面的操作，也必须处理用户的输入和游戏逻辑，并且有被限制的帧率
    * 有些特定的业务模型，可以用队列来简化，如抢单：（本来打算写写抢单逻辑，但发现单单从同步队列来考虑还不够）
    	* Model层的repo负责存储订单，提供增删改查操作，并对外通过事件总线发出通知
        * 线程A负责接收服务器来的订单消息，repo.add()
        * 线程B负责不断遍历所有订单，在后台刷新每个订单的倒计时等恒变状态，并调用repo.update(), repo.delete()
        * 主线程负责：思路断了
        * 重新调整思路：
        	* Model层不变
        	* 线程A不变
        	* 现在说谁能改变订单状态：
        		* 后台任务，主要是各种倒计时
        		* 用户操作
        	* 编不下去了


## 6 管道：PiperWriter和PiperReader

这个io在read上的阻塞是可以interrupt的，与之相比，System.in.read()就不可中断

## 7 java提供的并发构件


### 7.1 CountDownLatch

```
package com.cowthan.concurrent.c14;

//: concurrency/CountDownLatchDemo.java
import java.util.concurrent.*;
import java.util.*;

// Performs some portion of a task:
class TaskPortion implements Runnable {
	private static int counter = 0;
	private final int id = counter++;
	private static Random rand = new Random(47);
	private final CountDownLatch latch;

	TaskPortion(CountDownLatch latch) {
		this.latch = latch;
	}

	public void run() {
		try {
			doWork();
			latch.countDown();
		} catch (InterruptedException ex) {
			// Acceptable way to exit
		}
	}

	public void doWork() throws InterruptedException {
		TimeUnit.MILLISECONDS.sleep(rand.nextInt(2000));
		System.out.println(this + "completed");
	}

	public String toString() {
		return String.format("%1$-3d ", id);
	}
}

// Waits on the CountDownLatch:
class WaitingTask implements Runnable {
	private static int counter = 0;
	private final int id = counter++;
	private final CountDownLatch latch;

	WaitingTask(CountDownLatch latch) {
		this.latch = latch;
	}

	public void run() {
		try {
			latch.await();
			System.out.println("Latch barrier passed for " + this);
		} catch (InterruptedException ex) {
			System.out.println(this + " interrupted");
		}
	}

	public String toString() {
		return String.format("WaitingTask %1$-3d ", id);
	}
}

public class CountDownLatchDemo {
	static final int SIZE = 100;

	public static void main(String[] args) throws Exception {
		ExecutorService exec = Executors.newCachedThreadPool();
		// All must share a single CountDownLatch object:
		CountDownLatch latch = new CountDownLatch(SIZE);
		for (int i = 0; i < 10; i++)
			exec.execute(new WaitingTask(latch));
		for (int i = 0; i < SIZE; i++)
			exec.execute(new TaskPortion(latch));
		System.out.println("Launched all tasks");
		exec.shutdown(); // Quit when all tasks complete
	}
} /* (Execute to see output) */// :~

```

* 适用于：
	* 一组子任务并行执行，另一组任务等待着一组完成才进行，或等待某个条件完成才进行
		* 并行执行的任务数，或者等待的这个条件，可以抽象成倒数，倒数到0，则另一组任务就可以继续执行
    * 一个任务会被分解成多个子任务x，y，z
    * 其中一个子任务B会等待其他几个子任务完成才会继续执行
    * 所以提供一个CountDownLatch对象，并设置初始值
    	* 任务B在CountDownLatch对象上await：latch.await();
    	* 每完成一个子任务，就在CountDownLatch对象上倒数一次：latch.countDown();
		* 直到倒数到0，await的对象就会被唤醒
		* 任务B可以有多个

* 限制：
	* 只能用一次，如果要用多次，参考CyclicBarrier


### 7.2 CyclicBarrier

例子
```
package com.cowthan.concurrent.c14;

import java.util.ArrayList;
import java.util.List;
import java.util.Random;
import java.util.concurrent.BrokenBarrierException;
import java.util.concurrent.CyclicBarrier;
import java.util.concurrent.ExecutorService;
import java.util.concurrent.Executors;
import java.util.concurrent.TimeUnit;

class Horse implements Runnable {
	private static int counter = 0;
	private final int id = counter++;
	private int strides = 0;
	private static Random rand = new Random(47);
	private static CyclicBarrier barrier;

	public Horse(CyclicBarrier b) {
		barrier = b;
	}

	public synchronized int getStrides() {
		return strides;
	}

	public void run() {
		try {
			while (!Thread.interrupted()) {
				synchronized (this) {
					strides += rand.nextInt(3); // Produces 0, 1 or 2
				}
				barrier.await();
			}
		} catch (InterruptedException e) {
			// A legitimate way to exit
		} catch (BrokenBarrierException e) {
			// This one we want to know about
			throw new RuntimeException(e);
		}
	}

	public String toString() {
		return "Horse " + id + " ";
	}

	public String tracks() {
		StringBuilder s = new StringBuilder();
		for (int i = 0; i < getStrides(); i++)
			s.append("*");
		s.append(id);
		return s.toString();
	}
}

class HorseRace {
	static final int FINISH_LINE = 75;
	private List<Horse> horses = new ArrayList<Horse>();
	private ExecutorService exec = Executors.newCachedThreadPool();
	private CyclicBarrier barrier;

	public HorseRace(int nHorses, final int pause) {
		barrier = new CyclicBarrier(nHorses, new Runnable() {
			public void run() {
				StringBuilder s = new StringBuilder();
				for (int i = 0; i < FINISH_LINE; i++)
					s.append("="); // The fence on the racetrack
				System.out.println(s);
				for (Horse horse : horses)
					System.out.println(horse.tracks());
				for (Horse horse : horses)
					if (horse.getStrides() >= FINISH_LINE) {
						System.out.println(horse + "won!");
						exec.shutdownNow();
						return;
					}
				try {
					TimeUnit.MILLISECONDS.sleep(pause);
				} catch (InterruptedException e) {
					System.out.println("barrier-action sleep interrupted");
				}
			}
		});
		for (int i = 0; i < nHorses; i++) {
			Horse horse = new Horse(barrier);
			horses.add(horse);
			exec.execute(horse);
		}
	}

}

public class CyclicBarrierDemo {
	public static void main(String[] args) {
		int nHorses = 3;  //几匹马
		int pause = 200;  //等多久走一步
		new HorseRace(nHorses, pause);
	}
}

```

* 适用于：
    * 某个人物要等待多个任务并行进行，直到都完成，才会执行
    * 可以重用
    * 不得不说，CyclicBarrier还有点不好理解，看了demo代码还是没整明白
    	* 怎么是horse在barrier上await呢
    	* CyclicBarrier构造怎么还得传入必须await的线程个数呢

* 介绍
	* 构造：barrier = new CyclicBarrier(n, new Runnable(){})
		* 参数1：计数值，当有线程在barrier上await时，计数减一，n个线程都await了，计数就成0了，栅栏动作就会执行
		* 参数2：叫做栅栏动作，计数到0时，会自动执行

* CyclicBarrierDemo讲解：
	* 栅栏动作做的事情是：
		* 打印线路，打印终点
		* 打印每匹马当前的位置
		* 判断是否有马走到终点，有则提示夺冠，并结束所有线程（shutdownNow)
	* 栅栏动作执行完后，计数又会重置，此时
		* 每匹马再向前走一步，距离是随机数
		* 走完之后，await一下
		* 所有马都走完一步，await倒数计数值又是0了，再激活栅栏动作
	* 如此循环
	* 所以，栅栏动作等所有子任务都await了，才运行，此时所有子任务都阻塞，子任务等栅栏动作完成，计数自动重置，再被唤醒

* 总结：
	* 构造时，传入计数值和栅栏动作
	* 计数值减一操作由子任务的await完成
	* 栅栏动作在计数值为0时激活，并且运行完会自动重置计数值，并唤醒await的线程们


* 更多：
	* 思考：如果没有CyclicBarrier，仿真赛马你会怎么实现？
	* 你的实现会考虑起始和终结的情况吗？宣布夺冠之后，所有的马都能立即停止前进吗？统计开始和结束时，所有马的状态保持前后一致吗？
	* 提示1：CyclicBarrier的子任务，会在await上等待栅栏动作的结束，并且await是可以被interrupt的
	* 提示2：赛场统计是由栅栏动作完成的，此动作会在每一批马都前进一步之后，所有马都await，栅栏动作开始统计


