# Drawable

Drawable
===========================

* 本文探讨的是：
    * 安卓提供的几个Drawable类型
    * 自定义Drawable
    * Drawable的使用场景


* 前言
    * 注意Drawable用在哪儿，一般能设置background和src的地方都能设置Drawable，就是R.drawable

##1 内置Drawable类型

###BitmapDrawable和NineOldPatchDrawable：位图

对应的xml：
```java
<bitmap xmlns:android="http://schemas.android.com/apk/res/android"
    android:src="@drawable/line"
    android:tileMode="repeat"
    android:antialias=""
    android:filter=""
    android:dither=""
    android:gravity=""
    >
</bitmap>
```

* 介绍：
    * 一个BitmapDrawable就是封装了一个位图
    * 直接以文件的方式，就是封装了一个原始的位图
    * 以Xml方式，可以对原始的位图进行一系列的处理，比如说抗锯齿，拉伸，对齐等等。
    * 要了解BitmapDrawable的使用，还需要明白Bitmap、BitmapFactory等类
        * Bitmap代表了一个原始的位图，并且可以对位图进行一系列的变换操作
        * BitmapFactory提供一系列的方法用于产生一个Bitmap对象， 多用在Canvas中

* Tile模式：
    * 代码设置：`drawable2.setTileModeXY(TileMode.REPEAT, TileMode.CLAMP)`
    * 当tile模式被启用，位图是重复的，并且gravity属性将被忽略
    * REPEAT：重复，可以用来画虚线，需提供一张短的虚线图
    * MIRROR：镜面反射
    * CLAMP：边缘拉伸，不好看，不知道什么时候可以用，这个和电脑屏保的模式应该有些不同，这个拉伸的是图片最后的那一个像素；横向的最后一个横行像素，不断的重复，纵项的那一列像素，不断的重复

__通过代码控制__
```java
//方式1
InputStream inputStream = getResources().openRawResource(R.drawable.animate_shower);
BitmapDrawable drawable = new BitmapDrawable(inputStream);
Bitmap bitmap = drawable.getBitmap();

//方式2
Resources res = getResources();
BitmapDrawable drawable2 = (BitmapDrawable) res.getDrawable(R.drawable.animate_shower);

//获取
drawable2.getCurrent();//对于没有状态变化的Drawable，这个就是drawable.this，对于选择器或者level drawable，这个就是当前被激活的drawable，因为是多个drawable的组合


//常见属性
drawable2.setTileModeXY(TileMode.REPEAT, TileMode.CLAMP);//Sets the repeat behavior，当Bitmap小于这个drawable时，需要指定重复模式
drawable2.setGravity(Gravity.CENTER);//设置bitmap在bound中的位置，和tile模式冲突，即只有tile不是disable时，gravity才起作用，要么重复，要么考虑gravity
drawable2.setAntiAlias(true);//抗锯齿
drawable2.setFilterBitmap(false);//大体上就是使用Bitmap时，如果要缩放或旋转，会改善视觉效果，但速度变慢
drawable2.setDither(false);//如果位图与屏幕的像素配置不同时，是否允许抖动.（例如：一个位图的像素设置是 ARGB 8888，但屏幕的设置是RGB 565）


```


###ClipDrawable: 裁剪

对应的xml
```java
<clip xmlns:android="http://schemas.android.com/apk/res/android"
    android:drawable="@drawable/android"
    android:clipOrientation="horizontal"
    android:gravity="left" />
```

图片裁剪：
 * ——默认是0，即完全裁剪
 * ——最大是10000，即完全不裁剪
 * ——通过setLevel设置裁剪大小

* 重点属性：
    * level：
        * 默认是0，即完全裁剪
        * 最大是10000，即完全不裁剪
        * 通过setLevel设置裁剪大小

    * gravity：和View的关系，也和裁剪方向一起影响裁剪行为
        * top	将这个对象放在容器的顶部，不改变其大小。当clipOrientation 是"vertical"，裁剪发生在drawable的底部（bottom）
        * bottom	将这个对象放在容器的底部，不改变其大小。当clipOrientation 是 "vertical"，裁剪发生在drawable的顶部（top）
        * left	将这个对象放在容器的左部，不改变其大小。当clipOrientation 是 "horizontal"，裁剪发生在drawable的右边（right）。默认的是这种情况。
        * right	将这个对象放在容器的右部，不改变其大小。当clipOrientation 是 "horizontal"，裁剪发生在drawable的左边（left）。
        * center_vertical	将对象放在垂直中间，不改变其大小。裁剪的情况和”center“一样。
        * fill_vertical	垂直方向上不发生裁剪。（除非drawable的level是 0，才会不可见，表示全部裁剪完）
        * center_horizontal	将对象放在水平中间，不改变其大小。裁剪的情况和”center“一样。
        * fill_horizontal	水平方向上不发生裁剪。（除非drawable的level是 0，才会不可见，表示全部裁剪完）
        * center	将这个对象放在水平垂直坐标的中间，不改变其大小。当clipOrientation 是 "horizontal"裁剪发生在左右。当clipOrientation是"vertical",裁剪发生在上下。
        * fill	填充整个容器，不会发生裁剪。(除非drawable的level是 0，才会不可见，表示全部裁剪完)。
        * clip_vertical   额外的选项，它能够把它的容器的上下边界，设置为子对象的上下边缘的裁剪边界。裁剪要基于对象垂直重力设置：如果重力设置为top，则裁剪下边，如果设置为bottom，则裁剪上边，否则则上下两边都要裁剪。
        * clip_horizontal  额外的选项，它能够把它的容器的左右边界，设置为子对象的左右边缘的裁剪边界。裁剪要基于对象垂直重力设置
    * clipOrientation：裁剪方向
        * horizontal	水平方向裁剪
        * vertical	垂直方向裁剪

__java代码__
```java
ClipDrawable clipDrawable = new ClipDrawable(
        getResources().getDrawable(R.drawable.animate_shower),
        Gravity.CENTER,
        ClipDrawable.VERTICAL);

final View v = findViewById(R.id.tv);
v.setBackground(clipDrawable);
v.getBackground().setLevel(100);
```


###InsetDrawable

* InsetDrawable本身是一张Drawable，再往里嵌入一个：
    * 当控件需要的背景比实际的边框小的时候比较适合使用InsetDrawable，指定了上下左右空出的距离
    * 注意：对应View的内容也以InsetDRAWable的边距为边距

**xml**
```xml
<inset xmlns:android="http://schemas.android.com/apk/res/android"   
			    android:drawable="@drawable/image4"  
			    android:insetLeft="50dp"  
			    android:insetRight="50dp"  
			    android:insetTop="20dp"  
			    android:insetBottom="20dp">  
			</inset>
```

**java**
```java
InsetDrawable insetDrawable=new InsetDrawable(getResources().getDrawable(R.drawable.animate_shower), 20, 30, 30, 20); 
	
```

###LayerDrawable

* 创建LayerDrawable和使用
    * 一个LayerDrawable是一个可以管理一组drawable对象的drawable。
	* 在LayerDrawable的drawable资源按照列表的顺序绘制，列表的最后一个drawable绘制在最上层。
	* 它所包含的一组drawable资源用多个<item>元素表示，一个<item>元素代表一个drawable资源。

在默认的情况下，所有的drawable item都会缩放到合适的大小来适应视图。  
因此，在一个layer-list中定义不同的位置可能会增加视图的尺寸和被自动缩放。  为了避免被缩放，可以在<item>节点里加上<bitmap>元素来指定一个drawable，  并且定义一些不会被拉伸的gravity属性，例如center。  
举个例子，下面在item里面定义一个drawable，图片就会自动缩放以适应视图的大小。

**可能会发生拉伸的用法**
```xml
<item android:drawable="@drawable/image" />
```

**为了避免缩放，可以使用<bitmap>的子元素来指定drawable资源**
```xml
<item>
  <bitmap android:src="@drawable/image"
          android:gravity="center" />
</item>
```

**例子：在xml文件中定义layerDrawable，然后使用**
```xml
<?xml version="1.0" encoding="utf-8"?>
<layer-list xmlns:android="http://schemas.android.com/apk/res/android">
    <item android:drawable="@drawable/black_lotus"
        android:left="20dp"
        android:top="20dp">
    </item>
    <item android:drawable="@drawable/black_lotus"
        android:left="40dp"
        android:top="40dp">
    </item>
    <item android:drawable="@drawable/black_lotus"
        android:left="60dp"
        android:top="60dp">
    </item>
</layer-list>

<ImageView
    android:layout_height="wrap_content"
    android:layout_width="wrap_content"
    android:src="@drawable/layers" />

```

**代码控制**
```java
Bitmap bitmap=BitmapFactory.decodeResource(getResources(), R.drawable.animate_shower);  
Drawable[] drawables=new Drawable[3];  
//drawables[0]=new PaintDrawable(Color.BLACK);  
//drawables[1]=new PaintDrawable(Color.BLUE);  
drawables[0]=new BitmapDrawable(bitmap);  
drawables[1]=new BitmapDrawable(bitmap);  
drawables[2]=new BitmapDrawable(bitmap);  
LayerDrawable layer=new LayerDrawable(drawables);  
layer.setLayerInset(0, 20, 20, 0, 0); //index（第几个Drawable）, 左，上，右，下--像素，说的是左上角相对于LayerDrawable左上角的位置 ，和右下角（后两个参数）相对于LayerDrawable右下角的位置
layer.setLayerInset(1, 40, 40, 0, 0);  
layer.setLayerInset(2, 60, 60, 60, 60);  

 View v = findViewById(R.id.tv);
 //v.setBackground(layer);//--用代码中创建的LayerDrawable，在这里图片会被拉伸
 v.setBackgroundResource(R.drawable.d_layer_drawable);
```

**上例中没有被拉伸的xml定义**
```xml
<?xml version="1.0" encoding="utf-8"?>
<layer-list xmlns:android="http://schemas.android.com/apk/res/android">
    <item android:drawable="@drawable/animate_shower"
        android:left="20dp"
        android:top="20dp">
    </item>
    <item android:drawable="@drawable/animate_shower"
        android:left="40dp"
        android:top="40dp">
    </item>
    <item
        android:left="60dp"
        android:top="60dp">>
	  <bitmap android:src="@drawable/animate_shower"
	          android:gravity="top" />
	</item>
</layer-list>
```

###LevelDrawable

* 简介：
    * 外界存在一个数值范围，控件要根据这个数值切换背景或者图标，
    * 就可以用LevelDrawable
    * 电源显示就是用的LevelDrawable
 
    调用Drawable的setLevel()方法可以加载level-list或代码中定义的某个drawable资源，  
    判断加载某项的方式：level-list中某项的android:maxLevel数值大于或者等于setLevel设置的数值，就会被加载。

* 使用LevelDrawable注意几点：
    * 默认的level为0，如果没有和0匹配的level，那么就不显示。
    * level匹配以maxLevel优先。即如果有个item，min：1，max：2。   另一份item，min：2，max：3。
    * 如果此时设置level=2，那么会匹配第一个item。

**代码控制**
```java
LevelListDrawable levelListDrawable = new LevelListDrawable();
levelListDrawable.addLevel(0, 20, getResources().getDrawable(R.drawable.animate_shower)); //min, max, drawable
levelListDrawable.addLevel(20, 40, getResources().getDrawable(R.drawable.kaola));
levelListDrawable.addLevel(40, 70, getResources().getDrawable(R.drawable.qie));
levelListDrawable.addLevel(70, 100, getResources().getDrawable(R.drawable.shuimu));

final View v = findViewById(R.id.tv);
v.setBackground(levelListDrawable);

//改变背景的level：
v.getBackground().setLevel(progress);
```
	
###StateListDrawable

**代码控制**
```java
StateListDrawable drawable=new StateListDrawable();
//如果要设置莫项为false，在前面加负号 ，比如android.R.attr.state_focesed标志true，-android.R.attr.state_focesed就标志false
drawable.addState(new int[]{android.R.attr.state_focused}, this.getResources().getDrawable(R.drawable.kaola));
drawable.addState(new int[]{android.R.attr.state_pressed}, this.getResources().getDrawable(R.drawable.qie));
drawable.addState(new int[]{android.R.attr.state_selected}, this.getResources().getDrawable(R.drawable.shuimu));
drawable.addState(new int[]{}, this.getResources().getDrawable(R.drawable.shuimu));//默认

View v = findViewById(R.id.tv);
v.setBackground(drawable);
```

**xml控制**
```xml
<?xml version="1.0" encoding="UTF-8"?>  
			<selector  
			  xmlns:android="http://schemas.android.com/apk/res/android">  
			    <item android:state_focused="true" android:drawable="@drawable/botton_add" />  
			    <item android:state_pressed="true" android:drawable="@drawable/botton_add_down" />  
			    <item android:state_selected="true" android:drawable="@drawable/botton_add" />  
			    <item android:drawable="@drawable/botton_add" />//默认  
			</selector> 
```

* 注意：
    * android:drawable必须的，指向一个drawable资源
    * 顺序很重要：	
	    * android:state_pressed  是否按下
		* android:state_focused  是否获得获得焦点
		* android:state_hovered  鼠标在上面滑动的状态。通常和state_focused使用同样的drawable
		* android:state_selected 是否选中
		* android:state_checkable 是否可以被勾选（checkable）。只能用在可以勾选的控件
		* android:state_checked 是否被勾选上
		* android:state_enabled 是否可用
		* android:state_activated 是否被激活并持久的选择
		* android:state_window_focused 当前应用程序是否获得焦点
	* 注意：Android系统将会选中第一条符合当前状态的item。
	* 因此，如果第一项列表中包含了所有的状态属性，那么它是每次就只用他
	* 这就是为什么你的默认值应该放在最后面。
    * 也就说这些状态所占范围越大的，越必须放后面，基本上就照着上面的顺序放就行


###TransitionDrawable

实现两张Drawable之间的渐入渐出切换：就两张，只能渐隐渐现，就一次，没法循环的来

**xml**
```xml
<transition xmlns:android="http://schemas.android.com/apk/res/android" >  
			    <item android:drawable="@drawable/image01"/>  
			    <item android:drawable="@drawable/image02"/>  
			</transition> 
```

**代码控制**
```java
TransitionDrawable transitionDrawable=null;  
transitionDrawable= new TransitionDrawable(new Drawable[] {
	getResources().getDrawable(R.drawable.animate_shower),
	getResources().getDrawable(R.drawable.kaola),
});
findViewById(R.id.tv).setBackground(transitionDrawable);
transitionDrawable.startTransition(3000);//间隔3秒
```

###RotateDrawable

旋转，范围1到10000， 对应角度是xml指定的  
android:fromDegrees="-90" 和android:toDegrees="180"  
选中中心是xml里指定的

**xml控制**
```xml
<?xml version="1.0" encoding="utf-8"?>  
			<rotate xmlns:android="http://schemas.android.com/apk/res/android"  
			    android:drawable="@drawable/image02"  
			    android:visible="true"  
			    android:fromDegrees="-90"   ---逆时针90度
			    android:toDegrees="180"    ---顺时针180度，范围是270度，对应1到10000
			    android:pivotX="50%"  ---相对于Drawable自己
			    android:pivotY="50%">  ---相对于Drawable自己
			</rotate>
```

```java
v.setBackgroundResource(R.drawable.d_rotate_drawable);

//控制旋转，注意是setLevel，范围是1到1W
v.getBackground().setLevel(progress);
```

###ScaleDrawable

缩放： setLevel()设置缩放比例，最大值10000

```java
ScaleDrawable scaleDrawable = new ScaleDrawable(
		getResources().getDrawable(R.drawable.animate_shower), 
		Gravity.CENTER, 
		0.1f,  //好像是缩放的范围，缩放范围是10%，即增加10%就给你到100%，即从90%开始缩放，也就是setLevel(1)就是90%，1到10000对应80%到100%
		0.0f);//这里的0表示缩放范围是0，即纵向不缩放

final View v = findViewById(R.id.tv);
v.setBackground(scaleDrawable);
v.getBackground().setLevel(0);
```

```xml
<?xml version="1.0" encoding="utf-8"?>  
			<scale xmlns:android="http://schemas.android.com/apk/res/android"  
			    android:scaleWidth="50%"  
			    android:scaleHeight="50%"  
			    android:drawable="@drawable/image1"   
			    android:scaleGravity="center_vertical|center_horizontal"  
			    >  
			</scale>
```

###GradientDrawable和ShapeDrawable

**下面是引用别人的话，出处已经找不到了：**

* 在研究Android Drawable资源的时候，发现了一个奇怪的问题。在官方API介绍中：
    * ShapeDrawable：This object can be defined in an XML file with the <shape> element（这个对象可以用<shape>元素在xml文件中定义）
    * GradientDrawable 介绍：This object can be defined in an XML file with the <shape> element（这个对象可以用<shape>元素在xml文件中定义）
    * 两者的介绍一模一样，都说可以使用<shape>标签在xml文件中定义。 
	* 那么，到底用<shape>标签定义的是什么的呢？
    * 经过下面的实验：
    * TextView textView=(TextView)findViewById(R.id.textView);
    * ShapeDrawable gradientDrawable=(ShapeDrawable)textView.getBackground();
    * 报错，类型转换错误java.lang.ClassCastException:android.graphics.drawable.GradientDrawable 
    * 所以是GradientDrawable
    * 那么，ShapeDrawable是怎么定义的，找了网上的资料，结果硬是没找到如何在XML文件中定义它，只能通过代码的方式实现

**xml规格**
```xml
<?xml version="1.0" encoding="utf-8"?>
<shape
    xmlns:android="http://schemas.android.com/apk/res/android"
    android:shape=["rectangle" | "oval" | "line" | "ring"] >
    <corners
        android:radius="integer"
        android:topLeftRadius="integer"
        android:topRightRadius="integer"
        android:bottomLeftRadius="integer"
        android:bottomRightRadius="integer" />
    <gradient
        android:angle="integer"
        android:centerX="integer"
        android:centerY="integer"
        android:centerColor="integer"
        android:endColor="color"
        android:gradientRadius="integer"
        android:startColor="color"
        android:type=["linear" | "radial" | "sweep"]
        android:useLevel=["true" | "false"] />
    <padding
        android:left="integer"
        android:top="integer"
        android:right="integer"
        android:bottom="integer" />
    <size
        android:width="integer"
        android:height="integer" />
    <solid
        android:color="color" />
    <stroke
        android:width="integer"
        android:color="color"
        android:dashWidth="integer"
        android:dashGap="integer" />
</shape>
```

**解释**  
    ----------------<gradient>指定这个shape的渐变颜色
    android:angle  渐变的角度。 0 代表从 left 到 right。90 代表bottom到 top。必须是45的倍数，默认为0
    android:centerX  渐变中心的相对X坐标，在0到1.0之间。
    android:centerY  渐变中心的相对Y坐标，在0到1.0之间。
    android:centerColor  可选的颜色值。基于startColor和endColor之间
    android:endColor  结束的颜色
    android:gradientRadius  渐变的半径。只有在 android:type="radial"才使用
    android:startColor  开始的颜色值。
    android:type 渐变的模式，下面值之一：
    "linear"	线形渐变。这也是默认的模式
    "radial"	辐射渐变。startColor即辐射中心的颜色
    "sweep"	扫描线渐变。
    android:useLevel  如果在LevelListDrawable中使用，则为true

    ----------------<padding>内容与视图边界的距离
    android:left 左边填充距离.
    android:top 顶部填充距离.
    android:right  右边填充距离.
    android:bottom 底部填充距离.

    ----------------<size>这个shape的大小
    android:height  这个shape的高度。
    android:width  这个shape的宽度。
    注意：默认情况下，这个shape会缩放到与他所在容器大小成正比。
	当你在一个ImageView中使用这个shape，你可以使用 android:scaleType="center"来限制这种缩放。

    ----------------<solid>填充这个shape的纯色
    android:color  颜色值，十六进制数，或者一个Color资源
 
    ----------------<stroke> 这个shape使用的笔画，当android:shape="line"的时候，必须设置改元素。
    android:width 笔画的粗细。
    android:color 笔画的颜色
    android:dashGap 每画一条线就间隔多少。只有当android:dashWidth也设置了才有效。
    android:dashWidth 每画一条线的长度。只有当 android:dashGap也设置了才有效。

	
##2 自定义Drawable


