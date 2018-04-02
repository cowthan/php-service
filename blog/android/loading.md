# loading系列库

没啥，就是拷的AVLoaingIndicatorView，版本2.1.3
===================================================

原项目地址：https://github.com/81813780/AVLoadingIndicatorView

## 1 用法

注意：最新的是view库里的代码，list库里也有，但只是为了配合XRecyclerView，是旧版，再别的地方就别用了

```java
<org.ayo.view.progress.av.AVLoadingIndicatorView
    android:id="@+id/avi"
    android:layout_width="wrap_content"
    android:layout_height="wrap_content"
    style="@style/AVLoadingIndicatorView.Large"
    />

内置style有三个：
<style name="SwipeBackLayout">
    <item name="edge_size">50dip</item>
    <item name="shadow_left">@drawable/ayo_swipe_shadow_left</item>
    <item name="shadow_right">@drawable/ayo_swipe_shadow_right</item>
    <item name="shadow_bottom">@drawable/ayo_swipe_shadow_bottom</item>
</style>

<style name="AVLoadingIndicatorView">
    <item name="minWidth">48dip</item>
    <item name="maxWidth">48dip</item>
    <item name="minHeight">48dip</item>
    <item name="maxHeight">48dip</item>
    <item name="indicatorName">BallPulseIndicator</item>
</style>

<style name="AVLoadingIndicatorView.Large">
    <item name="minWidth">76dip</item>
    <item name="maxWidth">76dip</item>
    <item name="minHeight">76dip</item>
    <item name="maxHeight">76dip</item>
    <item name="indicatorName">BallPulseIndicator</item>
</style>

<style name="AVLoadingIndicatorView.Small">
    <item name="minWidth">24dip</item>
    <item name="maxWidth">24dip</item>
    <item name="minHeight">24dip</item>
    <item name="maxHeight">24dip</item>
    <item name="indicatorName">BallPulseIndicator</item>
</style>

代码里：
void startAnim(){
    avi.show();
    // or avi.smoothToShow();
}

void stopAnim(){
    avi.hide();
    // or avi.smoothToHide();
}

indicatorName的值：（内置的是以下值，自定义的用全限定名）
Row 1
BallPulseIndicator
BallGridPulseIndicator
BallClipRotateIndicator
BallClipRotatePulseIndicator

Row 2
SquareSpinIndicator
BallClipRotateMultipleIndicator
BallPulseRiseIndicator
BallRotateIndicator

Row 3
CubeTransitionIndicator
BallZigZagIndicator
BallZigZagDeflectIndicator
BallTrianglePathIndicator

Row 4
BallScaleIndicator
LineScaleIndicator
LineScalePartyIndicator
BallScaleMultipleIndicator

Row 5
BallPulseSyncIndicator
BallBeatIndicator
LineScalePulseOutIndicator
LineScalePulseOutRapidIndicator

Row 6
BallScaleRippleIndicator
BallScaleRippleMultipleIndicator
BallSpinFadeLoaderIndicator
LineSpinFadeLoaderIndicator

Row 7
TriangleSkewSpinIndicator
PacmanIndicator
BallGridBeatIndicator
SemiCircleSpinIndicator

自定义Indicator：

public class MyCustomIndicator extends Indicator {


    public static final float SCALE=1.0f;

    //scale x ,y
    private float[] scaleFloats=new float[]{SCALE,
            SCALE,
            SCALE,
            SCALE,
            SCALE};



    @Override
    public void draw(Canvas canvas, Paint paint) {
        float circleSpacing=4;
        float radius=(Math.min(getWidth(),getHeight())-circleSpacing*2)/12;
        float x = getWidth()/ 2-(radius*2+circleSpacing);
        float y=getHeight() / 2;
        for (int i = 0; i < 4; i++) {
            canvas.save();
            float translateX=x+(radius*2)*i+circleSpacing*i;
            canvas.translate(translateX, y);
            canvas.scale(scaleFloats[i], scaleFloats[i]);
            canvas.drawCircle(0, 0, radius, paint);
            canvas.restore();
        }
    }

    @Override
    public ArrayList<ValueAnimator> onCreateAnimators() {
        ArrayList<ValueAnimator> animators=new ArrayList<>();
        int[] delays=new int[]{120,240,360,480};
        for (int i = 0; i < 4; i++) {
            final int index=i;

            ValueAnimator scaleAnim=ValueAnimator.ofFloat(1,0.3f,1);

            scaleAnim.setDuration(750);
            scaleAnim.setRepeatCount(-1);
            scaleAnim.setStartDelay(delays[i]);

            addUpdateListener(scaleAnim,new ValueAnimator.AnimatorUpdateListener() {
                @Override
                public void onAnimationUpdate(ValueAnimator animation) {
                    scaleFloats[index] = (float) animation.getAnimatedValue();
                    postInvalidate();

                }
            });
            animators.add(scaleAnim);
        }
        return animators;
    }


}

```


