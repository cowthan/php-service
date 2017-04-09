# 线程和线程池

## 1 Java的线程


### 1.1 线程和线程池

Java的线程就是Thread，一个Thread对象，就是一个线程  
Runnable是一个接口，给Thread提供任务

这两个就是Java里最基本的线程


```
new Thread(Runnable, "thread-name").start();
```

但线程对于操作系统来说，是一种资源，既然是资源，就不能无限使用，同时线程也是一种重量级的对象，初始化也是个费事的工程，所以为了控制线程的数量，并且对已经初始化过的线程进行重用，我们需要用到线程池，Java为我们提供了线程池的实现，就是Executor。


下面看看Java的线程池：  

Executor：一个接口，其定义了一个接收Runnable对象的方法executor，其方法签名为executor(Runnable command)  

Executors：控制着一堆线程池  

ExecutorService：继承Executor，进行了扩展，如对Callable和Future的支持，shutdown等，这个是具有服务生命周期的Executor  
例如关闭，这东西知道如何构建恰当的上下文来执行Runnable对象，是一个比Executor使用更广泛的子类接口，其提供了生命周期管理的方法，以及可跟踪一个或多个异步任务执行状况返回Future的方法  

AbstractExecutorService：实现了ExecutorService，但没有实现executor方法  

ThreadPoolExecutor：实现了executor方法，这才是真正的线程池，Executors里的各种线程池，其实就是这个类的不同配置  


ScheduledExecutorService：一个接口，继承自ExecutorService, 一个可定时调度任务的接口  


ScheduledThreadPoolExecutor：ScheduledExecutorService的实现，父类是ThreadPoolExecutor，一个可定时调度任务的线程池


用法：Executors的每个方法都可以传入第二个参数，一个ThreadFactory对象
```
ExecutorService exec = Executors.newCachedThreadPool(); //线程数总是会满足所有任务，所有任务都是并行执行，同时抢时间片，而旧线程会被缓存和复用
ExecutorService exec = Executors.newFixedThreadPool(2); //两个线程同时运行，其他的会排队等待
ExecutorService exec = Executors.newSingleThreadExecutor(); //1个线程同时运行，即多个任务会串行执行
ExecutorService exec = Executors.newWorkStealingPool();  //不知道啥意思
ScheduledExecutorService exec = Executors.newScheduledThreadPool(10);//不知道啥意思
ScheduledExecutorService exec = Executors.newSingleThreadScheduledExecutor();//不知道啥意思

for(int i = 0; i < 5; i++){
	exec.execute(new LiftOff1()); //提交任务
}

//shutdown会关闭线程池入口，不能再提交新任务，但之前提交的，会正常运行到结束
//如果不关闭，线程池会一直开着，等待提交任务，进程也就不会关闭
exec.shutdown();  
```

定时，延时--Schduled
```
//使用newScheduledThreadPool来模拟心跳机制
public class HeartBeat {
    public static void main(String[] args) {
        ScheduledExecutorService executor = Executors.newScheduledThreadPool(5);  //5是corePoolSize
        Runnable task = new Runnable() {
            public void run() {
                System.out.println("HeartBeat.........................");
            }
        };
        executor.scheduleAtFixedRate(task,5,3, TimeUnit.SECONDS);   //5秒后第一次执行，之后每隔3秒执行一次
    }
}
```

### 1.2 ThreadFactory和Thread

先看ThreadFactory

```
//设置ThreadFactory：只有当需要新线程时，才会来这里调用，就是说ThreadFactory本身不管理线程池，只是给线程池干活（提供新线程）

ExecutorService exec = Executors.newFixedThreadPool(2, new ThreadFactory() {
	
	private int threadCount = 0;
	
	@Override
	public Thread newThread(Runnable r) {
		threadCount++;
		Thread tr = new Thread(r, "thread-from-ThreadFactory-" + threadCount);
		//这里可以给线程设置一些属性
		tr.setUncaughtExceptionHandler(new Thread.UncaughtExceptionHandler(){  
			@Override  
			public void uncaughtException(Thread t, Throwable e) {  
				e.printStackTrace();
			}  
		});  
		//tr.setDaemon(true);    //设置这个为true，则主线程退出时，子线程不管是否结束，都退出
		tr.setPriority(Thread.MAX_PRIORITY);  
		
		return tr;
	}
});
```

Thread里的线程属性：

`后台线程：`  
tr.setDaemon(true);      //设置这个为true，则主线程退出时，子线程不管是否结束，都退出
后台线程表示在程序后台提供一种通用服务的线程，且不是程序不可或缺的部分
当所有非后台线程结束了，后台线程也就结束了
isDaemon()判断是否后台线程
从后台线程创建的线程，自动默认是后台线程

`优先级：`  
tr.setPriority(Thread.MAX_PRIORITY);    
仅仅是执行频率较低，不会造成死锁（线程得不到执行）
JDK有十个优先级，但和操作系统映射的不是很好
windows有7个优先级，但不固定
Sun的Solaris有2的31次方个优先级
所以调用优先级时，安全的做法是只使用：MAX_PRIORITY, NORM_PRIORITY, MIN_PRIORITY

`线程名字：参数2`  
Thread tr = new Thread(r, "thread-name-" + threadCount);  

`全局异常`
全局异常是基于线程的，并且异常不能跨线程传递
```
tr.setUncaughtExceptionHandler(new Thread.UncaughtExceptionHandler(){  
	@Override  
	public void uncaughtException(Thread t, Throwable e) {  
		e.printStackTrace();
	}  
});  

而Thread.setUncaughtExceptionHandler是给所有线程都设置一个全局异常捕捉
```

`isAlive()：线程是否还活着`
这个会影响join  
run方法执行完毕，是否isAlive？  
线程被中断，是否isAlive？  



### 1.3 关于ThreadPoolExecutor



上面提到的线程池基本结构都是这个：
```
public ThreadPoolExecutor(int corePoolSize,
                          int maximumPoolSize,
                          long keepAliveTime,
                          TimeUnit unit,
                          BlockingQueue<Runnable> workQueue,
                          ThreadFactory threadFactory,  
                          RejectedExecutionHandler handler) //后两个参数为可选参数
                          
corePoolSize： 线程池维护线程的最少数量

maximumPoolSize：线程池维护线程的最大数量

keepAliveTime： 线程池维护线程所允许的空闲时间，超时则线程死，死到最少数量corePoolSize

unit： keepAliveTime的单位

workQueue： 线程池所使用的缓冲队列

threadFactory：创建新线程的方式，使用ThreadFactory创建新线程，默认使用defaultThreadFactory创建线程

handler： 线程池对拒绝任务的处理策略




```
corePoolSize：核心线程数，如果运行的线程少于corePoolSize，则创建新线程来执行新任务，即使线程池中的其他线程是空闲的  

maximumPoolSize:最大线程数，可允许创建的线程数，corePoolSize和maximumPoolSize设置的边界自动调整池大小：  
corePoolSize <运行的线程数< maximumPoolSize:仅当队列满时才创建新线程  
corePoolSize=运行的线程数= maximumPoolSize：创建固定大小的线程池  

keepAliveTime:如果线程数多于corePoolSize,则这些多余的线程的空闲时间超过keepAliveTime时将被终止  

unit:keepAliveTime参数的时间单位  

workQueue:保存任务的阻塞队列，与线程池的大小有关：  
  当运行的线程数少于corePoolSize时，在有新任务时直接创建新线程来执行任务而无需再进队列  
  当运行的线程数等于或多于corePoolSize，在有新任务添加时则选加入队列，不直接创建线程  
  当队列满时，在有新任务时就创建新线程  
  
threadFactory:使用ThreadFactory创建新线程，默认使用defaultThreadFactory创建线程  

handle:定义处理被拒绝任务的策略，默认使用ThreadPoolExecutor.AbortPolicy,任务被拒绝时将抛出RejectExecutorException


再看Executors里一堆new方法怎么用的：
```
public static ExecutorService newCachedThreadPool() {
    return new ThreadPoolExecutor(0, Integer.MAX_VALUE,
                                  60L, TimeUnit.SECONDS,
                                  //使用同步队列，将任务直接提交给线程
                                  new SynchronousQueue<Runnable>());
}


//线程池：指定线程个数
public static ExecutorService newFixedThreadPool(int nThreads) {
    return new ThreadPoolExecutor(nThreads, nThreads,
                                  0L, TimeUnit.MILLISECONDS,
                                  //使用一个基于FIFO排序的阻塞队列，在所有corePoolSize线程都忙时新任务将在队列中等待
                                  new LinkedBlockingQueue<Runnable>());
}


//单线程：基于一个固定个数的线程池，不管在哪里，实现串行执行，都是基于一个其他的线程池和一个队列
public static ExecutorService newSingleThreadExecutor() {
   return new FinalizableDelegatedExecutorService
                     	//corePoolSize和maximumPoolSize都等于，表示固定线程池大小为1
                        (new ThreadPoolExecutor(1, 1,
                                                0L, TimeUnit.MILLISECONDS,
                                                new LinkedBlockingQueue<Runnable>()));
}
```


分析：
```
private final AtomicInteger ctl = new AtomicInteger(ctlOf(RUNNING, 0));
BlockingQueue<Runnable> workQueue;

public void execute(Runnable command) {
    if (command == null)
        throw new NullPointerException();
    /*
     * Proceed in 3 steps:
     *
     * 1. If fewer than corePoolSize threads are running, try to
     * start a new thread with the given command as its first
     * task.  The call to addWorker atomically checks runState and
     * workerCount, and so prevents false alarms that would add
     * threads when it shouldn't, by returning false.
     *
     * 2. If a task can be successfully queued, then we still need
     * to double-check whether we should have added a thread
     * (because existing ones died since last checking) or that
     * the pool shut down since entry into this method. So we
     * recheck state and if necessary roll back the enqueuing if
     * stopped, or start a new thread if there are none.
     *
     * 3. If we cannot queue task, then we try to add a new
     * thread.  If it fails, we know we are shut down or saturated
     * and so reject the task.
     */
    int c = ctl.get();
    
    //当前正在工作的线程数 < 允许的线程数，则创建新线程，运行task
    if (workerCountOf(c) < corePoolSize) {
        if (addWorker(command, true))  ///有个Worker内部类，内部会调用ThreadFactory.newThread()
            return;
        c = ctl.get();
    }
    if (isRunning(c) && workQueue.offer(command)) {   
        int recheck = ctl.get();
        if (! isRunning(recheck) && remove(command))
            reject(command);             //调用RejectedExecutionHandler的handler.rejectedExecution(command, this);
        else if (workerCountOf(recheck) == 0)
            addWorker(null, false);   //return false;
    }
    else if (!addWorker(command, false))
        reject(command);  //handler.rejectedExecution(command, this);
}
```


### 1.4 Callable和Future的使用

Callable和Future：要的是那个call方法，future里放的是子线程的返回结果，get方法会阻塞等待返回，就是等call方法返回


Runnable不产生返回值，ExecutorService.execute(Runnable)，走的是run方法  
Callable产生返回值，ExecutorService.submit(Callable)，走的是call方法
```
用法1：submit Callable and get a Future, block in future.get()

interface ArchiveSearcher { String search(String target); }
class App {
    ExecutorService executor = ...
    ArchiveSearcher searcher = ...
    
    void showSearch(final String target) throws InterruptedException {
    
      Future future = executor.submit(new Callable() {
	      public String call() {
	          return searcher.search(target);
	      }}
	  );
	  
      displayOtherThings(); // do other things while searching
      
      try {
        displayText(future.get()); // use future---在这里会阻塞等待
      } catch (ExecutionException ex) { cleanup(); return; }
    }
}

用法2：execute a FutureTask, and get a 

FutureTask future = new FutureTask(new Callable<String>() {
    	public String call() {
    		return searcher.search(target);
		}
	}
);
executor.execute(future);

future.get()


关于Callable：能返回就给返回，不能返回就抛异常
public interface Callable<V> {
    V call() throws Exception;
}


关于Future：
public interface Future<V> {

    boolean cancel(boolean mayInterruptIfRunning);
    boolean isCancelled();
    boolean isDone();
    V get() throws InterruptedException, ExecutionException;
    V get(long timeout, TimeUnit unit)  throws InterruptedException, ExecutionException, TimeoutException;
}
```
1 boolean cancel(boolean mayInterruptIfRunning);

参数的意思：  
正在执行的task是否允许打断，如果是true，会打断，如果false，则允许in-progress的任务执行完

何时失败：  
已经运行完的task  
已经被cancel过的task  
无法被中断的任务  

怎么成功：  
还没start的任务，比如在等待的，可以cancel
正在running的任务，参数mayInterruptIfRunning指定了是不是可以尝试interrupt

副作用：  
只要cancel被调用了且返回true，isDone和isCancelled一直返回true


2 get：取回结果，如有必要，可以阻塞

可以阻塞就可以被interrupt  
get()没有超时时间    
get(1, TimeUnit.Second)：表示最多阻塞1秒，过了一秒就抛出超时异常    



### 1.5 线程池源码简单分析:Executor接口



```
public interface Executor {
    void execute(Runnable command);
}

```
意思就是给你一个command，你想让它在哪儿执行run  


Excutor能决定的事：
* 选择哪个线程
* 执行runnable

Excutor管不了的事：
* Callable，Future管不了
* 没有一个线程池，线程池可能需要自己写，跟Executor没关系
* 没法延时，定时的运行任务

例子：
看下面代码里的三个Executor的实现，取自java源码里的注释，这几行代码基本阐明了Executor的作用
```
public class C2 {
	
	public static class MyExecutors{
		
		public static Executor newDirectThreadPool(){
			return new DirectExecutor();
		}
		
		public static Executor newPerTaskPerThreadThreadPool(){
			return new ThreadPerTaskExecutor();
		}
		
		public static Executor newSerialThreadPool(){
			return new SerialExecutor(new DirectExecutor());
		}
	}
	
	/**
	 * an executor can run the submitted task immediately in the caller's thread
	 */
	public static class DirectExecutor implements Executor {
	    public void execute(Runnable r) {
	    	r.run();
	    }
	}
	
	/**
	 * spawns a new thread for each task
	 */
	public static class ThreadPerTaskExecutor implements Executor {
	    public void execute(Runnable r) {
	    	new Thread(r).start();
	    }
	}
	
	/**
	 * serializes the submission of tasks to a second executor
	 * 类似安卓的AsyncTask里的串行化实现
	 */
	public static class SerialExecutor implements Executor {
	    final Queue<Runnable> tasks = new ArrayDeque<>();
	    final Executor executor;
	    Runnable active;
	 
	    SerialExecutor(Executor executor) {
	      this.executor = executor;
	    }
	 
	    public synchronized void execute(final Runnable r) {
	      tasks.add(new Runnable() {
	        public void run() {
	          try {
	            r.run();
	          } finally {
	            scheduleNext();
	          }
	        }
	      });
	      if (active == null) {
	        scheduleNext();
	      }
	    }
	 
	    protected synchronized void scheduleNext() {
	      if ((active = tasks.poll()) != null) {
	        executor.execute(active);
	      }
	    }
	  }
	
	public static void main(String[] args) {
		Runnable task = new Runnable() {
			public void run() {
				try {
					Thread.sleep(2000);
					System.out.println("running on thread " + Thread.currentThread().getName());
				} catch (InterruptedException e) {
					e.printStackTrace();
				}
			}
		};
		
		MyExecutors.newDirectThreadPool().execute(task);
		MyExecutors.newPerTaskPerThreadThreadPool().execute(task);
		MyExecutors.newSerialThreadPool().execute(task);
		
	}
	
}
```


### 1.6 线程池源码简单分析:ExecutorService接口

ThreadPoolExecutor extends AbstractExecutorService  管的是线程池
AbstractExecutorService extends ExecutorService     管的是任务启动，关闭，返回
ExecutorService extends Executor  只是interface
                  

而ExecutorService执行任务的方式有以下三种：  
exec.execute(runnable)  
exec.execute(FutureTask)   
Future<Result> future = exec.submit(Callable)  

ExecutorService接口如下：
```
public interface ExecutorService extends Executor {
    void shutdown();
    List<Runnable> shutdownNow();
    boolean isShutdown();
    boolean isTerminated();
    boolean awaitTermination(long timeout, TimeUnit unit)
        throws InterruptedException;
    <T> Future<T> submit(Callable<T> task);
    <T> Future<T> submit(Runnable task, T result);
    Future<?> submit(Runnable task);
    <T> List<Future<T>> invokeAll(Collection<? extends Callable<T>> tasks)
        throws InterruptedException;
    <T> List<Future<T>> invokeAll(Collection<? extends Callable<T>> tasks,
                                  long timeout, TimeUnit unit)
        throws InterruptedException;

    <T> T invokeAny(Collection<? extends Callable<T>> tasks)
        throws InterruptedException, ExecutionException;
    <T> T invokeAny(Collection<? extends Callable<T>> tasks,
                    long timeout, TimeUnit unit)
        throws InterruptedException, ExecutionException, TimeoutException;
}
```

可以看出，一个ExecutorService知道如何启动任务，知道如何返回任务的结果，知道如何关闭任务

要往下说ExecutorService怎么执行任务，先回头看看所能执行的任务，分三种：  
exec.execute(Runnable)    
exec.execute(FutureTask)  其实就是execute(Runnable)  
Future<Result> future = exec.submit(Callable)    

跟着下面的源码，很容易就理清这仨的关系了
```
public interface Runnable {
    V run();
}

public interface Callable<V> {
    V call() throws Exception;
}


关于Future：
public interface Future<V> {

    boolean cancel(boolean mayInterruptIfRunning);
    boolean isCancelled();
    boolean isDone();
    V get() throws InterruptedException, ExecutionException;
    V get(long timeout, TimeUnit unit)  throws InterruptedException, ExecutionException, TimeoutException;
}

public interface RunnableFuture<V> extends Runnable, Future<V> {
    /**
     * Sets this Future to the result of its computation
     * unless it has been cancelled.
     */
    void run();
}


public class FutureTask<V> implements RunnableFuture<V>{

}

FutureTask的初始化：
FutureTask(Callable<V> callable)
FutureTask(Runnable runnable, V result)
注意：Runnable + 返回值就是一个Callable了啊，具体看RunnableAdapter

总之，FutureTask内部就有了一个Callable

关于：FutureTask(Runnable runnable, V result)
调用了：Executors.callable()
public static <T> Callable<T> callable(Runnable task, T result) {
    if (task == null)
        throw new NullPointerException();
    return new RunnableAdapter<T>(task, result);
}

static final class RunnableAdapter<T> implements Callable<T> {
    final Runnable task;
    final T result;
    RunnableAdapter(Runnable task, T result) {
        this.task = task;
        this.result = result;
    }
    public T call() {
        task.run();
        return result;
    }
}
```

下面就可以看看ExecutorService.submit()和execute方法了
```
public Future<?> submit(Runnable task) {
    if (task == null) throw new NullPointerException();
    RunnableFuture<Void> ftask = newTaskFor(task, null);
    execute(ftask);
    return ftask;
}

public <T> Future<T> submit(Runnable task, T result) {
    if (task == null) throw new NullPointerException();
    RunnableFuture<T> ftask = newTaskFor(task, result);
    execute(ftask);
    return ftask;
}

public <T> Future<T> submit(Callable<T> task) {
    if (task == null) throw new NullPointerException();
    RunnableFuture<T> ftask = newTaskFor(task);
    execute(ftask);
    return ftask;
}

protected <T> RunnableFuture<T> newTaskFor(Runnable runnable, T value) {
    return new FutureTask<T>(runnable, value);
}

protected <T> RunnableFuture<T> newTaskFor(Callable<T> callable) {
    return new FutureTask<T>(callable);
}
```

最后都归结到了execute(FutureTask)，其实就是execute(Runnable command)，这个在ThreadPoolExecutor里实现了

```
public void execute(Runnable command) {
    if (command == null)
        throw new NullPointerException();
    /*
     * Proceed in 3 steps:
     *
     * 1. If fewer than corePoolSize threads are running, try to
     * start a new thread with the given command as its first
     * task.  The call to addWorker atomically checks runState and
     * workerCount, and so prevents false alarms that would add
     * threads when it shouldn't, by returning false.
     *
     * 2. If a task can be successfully queued, then we still need
     * to double-check whether we should have added a thread
     * (because existing ones died since last checking) or that
     * the pool shut down since entry into this method. So we
     * recheck state and if necessary roll back the enqueuing if
     * stopped, or start a new thread if there are none.
     *
     * 3. If we cannot queue task, then we try to add a new
     * thread.  If it fails, we know we are shut down or saturated
     * and so reject the task.
     */
    int c = ctl.get();
    if (workerCountOf(c) < corePoolSize) {
        if (addWorker(command, true))
            return;
        c = ctl.get();
    }
    if (isRunning(c) && workQueue.offer(command)) {
        int recheck = ctl.get();
        if (! isRunning(recheck) && remove(command))
            reject(command);
        else if (workerCountOf(recheck) == 0)
            addWorker(null, false);
    }
    else if (!addWorker(command, false))
        reject(command);
}
```

所以，其实Future.get的阻塞，不是线程池负责的，而是在submit方法返回的RunnableFuture里，RunnableFuture的实现类就是FutureTask

```java
///这就是Runnable的run方法
public void run() {
    if (state != NEW ||
        !U.compareAndSwapObject(this, RUNNER, null, Thread.currentThread()))
        return;
    try {
        Callable<V> c = callable;
        if (c != null && state == NEW) {
            V result;
            boolean ran;
            try {
                result = c.call();
                ran = true;
            } catch (Throwable ex) {
                result = null;
                ran = false;
                setException(ex);
            }
            if (ran)
                set(result);
        }
    } finally {
        // runner must be non-null until state is settled to
        // prevent concurrent calls to run()
        runner = null;
        // state must be re-read after nulling runner to prevent
        // leaked interrupts
        int s = state;
        if (s >= INTERRUPTING)
            handlePossibleCancellationInterrupt(s);
    }
}

public boolean cancel(boolean mayInterruptIfRunning) {
    if (!(state == NEW &&
          U.compareAndSwapInt(this, STATE, NEW,
              mayInterruptIfRunning ? INTERRUPTING : CANCELLED)))
        return false;
    try {    // in case call to interrupt throws exception
        if (mayInterruptIfRunning) {
            try {
                Thread t = runner;
                if (t != null)
                    t.interrupt();
            } finally { // final state
                U.putOrderedInt(this, STATE, INTERRUPTED);
            }
        }
    } finally {
        finishCompletion();
    }
    return true;
}

/**
 * @throws CancellationException {@inheritDoc}
 */
public V get() throws InterruptedException, ExecutionException {
    int s = state;
    if (s <= COMPLETING)
        s = awaitDone(false, 0L);
    return report(s);
}

/**
 * @throws CancellationException {@inheritDoc}
 */
public V get(long timeout, TimeUnit unit)
    throws InterruptedException, ExecutionException, TimeoutException {
    if (unit == null)
        throw new NullPointerException();
    int s = state;
    if (s <= COMPLETING &&
        (s = awaitDone(true, unit.toNanos(timeout))) <= COMPLETING)
        throw new TimeoutException();
    return report(s);
}

 private int awaitDone(boolean timed, long nanos)
    throws InterruptedException {
    // The code below is very delicate, to achieve these goals:
    // - call nanoTime exactly once for each call to park
    // - if nanos <= 0, return promptly without allocation or nanoTime
    // - if nanos == Long.MIN_VALUE, don't underflow
    // - if nanos == Long.MAX_VALUE, and nanoTime is non-monotonic
    //   and we suffer a spurious wakeup, we will do no worse than
    //   to park-spin for a while
    long startTime = 0L;    // Special value 0L means not yet parked
    WaitNode q = null;
    boolean queued = false;
    for (;;) {
        int s = state;
        if (s > COMPLETING) {
            if (q != null)
                q.thread = null;
            return s;
        }
        else if (s == COMPLETING)
            // We may have already promised (via isDone) that we are done
            // so never return empty-handed or throw InterruptedException
            Thread.yield();
        else if (Thread.interrupted()) {
            removeWaiter(q);
            throw new InterruptedException();
        }
        else if (q == null) {
            if (timed && nanos <= 0L)
                return s;
            q = new WaitNode();
        }
        else if (!queued)
            queued = U.compareAndSwapObject(this, WAITERS,
                                            q.next = waiters, q);
        else if (timed) {
            final long parkNanos;
            if (startTime == 0L) { // first time
                startTime = System.nanoTime();
                if (startTime == 0L)
                    startTime = 1L;
                parkNanos = nanos;
            } else {
                long elapsed = System.nanoTime() - startTime;
                if (elapsed >= nanos) {
                    removeWaiter(q);
                    return state;
                }
                parkNanos = nanos - elapsed;
            }
            // nanoTime may be slow; recheck before parking
            if (state < COMPLETING)
                LockSupport.parkNanos(this, parkNanos);
        }
        else
            LockSupport.park(this);
    }
}

static final class WaitNode {
    volatile Thread thread;
    volatile WaitNode next;
    WaitNode() { thread = Thread.currentThread(); }
}
```

------------------

最后，FutureTask实现的是RunnableFuture，其实你完全可以不用FutureTask，
例如你想实现个MyFutureTask，这个不会在get方法里阻塞，而是基于异步IO，
```
public class CustomThreadPoolExecutor extends ThreadPoolExecutor {
 
    static class CustomTask implements RunnableFuture {...}
 
    protected  RunnableFuture newTaskFor(Callable c) {
        return new CustomTask(c);
    }
    protected  RunnableFuture newTaskFor(Runnable r, V v) {
        return new CustomTask(r, v);
    }
    // ... add constructors, etc.
  }
```
