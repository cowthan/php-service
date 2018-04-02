# MeterialDesign详解

Meterial：CoordinatrLayout
===========================

参考：
http://blog.csdn.net/u010687392/article/details/46852565
http://blog.csdn.net/xyz_lmn/article/details/48055919
http://www.open-open.com/lib/view/open1438265746378.html
http://blog.csdn.net/pengkv/article/details/46429759


这个控件简直了，真TM复杂啊，下面我们一层一层来剖析

* 到底是个啥
    * 它是组织它众多子view之间互相协作的一个ViewGroup，要解决的就是滑动相关的问题
    * CoordinatorLayout 的神奇之处就在于 Behavior 对象
    * CoordinatorLayout使得子view之间知道了彼此的存在，一个子view的变化可以通知到另一个子view
    * CoordinatorLayout 所做的事情就是当成一个通信的桥梁，连接不同的view，使用 Behavior 对象进行通信
    * 可以跟一个AppBarLayout作为子View
    * 然后跟一个滚动控件，LinearLayout、RecyclerView、NestedScrollView等(ScorllView不行）， 并标记app:layout_behavior="@string/appbar_scrolling_view_behavior"
    * 其实是个FrameLayout，所以可以用layout_gravity来控制子控件位置


## 1 ActionBarLayout

```xml
一般用法：

<android.support.design.widget.CoordinatorLayout
    xmlns:android="http://schemas.android.com/apk/res/android"
    xmlns:app="http://schemas.android.com/apk/res-auto"
    android:id="@+id/coordinator_layout"
    android:layout_width="match_parent"
    android:layout_height="match_parent">

    <android.support.design.widget.AppBarLayout
        android:id="@+id/appbar_layout"
        android:layout_width="match_parent"
        android:layout_height="wrap_content"
        android:fitsSystemWindows="true">
        <android.support.v7.widget.Toolbar
            android:id="@+id/toolBar"
            android:layout_width="match_parent"
            android:layout_height="?attr/actionBarSize"
            android:background="#30469b"
            app:layout_scrollFlags="scroll|enterAlways" />
        <android.support.design.widget.TabLayout
            ......
             />
    </android.support.design.widget.AppBarLayout>

    <LinearLayout
        android:layout_width="match_parent"
        android:layout_height="match_parent"
        android:orientation="vertical"
        android:scrollbars="none"
        app:layout_behavior="@string/appbar_scrolling_view_behavior">
 <!-- content view .....-->
    </LinearLayout>

</android.support.design.widget.CoordinatorLayout>

```

* 注意：
    * ToolBar标记了layout_scrollFlags滚动事件，那么当LinearLayout滚动时便可触发ToolBar中的layout_scrollFlags效果
        * 即往上滑动隐藏ToolBar，下滑出现ToolBar
        * 而不会隐藏TabLayout，因为TabLayout没有标记scrollFlags事件，所以TabLayout会吸顶
        * 这个机制只有在AppbarLayout作为CoordinatorLayout的子View时，才会激活
        * 注意：吸顶的应该在被隐藏的控件下面
    * layout_scrollFlags中的几个值：只有在AppBarLayout里才有用
        * scroll: 所有想滚动出屏幕的view都需要设置这个flag， 没有设置这个flag的view将被固定在屏幕顶部
        * enterAlways:这个flag让任意向下的滚动都会导致该view变为可见，启用快速“返回模式”。
        * enterAlwaysCollapsed:当你的视图已经设置minHeight属性又使用此标志时，你的视图只能已最小高度进入，只有当滚动视图到达顶部时才扩大到完整高度。
        * exitUntilCollapsed:滚动退出屏幕，最后折叠在顶端。


## 2 FloatingActionButton

FloatingActionButton是最简单的使用CoordinatorLayout的例子，FloatingActionButton默认使用FloatingActionButton.Behavior。

```xml
<?xml version="1.0" encoding="utf-8"?>
<android.support.design.widget.CoordinatorLayout
    xmlns:android="http://schemas.android.com/apk/res/android"
    xmlns:app="http://schemas.android.com/apk/res-auto"
    android:layout_width="match_parent"
    android:layout_height="match_parent">


    <android.support.design.widget.FloatingActionButton
        android:id="@+id/fab"
        android:layout_width="wrap_content"
        android:layout_height="wrap_content"
        android:layout_gravity="end|bottom"
        android:layout_margin="16dp"
        android:src="@drawable/ic_done" />

</android.support.design.widget.CoordinatorLayout>
```

## 3 CollapsingToolbarLayout

CollapsingToolbarLayout作用是提供了一个可以折叠的Toolbar
所以必须和Toolbar配合使用

效果：
(./doc/img/c1.jpg)

```xml
<?xml version="1.0" encoding="utf-8"?>
<android.support.design.widget.CoordinatorLayout xmlns:android="http://schemas.android.com/apk/res/android"
    xmlns:app="http://schemas.android.com/apk/res-auto"
    android:id="@+id/main_content"
    android:layout_width="match_parent"
    android:layout_height="match_parent"
    android:fitsSystemWindows="true">

    <android.support.design.widget.AppBarLayout
        android:id="@+id/appbar"
        android:layout_width="match_parent"
        android:layout_height="256dp"
        android:theme="@style/ThemeOverlay.AppCompat.Dark.ActionBar"
        android:fitsSystemWindows="true">

        <android.support.design.widget.CollapsingToolbarLayout
            android:id="@+id/collapsing_toolbar"
            android:layout_width="match_parent"
            android:layout_height="match_parent"
            app:layout_scrollFlags="scroll|exitUntilCollapsed"
            android:fitsSystemWindows="true"
            app:contentScrim="?attr/colorPrimary"
            app:expandedTitleMarginStart="48dp"
            app:expandedTitleMarginEnd="64dp">

            <ImageView
                android:id="@+id/backdrop"
                android:layout_width="match_parent"
                android:layout_height="match_parent"
                android:scaleType="centerCrop"
                android:fitsSystemWindows="true"
                android:src="@drawable/header"
                app:layout_collapseMode="parallax"
                />

            <android.support.v7.widget.Toolbar
                android:id="@+id/toolbar"
                android:layout_width="match_parent"
                android:layout_height="?attr/actionBarSize"
                app:popupTheme="@style/ThemeOverlay.AppCompat.Light"
                app:layout_collapseMode="pin" />

        </android.support.design.widget.CollapsingToolbarLayout>

    </android.support.design.widget.AppBarLayout>

    <android.support.v4.widget.NestedScrollView
        android:layout_width="match_parent"
        android:layout_height="match_parent"
        app:layout_behavior="@string/appbar_scrolling_view_behavior">

        <LinearLayout
            android:layout_width="match_parent"
            android:layout_height="match_parent"
            android:orientation="vertical"
            android:paddingTop="24dp">

            <android.support.v7.widget.CardView
                android:layout_width="match_parent"
                android:layout_height="wrap_content"
                android:layout_margin="16dp">

                <LinearLayout
                    style="@style/Widget.CardContent"
                    android:layout_width="match_parent"
                    android:layout_height="wrap_content">

                    <TextView
                        android:layout_width="match_parent"
                        android:layout_height="wrap_content"
                        android:text="CardView"
                        android:textAppearance="@style/TextAppearance.AppCompat.Title" />

                    <TextView
                        android:layout_width="match_parent"
                        android:layout_height="wrap_content"
                        android:text="@string/card_string" />

                </LinearLayout>

            </android.support.v7.widget.CardView>
          ……
        </LinearLayout>

    </android.support.v4.widget.NestedScrollView>

    <android.support.design.widget.FloatingActionButton
        android:layout_height="wrap_content"
        android:layout_width="wrap_content"
        app:layout_anchor="@id/appbar"
        app:layout_anchorGravity="bottom|right|end"
        android:src="@drawable/ic_done"
        android:layout_margin="@dimen/fab_margin"
        android:clickable="true"/>

</android.support.design.widget.CoordinatorLayout>
```

Meterial：TabLayout
===========================

* 这个就是indicator的官方框架
    * 涉及到TabLayout, Tab, TabView, TabItem
    * 可以自己定义item怎么显示
        * 参考：http://www.jianshu.com/p/7f79b08f5afa
        * 参考：http://blog.csdn.net/chendong_/article/details/53044528
    * 默认的样式是文字+横线


## 1 基本使用

```
<android.support.design.widget.TabLayout
            android:id="@+id/tabLayout"
            android:layout_width="match_parent"
            android:layout_height="wrap_content"
            android:background="#30469b"
            app:tabGravity="fill"
            app:tabMode="fixed"
            app:tabSelectedTextColor="#ff0000"
            app:tabTextColor="#ffffff" />
```

* TabLayout实现了：
    * 固定的Tab，根据TabLayout的宽度适配每个Item的宽度
    * 固定的Tab，在TabLayout中居中显示所有Item
    * 可滑动的Tab

(./doc/img/TL1.gif)
(./doc/img/TL2.gif)
(./doc/img/TL3.gif)

* 常用属性：
    * tabGravity  —Tab的重心，有填充和居中两个值，为别为fill和center
    * tabMode  —Tab的模式，有固定和滚动两个模式，分别为 fixed 和 scrollable
    * tabTextColor  —设置默认状态下Tab上字体的颜色
    * tabSelectedTextColor  —设置选中状态下Tab上字体的颜色


```java
在代码中动态加入Tab
TabLayout mTabLayout = (TabLayout) findViewById(R.id.tabLayout);
mTabLayout.addTab(mTabLayout.newTab().setText("TabOne"));//给TabLayout添加Tab
mTabLayout.addTab(mTabLayout.newTab().setText("TabTwo"));
mTabLayout.addTab(mTabLayout.newTab().setText("TabThree"));
//给TabLayout设置关联ViewPager，如果设置了ViewPager，那么ViewPagerAdapter中的getPageTitle()方法返回的就是Tab上的标题

设置ViewPager
ViewPager mViewPager = (ViewPager) findViewById(R.id.viewpager);
MyViewPagerAdapter viewPagerAdapter = new MyViewPagerAdapter(getSupportFragmentManager());
viewPagerAdapter.addFragment(FragmentOne.newInstance(), "TabOne");//添加Fragment
viewPagerAdapter.addFragment(FragmentTwo.newInstance(), "TabTwo");
viewPagerAdapter.addFragment(FragmentThree.newInstance(), "TabThree");
mViewPager.setAdapter(viewPagerAdapter);//设置适配器

连接TabLayout和ViewPager
mTabLayout.setupWithViewPager(mViewPager);

```

## 2 自定义样式

http://blog.csdn.net/chendong_/article/details/53044528

http://www.jianshu.com/p/7f79b08f5afa


Meterial：主题
===========================


## 1 主题颜色

我们一般用AppCompatActivity，也就只能用AppCompat的主题，这类主题会按照21划分，21以上会加载Material的主题

```
values目录的style.xml

<style name="AppTheme" parent="Theme.AppCompat.Light.NoActionBar">
	<!-- Customize your theme here. -->
	<item name="colorPrimary">@color/colorPrimary</item>
	<item name="colorPrimaryDark">@color/colorPrimaryDark</item>
	<item name="colorAccent">@color/colorAccent</item>

	<item name="android:windowNoTitle">true</item>
	<item name="android:windowContentOverlay">@null</item>
	<item name="android:windowBackground">@drawable/logo</item>
</style>

<style name="AppTheme.Transparent" parent="Theme.AppCompat.Light.NoActionBar">
	<item name="android:windowIsTranslucent">true</item>
	<item name="android:windowAnimationStyle">@style/Animation.Activity.Translucent.Style</item>
</style>

values-21目录的style.xml
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
	<item name="android:windowBackground">@drawable/logo</item>
</style>

```

Meterial：Toolbar
===========================

参考：
http://www.jcodecraeer.com/a/anzhuokaifa/androidkaifa/2014/1118/2006.html

* 由这个Toolbar引出来的几个地方还是挺奇葩的：
    * systembar设置成浅色背景的问题，字体就需要设置成深色
        * 但只支持小米，魅族，6.0以上系统
        * 但6.0的三星还是没法把systembar设置成白色，一旦给白色，就会显示成灰条
    * clipToPadding和clipToChildren的意思，还是没弄懂
    * windowIsTranlusent的问题，swipeback需要吗
    * Toolbar还是没法满足国内的标题栏需求啊

## 1 关于Style

Toolbar取代了ActionBar，只有api-21才能用，所以得用support-v7里的toolbar

使用 Toolbar，首先要将让原本的 ActionBar 隐藏起来

先整一下style

* res/values-v21/styles.xml  是安卓5.0的样式
* res/values/styles.xml

使用toolbar时，上面两个style都需要调整

```xml
<resources>

  <!-- Base application theme. -->
  <style name="AppTheme" parent="AppTheme.Base">
  </style>

  <style name="AppTheme.Base" parent="Theme.AppCompat.NoActionBar">
    <item name="windowActionBar">false</item>
    <del><item name="android:windowNoTitle">true</item></del>
    <!-- 使用 API Level 22 编译的话，要拿掉前缀字 -->
    <item name="windowNoTitle">true</item>
  </style>

</resources>

5.0里
<resources>
    <style name="AppTheme" parent="AppTheme.Base">
    </style>
</resources>

注意：
1 AppTheme.Base是我们自己加的，就是要隐藏ActionBar
2 注意用api-22编译，要拿掉前缀字，为啥
```


## 2 布局

```xml
<android.support.v7.widget.Toolbar
  android:id="@+id/toolbar"
  android:layout_height="?attr/actionBarSize"
  android:layout_width="match_parent" >

</android.support.v7.widget.Toolbar>
```


```xml
菜单：
<menu xmlns:android="http://schemas.android.com/apk/res/android"
      xmlns:app="http://schemas.android.com/apk/res-auto"
      xmlns:tools="http://schemas.android.com/tools"
      tools:context=".MainActivity">

  <item android:id="@+id/action_edit"
        android:title="@string/action_edit"
        android:orderInCategory="80"
        android:icon="@drawable/ab_edit"
        app:showAsAction="ifRoom" />

  <item android:id="@+id/action_share"
        android:title="@string/action_edit"
        android:orderInCategory="90"
        android:icon="@drawable/ab_share"
        app:showAsAction="ifRoom" />

  <item android:id="@+id/action_settings"
        android:title="@string/action_settings"
        android:orderInCategory="100"
        app:showAsAction="never"/>
</menu>


```

看起来：
(./doc/img/toobar/t2.png)


所有可以设置的样式：
(./doc/img/toobar/t1.png)


说明：

* colorPrimaryDark
    * 状态栏背景色。
    * 在 style 的属性中设置。
* textColorPrimary
    * App bar 上的标题与更多菜单中的文字颜色。
    * 在 style 的属性中设置。
* App bar 的背景色
    * Actionbar 的背景色设定在 style 中的 colorPrimary。
    * Toolbar 的背景色在layout文件中设置background属性。
* colorAccent
    * 各控制元件(如：check box、switch 或是 radoi) 被勾选 (checked) 或是选定 (selected) 的颜色。
    * 在 style 的属性中设置。
* colorControlNormal
    * 各控制元件的预设颜色。
    * 在 style 的属性中设置
* windowBackground
    * App 的背景色。
    * 在 style 的属性中设置
* navigationBarColor
    * 导航栏的背景色，但只能用在 API Level 21 (Android 5) 以上的版本
    * 在 style 的属性中设置，只能在value-21里设置
    * `<item name="android:navigationBarColor">@color/accent_material_light</item>`


## 3 代码：

```java
package biz.mosil.demo.toolbar;

import android.support.v7.app.ActionBarActivity;
import android.os.Bundle;
import android.support.v7.widget.Toolbar;
import android.view.Menu;
import android.view.MenuItem;
import android.widget.Toast;


public class MainActivity extends ActionBarActivity {

  @Override
  protected void onCreate(Bundle savedInstanceState) {
    super.onCreate(savedInstanceState);
    setContentView(R.layout.activity_main);

    Toolbar toolbar = (Toolbar) findViewById(R.id.toolbar);

    // App Logo
    toolbar.setLogo(R.drawable.ic_launcher);
    // Title
    toolbar.setTitle("My Title");
    // Sub Title
    toolbar.setSubtitle("Sub title");

    setSupportActionBar(toolbar);

    // Navigation Icon 要設定在 setSupoortActionBar 才有作用
    // 否則會出現 back bottom
    toolbar.setNavigationIcon(R.drawable.ab_android);
    // Menu item click 的監聽事件一樣要設定在 setSupportActionBar 才有作用
    toolbar.setOnMenuItemClickListener(onMenuItemClick);
  }

  private Toolbar.OnMenuItemClickListener onMenuItemClick = new Toolbar.OnMenuItemClickListener() {
    @Override
    public boolean onMenuItemClick(MenuItem menuItem) {
      String msg = "";
      switch (menuItem.getItemId()) {
        case R.id.action_edit:
          msg += "Click edit";
          break;
        case R.id.action_share:
          msg += "Click share";
          break;
        case R.id.action_settings:
          msg += "Click setting";
          break;
      }

      if(!msg.equals("")) {
        Toast.makeText(MainActivity.this, msg, Toast.LENGTH_SHORT).show();
      }
      return true;
    }
  };


  @Override
  public boolean onCreateOptionsMenu(Menu menu) {
    // 為了讓 Toolbar 的 Menu 有作用，這邊的程式不可以拿掉
    getMenuInflater().inflate(R.menu.menu_main, menu);
    return true;
  }
}

```