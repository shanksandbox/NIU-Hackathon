package com.pride.adapter;

import android.app.Activity;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.RelativeLayout;
import android.widget.TextView;
import androidx.recyclerview.widget.RecyclerView;
import com.pride.item.ItemCategory;
import com.pride.BnB.R;
import com.pride.util.JsonUtils;
import com.pride.util.OnClick;
import com.squareup.picasso.Picasso;

import java.util.ArrayList;


public class HomeCategoryAdapter extends RecyclerView.Adapter<HomeCategoryAdapter.ItemRowHolder> {

    private ArrayList<ItemCategory> dataList;
    private Activity mContext;
    private JsonUtils jsonUtils;

    public HomeCategoryAdapter(Activity context, ArrayList<ItemCategory> dataList, OnClick onClick) {
        this.dataList = dataList;
        this.mContext = context;
        jsonUtils=new JsonUtils(mContext,onClick);
    }

    @Override
    public ItemRowHolder onCreateViewHolder(ViewGroup parent, int viewType) {
        View v = LayoutInflater.from(parent.getContext()).inflate(R.layout.row_home_cat_item, parent, false);
        return new ItemRowHolder(v);
    }

    @Override
    public void onBindViewHolder(final ItemRowHolder holder, final int position) {
        final ItemCategory singleItem = dataList.get(position);

        Picasso.get().load(singleItem.getCategoryImageBig()).placeholder(R.drawable.place_holder_small).into(holder.image);
        holder.text_cat.setText(singleItem.getCategoryName());
        holder.lyt_parent.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                jsonUtils.onClickAd(position,"",singleItem.getCategoryId(),singleItem.getCategoryName());
            }
        });
    }

    @Override
    public int getItemCount() {
        return (null != dataList ? dataList.size() : 0);
    }

    public class ItemRowHolder extends RecyclerView.ViewHolder {
        public ImageView image;
        private TextView text_cat;
        private RelativeLayout lyt_parent;

        private ItemRowHolder(View itemView) {
            super(itemView);
            image = itemView.findViewById(R.id.image);
            lyt_parent = itemView.findViewById(R.id.rootLayout);
            text_cat = itemView.findViewById(R.id.text_cat);
        }
    }
}
