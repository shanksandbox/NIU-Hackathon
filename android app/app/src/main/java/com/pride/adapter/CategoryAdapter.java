package com.pride.adapter;

import android.annotation.SuppressLint;
import android.app.Activity;
import android.os.Bundle;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.RelativeLayout;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;

import com.pride.item.ItemCategory;
import com.pride.BnB.R;
import com.pride.util.Constant;
import com.pride.util.JsonUtils;
import com.pride.util.OnClick;
import com.facebook.ads.Ad;
import com.facebook.ads.AdError;
import com.facebook.ads.AdOptionsView;
import com.facebook.ads.MediaView;
import com.facebook.ads.NativeAd;
import com.facebook.ads.NativeAdLayout;
import com.facebook.ads.NativeAdListener;
import com.google.ads.mediation.admob.AdMobAdapter;
import com.google.android.ads.nativetemplates.NativeTemplateStyle;
import com.google.android.ads.nativetemplates.TemplateView;
import com.google.android.gms.ads.AdLoader;
import com.google.android.gms.ads.AdRequest;
import com.squareup.picasso.Picasso;

import java.util.ArrayList;
import java.util.List;


public class CategoryAdapter extends RecyclerView.Adapter {

    private Activity activity;
    private final int VIEW_TYPE_ITEM = 1;
    private final int VIEW_TYPE_Ad = 0;
    private ArrayList<ItemCategory> dataList;
    private JsonUtils jsonUtils;

    public CategoryAdapter(Activity context, ArrayList<ItemCategory> dataList, OnClick onClick) {
        this.dataList = dataList;
        this.activity = context;
        jsonUtils = new JsonUtils(activity, onClick);
    }

    @NonNull
    @Override
    public RecyclerView.ViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        if (viewType == VIEW_TYPE_ITEM) {
            View view = LayoutInflater.from(activity).inflate(R.layout.row_cat_item, parent, false);
            return new ViewHolder(view);
        } else if (viewType == VIEW_TYPE_Ad) {
            View view = LayoutInflater.from(activity).inflate(R.layout.admob_adapter, parent, false);
            return new AdOption(view);
        }
        return null;
    }

    @Override
    public void onBindViewHolder(@NonNull final RecyclerView.ViewHolder holder, @SuppressLint("RecyclerView") final int position) {

        if (holder.getItemViewType() == VIEW_TYPE_ITEM) {

            final ViewHolder viewHolder = (ViewHolder) holder;
            final ItemCategory singleItem = dataList.get(position);
            Picasso.get().load(singleItem.getCategoryImageBig()).placeholder(R.drawable.place_holder_small).into(viewHolder.image);
            viewHolder.text_cat.setText(singleItem.getCategoryName());

            viewHolder.lyt_parent.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    jsonUtils.onClickAd(position, "", singleItem.getCategoryId(), singleItem.getCategoryName());
                }
            });
        } else if (holder.getItemViewType() == VIEW_TYPE_Ad) {

            final AdOption adOption = (AdOption) holder;
            if (Constant.SAVE_ADS_NATIVE_ON_OFF.equals("true")) {
                if (Constant.SAVE_NATIVE_TYPE.equals("admob")) {

                    if (adOption.linearLayout.getChildCount() == 0) {

                        View view = activity.getLayoutInflater().inflate(R.layout.admob_ad, null, true);

                        final TemplateView templateView = view.findViewById(R.id.my_template);
                        if (templateView.getParent() != null) {
                            ((ViewGroup) templateView.getParent()).removeView(templateView); // <- fix
                        }
                        adOption.linearLayout.addView(templateView);
                        AdLoader adLoader = new AdLoader.Builder(activity, Constant.SAVE_NATIVE_ID)
                                .forNativeAd(nativeAd -> {
                                    NativeTemplateStyle styles = new
                                            NativeTemplateStyle.Builder()
                                            .build();

                                    templateView.setStyles(styles);
                                    templateView.setNativeAd(nativeAd);

                                })
                                .build();
                        AdRequest adRequest;
                        if (JsonUtils.personalization_ad) {
                            adRequest = new AdRequest.Builder()
                                    .build();
                        } else {
                            Bundle extras = new Bundle();
                            extras.putString("npa", "1");
                            adRequest = new AdRequest.Builder()
                                    .addNetworkExtrasBundle(AdMobAdapter.class, extras)
                                    .build();

                        }
                        adLoader.loadAd(adRequest);
                    }

                } else {
                    if (adOption.linearLayout.getChildCount() == 0) {

                        LayoutInflater inflater = LayoutInflater.from(activity);
                        LinearLayout adView = (LinearLayout) inflater.inflate(R.layout.native_ad_layout, adOption.linearLayout, false);

                        adOption.linearLayout.addView(adView);

                        // Add the AdOptionsView
                        final LinearLayout adChoicesContainer = adView.findViewById(R.id.ad_choices_container);

                        // Create native UI using the ad metadata.
                        final TextView nativeAdTitle = adView.findViewById(R.id.native_ad_title);
                        final MediaView nativeAdMedia = adView.findViewById(R.id.native_ad_media);
                        final TextView nativeAdSocialContext = adView.findViewById(R.id.native_ad_social_context);
                        final TextView nativeAdBody = adView.findViewById(R.id.native_ad_body);
                        final TextView sponsoredLabel = adView.findViewById(R.id.native_ad_sponsored_label);
                        final Button nativeAdCallToAction = adView.findViewById(R.id.native_ad_call_to_action);

                        final NativeAd nativeAd = new NativeAd(activity, Constant.SAVE_NATIVE_ID);
                        //AdSettings.addTestDevice("1035dc69-0d11-45c5-bfaf-8f7f7e76e42a");
                        NativeAdListener nativeAdListener = new NativeAdListener() {
                            @Override
                            public void onMediaDownloaded(Ad ad) {
                                Log.d("status_data", "MediaDownloaded" + " " + ad.toString());
                            }

                            @Override
                            public void onError(Ad ad, AdError adError) {
                                Toast.makeText(activity, adError.toString(), Toast.LENGTH_SHORT).show();
                                Log.d("status_data", "error" + " " + adError.toString());
                            }

                            @Override
                            public void onAdLoaded(Ad ad) {
                                // Race condition, load() called again before last ad was displayed
                                if (nativeAd == null || nativeAd != ad) {
                                    return;
                                }
                                // Inflate Native Ad into Container
                                Log.d("status_data", "on load" + " " + ad.toString());

                                NativeAdLayout nativeAdLayout = new NativeAdLayout(activity);
                                AdOptionsView adOptionsView = new AdOptionsView(activity, nativeAd, nativeAdLayout);
                                adChoicesContainer.removeAllViews();
                                adChoicesContainer.addView(adOptionsView, 0);

                                // Set the Text.
                                nativeAdTitle.setText(nativeAd.getAdvertiserName());
                                nativeAdBody.setText(nativeAd.getAdBodyText());
                                nativeAdSocialContext.setText(nativeAd.getAdSocialContext());
                                nativeAdCallToAction.setVisibility(nativeAd.hasCallToAction() ? View.VISIBLE : View.INVISIBLE);
                                nativeAdCallToAction.setText(nativeAd.getAdCallToAction());
                                sponsoredLabel.setText(nativeAd.getSponsoredTranslation());

                                // Create a list of clickable views
                                List<View> clickableViews = new ArrayList<>();
                                clickableViews.add(nativeAdTitle);
                                clickableViews.add(nativeAdCallToAction);

                                // Register the Title and CTA button to listen for clicks.
                                nativeAd.registerViewForInteraction(
                                        adOption.linearLayout,
                                        nativeAdMedia,
                                        clickableViews);

                            }

                            @Override
                            public void onAdClicked(Ad ad) {
                                Log.d("status_data", "AdClicked" + " " + ad.toString());
                            }

                            @Override
                            public void onLoggingImpression(Ad ad) {
                                Log.d("status_data", "Impression" + " " + ad.toString());
                            }

                        };
                        // Request an ad
                        nativeAd.loadAd(nativeAd.buildLoadAdConfig().withAdListener(nativeAdListener).build());
                    }
                }
            }
        }

    }

    @Override
    public int getItemCount() {
        return (null != dataList ? dataList.size() : 0);
    }

    @Override
    public int getItemViewType(int position) {
        return dataList.get(position) != null ? VIEW_TYPE_ITEM : VIEW_TYPE_Ad;
    }

    public class ViewHolder extends RecyclerView.ViewHolder {
        public ImageView image;
        private TextView text_cat;
        private RelativeLayout lyt_parent;
        private LinearLayout lay_bg;

        public ViewHolder(View itemView) {
            super(itemView);
            image = itemView.findViewById(R.id.image);
            lyt_parent = itemView.findViewById(R.id.rootLayout);
            text_cat = itemView.findViewById(R.id.text_cat);
            lay_bg = itemView.findViewById(R.id.lay_bg);
        }
    }

    public class AdOption extends RecyclerView.ViewHolder {

        private LinearLayout linearLayout;

        public AdOption(View itemView) {
            super(itemView);

            linearLayout = itemView.findViewById(R.id.adView_admob);

        }

    }

}