# AyoComponent基础库

对应的

内容包括：
- Activity，Fragment组件，UI组件基于Fragmentation
    - 支持Activity核心或者Fragment核心
    - 支持免manifest的Fragment核心，支持Schema和内存重启
- WorkThread的问题：是用IntentService，还是HandlerThread，还是开线程池，还是用AsyncTask，还是直接new Thread
- Service，Provider，Receiver

没整到jcenter上，暂时就用aar包吧，也挺省劲：[下载地址](https://github.com/cowthan/AyoCompoment/blob/master/ayo-component.aar?raw=true)
下载下来文件名是：ayo-component.aar，拷到libs目录里
然后在build.gradle里引入：
```
repositories {
    flatDir {
        dirs 'libs'
    }
}

compile(name:'ayo-component', ext:'aar')
```



目录：
- MasterFragment使用
    - 主题：Theme，Material Theme
    - 模板Activity声明：内置和自定义，Activity在manifest里配置的可选项
    - 屏幕旋转
    - SystemBar一体化问题
    - 启动加速
    - ayo-menu的使用：写demo时用的菜单组件
- WorkThread，Service问题
- ContentProvider是否应该作为你app内数据库管理的第一选择
- Receiver和EventBus的选择，EventBus能跨进程吗
- 多进程问题的考虑

## 1 MasterFragment使用

* Master框架都提供了：
    * Fragmentation的拷贝和修改（生命周期相关修改）
    * MasterFragment：基于Fragmentation，可以：
        * 通过Master的startPage方法和一个预先声明的模板Activity绑定，达到免manifest的目的
        * 可以嵌在MasterActivity里，这个方式就是最传统的方式，用Activity加载Fragment
        * 可以嵌在MasterFragment里，这个方式也是传统的方式，用Fragment加载Fragment
    * 不论是MasterActivity，还是MasterFragment，都可以：
        * 加载一个Fragment，成为其父
        * 同时显示多个Fragment，成为他们的父
        * 同时打开多个，并通过show和hide控制，这些child之间不会有后退关系
        * 通过ViewPager管理多个Fragment
        * 打开多个，但不是同时打开，通过add，pop等控制，会有后退关系
    * MasterFragment和MasterActivity，还考虑了：
        * 和Fragment相关的内存重启，需要注意在create时判断saveStateInstance是否为空，不要重复load fragment
            * fragment本身会在其父中被save和恢复，只需要读出来
            * fragment的setArgument带的参数会save和恢复，只需要读出来
        * Schema
        * 过场动画
        * Transition动画
        * 滑动返回
        * SystemBar一体化
        * 生命周期，便于统计
        * Fragment可见和不可见的生命周期，以及是否第一次可见，都保证View已经初始化，代替ViewPager的setUserHintVisi..，可以真正实现Fragment各处通用


### 1 到底用什么UI框架

* 先说说都有什么UI框架
    * 本项目的初衷：免Manifest框架
        * tmpl里的几个Activity需要事先声明，当然也可以自己定制，自己处理Fragment加载和schema等问题
        * 实现业务Fragment，需要继承MasterFragment，注意几个生命周期
        * 打开Fragment：
            * 你可以再写一个Activity（继承MasterActivty），来加载你的Fragment，这是传统套路
            * 你也可以通过Master里的startPage方法，打开你的Fragment，这个方式可以不用在manifest再声明什么
        * 支持MasterTabFragment，和MasterPagerFragment，这俩是Tab页和ViewPager页
    * 由于拷进了Fragmentation，所以也支持它的几个典型框架了：
        * 一个Activity，多个Fragment：这些Fragment可以是平级，有栈关系，有for result关系
        * 多个Activity，多个Fragment：和单Activity本质一样
        * 关于Fragmentation的高级用法，咱先不考虑，暂时就把Fragmentation当成一个封装了Fragment生命周期的功能模块

### 2 Theme配置


Master是基于AppCompatActivity，也就只能用AppCompat的主题，这类主题会按照API 21划分，21以上会加载Material的主题，所以也支持Transition等design的东西

```
------values目录的style.xml
<style name="AppTheme" parent="Theme.AppCompat.Light.NoActionBar">
	<!-- Customize your theme here. -->
	<item name="colorPrimary">@color/colorPrimary</item>
	<item name="colorPrimaryDark">@color/colorPrimaryDark</item>
	<item name="colorAccent">@color/colorAccent</item>

	<item name="android:windowNoTitle">true</item>
	<item name="android:windowContentOverlay">@null</item>
	<item name="android:windowBackground">@android:color/transparent</item>
</style>

<style name="AppTheme.Splash" parent="Theme.AppCompat.Light.NoActionBar">
	<item name="android:windowBackground">@drawable/logo</item>
</style>

<style name="AppTheme.Transparent" parent="Theme.AppCompat.Light.NoActionBar">
	<item name="android:windowIsTranslucent">true</item>
	<item name="android:windowAnimationStyle">@style/Animation.Activity.Translucent.Style</item>
</style>

<style name="Animation.Activity.Style" parent="@android:style/Animation.Activity">
    <item name="android:activityOpenEnterAnimation">@anim/base_slide_in_from_right</item>
    <item name="android:activityOpenExitAnimation">@anim/base_hold_stand</item>
    <item name="android:activityCloseEnterAnimation">@anim/base_hold_stand</item>
    <item name="android:activityCloseExitAnimation">@anim/base_slide_out_to_right</item>
    <item name="android:taskOpenEnterAnimation">@anim/base_slide_in_from_right</item>
    <item name="android:taskOpenExitAnimation">@anim/base_hold_stand</item>
    <item name="android:taskCloseEnterAnimation">@anim/base_hold_stand</item>
    <item name="android:taskCloseExitAnimation">@anim/base_slide_out_to_right</item>
    <item name="android:taskToFrontEnterAnimation">@anim/base_slide_in_from_right</item>
    <item name="android:taskToFrontExitAnimation">@anim/base_hold_stand</item>
    <item name="android:taskToBackEnterAnimation">@anim/base_hold_stand</item>
    <item name="android:taskToBackExitAnimation">@anim/base_slide_out_to_right</item>
</style>

<style name="Animation.Activity.Translucent.Style" parent="@android:style/Animation.Translucent">
    <item name="android:windowEnterAnimation">@anim/base_slide_in_from_right</item>
    <item name="android:windowExitAnimation">@anim/base_slide_out_to_right</item>
</style>

-------values-21目录的style.xml
<style name="AppTheme" parent="Theme.AppCompat.Light.NoActionBar">
	<!-- Customize your theme here. -->
	<item name="colorPrimary">@color/colorPrimary</item>
	<item name="colorPrimaryDark">@color/colorPrimaryDark</item>
	<item name="colorAccent">@color/colorAccent</item>
	<item name="android:textColorPrimary">@color/textColorPrimary</item>
	<item name="android:navigationBarColor">@color/navigationBarColor</item>
	<item name="android:colorControlHighlight">@color/colorControlHighlight</item>

	<item name="android:windowNoTitle">true</item>
	<item name="android:windowContentOverlay">@null</item>
	<item name="android:windowBackground">@android:color/transparent</item>
</style>


------colors.xml
<?xml version="1.0" encoding="utf-8"?>
<resources>
    <color name="colorPrimary">#ff0000</color>
    <color name="colorPrimaryDark">#ffff00</color>
    <color name="colorAccent">#00ff00</color>
    <color name="colorControlHighlight">#e60000</color>
    <color name="textColorPrimary">#000000</color>
    <color name="navigationBarColor">#ff00ff</color>

    <color name="green">#a3c639</color>
    <color name="dark_green">#85a71d</color>
    <color name="yellow">#ff0</color>
    <color name="gray">#ccc</color>
    <color name="pink">#e91e63</color>
</resources>


-------logo.xml
<?xml version="1.0" encoding="utf-8"?>
<layer-list xmlns:android="http://schemas.android.com/apk/res/android" >
    <item>

        <shape android:shape="rectangle" >
            <solid android:color="#ffffff" />
        </shape>
    </item>

    <item android:bottom="48dp">
        <bitmap
            android:gravity="center"
            android:src="@drawable/img1" />
    </item>
</layer-list>

```

关于Color Pallete：
- 单词我都不知道有没有拼对
- 有图就不用说话：
!()[./img/l-design.png)


关于windowIsTranslucent：
- 大多数情况下，是要配合swipe back功能来使用，所以模板Activity为了考虑所有情况，最好使用AppTheme.Transparent
- 这个设为true会引入一些问题，具体我现在都想不起来，以后慢慢在这记录吧
- 第一个问题：Activity切换动画无效化了，需要自己设置，参考windowAnimationStyle

Splash主题为什么单独拎出来：
- 主要是为了设置一个特殊的windowBackground，避免app打开过慢时出现白屏或者没反应的尴尬，以一个背景占位
- 所以Splash初次打开时，待在屏幕上的时间可能会比你设置的时间要长，长出来的时间就是app初始化时间
- 关于冷启动，热启动的优化，参考这里：http://www.jianshu.com/p/f5514b1a826c?utm_campaign=hugo&utm_medium=reader_share&utm_content=note&utm_source=qq


### 3 Manifest配置：

下面是manifest配置：
- 注意，四个模板的schema访问方式是：ayo://page/standard?page=fragment全限定名&name=value"
    - 其中，name和value可以有多对，都会被放到Fragment的argument里，相当于通过bundle传参数
    - page参数一定要有
    - 如果没有page，则表示打开的不是一个模板Activity，而是一个具体Acitity，其内部已经知道自己要加载哪个Fragment了



```
<activity android:name=".SplashActivity"
    android:configChanges="orientation|screenSize|keyboardHidden|navigation"
    android:screenOrientation="portrait"
    android:launchMode="standard"
    android:theme="@style/AppTheme.Splash">
    <intent-filter>
        <action android:name="android.intent.action.MAIN" />
        <category android:name="android.intent.category.LAUNCHER" />
    </intent-filter>
</activity>
<activity android:name=".MainActivity"
    android:configChanges="orientation|screenSize|keyboardHidden|navigation"
    android:screenOrientation="portrait"
    android:launchMode="standard"
    android:theme="@style/AppTheme">
    <intent-filter>
        <action android:name="android.intent.action.MAIN" />
        <category android:name="android.intent.category.LAUNCHER" />
    </intent-filter>
</activity>

<activity
    android:name="org.ayo.component.tmpl.TmplStarndardActivity"
    android:configChanges="orientation|screenSize|keyboardHidden|navigation"
    android:screenOrientation="portrait"
    android:launchMode="standard"
    android:theme="@style/AppTheme.Transparent" >
    <intent-filter>
        <category android:name="android.intent.category.DEFAULT"/>
        <action android:name="android.intent.action.VIEW"/>
        <category android:name="android.intent.category.BROWSABLE"/>
        <data android:scheme="ayo" android:host="page" android:path="/standard" />
    </intent-filter>
</activity>
<activity
    android:name="org.ayo.component.tmpl.TmplSingleTopActivity"
    android:configChanges="orientation|screenSize|keyboardHidden|navigation"
    android:screenOrientation="portrait"
    android:launchMode="singleTop"
    android:theme="@style/AppTheme.Transparent"  >
    <intent-filter>
        <category android:name="android.intent.category.DEFAULT"/>
        <action android:name="android.intent.action.VIEW"/>
        <category android:name="android.intent.category.BROWSABLE"/>
        <data android:scheme="ayo" android:host="page" android:path="/singletop" />
    </intent-filter>
</activity>
<activity
    android:name="org.ayo.component.tmpl.TmplSingleTaskActivity"
    android:configChanges="orientation|screenSize|keyboardHidden|navigation"
    android:screenOrientation="portrait"
    android:launchMode="singleTask"
    android:theme="@style/AppTheme.Transparent"  >
    <intent-filter>
        <category android:name="android.intent.category.DEFAULT"/>
        <action android:name="android.intent.action.VIEW"/>
        <category android:name="android.intent.category.BROWSABLE"/>
        <data android:scheme="ayo" android:host="page" android:path="/singletask" />
    </intent-filter>
</activity>
<activity
    android:name="org.ayo.component.tmpl.TmplSingleInstanceActivity"
    android:configChanges="orientation|screenSize|keyboardHidden|navigation"
    android:screenOrientation="portrait"
    android:launchMode="singleInstance"
    android:theme="@style/AppTheme.Transparent"  >
    <intent-filter>
        <category android:name="android.intent.category.DEFAULT"/>
        <action android:name="android.intent.action.VIEW"/>
        <category android:name="android.intent.category.BROWSABLE"/>
        <data android:scheme="ayo" android:host="page" android:path="/singleinstance" />
    </intent-filter>
</activity>
```

### 4 自定义模板Activity

可能有些业务需要你自己定义模板Activity，如支持屏幕旋转，需要在manifest配置个性化参数等，然后在manifest声明
```
public class CustomTmplActivity extends TmplBaseActivity {

}

<activity
    android:name=".master.CustomTmplActivity"
    android:configChanges="orientation|screenSize|keyboardHidden|navigation"
    android:screenOrientation="portrait"
    android:launchMode="standard"
    android:theme="@style/AppTheme.Transparent"  >
    <intent-filter>
        <category android:name="android.intent.category.DEFAULT"/>
        <action android:name="android.intent.action.VIEW"/>
        <category android:name="android.intent.category.BROWSABLE"/>
        <data android:scheme="ayo" android:host="page" android:path="/custom1" />
    </intent-filter>
</activity>
```

### 5 实现业务逻辑

开始干活了
- `实现业务Fragment了，继承MasterFragment，MasterTabFragment，MasterPagerFragment吧`
- 不过需要注意的是，一般你的项目内不会直接继承MasterFragment什么的，而是会先提供一层基类
    - 在基类里能做一些统计之类的事
    - 基类，或者第三方库的一层封装，能让你的业务逻辑和外部库解耦，方便替换和修改

MasterFragment的生命周期：
- onCreate2(View contentView, @Nullable Bundle savedInstanceState)
- onDestory2()
- onPageVisibleChanged(boolean visible, boolean isFirstTimeVisible, @Nullable Bundle savedInstanceState)
- 这里如果有疑问，建议先去看看Fragmentation的文档和demo，虽然我们没有用到它所有功能，但思想上的事，都是相通的
- 地址在这：https://github.com/YoKeyword/Fragmentation
- 另外，切记不要直接用Fragmentation升级本库，为了生命周期的顺序问题，其源码我已经修改过了，后面会对这里的修改做标记

提供了几个样板页：
- 普通页面：MasterFragment
- Tab页：MasterTabFragment
- ViewPager页：MasterPagerFragment
- 列表页：还没
- Splash页：还没

```
public abstract class BasePage extends MasterFragment {

    /**
     * 所有页面通过这个方法来打开
     * @param a
     * @param pageClass
     * @param bundle
     * @param tmplClass
     */
    public static void startPage(Activity a, Class<? extends MasterPage> pageClass, Bundle bundle, Class<? extends TmplBaseActivity> tmplClass){
        Master.startPage(a, pageClass, bundle, tmplClass);
        //如果extends MasterActivity，则这里换成：Master.startActivity(a, pageClass, bundle);
    }
    /**
     * 所有页面通过这个方法来打开
     * @param a
     * @param pageClass
     * @param bundle
     */
    public static void startPage(Activity a, Class<? extends MasterPage> pageClass, Bundle bundle){
        Master.startPage(a, pageClass, bundle);
        //如果extends MasterActivity，则这里换成：Master.startActivity(a, pageClass, bundle);
    }

}


public class DemoFragment extends BasePage {


    @Override
    protected int getLayoutId() {
        return R.layout.ac_demo_component;
    }

    @Override
    protected void onCreate2(View contentView, @Nullable Bundle savedInstanceState) {
        Toaster.toastShort(getActivity().getClass().getName());
        TextView title = (TextView) contentView.findViewById(R.id.title);
        TextView title2 = (TextView) contentView.findViewById(R.id.title2);

        title.setText(getActivity().getClass().getName());
        title2.setText("haha = " + getArguments().get("haha"));
    }

    @Override
    protected void onDestroy2() {

    }

    @Override
    protected void onPageVisibleChanged(boolean visible, boolean isFirstTimeVisible, @Nullable Bundle savedInstanceState) {
        String s = "onPageVisibleChanged--{dd}->" + getClass().getSimpleName() + ", " + (isFirstTimeVisible ? "是" : "非") + "第一次";
        s = s.replace("{dd}", visible ? "来了" : "走了");
        Log.i("MainActivity", s);
    }

}
```

### 6 打开界面

```
最原始的打开界面方法是Master类里的这个方法：
public static void startPage(Activity a, Class<? extends MasterFragment> clazz, Bundle b, Class<? extends Activity> tmplActvity){
    Intent intent = new Intent(a, tmplActvity);
    intent.putExtra("data", b);
    intent.putExtra("page", clazz.getName());
    a.startActivity(intent);
}

public static void startPageForResult(Activity a, Class<? extends MasterFragment> clazz, Bundle b, Class<? extends Activity> tmplActvity, int requestCode){
    Intent intent = new Intent(a, tmplActvity);
    intent.putExtra("data", b);
    intent.putExtra("page", clazz.getName());
    a.startActivityForResult(intent, requestCode);
}

大体上所有的trick都在这里了，其实这没啥，就是指定打开哪个Activity，让它加载哪个Fragment，哎卧槽，一点儿没技术含量
```

接收返回结果：
```
@Override
public void onActivityResult(int requestCode, int resultCode, Intent data) {
    super.onActivityResult(requestCode, resultCode, data);
}
```

### 7 状态栏一体化

```
注意，这两个方法总是得一块用，不要单独用
///status bar和navigate bar的颜色，如果是浅色，还需要配合SystamBarExtra里的方法加以优化，但不支持三星手机
getAgent().renderSystemBar(Color.YELLOW, Color.GREEN);
///status bar和navigate bar是否被侵占
getAgent().enableSystemBarTakenByContent(false);


这两个方法其实是对应着主题里的：
<item name="colorPrimary">@color/colorPrimary</item>
<item name="colorPrimaryDark">@color/colorPrimaryDark</item>
<item name="colorAccent">@color/colorAccent</item>
和布局里的：
fitSystemWindow="true"

当然，代码里设置的优先级比较高
```

下面多说点，systembar涉及到两个问题：
- 问题1：颜色设置，可以开启和关闭，分status bar和navigation bar
- 问题2：是否侵入，可以开启和关闭，和颜色设置不冲突

代码：
```java
@Override
protected void onCreate(Bundle savedInstanceState) {
    super.onCreate(savedInstanceState);
    setContentView(R.layout.sample_ac_main);

    //关闭StatusBar和NavigationBar侵入
    getAgent().enableSystemBarTakenByContent(false);

    //给StatusBar和NavigaionBar染色
    getAgent().renderSystemBar(Color.parseColor("#55ff0000"), Color.parseColor("#55ff0000"));

}

```

* 解析：
    * 这里就是对开源代码SystemBarTintManager的简单封装
    * enableSystemBarTakenByContent其实就是设置根布局的`android:fitsSystemWindows`属性


fitSystemWindows是true时：enableSystemBarTakenByContent(false)，内容给SystemBar留空
![](./img/mm2.png)

fitSystemWindows是false时：enableSystemBarTakenByContent(true)，内容侵入SystemBar
![](./img/mm3.png)


其他问题：
- 关于clipToPadding和clipToChildren：默认都为true
- http://www.jcodecraeer.com/a/anzhuokaifa/androidkaifa/2015/0317/2613.html
- 好像和滚动有关，可以上下滚动时，内容是否可以滚动到标题栏里

```
<ListView
    android:layout_gravity="center_vertical"
    android:id="@+id/list"
    android:clipChildren="false"
    android:clipToPadding="false"
    android:paddingTop="50dip"
    android:layout_width="match_parent"
    android:layout_height="match_parent" />
```

ListView初始化之后，由于top的50dp的padding，看似顶着标题栏，但往上滚动时，内容就会跑到padding的50dp里，也就能从标题栏看到了（如果标题栏带透明）


实际用例，还得考虑systembar的背景变成浅色时，字体颜色的问题，但三星手机好像不太支持状态栏背景浅色（会变成灰色代替）
```java
public static void breakerSystemBar(AyoActivity a){
    a.agent.enableSystemBarTakenByContent(false);
    a.agent.renderSystemBar(Color.WHITE, Color.WHITE);

    /// 下面这三个控制状态栏字体颜色，分别管：6.0， MIUI，魅族系统----当systembar的背景被设置成浅色，字体就得变成深色（一般情况下背景是深色，字体默认是浅色）
    if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.M) {
        a.getWindow().getDecorView().setSystemUiVisibility( View.SYSTEM_UI_FLAG_LAYOUT_FULLSCREEN|View.SYSTEM_UI_FLAG_LIGHT_STATUS_BAR);
    }
    MIUISetStatusBarLightMode(a.getWindow(), true);
    FlymeSetStatusBarLightMode(a.getWindow(), true);

}

public static boolean MIUISetStatusBarLightMode(Window window, boolean dark) {
    boolean result = false;
    if (window != null) {
        Class clazz = window.getClass();
        try {
            int darkModeFlag = 0;
            Class layoutParams = Class.forName("android.view.MiuiWindowManager$LayoutParams");
            Field field = layoutParams.getField("EXTRA_FLAG_STATUS_BAR_DARK_MODE");
            darkModeFlag = field.getInt(layoutParams);
            Method extraFlagField = clazz.getMethod("setExtraFlags", int.class, int.class);
            if(dark){
                extraFlagField.invoke(window,darkModeFlag,darkModeFlag);//状态栏透明且黑色字体
            }else{
                extraFlagField.invoke(window, 0, darkModeFlag);//清除黑色字体
            }
            result=true;
        }catch (Exception e){

        }
    }
    return result;
}

public static boolean FlymeSetStatusBarLightMode(Window window, boolean dark) {
    boolean result = false;
    if (window != null) {
        try {
            WindowManager.LayoutParams lp = window.getAttributes();
            Field darkFlag = WindowManager.LayoutParams.class
                    .getDeclaredField("MEIZU_FLAG_DARK_STATUS_BAR_ICON");
            Field meizuFlags = WindowManager.LayoutParams.class
                    .getDeclaredField("meizuFlags");
            darkFlag.setAccessible(true);
            meizuFlags.setAccessible(true);
            int bit = darkFlag.getInt(null);
            int value = meizuFlags.getInt(lp);
            if (dark) {
                value |= bit;
            } else {
                value &= ~bit;
            }
            meizuFlags.setInt(lp, value);
            window.setAttributes(lp);
            result = true;
        } catch (Exception e) {

        }
    }
    return result;
}
```



### 8 模板Activity声明：内置和自定义，Activity在manifest里配置的可选项


### 9 屏幕旋转


### 10 ayo-menu的使用：写demo必备小帮手




### 11 过场动画，和Transition动画