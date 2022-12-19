package com.pride.util;

import android.app.Activity;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.Intent;
import android.os.Bundle;

import androidx.annotation.NonNull;

import com.pride.BnB.ActivityDetail;
import com.pride.BnB.R;
import com.facebook.ads.Ad;
import com.facebook.ads.AdError;
import com.facebook.ads.CacheFlag;
import com.facebook.ads.InterstitialAdListener;
import com.google.ads.mediation.admob.AdMobAdapter;
import com.google.android.gms.ads.AdRequest;
import com.google.android.gms.ads.FullScreenContentCallback;
import com.google.android.gms.ads.LoadAdError;
import com.google.android.gms.ads.interstitial.InterstitialAd;
import com.google.android.gms.ads.interstitial.InterstitialAdLoadCallback;
import com.ixidev.gdpr.GDPRChecker;


public class PopUpAds {

    public static ProgressDialog pDialog;

    public static void ShowInterstitialAds(final Context context, final String Id) {

        if (Constant.SAVE_ADS_FULL_ON_OFF.equals("true")) {
            if (Constant.SAVE_FULL_TYPE.equals("admob")) {
                Constant.AD_COUNT++;
                if (Constant.AD_COUNT == Integer.parseInt(Constant.SAVE_ADS_CLICK)) {
                    Constant.AD_COUNT = 0;
                    Loading(context);
                    GDPRChecker.Request request = GDPRChecker.getRequest();
                    AdRequest.Builder builder = new AdRequest.Builder();
                    if (request == GDPRChecker.Request.NON_PERSONALIZED) {
                        Bundle extras = new Bundle();
                        extras.putString("npa", "1");
                        builder.addNetworkExtrasBundle(AdMobAdapter.class, extras);
                    }
                    InterstitialAd.load(context, Constant.SAVE_ADS_FULL_ID, builder.build(), new InterstitialAdLoadCallback() {
                        @Override
                        public void onAdLoaded(@NonNull InterstitialAd interstitialAd) {
                            super.onAdLoaded(interstitialAd);
                            interstitialAd.show((Activity) context);
                            pDialog.dismiss();
                            interstitialAd.setFullScreenContentCallback(new FullScreenContentCallback() {
                                @Override
                                public void onAdDismissedFullScreenContent() {
                                    super.onAdDismissedFullScreenContent();
                                    Intent intent_single = new Intent(context, ActivityDetail.class);
                                    intent_single.putExtra("Id", Id);
                                    intent_single.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
                                    context.startActivity(intent_single);
                                }

                                @Override
                                public void onAdFailedToShowFullScreenContent(com.google.android.gms.ads.AdError adError) {
                                    super.onAdFailedToShowFullScreenContent(adError);
                                    pDialog.dismiss();
                                    Intent intent_single = new Intent(context, ActivityDetail.class);
                                    intent_single.putExtra("Id", Id);
                                    intent_single.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
                                    context.startActivity(intent_single);
                                }
                            });
                        }

                        @Override
                        public void onAdFailedToLoad(@NonNull LoadAdError loadAdError) {
                            super.onAdFailedToLoad(loadAdError);
                            pDialog.dismiss();
                            Intent intent_single = new Intent(context, ActivityDetail.class);
                            intent_single.putExtra("Id", Id);
                            intent_single.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
                            context.startActivity(intent_single);
                        }
                    });
                } else {
                    Intent intent_single = new Intent(context, ActivityDetail.class);
                    intent_single.putExtra("Id", Id);
                    intent_single.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
                    context.startActivity(intent_single);
                }
            } else {
                Constant.AD_COUNT++;
                if (Constant.AD_COUNT == Integer.parseInt(Constant.SAVE_ADS_CLICK)) {
                    Constant.AD_COUNT = 0;
                    Loading(context);
                    com.facebook.ads.InterstitialAd mInterstitialfb = new com.facebook.ads.InterstitialAd(context, Constant.SAVE_ADS_FULL_ID);
                    InterstitialAdListener interstitialAdListener = new InterstitialAdListener() {
                        @Override
                        public void onInterstitialDisplayed(Ad ad) {
                        }

                        @Override
                        public void onInterstitialDismissed(Ad ad) {
                            Intent intent_single = new Intent(context, ActivityDetail.class);
                            intent_single.putExtra("Id", Id);
                            intent_single.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
                            context.startActivity(intent_single);
                        }

                        @Override
                        public void onError(Ad ad, AdError adError) {
                            pDialog.dismiss();
                            Intent intent_single = new Intent(context, ActivityDetail.class);
                            intent_single.putExtra("Id", Id);
                            intent_single.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
                            context.startActivity(intent_single);
                        }

                        @Override
                        public void onAdLoaded(Ad ad) {
                            pDialog.dismiss();
                            mInterstitialfb.show();
                        }

                        @Override
                        public void onAdClicked(Ad ad) {
                        }

                        @Override
                        public void onLoggingImpression(Ad ad) {
                        }
                    };
                    com.facebook.ads.InterstitialAd.InterstitialLoadAdConfig loadAdConfig = mInterstitialfb.buildLoadAdConfig().withAdListener(interstitialAdListener).withCacheFlags(CacheFlag.ALL).build();
                    mInterstitialfb.loadAd(loadAdConfig);

                } else {
                    Intent intent_single = new Intent(context, ActivityDetail.class);
                    intent_single.putExtra("Id", Id);
                    intent_single.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
                    context.startActivity(intent_single);
                }
            }
        } else {
            Intent intent_single = new Intent(context, ActivityDetail.class);
            intent_single.putExtra("Id", Id);
            intent_single.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
            context.startActivity(intent_single);
        }
    }

    public static void Loading(Context context) {
        pDialog = new ProgressDialog(context);
        pDialog.setMessage(context.getResources().getString(R.string.loading));
        pDialog.setCancelable(false);
        pDialog.show();
    }
}
