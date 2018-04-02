# EventBus

# Eventbus

---------------------------


事件总线
===========================
* 事件总线
    * EventBus使用
    * EventBus代码解析
    * EventBus使用场景


* 怎么使用？
    * 定义个事件Event类
    * 注册和注销：谁对这个事件感兴趣？
    * 定义接收器：怎么个感兴趣法？
    * 发送事件：事件从谁那发出？


####
##1 多说无益，直接贴代码吧

```java
package com.cowthan.sample.zeventbus;

import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;

import com.cowthan.sample.BaseActivity;
import com.cowthan.sample.R;

import org.ayo.eventbus.EventBus;

/**
 * Created by Administrator on 2016/1/26.
 */
public class EventBusDemoActivity extends BaseActivity{

    TextView tv_receiver;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.ac_eventbus);

        Button btn_post = findViewById(R.id.btn_post);
        tv_receiver = findViewById(R.id.tv_reveiver);

        EventBus.getDefault().register(this);

        btn_post.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                AnyEvent e = new AnyEvent();
                e.time = System.currentTimeMillis() + "";
                EventBus.getDefault().post(e);
            }
        });
    }

    @Override
    protected void onDestroy() {
        EventBus.getDefault().unregister(this);
        super.onDestroy();
    }

    public static class AnyEvent{
        public String time;
    }

    //方法名可以任意
    @Subscribe(threadMode = ThreadMode.MAIN)
    public void onEventMainThread(AnyEvent e){
        tv_receiver.setText("收到个事件：" + e.time);
    }
}
```

对应的布局文件：
```
<?xml version="1.0" encoding="utf-8"?>
<RelativeLayout xmlns:android="http://schemas.android.com/apk/res/android"
    android:layout_width="match_parent"
    android:layout_height="match_parent"
    android:background="#ffffff"
    >

    <Button
        android:id="@+id/btn_post"
        android:layout_width="wrap_content"
        android:layout_height="wrap_content"
        android:layout_centerHorizontal="true"
        android:layout_centerVertical="true"
        android:padding="10dp"
        android:text="发出个事件" />

    <TextView
        android:id="@+id/tv_reveiver"
        android:layout_width="wrap_content"
        android:layout_height="wrap_content"
        android:layout_below="@+id/btn_post"
        android:layout_centerHorizontal="true"
        android:layout_marginTop="56dp"
        android:text="收到个事件："
        android:textAppearance="?android:attr/textAppearanceLarge" />


</RelativeLayout>
```

##2 这个小框架完全是非侵入式的，不得不赞

##3 事件接收器运行在哪个线程？

```
public void onEventMainThread(param)
{
    UI线程
}

public void onEventPostThread(param)
{
    事件在哪儿发送，就在哪儿处理
}

public void onEventBackgroundThread(param)
{
    后台线程，就一个
}

public void onEventAsync(param)
{
    后台线程，每个Event开一个
}

///注解
///threadMode可以是：
@Subscribe(threadMode = ThreadMode.MAIN)
public void onEvent(param){

}
```

##4 新版的EventBus支持注解，也必须使用注解

截止2016.4.16的新版本，是强制使用注解，基本代码就可以说明一切了


```java
@Subscribe(threadMode = ThreadMode.MAIN)
public void onEvent(param){

}

@Subscribe
public void onEvent(AnyEventType event) {/* Do something */};

public @interface Subscribe {
    ThreadMode threadMode() default ThreadMode.POSTING;

    /**
     * If true, delivers the most recent sticky event (posted with
     * {@link EventBus#postSticky(Object)}) to this subscriber (if event available).
     */
    boolean sticky() default false;

    /** Subscriber priority to influence the order of event delivery.
     * Within the same delivery thread ({@link ThreadMode}), higher priority subscribers will receive events before
     * others with a lower priority. The default priority is 0. Note: the priority does *NOT* affect the order of
     * delivery among subscribers with different {@link ThreadMode}s! */
    int priority() default 0;
}

//这四种线程模式上面有了，不用再解释了
public enum ThreadMode {
    POSTING,
    MAIN,
    BACKGROUND,
    ASYNC
}

```


##5 sticky和priority：register(Object subscriber, boolean sticky, int priority)

尚未研究

##6 代码解析

* 大体流程：
    * register方法中会给所有subscriber，Event存起来，便于查找
    * post方法最终走到了postToSubscription(Subscription subscription, Object event, boolean isMainThread)
    * postToSubscription方法中就开始考虑线程了：
        * post线程：在哪儿发事件，就在哪儿调用
        * 主线程：MainThread，对应HandlerPoster，是一个Handler的实现类，looper是主线程的looper（Looper.getMainLooper()），
        也是个循环处理所有Event
        * Background线程：对应BackgroundPoster，默认使用的线程池是Executors.newCachedThreadPool()，可以在EventBus的Builder中配置，
        run起来之后，是在一个线程中循环处理队列中的所有Event
        * Async线程：对应AyncPoster，默认使用的线程池是Executors.newCachedThreadPool()，可以在EventBus的Builder中配置，
        没有循环，每一个Event都是起一个新线程

* 队列消息处理方式：
    * 方式1：在一个线程循环处理
    * 方式2：每个消息开一个线程


##7 使用场景

* 基本上所有你感觉参数传递起来有点麻烦的地方，都可以通过EventBus来处理：
    * Activity间传递，但注意：
        * 不建议fragment间使用EventBus
        * 不建议fragment与Activity之间使用EventBus
    * 组件间传递：
        * service和Activity
        * broadcast和Activity

* 总结：
    * 有父子关系的两个东西，之间应该相互持有引用，可以直接通信
    * 并列关系，顺序关系的东西，推荐通过EventBus通信


##9 异常处理

没怎么看，比如ThrowableFailureEvent，ErrorDialogConfig这些东西，干啥的都