# ButterKnife

控件注入
===========================
* 控件注入
    * XUtils的注入功能
    * ButterKinfe
    * 基于性能的考量

XUtils的不再做介绍，建议使用ButterKnife，虽然没有直接的证据表明二者性能上有明显差异，但
ButterKnife明显比XUtils流行，而且有更多的插件支持，功能也更多

* ButterKnife：
    * 文档：http://jakewharton.github.io/butterknife/
    * Github：https://github.com/JakeWharton/butterknife
    * 插件：http://blog.csdn.net/jdsjlzx/article/details/49101433


* XUtils和ButterKnife对比：
    * 二者都是反射出注解，然后findViewById
    * XUtils还涉及到了parent？啥意思？
    * ButterKnife有缓存，XUtils不知道有没有
    * 总是用XUtils的东西也没意思，能不用就不用吧

#ButterKnife简陋参考手册：文档的简单翻译

##1 控件绑定
```java
class ExampleActivity extends Activity {
  @Bind(R.id.title) TextView title;
  @Bind(R.id.subtitle) TextView subtitle;
  @Bind(R.id.footer) TextView footer;

  @Override public void onCreate(Bundle savedInstanceState) {
    super.onCreate(savedInstanceState);
    setContentView(R.layout.simple_activity);
    ButterKnife.bind(this);
    // TODO Use fields...
  }
}

生成的代码类似下面:
public void bind(ExampleActivity activity) {
  activity.subtitle = (android.widget.TextView) activity.findViewById(2130968578);
  activity.footer = (android.widget.TextView) activity.findViewById(2130968579);
  activity.title = (android.widget.TextView) activity.findViewById(2130968577);
}

```

##2 资源的注入
```java
class ExampleActivity extends Activity {
  @BindString(R.string.title) String title;
  @BindDrawable(R.drawable.graphic) Drawable graphic;
  @BindColor(R.color.red) int red; // int or ColorStateList field
  @BindDimen(R.dimen.spacer) Float spacer; // int (for pixel size) or float (for exact value) field
  // ...
}
```

##3 无Activity：View的绑定

```java
public class FancyFragment extends Fragment {
  @Bind(R.id.button1) Button button1;
  @Bind(R.id.button2) Button button2;

  @Override public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
    View view = inflater.inflate(R.layout.fancy_fragment, container, false);
    ButterKnife.bind(this, view);
    // TODO Use fields...
    return view;
  }

  @Override public void onDestroyView() {
      super.onDestroyView();
      ButterKnife.unbind(this);
  }
}
```

##4 ViewHolder的绑定

```java
static class ViewHolder {
    @Bind(R.id.title) TextView name;
    @Bind(R.id.job_title) TextView jobTitle;

    public ViewHolder(View view) {
      ButterKnife.bind(this, view);
    }
}
```

###5 事件绑定

```java
@OnClick(R.id.submit)
public void submit(View view) {
  // TODO submit data to server...
}

@OnClick(R.id.submit)
public void submit() {
  // TODO submit data to server...
}

@OnClick(R.id.submit)
public void sayHi(Button button) {
  button.setText("Hello!");
}

@OnClick({ R.id.door1, R.id.door2, R.id.door3 })
public void pickDoor(DoorView door) {
  if (door.hasPrizeBehind()) {
    Toast.makeText(this, "You win!", LENGTH_SHORT).show();
  } else {
    Toast.makeText(this, "Try again", LENGTH_SHORT).show();
  }
}

//注意，自定义控件，给自己绑定事件，不用指定id
public class FancyButton extends Button {
  @OnClick
  public void onClick() {
    // TODO do something!
  }
}

```

__这个不知道啥意思__
```
MULTI-METHOD LISTENERS

Method annotations whose corresponding listener has multiple callbacks can be used to bind to any one of them. Each annotation has a default callback that it binds to. Specify an alternate using the callback parameter.

@OnItemSelected(R.id.list_view)
void onItemSelected(int position) {
  // TODO ...
}

@OnItemSelected(value = R.id.maybe_missing, callback = NOTHING_SELECTED)
void onNothingSelected() {
  // TODO ...
}
```



###6 特殊语法

__List<View>的绑定__
```java
@Bind({ R.id.first_name, R.id.middle_name, R.id.last_name })
List<EditText> nameViews;
```

__apply方法__
```java
ButterKnife.apply(nameViews, DISABLE);
ButterKnife.apply(nameViews, ENABLED, false);

//Action and Setter interfaces allow specifying simple behavior.

static final ButterKnife.Action<View> DISABLE = new ButterKnife.Action<View>() {
  @Override public void apply(View view, int index) {
    view.setEnabled(false);
  }
};
static final ButterKnife.Setter<View, Boolean> ENABLED = new ButterKnife.Setter<View, Boolean>() {
  @Override public void set(View view, Boolean value, int index) {
    view.setEnabled(value);
  }
};

//An Android Property can also be used with the apply method.

ButterKnife.apply(nameViews, View.ALPHA, 0.0f);
```

__这个也不知道啥意思__
```java
BONUS

Also included are findById methods which simplify code that still has to find views on a View, Activity, or Dialog. It uses generics to infer the return type and automatically performs the cast.

View view = LayoutInflater.from(context).inflate(R.layout.thing, null);
TextView firstName = ButterKnife.findById(view, R.id.first_name);
TextView lastName = ButterKnife.findById(view, R.id.last_name);
ImageView photo = ButterKnife.findById(view, R.id.photo);
//Add a static import for ButterKnife.findById and enjoy even more fun.
```