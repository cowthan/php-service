# 列表Adapter模板化

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