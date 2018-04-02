# 滑动

## 4 仿ViewPager

代码里是FakeHorizontalScrollView

* 技术点：
    * 解决滑动冲突，内部可以嵌套ListView
    * 动作完全仿ViewPager
    * 使用了Scroller和VelocityTracker
    * 左右可以over slide，露出FakeHorizontalScrollView的背景


* 在这里记录一下SwipeRefresh的原理：
    * 一个SwipeRefreshLayout，只要子控件不能再往下滑了，就激活下拉刷新效果
        * 所以需要给每一个控件一个回调，判断能不能往下滑了
    * 往上滑也是一个道理


View详解
===========================

* 本文探讨的是：
    * 事件拦截
    * 常见控件的滑动事件
    * 滑动相关特效


## 0 处理滑动

处理滑动的一般代码是：
```java
int mLastX = 0;
int mLastY = 0;

@Override
public boolean onTouchEvent(MotionEvent e) {
    boolean consume = false;
    int x = (int)e.getX();
    int y = (int)e.getY();
    if(e.getAction() == MotionEvent.ACTION_DOWN){
        consume = true;
    }else if(e.getAction() == MotionEvent.ACTION_MOVE){
        int dx = x - mLastX;
        int dy = y - mLastY;
        consume = true;
        onFingerMove(x, y, dx, dy);
    }else if(e.getAction() == MotionEvent.ACTION_UP){
        consume = true;
    }

    mLastX = x;
    mLastY = y;
    return consume;
}


protected void onFingerMove(int x, int y, int dx, int dy){}
```

## 1 事件传递机制

传递过程：Activity-->Window-->ViewGroup链（1到n个）->View（0或1个）
ViewGroup链至少有一个ViewGroup
最后传给的View，可能0个或者1个
如果最底层View的onTouchEvent返回false，则事件会被回传给父容器的onTouchEvent，直到回到Activity的onTouchEvent

```java
下面是和事件分发有关的源码

//Activity#dispatchTouchEvent
public boolean dispatchTouchEvent(MotionEvent ev){

    ///这段暂时忽略，不知道干啥
    if(ev.getAction() == MotionEvent.ACTION_DOWN){
        onUserInteraction();
    }
    ///事件给Window分发，如果返回true，表示事件已被处理
    if(getWindow().superDispatchTouchEvent(ev)){
        return true;
    }
    ///如果事件没有被处理，则交给Activity的onTouchEvent
    return onTouchEvent(ev);
}

//PhoneWindow#superDispatchTouchEvent
public boolean superDispatchTouchEvent(MotionEvent ev){
    return mDecor.superDispatchTouchEvent(ev);
}

//DecoreView其实是FrameLayout，所以下一步其实是进入了ViewGroup的dispatchTouchEvent
//代码太长了，选几段重要的
public boolean dispatchTouchEvent(MotionEvent ev){

    ///处理拦截逻辑
    //如果是down事件，前面还有段代码，可以保证disallowIntercept一定为false，所以down时一定会调用onInterceptTouchEvent(ev)
    //如果拦截了down，则mFirstTouchTarget必为null，后续move和up不会再调用onInterceptTouchEvent(ev)，直接拦截
    //如果没拦截down
        //有子控件处理事件（能找到合适子控件，且子控件的dispatchTouchEvent返回true），则mFirstTouchTarget被赋值，
            //后续move和up事件还会继续尝试拦截，但是如果requestDisallowInterceptTouchEvent，则不走onInterceptTouchEvent，也不会拦截
        //无子控件处理事件，则mFirstTouchTarget为null，后续move和up直接拦截，不走onInterceptTouchEvent
    //对于后续move和up，无子控件处理事件，直接
    // Check for interception.
    final boolean intercepted;
    if (actionMasked == MotionEvent.ACTION_DOWN
            || mFirstTouchTarget != null) {
        final boolean disallowIntercept = (mGroupFlags & FLAG_DISALLOW_INTERCEPT) != 0;
        if (!disallowIntercept) {
            intercepted = onInterceptTouchEvent(ev);
            ev.setAction(action); // restore action in case it was changed
        } else {
            intercepted = false;
        }
    } else {
        // There are no touch targets and this action is not an initial down
        // so this view group continues to intercept touches.
        intercepted = true;
    }

    ///如果拦截了
    //传到了View#dispatchTouchEvent

    ///如果没拦截
    ///如果子控件处理了事件（能找到可以接收事件的子控件（没有正在进行动画，且被触摸的），且dispatchTouchEvent返回true），则赋值给mFirstTouchTarget
    ///如果是ViewGroup，调用了ViewGroup#dispatchEvent，重复上面过程
    ///如果是View，调用了View#dispatchEvent
    ///如果没有子控件处理事件，，则回到Acvitivity#onTouchEvent

}

//再看View#dispatchTouchEvent
//mOnTouchListener有，且mOnTouchListener.onTouch返回true，则事件处理结束
//否则，调用View#onTouchEvent(e)
public boolean dispatchTouchEvent(MotionEvent event) {
    // If the event should be handled by accessibility focus first.
    if (event.isTargetAccessibilityFocus()) {
        // We don't have focus or no virtual descendant has it, do not handle the event.
        if (!isAccessibilityFocusedViewOrHost()) {
            return false;
        }
        // We have focus and got the event, then use normal event dispatch.
        event.setTargetAccessibilityFocus(false);
    }

    boolean result = false;

    if (mInputEventConsistencyVerifier != null) {
        mInputEventConsistencyVerifier.onTouchEvent(event, 0);
    }

    final int actionMasked = event.getActionMasked();
    if (actionMasked == MotionEvent.ACTION_DOWN) {
        // Defensive cleanup for new gesture
        stopNestedScroll();
    }

    if (onFilterTouchEventForSecurity(event)) {
        if ((mViewFlags & ENABLED_MASK) == ENABLED && handleScrollBarDragging(event)) {
            result = true;
        }
        //noinspection SimplifiableIfStatement
        ListenerInfo li = mListenerInfo;
        if (li != null && li.mOnTouchListener != null
                && (mViewFlags & ENABLED_MASK) == ENABLED
                && li.mOnTouchListener.onTouch(this, event)) {
            result = true;
        }

        if (!result && onTouchEvent(event)) {
            result = true;
        }
    }

    if (!result && mInputEventConsistencyVerifier != null) {
        mInputEventConsistencyVerifier.onUnhandledEvent(event, 0);
    }

    // Clean up after nested scrolls if this is the end of a gesture;
    // also cancel it if we tried an ACTION_DOWN but we didn't want the rest
    // of the gesture.
    if (actionMasked == MotionEvent.ACTION_UP ||
            actionMasked == MotionEvent.ACTION_CANCEL ||
            (actionMasked == MotionEvent.ACTION_DOWN && !result)) {
        stopNestedScroll();
    }

    return result;
}

///View#onTouchEvent
//会调用performClick，激活onClickListner.onClick，还会处理longClick

```

* 几个点需要注意：
    * 一个TouchEvent事件，总是从属于一个事件序列，这个序列从down开始，经过多个move，直到up结束
    * 某个View只要拦截一个事件，则此序列都只能它来处理，onInterceptTouchEvent不会再被调用
    * 正常情况下，一个事件序列只能由一个View来消耗，但特殊情况下，一个View可以通过onTouchEvent强行把事件传给其他View
    * View不消耗ACTION_DOWN，即在onTouchEvent返回了false，则后续的move和up不再给它了，而是交给其父控件的onTouchEvent
    * View只消耗ACTION_DOWN，其他move和up都返回了false，则此事件消失，父的onTouchEvent不会被调用，并且此View还是可以接收到所有事件，最后这些消失的事件都传递给了Activity
    * ViewGroup默认onInterceptTouchEvent返回false，即不拦截
    * View没有onInterceptTouchEvent，事件来了，onTouchEvent肯定会调用
    * View默认onTouchEvent都返回true，除非clickable和longClickable都为false
    * disable和enable属性不影响onTouchEvent的返回，但可能影响是否调用clickLisenter
    * 一次onClick事件，必须收到down和up之后才会被激活
    * 事件传递由外向内，由父分发给子，但requestDisallowInterceptTouchEvent可以被子利用来干预父的事件分发，不过影响不了ACTION_DOWN事件
        * 这个得多说两句，父可以拦截ACTION_DOWN，理论上所有后续事件只能给父了
        * 但子可以通过requestDisallowInterceptTouchEvent得到后续的move和up？是这个意思吗
    * 子控件必须接收到down，才能接收后续move和up
        * 所以解决滑动冲突时，父肯定不能intercept事件down，不过后续的move，父可以intercept了
        * 父也可以intercept事件move，放行事件down，而子可以利用requestDisallowInterceptTouchEvent告诉父的dispatchTouchEvent要不要去拦截
            * 子反正肯定能拿到down
            * 对于move，子如果要，则parent.requestDisallowInterceptTouchEvent(true)，父就不会再走intercept，事件都给了子，滑动也就交给了子
            * 子如果不要，则parent.requestDisallowInterceptTouchEvent(false)，父就会intercept后续事件，滑动就交给了父的onTouchEvent来处理
        * 所以无论怎么处理滑动冲突，父总是能得到down（interceptTouchEvent里），子也总是能得到down事件（onTouchEvent里）
            * 但是父如果需要处理，则父的onTouchEvent只能从move开始了，只有move-up流
            * 子总是能形成down-move-up流

## 2 滑动冲突

* 几种冲突场景：
    * 父要左右滑，子要上下滑，ViewPager已经处理了这种冲突，肯定是在intercept里处理的
    * 父要左右滑，子也要左右滑，如SwipeBack + ViewPager，这个需要根据具体场景，如点击的位置，靠边缘，则滑动给SwipeBack处理

下面就是解决冲突常用的外部和内部拦截法，都是惯用套路，你只需要判断不同的条件就行，如点击位置，滑动方向

```java
///--------------------------------
外部拦截法：父处理冲突
///-------------------------------

public boolean onInterceptTouchEvent(MotionEvent e){
    boolean intercepted = false;
    int x = (int)e.getX();
    int y = (int)e.getY();
    if(e.getAction() == MotionEvent.ACTION_DOWN){
        intercepted = false;
    }else if(e.getAction() == MotionEvent.ACTION_MOVE){
        if(父需要滑动事件){
            intercepted = true;
        }else{
            intercepted = false;
        }
    }else if(e.getAction() == MotionEvent.ACTION_UP){
        intercepted = false;
    }

    mLastXIntercept = x;
    mLastYIntercept = y;
    return intercepted;
}


///--------------------------------
内部拦截法：子处理冲突，父需要配合
///-------------------------------

//子：根据条件决定是否允许父拦截
public boolean dispatchTouchEvent(MotionEvent e){
    int x = (int)e.getX();
    int y = (int)e.getY();
    if(e.getAction() == MotionEvent.ACTION_DOWN){
        parent.requestDisallowInterceptTouchEvent(true);
    }else if(e.getAction() == MotionEvent.ACTION_MOVE){
        int dx = x - mLastX;
        int dy = y - mLastY;
        if(父需要滑动事件){
            parent.requestDisallowInterceptTouchEvent(false);
        }else{
            //事件就到了onTouchEvent
        }
    }else if(e.getAction() == MotionEvent.ACTION_UP){
    }
    mLastX = x;
    mLastY = y;
    return super.dispatchTouchEvent(e);
}

//父：只要允许拦截，则move和up都拦截
public boolean onInterceptTouchEvent(MotionEvent e){
    int action = e.getAction();
    if(action == MotionEvent.ACTION_DOWN){
        return false;
    }else{
        return true;
    }
}

```

## 3 怎么让控件滑动

上一节解决了滑动冲突，滑动权限最终被某一个控件获得

* 要点
    * 滑动的几种方式
    * 平滑滑动
    * 惯性滑动
    * DragHelper
    * over slide效果
    * pull to refresh实现
    * 常见控件的scroll相关回调和方法，跟着做一些特效

### 关于坐标系

* 要了解滑动， 先得了解安卓的几个坐标系
    * 屏幕坐标系：屏幕左上角为原点
        * 向右为正
        * 向下为正
        * 得到View的屏幕坐标，即左上角在屏幕上的位置：getLocationOnScreen(int[] location)
        * 得到Touch事件的屏幕坐标，ev.getRawX(), ev.getRawY()
    * View坐标系：父View的左上角为原点
        * 向右为正
        * 想下为正
        * 得到View在父控件的坐标：和布局过程有关，getLeft(), getTop()，getRight(), getBottom()，都是到父控件左边和上边的距离
        * 得到Touch事件在View里的坐标：ev.getX(), ev.getY()

### 3.1 scrollTo和scrollBy：控制View内容

```java
public void setScrollX(int value) {
    scrollTo(value, mScrollY);
}

public void setScrollY(int value) {
    scrollTo(mScrollX, value);
}

public final int getScrollX() {
    return mScrollX;
}

public final int getScrollY() {
    return mScrollY;
}

public void scrollBy(int x, int y) {
    scrollTo(mScrollX + x, mScrollY + y);
}

public void scrollTo(int x, int y) {
    if (mScrollX != x || mScrollY != y) {
        int oldX = mScrollX;
        int oldY = mScrollY;
        mScrollX = x;
        mScrollY = y;
        invalidateParentCaches();
        onScrollChanged(mScrollX, mScrollY, oldX, oldY);
        if (!awakenScrollBars()) {
            postInvalidateOnAnimation();
        }
    }
}

```

* 首先要明白mScrollX和mScrollY：
    * mScrollX = View左边缘 - View内容左边缘         水平方向距离
    * mScrollY = View上边缘 - View内容上边缘         垂直方向距离
    * 内容边缘在View边缘的左边时，mScrollX > 0
    * 内容边缘在View边缘的上边时，mScrollY > 0
    * scrollTo(-x, -y)，移动方向取决于当前scroll，但移动后的内容肯定位于View的左边和上边
    * scrollBy(-dx, -dy)，肯定是向左，向上scroll
    * 手指头往右滑动，从x到x1，滑动距离是dx = x1 - x，dx > 0，而View内容应该往右走dx
    * 手指头往下滑动，从y到y1，滑动距离是dy = y1 - y，dy > 0，而View内容应该往下走dy

* View的边缘：
    * 就是View的位置，上下左右四个顶点决定了View的边缘

* View的内容：
    * 可以认为是View所有的子控件就是View的内容，也就是子控件显示成的那副画
    * 所以scroll可能会显示出View本身的背景

* 所以：
    * scroll并不会移动View本身的位置


### 3.2 scrollTo和scrollBy的平滑滑动：Scroller

scrollTo和scrollBy都是瞬间滑动，如果要实现smoothScroll，就会用到Scroller
当然你也可以自己写，但写出来也就是个简陋版的Scroller

Scroller有固定的使用套路：
```java
private Scroller mScroller;

mScroller = new Scroller(getContext());

//在ACTION_MOVE时，下面代码能优化滑动效果，注意，跟着move事件scroll时，本身就是平滑的滑动，不需要Scroller参与
if(!mScroller.isFinished()){
    mScroller.abortAnimation();
}

//在ACTION_UP时，或者其他场景下，需要再滑动一端距离，调用方法：
//startX：左边起始位置，会直接定位到这个位置，再开始
//startY：上边起始位置，会直接定位到这个位置，再开始
//dx，dy：滑动的距离，负值，往左和上，正值，往右和下
public void startScroll(int startX, int startY, int dx, int dy) {
    startScroll(startX, startY, dx, dy, DEFAULT_DURATION);
}

一般这么调用：
mScroller.startScroll(getScrollX(), getScrollY(), dx, dy);
postInvalidate();   ///调用这句之后，会走到computeScroll()

@Override
public void computeScroll() {
    super.computeScroll();
    if(mScroller.computeScrollOffset()){
        scrollTo(mScroller.getCurrX(), mScroller.getCurrY());
        postInvalidate();
    }
}

效果：
Scroller会在指定时间内（默认250ms），给你插值一系列平滑的值，完成平滑滑动

你肯定有疑问，如果有插值器，不就更好了吗，确实有：
public Scroller(Context context, Interpolator interpolator, boolean flywheel)

默认的插值器是这个：
static class ViscousFluidInterpolator implements Interpolator {
    /** Controls the viscous fluid effect (how much of it). */
    private static final float VISCOUS_FLUID_SCALE = 8.0f;

    private static final float VISCOUS_FLUID_NORMALIZE;
    private static final float VISCOUS_FLUID_OFFSET;

    static {

        // must be set to 1.0 (used in viscousFluid())
        VISCOUS_FLUID_NORMALIZE = 1.0f / viscousFluid(1.0f);
        // account for very small floating-point error
        VISCOUS_FLUID_OFFSET = 1.0f - VISCOUS_FLUID_NORMALIZE * viscousFluid(1.0f);
    }

    private static float viscousFluid(float x) {
        x *= VISCOUS_FLUID_SCALE;
        if (x < 1.0f) {
            x -= (1.0f - (float)Math.exp(-x));
        } else {
            float start = 0.36787944117f;   // 1/e == exp(-1)
            x = 1.0f - (float)Math.exp(1.0f - x);
            x = start + x * (1.0f - start);
        }
        return x;
    }

    @Override
    public float getInterpolation(float input) {
        final float interpolated = VISCOUS_FLUID_NORMALIZE * viscousFluid(input);
        if (interpolated > 0) {
            return interpolated + VISCOUS_FLUID_OFFSET;
        }
        return interpolated;
    }
}

```

### 3.3 其他实现滑动的方法：

* 这里说的是控制真实View的位置，不是View影像，也不是View内容
    * layout(left, top, right, bottom)：此方法调用后，会重新绘制View
    * MarginLayoutParam控制margin来实现滑动
    * offsetLeftAndRight(dx)和offsetTopAndBottom(dy)：这个挺新鲜


### 3.4 使用动画来滑动： 控制View影像

* 动画
    * 使用补间动画，操作的都是View的影像，原View还在原来的位置
    * 或者属性动画控制translation属性，操作的都是View的影像，原View还在原来的位置
    * 使用属性动画可以自己控制3.3中提到的滑动接口，来控制真实View位置




### 3.5 VelocityTracker


```java
private VelocityTracker mVelocityTracker;

mVelocityTracker = VelocityTracker.obtain();

@Override
public boolean onTouchEvent(MotionEvent event) {
    mVelocityTracker.addMovement(event);
    if(event.getAction() == MotionEvent.ACTION_UP){
        int scrollX = getScrollX();
        int scrollToChildIndex = scrollX / mChildWidth;
        mVelocityTracker.computeCurrentVelocity(1000);
        float xVelocity = mVelocityTracker.getXVelocity();
        if(Math.abs(xVelocity) >= 50){
                ///滑动速度大于50时，如果是往右滑，速度大于0，往前翻； 如果是往左滑，速度小于0， 往后翻
                mChildIndex = xVelocity > 0 ? mChildIndex - 1 : mChildIndex + 1;
            }else{
                ///滑动速度小于50时，根据当前scroll的距离，判断是翻回去，还是往后翻一页，总之最后肯定是停在一页上
                mChildIndex = (scrollX + mChildWidth / 2) / mChildWidth;
            }
         }
    }
}
```

### 3.6 ViewDragHelper

Google的support为我们提供了DrawerLayout和SlidingPanelLayout两个布局，
在这两个布局背后，就是ViewDragHelper，使用这个类，基本可以实现所有滑动和拖放的效果

下面代码事先的效果是一个ViewGroup里两个控件都可以拖动，而且松手后会回弹到一个固定的位置

这里其实提供了一个ViewDragHelper使用的模板，实际情况下，可能一般不会直接继承ViewGroup，而是继承FrameLayout之类的

具体用法可以参考SwipeBackLayout的实现，是个好例子

```java

public class ViewDragHelperDemoView2 extends FrameLayout {
    public ViewDragHelperDemoView2(Context context) {
        super(context);
        init();
    }

    public ViewDragHelperDemoView2(Context context, AttributeSet attrs) {
        super(context, attrs);
        init();
    }

    public ViewDragHelperDemoView2(Context context, AttributeSet attrs, int defStyleAttr) {
        super(context, attrs, defStyleAttr);
        init();
    }

    @RequiresApi(api = Build.VERSION_CODES.LOLLIPOP)
    public ViewDragHelperDemoView2(Context context, AttributeSet attrs, int defStyleAttr, int defStyleRes) {
        super(context, attrs, defStyleAttr, defStyleRes);
        init();
    }

    ViewDragHelper mViewDragHelper;

    private void init(){
        mViewDragHelper = ViewDragHelper.create(this, callback);
    }

    @Override
    public boolean onInterceptHoverEvent(MotionEvent event) {
        return mViewDragHelper.shouldInterceptTouchEvent(event);
    }

    @Override
    public boolean onTouchEvent(MotionEvent event) {
        mViewDragHelper.processTouchEvent(event);
        return true;
    }

    @Override
    public void computeScroll() {
        //这里是一个常用模板，ViewDragHelper内部也是通过Scroller实现平滑滑动
        if(mViewDragHelper.continueSettling(true)){
            ViewCompat.postInvalidateOnAnimation(this);
        }
    }

    private ViewDragHelper.Callback callback = new ViewDragHelper.Callback(){

        /**
         * child是被触摸的View，这里你来告诉helper这个child是否可以开始检测滑动
         *
         * 一般方法是事先定好几个id，根据id判断是否可以滑动，如DrawerLayout
         int headerId= getResources().getIdentifier("sticky_header", "id", getContext().getPackageName());
         int contentId = getResources().getIdentifier("sticky_content", "id", getContext().getPackageName());
         表示header的id必须是R.id.sticky_header， 内容布局的id必须是R.id.sticky_content

         * @return
         */
        @Override
        public boolean tryCaptureView(View child, int pointerId) {
            ///如果这个ViewGroup有两个子控件，menuView和contentView，则返回child == contentView意思就是只有contentView能拖动
            return true;
        }

        /**
         * 返回值表示The new clamped position for left，返回0表示不发生滑动
         * @param child Child view being dragged
         * @param left  水平方向上child移动的距离
         * @param dx    和前一次相比的增量
         * @return   一般只需要返回left，但如果需要更精确的计算padding等属性，则需要做一些处理
         */
        @Override
        public int clampViewPositionHorizontal(View child, int left, int dx) {
            return left;
        }

        @Override
        public int clampViewPositionVertical(View child, int top, int dy) {
            return top;
        }

        //手松开
        @Override
        public void onViewReleased(View releasedChild, float xvel, float yvel) {
            super.onViewReleased(releasedChild, xvel, yvel);
            Toaster.toastShort("手指松开了");
            mViewDragHelper.smoothSlideViewTo(releasedChild, 300, 500);
            ViewCompat.postInvalidateOnAnimation(ViewDragHelperDemoView2.this);

        }


        @Override
        public void onViewCaptured(View capturedChild, int activePointerId) {
            ///用户触摸到View后回调
            super.onViewCaptured(capturedChild, activePointerId);
        }

        @Override
        public void onViewDragStateChanged(int state) {
            //在拖曳时回调，状态有idle和dragging，idle就是松手了呗
            super.onViewDragStateChanged(state);
        }

        @Override
        public void onViewPositionChanged(View changedView, int left, int top, int dx, int dy) {
            ///控件位置改变了，常用于配合进行一些特效，如缩放
            super.onViewPositionChanged(changedView, left, top, dx, dy);
        }

        @Override
        public void onEdgeDragStarted(int edgeFlags, int pointerId) {
            super.onEdgeDragStarted(edgeFlags, pointerId);
            Log.i("drag", "onEdgeDragStarted--" + edgeFlags);
        }

        @Override
        public boolean onEdgeLock(int edgeFlags) {
            boolean res = super.onEdgeLock(edgeFlags);
            Log.i("drag", "onEdgeLock--" + res);
            return res;
        }

        @Override
        public void onEdgeTouched(int edgeFlags, int pointerId) {
            super.onEdgeTouched(edgeFlags, pointerId);
            Log.i("drag", "onEdgeTouched--" + edgeFlags);
        }
    };


}
```

```xml
<FrameLayout xmlns:android="http://schemas.android.com/apk/res/android"
    xmlns:tools="http://schemas.android.com/tools"
    android:layout_width="match_parent"
    android:layout_height="match_parent"
    android:background="#ffffff">

    <org.ayo.ui.sample.view_learn.ViewDragHelperDemoView2
        android:layout_width="match_parent"
        android:layout_height="match_parent"
        android:background="#34e6a5"
        >
        <TextView
            android:layout_width="200dp"
            android:layout_height="100dp"
            android:textColor="#ffffff"
            android:gravity="center"
            android:layout_gravity="center"
            android:text="oooo 1 oooo"
            android:textSize="16sp"
            android:background="#000000" />

        <TextView
            android:layout_width="200dp"
            android:layout_height="100dp"
            android:textColor="#ffffff"
            android:gravity="center"
            android:layout_gravity="center"
            android:text="oooo 2 oooo"
            android:textSize="16sp"
            android:background="#000000" />
    </org.ayo.ui.sample.view_learn.ViewDragHelperDemoView2>


</FrameLayout>
```

* ViewDragHelper提供的功能有：
    * 触摸事件的封装
    * Scroller的封装
    * 边缘触摸相关功能



### 3.7 滑动总结：
* 滑动总结：
    * 滑动View本身：平移效果
        * layout(left, top, right, bottom)：此方法调用后，会重新绘制View
        * MarginLayoutParam控制margin来实现滑动
        * offsetLeftAndRight(dx)和offsetTopAndBottom(dy)：这个挺新鲜
        * translateX和tranlateY，但注意这个控制的是View的影像
        * 这里的滑动一般是移动View位置，或者平移动画
    * 滑动View的内容：scroll效果
        * scrollTo和scrollBy
        * 平滑移动要用Scroller或Handler或属性动画，推荐Scroller，如果是外部控制，则只能是Handler或属性动画
    * 激活滑动的方式：激活上面两种滑动方式
        * Touch事件：通过触摸事件来平移或者scroll
            * Touch事件传递
            * 解决冲突
        * 直接滑动：直接设置个平移量或scroll量，然后平移或scroll
    * 滑动的效果：
        * View本身的滑动
        * View内容的滑动
            * ViewPager效果：最终会停在某一页
            * ScrollView效果：停止的位置会根据手抬起时的滑动速度来决定
            * OverScroll效果：滑到边缘还可以滑，不过会回弹
    * 滑动的辅助工具：
        * 平滑相关：Scroller，还可以设置Intercepter和duraion
        * 速度相关：VelocityTracker
        * 终极大杀器：ViewDragHelper


## 4 几个常用的滑动控件研究

* 这里研究了所有常用的可以滑动的控件，给出：
    * 使用方法
    * 和滑动相关的回调

ScrollView

NestedScrollView

ViewPager

ListView

GridView

RecyclerView

DrawerLayout

SwipebackLayout

CodinatorView


## 4 实例1： 仿ScrollView

FakeScrollView


## 5 实例2： 仿ViewPager

FakeHorizontalScrollView


## 6 实例3：StickyLayout

可以包含一个ListView和一个任意的headerView，在合适时机会接管ListView的滑动权限


## 7 实例4： PinnedHeaderExpandableListView

Pinned效果的ExpandableListView，直接继承ExpandableListView


## 最后：手势


