# 其他

#




## 3 invokeAll和invokeAny


看方法doInvkeAny，大体套路是：
```
T doInvkeAny(Collection<Callable<T>> tasks, boolean timed, long nanos){

	int ntasks = tasks.size();
	ArrayList<Future<T>> futures = new ArrayList<Future<T>>(ntasks);
	
	ExecutorCompletionService<T> ecs = new ExecutorCompletionService<T>(this);
	Iterator<? extends Callable<T>> it = tasks.iterator();
	
	后面还有，没整明白是怎么添加的任务
}
```
涉及到了ExecutorCompletionService，CompletionService，LinkedBlockingQueue等等

这部分是干什么的都没整明白
```

private <T> T doInvokeAny(Collection<? extends Callable<T>> tasks,
                              boolean timed, long nanos)
    throws InterruptedException, ExecutionException, TimeoutException {
    if (tasks == null)
        throw new NullPointerException();
    int ntasks = tasks.size();
    if (ntasks == 0)
        throw new IllegalArgumentException();
    ArrayList<Future<T>> futures = new ArrayList<Future<T>>(ntasks);
    ExecutorCompletionService<T> ecs =
        new ExecutorCompletionService<T>(this);

    // For efficiency, especially in executors with limited
    // parallelism, check to see if previously submitted tasks are
    // done before submitting more of them. This interleaving
    // plus the exception mechanics account for messiness of main
    // loop.

    try {
        // Record exceptions so that if we fail to obtain any
        // result, we can throw the last exception we got.
        ExecutionException ee = null;
        final long deadline = timed ? System.nanoTime() + nanos : 0L;
        Iterator<? extends Callable<T>> it = tasks.iterator();

        // Start one task for sure; the rest incrementally
        futures.add(ecs.submit(it.next()));
        --ntasks;
        int active = 1;

        for (;;) {
            Future<T> f = ecs.poll();
            if (f == null) {
                if (ntasks > 0) {
                    --ntasks;
                    futures.add(ecs.submit(it.next()));
                    ++active;
                }
                else if (active == 0)
                    break;
                else if (timed) {
                    f = ecs.poll(nanos, TimeUnit.NANOSECONDS);
                    if (f == null)
                        throw new TimeoutException();
                    nanos = deadline - System.nanoTime();
                }
                else
                    f = ecs.take();
            }
            if (f != null) {
                --active;
                try {
                    return f.get();
                } catch (ExecutionException eex) {
                    ee = eex;
                } catch (RuntimeException rex) {
                    ee = new ExecutionException(rex);
                }
            }
        }

        if (ee == null)
            ee = new ExecutionException();
        throw ee;

    } finally {
        for (int i = 0, size = futures.size(); i < size; i++)
            futures.get(i).cancel(true);
    }
}
```
## 4 关于ExecutorCompletionService，CompletionService

实现了CompletionService，将执行完成的任务放到阻塞队列中，通过take或poll方法来获得执行结果
```
///例4：（启动10条线程，谁先执行完成就返回谁）
public class CompletionServiceTest {
    public static void main(String[] args) throws InterruptedException, ExecutionException {
        ExecutorService executor = Executors.newFixedThreadPool(10);        //创建含10条线程的线程池
        CompletionService completionService = new ExecutorCompletionService(executor);
        for (int i =1; i <=10; i ++) {
            final  int result = i;
            completionService.submit(new Callable() {
                public Object call() throws Exception {
                    Thread.sleep(new Random().nextInt(5000));   //让当前线程随机休眠一段时间
                    return result;
                }
            });
        }
        System.out.println(completionService.take().get());   //获取执行结果
    }
}
//输出结果可能每次都不同（在1到10之间）

```








# 第五课：


