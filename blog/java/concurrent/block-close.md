# 线程的阻塞和关闭


本节重点说说线程什么时候会阻塞，如何关闭

## 1 让出时间片

`Thread.yield();`  
通知并建议线程调度器，我已经做完了主要工作，时间片你可以分给别人了  
即使调用了这个，还是可能没有切换时间片，或者切换了，但是还是给了当前线程

`Thread.sleep(1000);`  
TimeUnit.SECOND.sleep(1);
让当前线程进入睡眠状态，程序就阻塞在这里了
这个的表现应该是比yield良好多了

但这两个的特性，都不应该过于依赖
因为系统对时间片的划分是不可依赖的
你的程序也不应对时间片的划分有什么依赖

## 2 任务的自我检查

有些任务会在循环中检查状态值，如canceled之类的，会自己退出任务
但有时我们需要任务更突然的终止任务

注意：如果有标志位canceled，isRunning等，这个一般是volatile的

```java
private volatile boolean isFinished = false;

while(!isFinished){
    //do sth
}
```


如果不想用标志位，还可以检查是否被中断
```java
while(!Thread.currentThread().isInterrupted()){
    //do sth
}
```


## 3 阻塞时终止

任务除了自我检查状态，也可能阻塞在sleep中，此时可能也需要将其终结

线程的状态：   
——new：已经创建完毕，且已经start，资源分配完毕，等待分配时间片了，这个状态只会持续很短的时间，下一步就会进入运行或者阻塞  
——run：就绪状态，只要给了时间片，就会运行，在任一时刻，thread可能运行也可能不运行  
——block：阻塞状态，程序本身能够运行，但有个条件阻止了它运行，调度器会忽略这个线程，直到跳出阻塞条件，重新进入就绪状态  
——dead：run()方法返回，或者被中断

都哪些方式可以进入block状态：  
——sleep：等时间到  
——wait：等notify  
——等待IO,如stream.read()   
——等待synchronized锁  
——等待Lock锁  


```
另外，如下的大循环也可以interrupt
while(!Thread.currentThread().isInterrupted()){
	ThreadLocalVariableHolder.increment();
	System.out.println(this);
	Thread.yield();
}
```

### 终结情形1：

```
//注意isCanceled是个boolean，而且一般会需要volatile修饰
public void run(){
	while(!isCanceled){
		//do some seriers work
	}
}
```
### 终结情形2：
```
//thread.interrupt()可以打断
//Executors得不到thread的引用，只能通过ExecutorService.shutdownNow()来打断
//如果能拿到Future，可以Future.cancel(true)来打断
//exec.execute(Runnable)看来是打断不了了，因为拿不到什么引用
//exec.submit()，还是能打断的，返回了Future
//本质上都是调用了thread.interrupt()

关于shutdown：
shutdown()：拒绝再接收新的task，但已有的task会执行到terminate  
shutdownNow()：禁止再接收新的task，已有task，在waiting的不会再start，已经执行的会尝试stop掉  

未shutdown状态：线程池还在运行，不管有没有running，waiting的task，都会一直等待add新task（通过execute或者submit）  
shutdown状态：执行完现有task，就会terminate

public void run(){
	while(!Thread.currentThread().isInterrupt()){   //或者用Thread.interrupted()判断
		//do some seriers work
	}
}

public void run(){
	while(true){
		Thread.sleep(1000); 
		//被interrupt会抛出异常，因为既然是阻塞，被意外终止，异常看似挺合理
		//do some seriers work
	}
}
```
### 终结情形3：终结不了的synchronized  
在等待synchronized的线程，不可以被interrupt  
但是注意，Lock可以尝试获取锁，并可以指定阻塞等待锁的时间限制

### 终结情形4：ReentrantLock.lockInterruptly()
```
ReentrantLock.lockInterruptly()，在这里获取不到锁，会阻塞，但是可以被interrupt方法中断

import java.util.concurrent.*;
import java.util.concurrent.locks.*;

class BlockedMutex {
  private Lock lock = new ReentrantLock();
  public BlockedMutex() {
    // Acquire it right away, to demonstrate interruption
    // of a task blocked on a ReentrantLock:
    lock.lock();
  }
  public void f() {
    try {
      // This will never be available to a second task
      lock.lockInterruptibly(); // Special call
      System.out.println("f()方法得到锁了？？");
    } catch(InterruptedException e) {
      System.out.println("f()方法没得到锁，而是被中断了，被interrupt了");
    }
  }
}

class Blocked2 implements Runnable {
  BlockedMutex blocked = new BlockedMutex();
  public void run() {
    System.out.println("等f()拿到ReentranLock");
    blocked.f();
    System.out.println("从f()返回了");
  }
}

public class Interrupting2 {
  public static void main(String[] args) throws Exception {
    Thread t = new Thread(new Blocked2());
    t.start();
    TimeUnit.SECONDS.sleep(1);
    System.out.println("调用了t.interrupt()");
    t.interrupt();
  }
} /* Output:
Waiting for f() in BlockedMutex
Issuing t.interrupt()
Interrupted from lock acquisition in f()
Broken out of blocked call
*///:~
```


### 终结情形5：IO密集型阻塞
```
在等待InputStream.read()的线程，不可以被interrupt
但是有个笨办法：关闭线程正在等待的底层IO资源，如关闭Socket
还有个更好的选择：nio，提供了更人性化的中断，被阻塞的nio通道会自动响应中断
```

## 4 实例

### 无法被打断的例子

```java
public static void main(String[] args) throws InterruptedException {
	Thread t = new Thread(new Runnable() {
		
		@Override
		public void run() {
			while(true){
				System.out.println("go on...");
			}
		}
	});
	
	t.start();
	
	Thread.sleep(2000);
	t.interrupt();
	
	while(true){
		Thread.yield();
	}
}

如果一个Runnable没有cancel类的标志位检查，也没有检查isInterrupt()，调用interrupt()会怎么地？  
是关不掉的，但这种形式的无限循环，一般不会出现在真实场景里
真实场景什么样？一般是一个无限循环，但里面会阻塞（等待条件成熟），有跳出条件  

```

### 处理InterrupttedException

线程被中断之后，会发异常InterrupttedException，有时需要清理资源，参考类c10.InterruptingIdiom
	
```java

class NeedsCleanup {
  private final int id;
  public NeedsCleanup(int ident) {
    id = ident;
    System.out.println("NeedsCleanup " + id);
  }
  public void cleanup() {
    System.out.println("Cleaning up " + id);
  }
}

class Blocked3 implements Runnable {
  private volatile double d = 0.0;
  public void run() {
    try {
      while(!Thread.interrupted()) {
        // point1
        NeedsCleanup n1 = new NeedsCleanup(1);
        // Start try-finally immediately after definition
        // of n1, to guarantee proper cleanup of n1:
        try {
          System.out.println("Sleeping");
          TimeUnit.SECONDS.sleep(1);
          // point2
          NeedsCleanup n2 = new NeedsCleanup(2);
          // Guarantee proper cleanup of n2:
          try {
            System.out.println("Calculating");
            // A time-consuming, non-blocking operation:
            for(int i = 1; i < 2500000; i++)
              d = d + (Math.PI + Math.E) / d;
            System.out.println("Finished time-consuming operation");
          } finally {
            n2.cleanup();
          }
        } finally {
          n1.cleanup();
        }
      }
      System.out.println("Exiting via while() test");
    } catch(InterruptedException e) {
      System.out.println("Exiting via InterruptedException");
    }
  }
}

public class InterruptingIdiom {
  public static void main(String[] args) throws Exception {
    long delay = 2000;
    Thread t = new Thread(new Blocked3());
    t.start();
    TimeUnit.MILLISECONDS.sleep(delay);
    t.interrupt();
  }
} /* Output: (Sample)
NeedsCleanup 1
Sleeping
NeedsCleanup 2
Calculating
Finished time-consuming operation
Cleaning up 2
Cleaning up 1
NeedsCleanup 1
Sleeping
Cleaning up 1
Exiting via InterruptedException
*///:~

```
