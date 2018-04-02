# MVP

# 简易MVP：谷歌contract风格
----------------------------------------

基类：
```java
public interface AyoPresenter<T extends AyoView>{
    void attachView(T view);
    void detachView();
}

public interface AyoView {
    void showError(String msg);
    void useNightMode(boolean isNight);
}
````

使用时，首先在项目app中自定义基类：
```java
public abstract class BasePresenter<T extends AyoView> {

    private Activity mActivity;
    private T v;

    public Activity activity(){
        return mActivity;
    }

    public T view(){
        return v;
    }

    public BasePresenter(Activity a){
        mActivity = a;
    }

    public void attachView(T view) {
        v = view;
    }

    public void detachView() {
        v = null;
    }

}
```

然后，定义条约：
```java
public interface LoginContract {

    interface View extends AyoView {

        void onLoginOk();
        void onLoginFail(String failMessage);
        void refreshLoginStatus(String s);
        void refreshAccountInfo(String username, String pwd);
    }

    interface Presenter extends AyoPresenter<View> {

        public static final int PLATFORM_QQ = 1;
        public static final int PLATFORM_WB = 2;
        public static final int PLATFORM_WX = 3;

        void doLogin(String username, String pwd);
        void clickRegist();
        void clickForget();
        void clickSocialLogin(int platform);
    }
}

```

然后，实现LoginContract.View，和LoginContract.Presenter
```java
public class LoginPresenter extends BasePresenter<LoginContract.View> implements LoginContract.Presenter{

}

```
