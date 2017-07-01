# service

## 1 定义，声明，启动和关闭

```
public class AppService extends Service {

    @Override
    public void onCreate() {
        super.onCreate();
    }

    @Override
    public int onStartCommand(Intent intent,  int flags, int startId) {
        return super.onStartCommand(intent, flags, startId);
    }

    @Nullable
    @Override
    public IBinder onBind(Intent intent) {
        return null;
    }
}


<service  
    android:name="com.example.servicetest.MyService"  
</service> 

Intent startIntent = new Intent(this, MyService.class);  
startService(startIntent);  

Intent stopIntent = new Intent(this, MyService.class);  
stopService(stopIntent);  
```

## 2 bind和unbind

在Service里：
```

private MyBinder mBinder = new MyBinder();  

@Override  
public IBinder onBind(Intent intent) {  
    return mBinder;  
}

class MyBinder extends Binder {  
  
    public void startDownload() {  
        Log.d("TAG", "startDownload() executed");  
        // 执行具体的下载任务  
    }  

} 

```

在Activity里：
```
MyService.MyBinder myBinder;

private ServiceConnection connection = new ServiceConnection() {  
  
    @Override  
    public void onServiceDisconnected(ComponentName name) {  
    }  

    @Override  
    public void onServiceConnected(ComponentName name, IBinder service) {  
        myBinder = (MyService.MyBinder) service;  
        myBinder.startDownload();  
    }  
};  

Intent bindIntent = new Intent(this, MyService.class);  
bindService(bindIntent, connection, BIND_AUTO_CREATE);  

unbindService(connection);  

```



## 3 检测Service是否在运行

```

```


## 4 前台Service

* 上面的Service是后台服务，在内存不足时，会被回收
    * startForeground(1, notification)可以让一个service成为前台服务
    * 相当于一个一直开着的界面，不会被随便杀死，但是需要在通知栏一直显示一个Notification

```
@Override  
public void onCreate() {  
    super.onCreate();  
    Notification notification = new Notification(R.drawable.ic_launcher, "有通知到来", System.currentTimeMillis());  
    Intent notificationIntent = new Intent(this, MainActivity.class);  
    PendingIntent pendingIntent = PendingIntent.getActivity(this, 0, notificationIntent, 0);  
    notification.setLatestEventInfo(this, "这是通知的标题", "这是通知的内容", pendingIntent);  
    startForeground(1, notification);  
    Log.d(TAG, "onCreate() executed");  
}  
  
```

## 5 Service和线程

* Service还是运行在主线程
* 耗时任务还是得通过子线程来处理


## 6 Service和进程

这时就需要aidl了

```
package com.example.servicetest;  
interface MyAIDLService {  
    int plus(int a, int b);  
    String toUpperCase(String str);  
} 


public class MyService extends Service {  
  
    ......  
  
    @Override  
    public IBinder onBind(Intent intent) {  
        return mBinder;  
    }  
  
    MyAIDLService.Stub mBinder = new Stub() {  
  
        @Override  
        public String toUpperCase(String str) throws RemoteException {  
            if (str != null) {  
                return str.toUpperCase();  
            }  
            return null;  
        }  
  
        @Override  
        public int plus(int a, int b) throws RemoteException {  
            return a + b;  
        }  
    };  
  
}  

<!-- 加上android:process=":remote"，则开启了一个新的进程--包名:remote，service会运行在这个进程下  -->
<service  
    android:name="com.example.servicetest.MyService"  
    android:process=":remote" >  
    <intent-filter>  
        <action android:name="com.example.servicetest.MyAIDLService"/>  
    </intent-filter>  
</service>  


private ServiceConnection connection = new ServiceConnection() {  
  
    @Override  
    public void onServiceDisconnected(ComponentName name) {  
    }  

    @Override  
    public void onServiceConnected(ComponentName name, IBinder service) {  
        myAIDLService = MyAIDLService.Stub.asInterface(service);  
        try {  
            int result = myAIDLService.plus(3, 5);  
            String upperStr = myAIDLService.toUpperCase("hello world");  
            Log.d("TAG", "result is " + result);  
            Log.d("TAG", "upperStr is " + upperStr);  
        } catch (RemoteException e) {  
            e.printStackTrace();  
        }  
    }  
};  

//这里需要隐式的去开启service了
Intent intent = new Intent("com.example.servicetest.MyAIDLService");  
bindService(intent, connection, BIND_AUTO_CREATE);

```