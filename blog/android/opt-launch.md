# 启动优化

# 初始化分步器：主要是为了让初始化过程可见并且可配置
----------------------------------------

初始化器，所有程序启动时要做的初始化工作，请放在这里

为什么呢

现在不建议在Application里做所有初始化工作，而是放在SplashActivity

 注意，初始化不完成，SplashActivity不结束，一直完不成初始化，大不了就一直不进应用

 可以允许的失败：可能有些初始化过程允许失败

你还可以把初始化过程分为几步，每一步都对结果进行监听，并作出单独处理

* 剩下的问题就是：
    * 1 初始化放在SpashActivity的哪里，才能保证界面瞬间弹出？
    * 2 对于SpashActivity需要用到的库，也必须做完初始化才能进行，那SpashActivity界面怎么设计呢？
    * 3 有些初始化是比较耗时的，比如视频，IM，Logger等


```java

Initializer.initailizer()
     .addStep(new StepOfCrash())
     .addStep(new StepOfAyoView())
     .addStep(new StepOfAyoSdk())
     .addStep(new StepOfSdCard(agent.getActivity()))
     .addStep(new StepOfLog())
     .addStep(new StepOfHttp())
     .addStep(new StepOfImageLoader())
     .setStepListener(new Initializer.StepListner() {
        @Override
        public boolean onSuffering(Initializer.Step step, boolean isSuccess, int currentStep, int total) {

            //统一判断
            if (!isSuccess && !step.acceptFail()) {
                //退出，提示错误
                Toaster.toastShort(step.getNotify());
                //finish();
                return false;
            }

            //单步逻辑
            if (step.getName().equals("UI Framework")) {
                if(currentStep == total){
                App.isInitialed = true;
                onLoadFinish();
            }

            return true;
        }
    })
    .suffer();

```