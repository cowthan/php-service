# notify系列库

Dialog:  通过Flyco源码分析一下怎么提供一个更通用的Dialog
===================================================

## 1 对话框大小的问题

Flyco默认是将Dialog设置为全屏大小：
```java
setContentView(mLlTop, new ViewGroup.LayoutParams(mDisplayMetrics.widthPixels, (int) mMaxHeight));
```

如果是Popup style，那就设置成wrap_content
```java
setContentView(mLlTop, new ViewGroup.LayoutParams(
           ViewGroup.LayoutParams.WRAP_CONTENT,
           ViewGroup.LayoutParams.WRAP_CONTENT));
```


## 2 根布局

总体的根布局是一个LinearLayout，里面又是一层Vertical的LinearLayout，然后才是用户自定义布局
```java
mLlTop.setGravity(Gravity.CENTER);

mLlControlHeight = new LinearLayout(mContext);
mLlControlHeight.setOrientation(LinearLayout.VERTICAL);

mOnCreateView = onCreateView();

```

## 3 大小控制

setUiBeforShow();

可以设定一些属性：
```java

widthScale(float widthScale)    设置对话框宽度,占屏幕宽的比例0-1
heightScale(float heightScale)  设置对话框高度,占屏幕宽的比例0-1
//这两个会被用在mLlControlHeight上

```

## 4 动画控制

```java

//这两个会被用在mLlControlHeight上
showAnim(BaseAnimatorSet showAnim)
dismissAnim(BaseAnimatorSet dismissAnim)

//还可以指定Dialog层面的动画style
show(int animStyle)

public void show(int animStyle) {
    Window window = getWindow();
    window.setWindowAnimations(animStyle);
    show();
}

```

## 5 显示，关闭，回调

```java

//设置自动延时关闭
autoDismiss(boolean autoDismiss)
autoDismissDelay(long autoDismissDelay)

//
show()
show(int animStyle)

dismiss()--使用指定动画
superDismiss() --直接关闭
//
setCanceledOnTouchOutside(boolean cancel)
``


## 6 其他

```java
dimEnabled(boolean isDimEnabled)   --- 设置背景是否昏暗

//对话框默认主题设置成了：
/** set dialog theme(设置对话框主题) */
private void setDialogTheme() {
    requestWindowFeature(Window.FEATURE_NO_TITLE);// android:windowNoTitle
    getWindow().setBackgroundDrawable(new ColorDrawable(Color.TRANSPARENT));// android:windowBackground
    getWindow().addFlags(LayoutParams.FLAG_DIM_BEHIND);// android:backgroundDimEnabled默认是true的
}

//生命周期
show:constrouctor---show---oncreate---onStart---onAttachToWindow
dismiss---onDetachedFromWindow---onStop

```


## 7 popup模式

有了上面的分析，popup模式就比较好理解了

```java

//设置成popup样式
BaseDialog(Context context, boolean isPopupStyle)

//宽高控制
if (mIsPopupStyle) {
    setContentView(mLlTop, new ViewGroup.LayoutParams(
            ViewGroup.LayoutParams.WRAP_CONTENT,
            ViewGroup.LayoutParams.WRAP_CONTENT));
}

//显示
/** show at location only valid for mIsPopupStyle true(指定位置显示,只对isPopupStyle为true有效) */
public void showAtLocation(int gravity, int x, int y) {
    if (mIsPopupStyle) {
        Window window = getWindow();
        LayoutParams params = window.getAttributes();
        window.setGravity(gravity);
        params.x = x;
        params.y = y;
    }

    show();
}

/** show at location only valid for mIsPopupStyle true(指定位置显示,只对isPopupStyle为true有效) */
public void showAtLocation(int x, int y) {
    int gravity = Gravity.LEFT | Gravity.TOP;//Left Top (坐标原点为右上角)
    showAtLocation(gravity, x, y);
}

```


## 8 用户操作的回调

```java
public interface ActionCallback{
    void onOk(Popable pop);
    void onCancel(Popable pop);
    void onSelected(Popable pop, int action, Object extra);
    void onAction(Popable pop, int action, Object extra);
}
```

## 9 常用的弹框演示都内置了

```java
ActionSheetDialog
MaterialDialog
NormalDialog
NormalListDialog

还缺：
LoadingDialog
BottomDialog--分享
```

## 10 自定义一个对话框样式

```java

```


# 原生dialog

原生对话框：AlertDialog
===========================

support v7里也有个AlertDialog，下面都是基于这个

三个核心参数：title，icon，msg，不设置就没有，设置空也是没设置，也不占地，都不设置就只显示个黑色透明背景

没有title，设置icon也没用

##第一种 普通对话框

```java
AlertDialog.Builder builder=new AlertDialog.Builder(MainActivity.this);
builder.setTitle("普通对话框");//标题
builder.setMessage("这是一个普通的对话框");//信息
builder.setIcon(R.drawable.ic_launcher);//图标
builder.create();//创建
builder.show();//显示
```


##第二种 确定取消对话框

```java
AlertDialog.Builder builder2=new AlertDialog.Builder(MainActivity.this);
builder2.setTitle("确定取消对话框");
builder2.setMessage("请选择确定或取消");
builder2.setIcon(R.drawable.ic_launcher);
builder2.setPositiveButton("确定", new OnClickListener() {
        //正能量按钮 Positive
        @Override
        public void onClick(DialogInterface dialog, int which) {
                //这里写点击按钮后的逻辑代码
                Toast.makeText(MainActivity.this, "你点击了确定", 0).show();
        }
});
builder2.setNegativeButton("取消", new OnClickListener() {
        //负能量按钮 NegativeButton
        @Override
        public void onClick(DialogInterface dialog, int which) {
                Toast.makeText(MainActivity.this,"你选择了取消",0).show();
        }
});
builder2.create().show();
```

##第三种 多按钮对话框

```java
AlertDialog.Builder builder3=new AlertDialog.Builder(MainActivity.this);
builder3.setTitle("多个按钮对话框");
builder3.setMessage("请选择");
builder3.setIcon(R.drawable.ic_launcher);
builder3.setPositiveButton("继续浏览", new OnClickListener() {

        @Override
        public void onClick(DialogInterface dialog, int which) {
                Toast.makeText(MainActivity.this,"继续浏览精彩内容",0).show();
        }
});
builder3.setNeutralButton("暂停休息", new OnClickListener() {

        @Override
        public void onClick(DialogInterface dialog, int which) {
                Toast.makeText(MainActivity.this,"起来活动活动吧", 0).show();
        }
});
builder3.setNegativeButton("离开页面", new OnClickListener() {

        @Override
        public void onClick(DialogInterface dialog, int which) {
                Toast.makeText(MainActivity.this,"欢迎下次使用", 0).show();
        }
});
builder3.create().show();
```

##第四种 列表对话框

item的布局不可定制

```xml
先在string.xml中添加以下代码
<string-array
        name="oem">
        <item >小米</item>
        <item >荣耀</item>
        <item >魅族</item>
        <item >乐视</item>
        <item >奇酷</item>
        <item >锤子</item>
    </string-array>
```

```java
//然后添加逻辑代码
final String arrItem[]=getResources().getStringArray(R.array.oem);
builder4.setItems(arrItem, new OnClickListener() {

        @Override
        public void onClick(DialogInterface dialog, int which) {
                Toast.makeText(MainActivity.this,"你选择了第"+arrItem[which],0).show();
        }
});
builder4.create().show();
```


##第五种 带Adapter的对话框

item的布局可以定制，传入一个adapter

```java
AlertDialog.Builder builder5=new AlertDialog.Builder(MainActivity.this);
builder5.setTitle("带Adapter的对话框");
builder5.setIcon(R.drawable.ic_launcher);
//获取数据源
//创建一个List对象并实例化
final List<Map<String, Object>> list=new ArrayList<Map<String,Object>>();
//图片
int arrImgID[]={R.drawable.ic_launcher,R.drawable.ic_launcher,R.drawable.ic_launcher,
                R.drawable.ic_launcher,R.drawable.ic_launcher,R.drawable.ic_launcher};
for (int i = 0; i < arrImgID.length; i++) {
        Map<String,Object> map=new HashMap<String,Object>();
        map.put("img", arrImgID[i]);
        map.put("title", "title"+i);
        list.add(map);
}
//创建Adapter对象并实例化
SimpleAdapter adapter=new SimpleAdapter(
                MainActivity.this,
                list,
                R.layout.layout_test1,
                new String[]{"img","title"},
                new int[]{R.id.iv,R.id.tv});
//将数据填充到Adapter
builder5.setAdapter(adapter, new OnClickListener() {

        @Override
        public void onClick(DialogInterface dialog, int which) {
                Toast.makeText(MainActivity.this, "你选择了"+list.get(which).get("title").toString().trim(), 0).show();
        }
});
builder5.create().show();
```

##第六种 单选对话框

可以不设置确定取消按钮，但不设置的话，单选点击之后就得立马dismiss吧，单选就显得没意义了

```java
AlertDialog.Builder builder6=new AlertDialog.Builder(MainActivity.this);
builder6.setTitle("单选对话框");
builder6.setIcon(R.drawable.ic_launcher);
//参数1  item数据源       参数2   默认选中的item  参数3 item点击监听
builder6.setSingleChoiceItems(R.array.oem, 0, new OnClickListener() {

        @Override
        public void onClick(DialogInterface dialog, int which) {
                Toast.makeText(MainActivity.this, which+"", 0).show();
        }
});
//设置按钮
builder6.setPositiveButton("确定", new OnClickListener() {

        @Override
        public void onClick(DialogInterface dialog, int which) {

        }
});
builder6.create().show();
```

##第七种 多选对话框

```java
AlertDialog.Builder builder7=new AlertDialog.Builder(MainActivity.this);
builder7.setTitle("多选对话框");
builder7.setIcon(R.drawable.ic_launcher);
builder7.setMultiChoiceItems(R.array.oem, null, new OnMultiChoiceClickListener() {

        @Override
        public void onClick(DialogInterface dialog, int which, boolean isChecked) {
                Toast.makeText(MainActivity.this, which+""+isChecked, 0).show();
        }
});
builder7.create().show();
```

##第八种 日期对话框

```java
//创建DatePickerDialog对象并实例化
//国内外日期计算不同    注意此处输出月份需+1   默认设置月份需-1
DatePickerDialog datePickerDialog=new DatePickerDialog(MainActivity.this,
                new OnDateSetListener() {

                        @Override
                        public void onDateSet(DatePicker view, int year, int monthOfYear,
                                        int dayOfMonth) {
                                Toast.makeText(MainActivity.this,
                                                year+"年"+(monthOfYear+1)+"月"+dayOfMonth+"日", 0).show();
                        }
                },
                2015, 8, 21);
//Date和Time只用show()  不用create()
datePickerDialog.show();
```

##第九种 时间对话框

```java
TimePickerDialog timePickerDialog=new TimePickerDialog(MainActivity.this,
    new OnTimeSetListener() {

            @Override
            public void onTimeSet(TimePicker view, int hourOfDay, int minute) {
                    Toast.makeText(MainActivity.this,
                                    hourOfDay+"点"+minute+"分", 0).show();
            }
    },
    17, 49, true);
timePickerDialog.show();
```

##第十种 自定义对话框
```java
AlertDialog.Builder builder10=new AlertDialog.Builder(MainActivity.this);
builder10.setTitle("自定义对话框");
builder10.setIcon(R.drawable.ic_launcher);
//获取自定义对话框View
View view=LayoutInflater.from(MainActivity.this).inflate(R.layout.layout_test2, null);
//获取控件
final EditText et_name=(EditText)view.findViewById(R.id.et_name);
final EditText et_pwd=(EditText)view.findViewById(R.id.et_pwd);
//设置按钮
builder10.setPositiveButton("确定", new OnClickListener() {

        @Override
        public void onClick(DialogInterface dialog, int which) {
                Toast.makeText(MainActivity.this, "您的信息为 姓名:"+et_name.getText().toString()+" 密码:"+et_pwd.getText().toString(), 0).show();
        }
});
//加载自定义布局
builder10.setView(view).create().show();
```


# pop库

# pop - a quick android dialog building lib
[![License](https://img.shields.io/badge/license-Apache%202-blue.svg)](https://www.apache.org/licenses/LICENSE-2.0) [![Android Arsenal](https://img.shields.io/badge/Android%20Arsenal-Pop-green.svg?style=true)](https://android-arsenal.com/details/1/3400)

Buiding a dialog in android is why so much pain! Look at the following code you need to build a simple Dialog.
```java
AlertDialog.Builder builder = new AlertDialog.Builder(getActivity());
builder.setTitle(R.string.pick_toppings);
builder.setBody(R.string.body)

// Add the buttons
builder.setPositiveButton(R.string.ok, new DialogInterface.OnClickListener() {
           public void onClick(DialogInterface dialog, int id) {
               // User clicked OK button
           }
       });
builder.setNegativeButton(R.string.cancel, new DialogInterface.OnClickListener() {
           public void onClick(DialogInterface dialog, int id) {
               // User cancelled the dialog
           }
       });
// Set other dialog properties
...

// Create the AlertDialog
AlertDialog dialog = builder.create();
```

## Serously! No more pain now. This how you create one.

```java
Pop.on(activity).with().title(R.string.title).body(R.string.body).show();
```
if you want to handle the button click, this is even more fun with naming and you can even have custom body of dialog.
```java
               Pop.on(this)
                    .with()
                    .title(R.string.title)
                    .icon(R.drawable.icon)
                    .cancelable(false)
                    .layout(R.layout.custom_pop)
                    .when(new Pop.Yah() {
                        @Override
                        public void clicked(DialogInterface dialog, View view) {
                            Toast.makeText(getBaseContext(), "Yah button clicked", Toast.LENGTH_LONG).show();
                        }
                    })
                    .when(new Pop.Nah() { // ignore if dont need negative button
                        @Override
                        public void clicked(DialogInterface dialog, View view) {
                            Toast.makeText(getBaseContext(), "Nah button clicked", Toast.LENGTH_LONG).show();
                        }
                    })
                    .show(new Pop.View() { // assign value to view element
                          @Override
                          public void prepare(View view) {
                            EditText etName = (EditText) view.findViewById(R.id.et_name);
                            Log.i(TAG, "etName :: " + etName.getText());
                            etName.setText("Test Name 123");
                          }
                     });
```
## How to include it in your project:

```groovy
dependencies {
	compile 'com.vistrav:pop:2.0'
}
``` 
##You can contribute!
In case you think you have some improvement, please feel free do pull request your feature and I would be happy to include it. Let's make this Pop very easy to use and rich with features.

##Other Userful Libraries
#### Ask - Android runtime permissions make easy
[![Github](https://img.shields.io/badge/github-Ask-orange.svg)](https://github.com/00ec454/Ask) [![Android Arsenal](https://img.shields.io/badge/Android%20Arsenal-Ask-brightgreen.svg?style=flat)](http://android-arsenal.com/details/1/3465)

##License

    Licensed under the Apache License, Version 2.0 (the "License");
    you may not use this file except in compliance with the License.
    You may obtain a copy of the License at

       http://www.apache.org/licenses/LICENSE-2.0

    Unless required by applicable law or agreed to in writing, software
    distributed under the License is distributed on an "AS IS" BASIS,
    WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
    See the License for the specific language governing permissions and
    limitations under the License.


# Ayo notify库

