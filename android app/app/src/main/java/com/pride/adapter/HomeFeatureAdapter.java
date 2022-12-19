package com.pride.adapter;

import android.app.Activity;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;

import androidx.recyclerview.widget.RecyclerView;

import com.pride.item.ItemPlaceList;
import com.pride.BnB.R;
import com.pride.util.PopUpAds;
import com.github.ornolfr.ratingview.RatingView;
import com.squareup.picasso.Picasso;

import java.util.ArrayList;


public class HomeFeatureAdapter extends RecyclerView.Adapter<HomeFeatureAdapter.ItemRowHolder> {

    private ArrayList<ItemPlaceList> dataList;
    private Activity mContext;

    public HomeFeatureAdapter(Activity context, ArrayList<ItemPlaceList> dataList) {
        this.dataList = dataList;
        this.mContext = context;
    }

    @Override
    public ItemRowHolder onCreateViewHolder(ViewGroup parent, int viewType) {
        View v = LayoutInflater.from(parent.getContext()).inflate(R.layout.row_slider_item, parent, false);
        return new ItemRowHolder(v);
    }

    @Override
    public void onBindViewHolder(final ItemRowHolder holder, final int position) {
        final ItemPlaceList singleItem = dataList.get(position);

        Picasso.get().load(singleItem.getPlaceImage()).placeholder(R.drawable.place_holder_small).into(holder.image);
        holder.text_title.setText(singleItem.getPlaceName());
        holder.text_cat.setText(singleItem.getPlaceCatName());
        if (singleItem.getPlaceRateAvg().isEmpty()) {
            holder.ratingView.setRating(0);
        } else {
            holder.ratingView.setRating(Float.parseFloat(singleItem.getPlaceRateAvg()));
        }
        if (mContext.getResources().getString(R.string.isRTL).equals("true")) {
            holder.lay_corner.setBackgroundResource(R.drawable.cat_corner_right);
        } else {
            holder.lay_corner.setBackgroundResource(R.drawable.cat_corner);
        }


        holder.lyt_parent.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                PopUpAds.ShowInterstitialAds(mContext, singleItem.getPlaceId());
            }
        });


    }

    @Override
    public int getItemCount() {
        return (null != dataList ? dataList.size() : 0);
    }

    public class ItemRowHolder extends RecyclerView.ViewHolder {
        public ImageView image;
        private TextView text_title, text_cat;
        private LinearLayout lyt_parent, lay_corner;
        private RatingView ratingView;

        private ItemRowHolder(View itemView) {
            super(itemView);
            image = itemView.findViewById(R.id.image);
            lyt_parent = itemView.findViewById(R.id.rootLayout);
            text_title = itemView.findViewById(R.id.text_title);
            ratingView = itemView.findViewById(R.id.ratingView);
            text_cat = itemView.findViewById(R.id.text_cat);
            lay_corner = itemView.findViewById(R.id.sec_time);
        }
    }
}
