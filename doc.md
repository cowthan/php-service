# 懂球帝

## http

* 支持：
    * 通过TypeToken支持泛型，配合FastJson
    * Response成功时，自动Json解析，映射到onFinish
    * Response失败时，如果是业务逻辑失败，取出data里的错误信息，映射到onFinish--FailInfo
    * Response失败时，如果是常规http失败（如404，500），映射到onFinish--FailInfo
    * Response失败时，如果是超时，映射到onFinish--FailInfo
    * HttpProblem参数暂时不可信
    * 完全版本也可以兼容RxJava

```

//------------公共设置
public static AyoHttp getRequest(){
    AyoHttp r = AyoHttp.request()
//                .connectionTimeout(10000)
//                .writeTimeout(10000)
//                .readTimeout(10000)
            .worker(worker)
            .streamConverter(streameConverter)   //ByteArrayConverter   FileConverter
            .topLevelConverter(topLevelConverter)
            .resonseConverter(responseConverter)
            .intercept(logIntercepter);

    Map<String, String> defaultHeaders = AppUtils.getOAuthMap(BaseApplication.getInstance());
    for(String key: defaultHeaders.keySet()){
        r.header(key, defaultHeaders.get(key));
    }

    return r;
}

//------------定义接口
public static void login(String tag, String referer, String username,
                         String password,
                         String access_token,
                         String platform,
                         String open_id,
                         String expire_in,
                         BaseHttpCallback<UserModel> callback){
    getRequest().flag(tag)
            .url(Urls.SERVER_PATH + Urls.social_login)
            .actionPost()
            .header("Referer", referer)
            .param("username", username)
            .param("access_token", access_token)
            .param("platform", platform)
            .param("open_id", open_id)
            .param("expire_in", expire_in)
            .param("password", password)
            .callback(callback, new TypeToken<UserModel>(){})
            .fire();
}

//------------调用接口
Https.login("三方登录", getPreRefer(), qq.getName(), qq.getPassword(), qq.getAccess_token(), qq.getPlatform(), qq.getOpenid(), qq.getExpires_in(),
        new BaseHttpCallback<UserModel>() {
            @Override
            public void onFinish(boolean isSuccess, HttpProblem problem, FailInfo resp, UserModel userModel) {
                if(isSuccess){
                    onLoginOk(userModel);
                }else{
                    onLoginFail(resp, qq);
                }
            }
        });
```


## 列表Item模板框架

用于灵活配置列表，拆分复杂Adapter，特别适用于聊天记录列表，强于MultiType开源库，他们太low

* 支持：
    * AyoSoloAdapter用于RecyclerView，不需继承
    * AyoSoloAdapter2用于ListView，GridView等，不需继承
    * AyoItemTemplate定义了某一个类型的某一种状态的模板，可以注册到AyoSoloAdapter里
    * 所有RecyclerView，都使用AyoSoloAdapter
    * ListView和GridView类似
    * AyoItemTemplat替代不了自定义View，如订单或商城item，需要配合使用
    * 没有注册的模板，会自动使用GuardItemTemplate，代替之前的fallback功能

用法：
```

List<AyoItemTemplate> t = new ArrayList<>();
t.add(new TopTemplate(getActivity()));
t.add(new Top2Template(getActivity()));

AyoSoloAdapter<TopBean> adapter = new AyoSoloAdapter<>(getActivity(), t);
mXRecyclerView.setAdapter(adapter);

```

定义Item模板：（如果布局不是来自xml，请参考GuardItemTemplate）
```
public class TopTemplate extends AyoItemTemplate {

    public TopTemplate(Activity activity) {
        super(activity);
    }

    @Override
    public boolean isForViewType(ItemBean itemBean, int position) {
        if(itemBean instanceof Top){
            Top t = (Top) itemBean;
            if(t.type == 1) return true;
        }
        return false;
    }

    @Override
    public void onBindViewHolder(ItemBean itemBean, int position, AyoViewHolder holder) {
        Top t = (Top) itemBean;
        View v = holder.findViewById(R.id.xx);
    }

    @Override
    protected int getLayoutId() {
        return R.layout.item_top;
    }
}
```

## Json单字段报错器：JsonTool

* 为何：
    * ios的json解析比较智能，能跳过单字段的错误，不耽误其他字段
    * 所以接口有问题时，ios总是能正常显示，安卓这总是页面找不到，测试老来找安卓
    * 而且好像fastjson不支持忽略单字段的错误（token流解析，不好跳过错误字符后面的内容）
    * 另外，调接口时，总是要看log拷json看错误，比较繁琐
    * 所以有了这么个库：
        * fastjson解析出错之后，可以使用以下代码再解析一下，找出某个单独字段的错误，而且可以忽略单字段错误， 返回一个至少可用的业务bean
        * 弊端：极端情况下会返回一个全是默认值的业务bean
        * 依赖于安卓内置的org.json，最好只用于开发阶段
        * json的错误会自动发邮件
    * 也可以在开发测试阶段配合邮箱监控一些可预见的错误，省掉了一部分插手机看log找错误的工作

```
json = "{\"product_code\":\"dxn040\",\"type_id\":\"asas1\",\"title\":\"dxn\\u7403\\u978b2\\u53f7\\u4ed3\",\"sale_price\":\"0.01\",\"list_price\":\"100\",\"postage\":0,\"is_haitao\":{},\"is_buyable\":true,\"sales\":3,\"status\":{\"key\":\"in_stock\",\"title\":\"\\u552e\\u5356\\u4e2d\",\"color\":\"\"},\"tags\":[{\"type\":\"text\",\"value\":\"AG\"},{\"type\":\"text\",\"value\":\"\\u957f\\u9489\"}],\"label\":\"C\\u7f57\\u540c\\u6b3e\",\"labels\":[{\"type\":\"image\",\"value\":\"http:\\/\\/test1-img1.dongqiudi.net\\/www\\/M00\\/00\\/13\\/ChOOulfstbmAZmBJAAAJcdrkyj0236.png\",\"layer\":1},{\"type\":\"text\",\"value\":\"\\u513f\\u7ae5\\u6b3e\",\"layer\":2}],\"groups\":null,\"spec_img\":{\"url\":\"http:\\/\\/test1-img1.dongqiudi.net\\/www\\/M00\\/0C\\/44\\/ChOOuljlsO2AfX52AABiqK0Ixpk347.png\",\"width\":510,\"height\":255},\"good_comment\":{},\"show_img_url\":\"http:\\/\\/test1-img1.dongqiudi.net\\/www\\/M00\\/0C\\/44\\/ChOOuljlsMyADj3RAACa2npzVIA143.png\",\"slides\":[{\"img_url\":\"http:\\/\\/test1-img1.dongqiudi.net\\/www\\/M00\\/0C\\/44\\/ChOOuljlsMyADj3RAACa2npzVIA143.png\"}],\"warehouse_info\":\"\\u5317\\u4eac2\\u53f7\\u4ed3\\uff0c16:45\\u4e4b\\u524d\\u8ba2\\u5355\\u5f53\\u65e5\\u53d1\\u8d27\\uff0c16:45\\u4e4b\\u540e\\u8ba2\\u5355\\u6b21\\u65e5\\u53d1\\u8d27\",\"warehouse_id\":\"3\",\"description\":\"https:\\/\\/test1-mall.dongqiudi.com\\/api\\/product\\/attribute?code=dxn040&type=base\",\"more_detail\":\"https:\\/\\/test1-mall.dongqiudi.com\\/api\\/product\\/attribute?code=dxn040\",\"comment_total\":0,\"go_pick_tips\":\"\\u8bf7\\u9009\\u62e9\\u989c\\u8272\\u5c3a\\u7801\",\"service\":[{\"title\":\"\\u6b63\\u54c1\\u4fdd\\u8bc1\",\"img_url\":\"http:\\/\\/test1-img1.dongqiudi.net\\/fastdfs\\/M00\\/00\\/18\\/oYYBAFaaHseAAXdAAAAHyXmS-0M222.png\",\"scheme\":\"dongqiudi:\\/\\/\\/news\\/143584\"},{\"title\":\"\\u987a\\u4e30\\u5305\\u90ae\",\"img_url\":\"http:\\/\\/test1-img1.dongqiudi.net\\/fastdfs\\/M00\\/00\\/18\\/oYYBAFaaHseAAXdAAAAHyXmS-0M222.png\",\"scheme\":\"dongqiudi:\\/\\/\\/news\\/143584\"},{\"title\":\"\\u65e0\\u5fe7\\u552e\\u540e\",\"img_url\":\"http:\\/\\/test1-img1.dongqiudi.net\\/fastdfs\\/M00\\/00\\/18\\/oYYBAFaaHseAAXdAAAAHyXmS-0M222.png\",\"scheme\":\"dongqiudi:\\/\\/\\/news\\/143584\"}],\"recommend_product_list\":[{\"product_code\":\"\\u8001\\u7248\\u672c\\u6570\\u636e\\u6d4b\\u8bd5\",\"type_id\":\"1\",\"img_url\":\"http:\\/\\/test1-img1.dongqiudi.net\\/www\\/M00\\/00\\/17\\/ChOOulgAUAmAHwi_AAAEJfDvq6w016.png\",\"title\":\"\\u5f00\\u6253\\u7684\\u6162\\u662f\\u62c9\\u7f8e\\u7684\\u62c9\\u8428\",\"sale_price\":\"299\",\"list_price\":\"399\",\"postage\":\"0\",\"is_haitao\":false,\"is_buyable\":true,\"sales\":2,\"status\":{\"key\":\"in_stock\",\"title\":\"\\u552e\\u5356\\u4e2d\",\"color\":\"\"},\"tags\":[{\"type\":\"text\",\"value\":\"SG\"},{\"type\":\"text\",\"value\":\"\\u77ed\\u9489\"}],\"label\":\"\\u6885\\u897f\\u540c\\u6b3e\",\"labels\":[{\"type\":\"image\",\"value\":\"http:\\/\\/test1-img1.dongqiudi.net\\/www\\/M00\\/00\\/13\\/ChOOulfstiCAJ3VOAAAMnHcqOb8588.png\",\"layer\":1},{\"type\":\"text\",\"value\":\"\\u7403\\u661f\\u540c\\u6b3e\",\"layer\":2}]},{\"product_code\":\"dxn041\",\"type_id\":\"1\",\"img_url\":\"http:\\/\\/test1-img1.dongqiudi.net\\/www\\/M00\\/0C\\/44\\/ChOOuljluZSAbirtAAC81Ri9QMk631.jpg\",\"title\":\"dxn041\\u7403\\u978b\\u9999\\u6e2f\\u4ed3\",\"sale_price\":\"0.01\",\"list_price\":\"300\",\"postage\":\"0\",\"is_haitao\":true,\"is_buyable\":true,\"sales\":3,\"status\":{\"key\":\"in_stock\",\"title\":\"\\u552e\\u5356\\u4e2d\",\"color\":\"\"},\"tags\":[{\"type\":\"text\",\"value\":\"AG\"},{\"type\":\"text\",\"value\":\"\\u957f\\u9489\"}],\"label\":\"\\u6885\\u897f\\u540c\\u6b3e\",\"labels\":[{\"type\":\"image\",\"value\":\"http:\\/\\/test1-img1.dongqiudi.net\\/www\\/M00\\/00\\/13\\/ChOOulfstdyAABSeAAANHQo3fRI038.png\",\"layer\":1},{\"type\":\"text\",\"value\":\"\\u6885\\u897f\\u540c\\u6b3e\",\"layer\":2}]},{\"product_code\":\"\\u591a\\u89c4\\u5219\\u6a21\\u677f2\",\"type_id\":\"1\",\"img_url\":\"http:\\/\\/test1-img1.dongqiudi.net\\/www\\/M00\\/00\\/10\\/ChOOulfg3_aAUxfEAACUin7WpGc669.jpg\",\"title\":\"\\u591a\\u89c4\\u5219\\u6a21\\u677f2\\u3010\\u5317\\u4eac\\u4ed3\\u5e93\\u3011\",\"sale_price\":\"0.02\",\"list_price\":\"1\",\"postage\":\"0\",\"is_haitao\":false,\"is_buyable\":true,\"sales\":6,\"status\":{\"key\":\"in_stock\",\"title\":\"\\u552e\\u5356\\u4e2d\",\"color\":\"\"},\"tags\":[{\"type\":\"text\",\"value\":\"AG\"},{\"type\":\"text\",\"value\":\"\\u77ed\\u9489\"}],\"label\":\"\\u6885\\u897f\\u540c\\u6b3e\",\"labels\":[]}]}";
GoodsDetailModel g = JsonSplitter.toObject(json, GoodsDetailModel.class);
```

报错信息如下：
```
04-06 18:58:04.259 32256-32378/? E/jssssssss: 解析错误，字段是(good_comment), 原因: 列表根本性错误，需要个[], 原始json是：{}
04-06 18:58:04.260 32256-32378/? E/jssssssss: 解析错误，字段是(groups), 原因: 列表根本性错误，需要个[], 原始json是：null
04-06 18:58:04.263 32256-32378/? E/jssssssss: 解析错误，字段是(is_haitao), 原因: getBoolean 错误--Value {} at is_haitao of type org.json.JSONObject cannot be converted to boolean, 原始json是：{}

同时得到一个可以用的业务bean对象
```

并且能自动发邮件给你设定的邮箱，邮箱在JsonTool类里设置