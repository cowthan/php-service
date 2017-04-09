# http


有用的就3个模块：ayohttp, converter-fastjson, worker-okhttp

其他模块是retrofit，okhttp，okhttpUtils的demo测试

---------

## 1 基本套路

* 发起一个请求，分为几步：
    * 配置：超时时间等
    * 构造请求AyoHttp：请求方式，path参数，query参数，post参数，上传流，文件流等
    * 发起请求：HttpWorker是请求的发起者，由各种第三方http库实现，现在支持OkHttpWorker
    * 响应：拿到header，解析InputStream，使用StreamConverter,支持byte[]，File，String三种解析
        * 超时，映射到BaseHttpCallback
        * 404， 500等，映射到BaseHttpCallback
        * byte[]和File直接映射到BaseHttpCallback
    * 对于String，可能是xml，json，protobuf等格式，并且里面分为状态字段和业务字段
        * TopLevelConverter解析状态字段，并映射到BaseHttpCallback（这个就是用户使用的callback）
        * ResposneConverter解析业务字段，并映射到BaseHttpCallback
    * 其中，ResponseConverter对应不同的解析器，如FastJson，Gson，Xml解析器等
    * 用户在BaseHttpCallback中实现自己的业务逻辑


* 声明：
    * OkHttpWorker不是直接基于OkHttp，而是基于开源项目OkHttpUtils
        * 地址：https://github.com/hongyangAndroid/okhttputils
    * StreamConverter如果使用FileConverter，就是文件下载，但文件下载还是有很多细节的，不建议直接使用这个框架
        * 参考：https://github.com/Aspsine/MultiThreadDownload，已用在了正式项目里，省不少劲
    * StreamConverter如果使用ByteArrayConverter，就是byte[]，但一般用不到，所以并未真正支持
    * 整个框架仿照的是retrofit，主框架简单至极，worker和converter作为插件化子模块，想用哪个，就引用哪个
    * 在此感谢上面涉及到的三位作者
    * http原理blog：http://blog.csdn.net/lmj623565791/article/details/47911083
    * 仿照Retrofit和OkHttpUtils，超时设置，cookie，https等的配置项，都使用okhttp和volly的原生设置，而不纳入框架内管理

* 声明2：
    * 请求方式支持：
        * get
        * post
            * post from
            * post string
            * post file
        * put
        * delete
        * head
        * patch
   * Json解析中的TypeToken处理的还不完善，需要自己手动传入（在BaseHttpCallback传入）
   * 上传文件时，上传进度提示没有实现
   * 重发策略，本人尚未深入研究，还不知道是怎么回事
   * 缓存，也未深入研究
   * 响应code是300到400时，是重定向，怎么处理的，也不知道
   * 403的授权问题，okhttp可以支持
   * https的问题，okhttp也支持
   * 所有授权和https的问题，本框架不做过多封装，但各个worker会暴露出各个库的配置项，可以直接配置，demo会给出


OKHttp支持的请求方式：以下代码是纯okhttp代码
```java
Request request = new Request.Builder()
        .url("https://api.github.com/markdown/raw")

        //all request methods
        .get()
        .post(RequestBody.create(MEDIA_TYPE_MARKDOWN, file))
        .post(RequestBody)
        .put(RequestBody)
        .delete()
        .delete(RequestBody)
        .head()
        .patch(RequestBody)
        .method(method, RequestBody)
        ///

        .addHeader(name, value)
        .cacheControl(CacheControl)
        .build();
```

RequestBody包括：
```
//post file：单文件上传，不以键值对的形式，应该也没法带其他post参数
public static final MediaType MEDIA_TYPE_MARKDOWN = MediaType.parse("text/x-markdown; charset=utf-8");
RequestBody.create(MEDIA_TYPE_MARKDOWN, file)

//post multipart：表单上传文件，可多文件上传，可附带post参数
RequestBody requestBody = new MultipartBody.Builder()
        .setType(MultipartBody.FORM)
        .addFormDataPart("title", "Square Logo")
        .addFormDataPart("image", "logo-square.png", RequestBody.create(MEDIA_TYPE_PNG, new File("website/static/logo-square.png")))
        .build();

//form表单：普通post
RequestBody formBody = new FormBody.Builder()
        .add("search", "Jurassic Park")
        .build();

//post string
String postBody = ""
        + "Releases\n"
        + "--------\n"
        + "\n"
        + " * _1.0_ May 6, 2013\n"
        + " * _1.1_ June 15, 2013\n"
        + " * _1.2_ August 11, 2013\n";
RequestBody.create(MEDIA_TYPE_MARKDOWN, postBody)

RequestBody.create支持：byte[], ByteString，File，String


//post streaming：自己构建RequestBody
RequestBody requestBody = new RequestBody() {
      @Override public MediaType contentType() {
        return MEDIA_TYPE_MARKDOWN;
      }

      @Override public void writeTo(BufferedSink sink) throws IOException {
        sink.writeUtf8("Numbers\n");
        sink.writeUtf8("-------\n");
        for (int i = 2; i <= 997; i++) {
          sink.writeUtf8(String.format(" * %s = %s\n", i, factor(i)));
        }
      }

      private String factor(int n) {
        for (int i = 2; i < n; i++) {
          int x = n / i;
          if (x * i == n) return factor(x) + " × " + i;
        }
        return Integer.toString(n);
      }
    };

    Request request = new Request.Builder()
        .url("https://api.github.com/markdown/raw")
        .post(requestBody)
        .build();

//带上传进度：需要拦截器，拦截上面构建的RequestBody
Request request = new Request.Builder()
    .url("https://publicobject.com/helloworld.txt")
    .build();

final ProgressListener progressListener = new ProgressListener() {
  @Override public void update(long bytesRead, long contentLength, boolean done) {
    System.out.println(bytesRead);
    System.out.println(contentLength);
    System.out.println(done);
    System.out.format("%d%% done\n", (100 * bytesRead) / contentLength);
  }
};

OkHttpClient client = new OkHttpClient.Builder()
    .addNetworkInterceptor(new Interceptor() {
      @Override public Response intercept(Chain chain) throws IOException {
        Response originalResponse = chain.proceed(chain.request());
        return originalResponse.newBuilder()
            .body(new ProgressResponseBody(originalResponse.body(), progressListener))
            .build();
      }
    })
    .build();

Response response = client.newCall(request).execute();

```

Okhttp解析发起请求：
```
private final OkHttpClient client = new OkHttpClient();
同步：Response response = client.newCall(request).execute();
异步：enqueue和callback，注意进度的callback需要自己实现（上传进度和下载进度）
```

OKHttp解析Response：
```
解析code，判断是否成功：
response.code()
response.isSuccessful()---code是200就是true

解析Header
response.header("Server")
或者
Headers responseHeaders = response.headers();
for (int i = 0, size = responseHeaders.size(); i < size; i++) {
  System.out.println(responseHeaders.name(i) + ": " + responseHeaders.value(i));
}

解析Body：ReponseBody
long contentLength = response.body().contentLenght()
MediaType mime = response.body().contentType()

String json = response.body().string()
byte[] bytes = response.body().bytes()
InputStream inputStream = response.body().byteStream()
Reader reader = response.body().charStream()
BufferedSource buffer = response.body().source();

```


* 其他问题：
    * okhttp的CacheController
    * okhttp的安全相关，Authenticate，Certificate，handshake，authenticator，CertificatePinner，trustManagerForCertificates
    * okhttp的拦截器怎么用
    * okhttp的cancel请求
    * okhttp的Callback
    * okhttp的execute和enqueue


## 2 使用

* 引库，需要3个库，核心库，converter库，worker库
    * 核心库提供了基本框架，但不具备实际功能
        * compile 'org.ayo:ayo-http:v1.0.0'
    * converter库提供了对业务字段的解析，现在只支持fastjson
        * compile 'org.ayo.http:converter-fastjson:v1.0.0'
    * worker库支持定制底层http实现，现在只支持okhttp3
        * compile 'org.ayo.http:worker-okhttp:v1.0.0'

* 项目代码
    * 你需要自己提供一个状态字段解析器，因为各个项目都不一样，一般形式是：{ code: 0, msg: "错误原因", result:[]或{} }
    * 日志相关，参考demo

```java

@Override
protected void onCreate(Bundle savedInstanceState) {
    super.onCreate(savedInstanceState);
    setContentView(R.layout.activity_main);


    ///下面这部分代码，就是固定模式，可以通过注解生成，当然retrofit支持，这里不支持
    getRequest().flag("测试接口")
            .actionGet()
            .url("http://chuanyue.iwomedia.cn/daogou/app/app?jid={jid}")
            .header("deviceId", "11122334")               //----请求头
            .path("jid", "234")                           //----path参数，会替换掉url中的{key}
            .queryString("nickname", "哈哈")              //----get参数，会拼到url中
            .queryString("os", "android")
//                .path("id", "1")
//                .param("pwd", "dddddfffggghhh")         //----post参数，form提交
//                .param("file-1", new File(""))          //----post参数，上传文件，可多文件上传，multipart的form提交
//                .file(new File(""))                     //----post提交文件，只支持一个文件，post stream方式
//                .stringEntity("hahahahahahahah哈哈哈哈哈哈哈哈lddddddddd2222222222") //----post提交文本，post stream方式
            .callback(new BaseHttpCallback<List<RespRegist>>() {
                @Override
                public void onFinish(boolean isSuccess, HttpProblem problem, FailInfo resp, List<RespRegist> respRegist) {
                    if(isSuccess){
                        Toast.makeText(getApplicationContext(), "请求成功--" + respRegist.size(), Toast.LENGTH_SHORT).show();
                    }else{
                        Toast.makeText(getApplicationContext(), "请求失败：" + resp.dataErrorReason, Toast.LENGTH_SHORT).show();
                    }
                }

                @Override
                public void onLoading(long current, long total) {
                    super.onLoading(current, total);
                }
            }, new TypeToken<List<RespRegist>>(){})
            .fire();


}

public AyoHttp getRequest(){
    return AyoHttp.request()
                .connectionTimeout(10000)
                .writeTimeout(10000)
                .readTimeout(10000)
                .worker(new OkhttpWorker())
                .streamConverter(new StreamConverter.StringConverter())   //ByteArrayConverter   FileConverter
                .topLevelConverter(new SampleTopLevelConverter())
                .resonseConverter(new FastJsonConverter())
                .intercept(new LogIntercepter());
}

```


Volly源码解析
===========================
* HTTP请求
    * 如何构造一个请求
    * 发起请求，管理请求：RequestQueue
    * 响应
    * 使用详解

####
## 1 构造一个请求：Request

* http请求：
    * Request.Method枚举了所有请求方式
    * get请求：需要传入url，拼接query parameters
        * StringRequest
        * RequestManager.get()
    * post请求1：普通post，带request parameters
        * StringRequest
        * RequestManager.post()
    * post请求2：传入StringEntity
        * ByteArrayRequest，但是这个类只有package权限
        * 只能用RequestManager.sendRequest或者post
    * post请求3：上传文件
        * 用到RequestMap，这个类可以往里传file，inputstream等
        * RequestManager.sendRequest或者post方法中参数data的就是RequestMap
    * PUT
    * DELETE
    * HEAD
    * OPTIONS
    * TRACE
    * PATCH

* 测试接口
    * get :
    * post 1: StringRequest
    * post 2: 得用到
    * 上传文件
    * 下载文件
    * PUT
    * DELETE
    * HEAD
    * OPTIONS
    * TRACE
    * PATCH
    * 仿验证码请求


### HurlStack

1 处理底层http请求，使用了URLConnection，大体上就是根据Request，返回Response

2 POST，PUT，PATCH这三种请求方式会添加body，所有会调用：addBodyIfExists
```java
byte[] body = request.getBody();
if (body != null) {
    connection.setDoOutput(true);
    connection.addRequestProperty(HEADER_CONTENT_TYPE, request.getBodyContentType());
    DataOutputStream out = new DataOutputStream(connection.getOutputStream());
    out.write(body);
    out.close();
}

///注意，body分多种，post请求参数，post entity，文件上传等
```

注意，request.getBody()，每一种Request都会把自己的所有参数转化为一个body，涉及到键值对，字符串，文件，输入流等

3 超时时间等在这里设置

4 返回HttpResponse：

* HttpResponse里封装了：
    * connection.getInputStream()，还没读呢
    * content length等参数
    * 响应的header


### Network，及其子类BasicNetwork

* 有何用
    * 驱动了HurlStack
    * 处理缓存：底层的缓存
    * 重定向
    * 把IO异常封装成Volly异常
    * 处理retry policy: attemptRetryOnException方法

***
不懂：ByteArrayPool这个是干什么的


```java
//返回：
return new NetworkResponse(statusCode, responseContents,
						    responseHeaders, false,
						    SystemClock.elapsedRealtime() - requestStart);
其中responseContents，是一个byte[]，就是把hurlStack返回的inputStream读出来了，
这个功能由entityToBytes方法完成，里面用到了ByteArrayPool
```


### RequestQueue

* 必须调用start()方法来启动volly，所以可以认为volly启动只有有个大循环
    * 等待队列：Map<String, Queue<Request<?>>> mWaitingRequests
    * 干活队列： PriorityBlockingQueue<Request<?>> mNetworkQueue
    * 缓存队列：PriorityBlockingQueue<Request<?>> mCacheQueue
    * 当前集合：Set<Request<?>> mCurrentRequests

* 默认线程池：
    * DEFAULT_NETWORK_THREAD_POOL_SIZE = 4
    * 个人推荐：根据Rxjava的思想：
        * IO线程应该是一个线程池，线程数不限，但重用空闲线程
        * CPU密集型线程，即计算密集型，有几个CPU，就有几个线程

* 干活的类：
    * Cache 和 CacheDispatcher：默认new DiskBasedCache(cacheDir)
    * Network 和 NetworkDispatcher[]： 数组大小就是线程池大小
    * ResponseDelivery： 默认new ExecutorDelivery(new Handler(Looper.getMainLooper()))
    * List<RequestFinishedListener> mFinishedListeners

####

* 初始化：start() 大循环开始
    * CacheDispatcher作为一个thread，启动了，处理的是mCacheQueue
    * NetworkDispatcher作为n个thread，启动了，n级线程池的线程数，默认是4，处理的是mNetworkQueue

####

* NetworkDispatcher怎么工作？
    * 线程优先级：Process.setThreadPriority(Process.THREAD_PRIORITY_BACKGROUND)
    * 处理的是mNetworkQueue
    * 对于每一个Request：
        * 调用NetWork.performRequest(request)，默认是BasicNetwork
        * 响应结果预处理：Response<?> response = request.parseNetworkResponse(networkResponse)，主要功能是把byte[]转成Request<T>的这个T类型，如变成String，文件等
        * 处理缓存：mCache.put(request.getCacheKey(), response.cacheEntry);  ？？？？？？？？？？？？？？？？？
            * 每一个请求有个唯一标识，默认url
        * 转发响应：ResponseDelivery.postResponse(request, response)，默认实现是ExecutorDelivery
            * 注意传入的参数，是个带主循环的handler：new ExecutorDelivery(new Handler(Looper.getMainLooper()))
            * 所有代码都在主线程运行
            * 转发到RequestQueue的RequestFinishedListener：这个是大回调，监听所有请求，可以根据id筛选
            * 转发到每个reqeust的deliverResponse方法，如StringReuqest，用到了Response.Listener
        * 基本完事
    * 总结：
        * 每个NetworkDispatcher都从队列里take，谁快谁就take下一个

####

* 缓存相关：
    * 存的值类型是Cache.Entry，存的是http响应中的byte[]，header，状态字段等
    * 取出：在BasicNetwork中，
        * 如果http响应是not modified的，就不做进一步解析了，而是从cache取出已经解析好的东西
            * Entry entry = request.getCacheEntry();
        * 但这个entry是谁放进request里的呢, 在CacheDispatcher，111行
    * 保存：在NetworkDispatcher中，保存，mCache.put(request.getCacheKey(), response.cacheEntry);
        * request.getCacheKey()，默认就是url，只对get请求做缓存？
        * response.cacheEntry，在Response.success(parsed, HttpHeaderParser.parseCacheHeaders(response))时赋值，参考StringRequest.parseNetworkResponse
    * ClearCacheRequest：一个虚构的请求（带hack性质的请求），调用isCanceled，会清空缓存， cache.clear()，并在handler队列的最前面执行一个runnable的callback
    * 接口是：Cache
    * 看默认实现类：DiskBasedCache
        * 文件保存
    * CacheDispatcher如何工作？
        * 独自启动一个线程，占一个大循环，在mCacheQueue队列中工作
        * 没有缓存，则放回mNetworkQueue，等NetworkDispatcher处理
        * 有缓存，但已过期
            * 则先把缓存放到request.cacheEntry里
            * 再把request放到mNetworkQueue
            * 如果服务器返回的结果是not modified，则使用缓存，否则忽略缓存
        * 有缓存，且有效，直接deliver出去
        * 有缓存，且有效，但已被设置为需要刷新，也是回到mNetworkQueue，和modified状态值对比

####

* RequestQueue.add(Request<?> request)
    * 涉及到mCurrentRequests，mNetworkQueue，mWaitingRequests，mCacheQueue

###StringRequest

传入键值对，以get，post等方式发起请求

响应是String


### ByteArrayRequest：通过RequestManager使用


### RequestMap：上传文件，但是回调中没有进度提示
