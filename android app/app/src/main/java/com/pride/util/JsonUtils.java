package com.pride.util;

import android.annotation.TargetApi;
import android.app.Activity;
import android.app.ProgressDialog;
import android.content.Context;
import android.graphics.Color;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.os.Build;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.view.Window;
import android.widget.LinearLayout;

import androidx.annotation.NonNull;

import com.pride.BnB.R;
import com.facebook.ads.Ad;
import com.facebook.ads.AdError;
import com.facebook.ads.CacheFlag;
import com.facebook.ads.InterstitialAdListener;
import com.google.ads.mediation.admob.AdMobAdapter;
import com.google.android.gms.ads.AdRequest;
import com.google.android.gms.ads.AdSize;
import com.google.android.gms.ads.AdView;
import com.google.android.gms.ads.FullScreenContentCallback;
import com.google.android.gms.ads.LoadAdError;
import com.google.android.gms.ads.interstitial.InterstitialAd;
import com.google.android.gms.ads.interstitial.InterstitialAdLoadCallback;
import com.ixidev.gdpr.GDPRChecker;

import java.io.ByteArrayOutputStream;
import java.io.InputStream;
import java.net.HttpURLConnection;
import java.net.URL;
import java.util.regex.Matcher;
import java.util.regex.Pattern;

public class JsonUtils {

    private Context _context;
    private Activity activity;
    public static boolean personalization_ad = false;
    private OnClick onClick;

    public JsonUtils(Context context) {
        this._context = context;
    }

    public JsonUtils(Context _context, Activity activity) {
        this._context = _context;
        this.activity = activity;
    }

    // constructor
    public JsonUtils(Context _context, OnClick onClick) {
        this._context = _context;
        this.onClick = onClick;
    }

    public static String getJSONString(String url) {
        String jsonString = null;
        HttpURLConnection linkConnection = null;
        try {
            URL linkurl = new URL(url);
            linkConnection = (HttpURLConnection) linkurl.openConnection();
            int responseCode = linkConnection.getResponseCode();
            if (responseCode == HttpURLConnection.HTTP_OK) {
                InputStream linkinStream = linkConnection.getInputStream();
                ByteArrayOutputStream baos = new ByteArrayOutputStream();
                int j = 0;
                while ((j = linkinStream.read()) != -1) {
                    baos.write(j);
                }
                byte[] data = baos.toByteArray();
                jsonString = new String(data);
            }
        } catch (Exception e) {
            e.printStackTrace();
        } finally {
            if (linkConnection != null) {
                linkConnection.disconnect();
            }
        }
        return jsonString;
    }


    public static String getJSONString(String url, String base64) {
        String jsonString = null;
        HttpURLConnection con = null;
        try {
            URL obj = new URL(url);
            con = (HttpURLConnection) obj.openConnection();
            con.setRequestMethod("POST");
            con.setRequestProperty("Content-Type", "application/x-www-form-urlencoded");

            String postJsonData = "data=" + base64;
            byte[] postDataBytes = postJsonData.getBytes("UTF-8");
            con.setDoOutput(true);
            con.getOutputStream().write(postDataBytes);
            int responseCode = con.getResponseCode();
            if (responseCode == HttpURLConnection.HTTP_OK) {

                InputStream linkinStream = con.getInputStream();
                ByteArrayOutputStream baos = new ByteArrayOutputStream();
                int j = 0;
                while ((j = linkinStream.read()) != -1) {
                    baos.write(j);
                }
                byte[] data = baos.toByteArray();
                jsonString = new String(data);
            }

        } catch (Exception e) {
            e.printStackTrace();
        } finally {
            if (con != null) {
                con.disconnect();
            }
        }
        return jsonString;
    }

    public static boolean isNetworkAvailable(Activity activity) {
        ConnectivityManager connectivity = (ConnectivityManager) activity
                .getSystemService(Context.CONNECTIVITY_SERVICE);
        if (connectivity == null) {
            return false;
        } else {
            NetworkInfo[] info = connectivity.getAllNetworkInfo();
            if (info != null) {
                for (int i = 0; i < info.length; i++) {
                    if (info[i].getState() == NetworkInfo.State.CONNECTED) {
                        return true;
                    }
                }
            }
        }
        return false;
    }

    final static String reg = "(?:youtube(?:-nocookie)?\\.com\\/(?:[^\\/\\n\\s]+\\/\\S+\\/|(?:v|e(?:mbed)?)\\/|\\S*?[?&]v=)|youtu\\.be\\/)([a-zA-Z0-9_-]{11})";

    public static String getVideoId(String videoUrl) {
        if (videoUrl == null || videoUrl.trim().length() <= 0)
            return null;

        Pattern pattern = Pattern.compile(reg, Pattern.CASE_INSENSITIVE);
        Matcher matcher = pattern.matcher(videoUrl);

        if (matcher.find())
            return matcher.group(1);
        return null;
    }

    public void forceRTLIfSupported(Window window) {
        if (_context.getResources().getString(R.string.isRTL).equals("true")) {
            if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.JELLY_BEAN_MR1) {
                window.getDecorView().setLayoutDirection(View.LAYOUT_DIRECTION_RTL);
            }
        }
    }

    public static void ShowBannerAds(Context context, LinearLayout mAdViewLayout) {
        if (Constant.SAVE_ADS_BANNER_ON_OFF.equals("true")) {
            AdView mAdView = new AdView(context);
            mAdView.setAdSize(AdSize.BANNER);
            mAdView.setAdUnitId(Constant.SAVE_ADS_BANNER_ID);
            AdRequest.Builder builder = new AdRequest.Builder();
            GDPRChecker.Request request = GDPRChecker.getRequest();
            if (request == GDPRChecker.Request.NON_PERSONALIZED) {
                // load non Personalized ads
                Bundle extras = new Bundle();
                extras.putString("npa", "1");
                builder.addNetworkExtrasBundle(AdMobAdapter.class, extras);
            } // else do nothing , it will load PERSONALIZED ads
            mAdView.loadAd(builder.build());
            mAdViewLayout.addView(mAdView);
        } else {
            mAdViewLayout.setVisibility(View.GONE);
        }
    }

    public static void ShowBannerAdsFb(LinearLayout adLayout, Activity activity) {

        if (Constant.SAVE_ADS_BANNER_ON_OFF.equals("true")) {
            com.facebook.ads.AdView adView = new com.facebook.ads.AdView(activity, Constant.SAVE_ADS_BANNER_ID, com.facebook.ads.AdSize.BANNER_HEIGHT_50);
            adView.loadAd();
            adLayout.addView(adView);
        } else {
            adLayout.setVisibility(View.GONE);
        }
    }

    @TargetApi(Build.VERSION_CODES.LOLLIPOP)
    public static void setStatusBarGradiant(Activity activity) {
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.LOLLIPOP) {
            activity.getWindow().getDecorView().setSystemUiVisibility(
                    View.SYSTEM_UI_FLAG_LAYOUT_STABLE
                            | View.SYSTEM_UI_FLAG_LAYOUT_FULLSCREEN);
            activity.getWindow().setStatusBarColor(Color.TRANSPARENT);
        }
    }

    //---------------Interstitial Ad---------------//
    public void onClickAd(final int position, final String type, final String id, final String title) {

        final ProgressDialog progressDialog = new ProgressDialog(_context);
        progressDialog.show();
        progressDialog.setMessage(_context.getResources().getString(R.string.loading));
        progressDialog.setCancelable(false);

        if (Constant.SAVE_ADS_FULL_ON_OFF.equals("true")) {
            if (Constant.SAVE_FULL_TYPE.equals("admob")) {

                Constant.AD_COUNT = Constant.AD_COUNT + 1;
                if (Constant.AD_COUNT == Integer.parseInt(Constant.SAVE_ADS_CLICK)) {
                    Constant.AD_COUNT = 0;
                    GDPRChecker.Request request = GDPRChecker.getRequest();
                    AdRequest.Builder builder = new AdRequest.Builder();
                    if (request == GDPRChecker.Request.NON_PERSONALIZED) {
                        Bundle extras = new Bundle();
                        extras.putString("npa", "1");
                        builder.addNetworkExtrasBundle(AdMobAdapter.class, extras);
                    }
                    InterstitialAd.load(_context, Constant.SAVE_ADS_FULL_ID, builder.build(), new InterstitialAdLoadCallback() {
                        @Override
                        public void onAdLoaded(@NonNull InterstitialAd interstitialAd) {
                            super.onAdLoaded(interstitialAd);
                            interstitialAd.show((Activity) _context);
                            progressDialog.dismiss();
                            interstitialAd.setFullScreenContentCallback(new FullScreenContentCallback() {
                                @Override
                                public void onAdDismissedFullScreenContent() {
                                    super.onAdDismissedFullScreenContent();
                                    onClick.position(position, type, id, title);
                                }

                                @Override
                                public void onAdFailedToShowFullScreenContent(com.google.android.gms.ads.AdError adError) {
                                    super.onAdFailedToShowFullScreenContent(adError);
                                    progressDialog.dismiss();
                                    onClick.position(position, type, id, title);
                                }
                            });
                        }

                        @Override
                        public void onAdFailedToLoad(@NonNull LoadAdError loadAdError) {
                            super.onAdFailedToLoad(loadAdError);
                            progressDialog.dismiss();
                            onClick.position(position, type, id, title);
                        }
                    });
                } else {
                    progressDialog.dismiss();
                    onClick.position(position, type, id, title);
                }

            } else {

                Constant.AD_COUNT = Constant.AD_COUNT + 1;
                if (Constant.AD_COUNT == Integer.parseInt(Constant.SAVE_ADS_CLICK)) {
                    Constant.AD_COUNT = 0;

                    final com.facebook.ads.InterstitialAd interstitialAd = new com.facebook.ads.InterstitialAd(_context, Constant.SAVE_ADS_FULL_ID);
                    InterstitialAdListener interstitialAdListener = new InterstitialAdListener() {
                        @Override
                        public void onInterstitialDisplayed(Ad ad) {
                            // Interstitial ad displayed callback
                            Log.e("fb_ad", "Interstitial ad displayed.");
                        }

                        @Override
                        public void onInterstitialDismissed(Ad ad) {
                            // Interstitial dismissed callback
                            progressDialog.dismiss();
                            onClick.position(position, type, id, title);
                            Log.e("fb_ad", "Interstitial ad dismissed.");
                        }

                        @Override
                        public void onError(Ad ad, AdError adError) {
                            // Ad error callback
                            progressDialog.dismiss();
                            onClick.position(position, type, id, title);
                            Log.e("fb_ad", "Interstitial ad failed to load: " + adError.getErrorMessage());
                        }

                        @Override
                        public void onAdLoaded(Ad ad) {
                            // Interstitial ad is loaded and ready to be displayed
                            Log.d("fb_ad", "Interstitial ad is loaded and ready to be displayed!");
                            progressDialog.dismiss();
                            // Show the ad
                            interstitialAd.show();
                        }

                        @Override
                        public void onAdClicked(Ad ad) {
                            // Ad clicked callback
                            Log.d("fb_ad", "Interstitial ad clicked!");
                        }

                        @Override
                        public void onLoggingImpression(Ad ad) {
                            // Ad impression logged callback
                            Log.d("fb_ad", "Interstitial ad impression logged!");
                        }
                    };

                    com.facebook.ads.InterstitialAd.InterstitialLoadAdConfig loadAdConfig = interstitialAd.buildLoadAdConfig().withAdListener(interstitialAdListener).withCacheFlags(CacheFlag.ALL).build();
                    interstitialAd.loadAd(loadAdConfig);

                } else {
                    progressDialog.dismiss();
                    onClick.position(position, type, id, title);
                }
            }

        } else {
            progressDialog.dismiss();
            onClick.position(position, type, id, title);
        }
    }
    //---------------Interstitial Ad---------------//

}
