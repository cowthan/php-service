# 内存优化



## 1 静态Activity和View


这种情况最好的解决方案就是：不要这么写代码

```java
public class MainActivity extends AppCompatActivity {

    private static Context context;
    private static TextView textView;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);
        context = this;
        textView = new TextView(this);
    }
}
```

## 2 内部类和匿名内部类

内部类和匿名内部类会持有外部对象的引用

特别是Thread和Handler，因为涉及到延时，并且都需要主动关闭，所以很容易产生内存泄露

* 使用静态内部类，配合WeakReference

```java
public class LeakActivity extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_leak);
        leakFun();
    }

    private void leakFun(){
        new Thread(new Runnable() {
            @Override
            public void run() {
                try {
                    Thread.sleep(Integer.MAX_VALUE);
                } catch (InterruptedException e) {
                    e.printStackTrace();
                }
            }
        });
    }
}
```

## 3 动画

在OnDestroy中停止动画
或者在View的某个生命周期里停止

```java
public class LeakActivity extends AppCompatActivity {

    private TextView textView;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_leak);
        textView = (TextView)findViewById(R.id.text_view);
        ObjectAnimator objectAnimator = ObjectAnimator.ofFloat(textView,"rotation",0,360);
        objectAnimator.setRepeatCount(ValueAnimator.INFINITE);
        objectAnimator.start();
    }
}
```

## 4 Handler泄露


这个是老生常谈的问题

解决方案1：做成静态内部类，配合WeakReference

```java
private static class MyHandler extends Handler {
    private final WeakReference<SampleActivity> mActivity;

    public MyHandler(SampleActivity activity) {
      mActivity = new WeakReference<SampleActivity>(activity);
    }

    @Override
    public void handleMessage(Message msg) {
      SampleActivity activity = mActivity.get();
      if (activity != null) {
        // ...
      }
    }
  }
```

解决方案2：onDestroy时，移除所有消息和回调

```java
handler.removeCallbacksAndMessages(null);
```

## 5 需要注册的，onDestroy时都要注销

如EventBus, RxJava，广播


## 6 你主动开启的线程池里的任务


## 7 Http，ImageLoader任务


## 8 自己写一个可关闭资源池，统一管理一个界面里Context里的可能造成内存泄露的资源

注意，内部类你没法管理，是底层机制决定了内部类对象必须持有外部对象的引用

```java
首先定义一组类，来表示可以被关闭的资源

public abstract class Closesable<T> {

    private WeakReference<T> closeable;

    public Closesable(T t){
        this.closeable = new WeakReference<T>(t);;
    }

    public void close(){
        final T t = closeable.get();
        if(t != null){
            closeMe(t);
        }
    }

    protected abstract  void closeMe(T t);
}


public class AnimatorCloseable extends Closesable<Animator> {
    public AnimatorCloseable(Animator animator) {
        super(animator);
    }

    @Override
    public void closeMe(Animator animator) {
        if(animator.isRunning()) animator.cancel();
    }
}

public class HandlerCloseable extends Closesable<Handler> {
    public HandlerCloseable(Handler handler) {
        super(handler);
    }

    @Override
    public void closeMe(Handler handler) {
        handler.removeCallbacksAndMessages(null);
    }
}

然后，每个定义资源池
资源或任务打开时，放入资源池
资源使用结束，或者任务运行结束，从资源池移除
资源和任务被用户主动关闭，从资源池移除
上下文销毁时，资源池关闭所有资源


import android.app.Activity;
import android.app.Application;
import android.app.Service;
import android.support.v4.app.Fragment;

import java.util.HashMap;
import java.util.Map;

/**
 */

public class LeakPool {

    public static LeakPool fromContext(Activity context){
        return new LeakPool();
    }
    public static LeakPool fromContext(Fragment context){
        return new LeakPool();
    }
    public static LeakPool fromContext(android.app.Fragment context){
        return new LeakPool();
    }
    public static LeakPool fromContext(Service context){
        return new LeakPool();
    }
    public static LeakPool fromContext(Application context){
        return new LeakPool();
    }

    private LeakPool(){

    }

    private Map<String, Closesable<?>> pool = new HashMap<>();

    public <T> void add(String tag, Closesable<T> closesable){
        if(pool.containsKey(tag)){
            throw new RuntimeException("已经存在任务：" + tag);
        }else{
            pool.put(tag, closesable);
        }
    }

    public void closeAndRemove(String tag){
        Closesable<?> closesable = pool.get(tag);
        if(closesable != null){
            closesable.close();
            pool.remove(tag);
        }
    }

    public void remove(String tag){
        Closesable<?> closesable = pool.get(tag);
        if(closesable != null){
            pool.remove(tag);
        }
    }

    public void closeAll(){
        for(Closesable<?> closesable : pool.values()){
            closesable.close();
        }
        pool.clear();
    }

}

```


## 9 MAT分析内存泄露

http://blog.csdn.net/ljd2038/article/details/53560829