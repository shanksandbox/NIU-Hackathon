package com.pride.BnB;

import android.annotation.SuppressLint;
import android.app.Dialog;
import android.app.ProgressDialog;
import android.content.ClipData;
import android.content.Context;
import android.content.Intent;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.net.Uri;
import android.os.AsyncTask;
import android.os.Build;
import android.os.Bundle;
import android.provider.Settings;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.webkit.WebSettings;
import android.webkit.WebView;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.ProgressBar;
import android.widget.ScrollView;
import android.widget.TextView;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;
import androidx.appcompat.widget.Toolbar;
import androidx.core.content.FileProvider;
import androidx.recyclerview.widget.GridLayoutManager;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import com.pride.adapter.GalleryAdapter;
import com.pride.item.ItemCategory;
import com.pride.item.ItemPlaceList;
import com.pride.item.ItemReview;
import com.pride.util.API;
import com.pride.util.Constant;
import com.pride.util.FavClickListener;
import com.pride.util.FavUnFavorite;
import com.pride.util.JsonUtils;
import com.pride.util.RecyclerTouchListener;
import com.pride.youtube.YoutubePlay;
import com.github.ornolfr.ratingview.RatingView;
import com.google.android.material.textfield.TextInputEditText;
import com.google.gson.Gson;
import com.google.gson.JsonObject;
import com.squareup.picasso.Picasso;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.File;
import java.io.FileOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.net.HttpURLConnection;
import java.net.URL;
import java.util.ArrayList;

import io.github.inflationx.viewpump.ViewPumpContextWrapper;
import libs.mjn.prettydialog.PrettyDialog;
import libs.mjn.prettydialog.PrettyDialogCallback;

public class ActivityDetail extends AppCompatActivity {

    Menu menu;
    JsonUtils jsonUtils;
    MyApplication myApplication;
    TextView text_title, text_total_rating, text_avg_rate, text_address, text_email, text_web, text_phone, no_gallery, text_distance;
    WebView webViewDesc;
    ImageView image_place, image_phone, image_video, image_direction, image_share;
    RatingView rating_place, rating_tap;
    LinearLayout layoutAd;
    ScrollView scrollView;
    ProgressBar mProgressBar;
    LinearLayout lyt_not_found;
    String Id;
    ArrayList<ItemPlaceList> mCategoryList;
    ArrayList<ItemCategory> mItemGalleries;
    ItemPlaceList itemPlaceList;
    RecyclerView recycler_gallery;
    GalleryAdapter galleryAdapter;
    ArrayList<ItemReview> mListReview;
    ReviewAdapter reviewAdapter;
    String rateMsg;
    boolean iswhichscreen;
    String stringRateAvg, stringTotalRate, userRate, userRateMsg;
    LinearLayout lay_rate;

    @Override
    protected void attachBaseContext(Context newBase) {
        super.attachBaseContext(ViewPumpContextWrapper.wrap(newBase));
    }

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.details_activity);
        JsonUtils.setStatusBarGradiant(ActivityDetail.this);
        final Toolbar toolbar = findViewById(R.id.toolbar);
        toolbar.setTitle(R.string.app_name);
        setSupportActionBar(toolbar);
        if (getSupportActionBar() != null) {
            getSupportActionBar().setDisplayHomeAsUpEnabled(true);
            getSupportActionBar().setDisplayShowHomeEnabled(true);
        }
        jsonUtils = new JsonUtils(this);
        jsonUtils.forceRTLIfSupported(getWindow());
        mCategoryList = new ArrayList<>();
        mItemGalleries = new ArrayList<>();
        myApplication = MyApplication.getAppInstance();
        mListReview = new ArrayList<>();

        Intent i = getIntent();
        Id = i.getStringExtra("Id");

        text_title = findViewById(R.id.text_place_title);
        text_total_rating = findViewById(R.id.text_place_rate_total);
        text_avg_rate = findViewById(R.id.text_place_rate_Avg);
        text_address = findViewById(R.id.text_address);
        text_email = findViewById(R.id.text_email);
        text_web = findViewById(R.id.text_web);
        text_phone = findViewById(R.id.text_phone);
        image_place = findViewById(R.id.image_place);
        image_phone = findViewById(R.id.image_phone);
        image_video = findViewById(R.id.image_video);
        image_direction = findViewById(R.id.image_dire);
        image_share = findViewById(R.id.image_share);
        webViewDesc = findViewById(R.id.text_description);
        rating_place = findViewById(R.id.ratingView);
        rating_tap = findViewById(R.id.ratingView_tap);
        layoutAd = findViewById(R.id.ad_view);
        scrollView = findViewById(R.id.scrollView1);
        mProgressBar = findViewById(R.id.progressBar);
        lyt_not_found = findViewById(R.id.lyt_not_found);
        no_gallery = findViewById(R.id.no_gallery);
        recycler_gallery = findViewById(R.id.rv_gallery);
        recycler_gallery.setHasFixedSize(true);
        RecyclerView.LayoutManager layoutManager = new LinearLayoutManager(ActivityDetail.this, LinearLayoutManager.HORIZONTAL, false);
        recycler_gallery.setLayoutManager(layoutManager);
        recycler_gallery.setFocusable(false);
        text_distance = findViewById(R.id.text_distance);
        lay_rate = findViewById(R.id.lay_rate);


        iswhichscreen = i.getBooleanExtra("isNotification", false);
        if (Constant.SAVE_ADS_BANNER_ON_OFF.equals("true")) {
            if (Constant.SAVE_BANNER_TYPE.equals("admob")) {
                JsonUtils.ShowBannerAds(ActivityDetail.this, layoutAd);
            } else {
                JsonUtils.ShowBannerAdsFb(layoutAd, ActivityDetail.this);
            }
        }


        if (Constant.USER_LATITUDE == null && Constant.USER_LONGITUDE == null) {
            Toast.makeText(ActivityDetail.this, getString(R.string.location_permission), Toast.LENGTH_SHORT).show();
            JsonObject jsObj = (JsonObject) new Gson().toJsonTree(new API());
            jsObj.addProperty("method_name", "get_single_place");
            jsObj.addProperty("user_id", MyApplication.getAppInstance().getUserId());
            jsObj.addProperty("place_id", Id);
            jsObj.addProperty("user_lat", "0");
            jsObj.addProperty("user_long", "0");
            if (JsonUtils.isNetworkAvailable(ActivityDetail.this)) {
                new getDetail(API.toBase64(jsObj.toString())).execute(Constant.API_URL);
            }
        } else {
            JsonObject jsObj = (JsonObject) new Gson().toJsonTree(new API());
            jsObj.addProperty("method_name", "get_single_place");
            jsObj.addProperty("user_id", MyApplication.getAppInstance().getUserId());
            jsObj.addProperty("place_id", Id);
            jsObj.addProperty("user_lat", Constant.USER_LATITUDE);
            jsObj.addProperty("user_long", Constant.USER_LONGITUDE);
            if (JsonUtils.isNetworkAvailable(ActivityDetail.this)) {
                new getDetail(API.toBase64(jsObj.toString())).execute(Constant.API_URL);
            }
        }

        lay_rate.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                mListReview.clear();
                JsonObject jsObj = (JsonObject) new Gson().toJsonTree(new API());
                jsObj.addProperty("method_name", "get_single_place");
                jsObj.addProperty("user_id", MyApplication.getAppInstance().getUserId());
                jsObj.addProperty("place_id", Id);
                jsObj.addProperty("user_lat", "0");
                jsObj.addProperty("user_long", "0");
                if (JsonUtils.isNetworkAvailable(ActivityDetail.this)) {
                    new getDetailReview(API.toBase64(jsObj.toString())).execute(Constant.API_URL);
                }
            }
        });

    }


    @SuppressLint("StaticFieldLeak")
    private class getDetail extends AsyncTask<String, Void, String> {

        String base64;

        private getDetail(String base64) {
            this.base64 = base64;
        }

        @Override
        protected void onPreExecute() {
            super.onPreExecute();
            showProgress(true);
        }

        @Override
        protected String doInBackground(String... params) {
            return JsonUtils.getJSONString(params[0], base64);

        }

        @Override
        protected void onPostExecute(String result) {
            super.onPostExecute(result);
            showProgress(false);
            if (null == result || result.length() == 0) {
                lyt_not_found.setVisibility(View.VISIBLE);
            } else {

                try {
                    JSONObject mainJson = new JSONObject(result);
                    JSONArray jsonArray = mainJson.getJSONArray(Constant.CATEGORY_ARRAY_NAME);
                    JSONObject objJson;
                    for (int i = 0; i < jsonArray.length(); i++) {
                        objJson = jsonArray.getJSONObject(i);
                        if (objJson.has("status")) {
                            lyt_not_found.setVisibility(View.VISIBLE);
                        } else {
                            ItemPlaceList objItem = new ItemPlaceList();
                            objItem.setPlaceId(objJson.getString(Constant.LISTING_H_ID));
                            objItem.setPlaceCatId(objJson.getString(Constant.LISTING_H_CAT_ID));
                            objItem.setPlaceName(objJson.getString(Constant.LISTING_H_NAME));
                            objItem.setPlaceImage(objJson.getString(Constant.LISTING_H_IMAGE));
                            objItem.setPlaceVideo(objJson.getString(Constant.LISTING_H_VIDEO));
                            objItem.setPlaceDescription(objJson.getString(Constant.LISTING_H_DES));
                            objItem.setPlaceAddress(objJson.getString(Constant.LISTING_H_ADDRESS));
                            objItem.setPlaceEmail(objJson.getString(Constant.LISTING_H_EMAIL));
                            objItem.setPlacePhone(objJson.getString(Constant.LISTING_H_PHONE));
                            objItem.setPlaceWebsite(objJson.getString(Constant.LISTING_H_WEBSITE));
                            objItem.setPlaceLatitude(objJson.getString(Constant.LISTING_H_MAP_LATITUDE));
                            objItem.setPlaceLongitude(objJson.getString(Constant.LISTING_H_MAP_LONGITUDE));
                            objItem.setPlaceRateAvg(objJson.getString(Constant.LISTING_H_RATING_AVG));
                            objItem.setPlaceRateTotal(objJson.getString(Constant.LISTING_H_RATING_TOTAL));
                            objItem.setPlaceDistance(objJson.getString(Constant.LISTING_H_DISTANCE));
                            objItem.setFavourite(objJson.getBoolean(Constant.LISTING_H_FAV));
                            objItem.setPlaceView(objJson.getString("total_views"));
                            mCategoryList.add(objItem);

                            JSONArray jsonArrayGallery = objJson.getJSONArray(Constant.GALLERY_ARRAY_NAME);
                            if (jsonArrayGallery.length() != 0) {
                                for (int j = 0; j < jsonArrayGallery.length(); j++) {
                                    JSONObject objChild = jsonArrayGallery.getJSONObject(j);
                                    ItemCategory itemCategory = new ItemCategory();
                                    itemCategory.setCategoryImageBig(objChild.getString(Constant.LISTING_H_GALLERY));
                                    mItemGalleries.add(itemCategory);
                                }
                            }

                            JSONArray jsonArrayChild = objJson.getJSONArray(Constant.ARRAY_NAME_REVIEW);
                            if (jsonArrayChild.length() > 0 && !jsonArrayChild.get(0).equals("")) {
                                for (int j = 0; j < jsonArrayChild.length(); j++) {
                                    JSONObject objChild = jsonArrayChild.getJSONObject(j);
                                    ItemReview item = new ItemReview();
                                    item.setReviewName(objChild.getString(Constant.REVIEW_NAME));
                                    item.setReviewRate(objChild.getString(Constant.REVIEW_RATE));
                                    item.setReviewMessage(objChild.getString(Constant.REVIEW_MESSAGE));
                                    mListReview.add(item);
                                }
                            }
                        }
                    }

                } catch (JSONException e) {
                    e.printStackTrace();
                }
                setResult();
            }
        }
    }

    @SuppressLint({"SetTextI18n", "SetJavaScriptEnabled"})
    private void setResult() {

        itemPlaceList = mCategoryList.get(0);

        text_title.setText(itemPlaceList.getPlaceName());
        if (itemPlaceList.getPlaceRateTotal().equals("0")) {
            text_total_rating.setVisibility(View.GONE);
        } else {
            text_total_rating.setVisibility(View.VISIBLE);

        }
        text_total_rating.setText(itemPlaceList.getPlaceRateTotal() + " " + getString(R.string.rate_place_title));
        text_address.setText(itemPlaceList.getPlaceAddress());
        text_email.setText(itemPlaceList.getPlaceEmail());
        text_web.setText(itemPlaceList.getPlaceWebsite());
        text_phone.setText(itemPlaceList.getPlacePhone());
        text_distance.setText(itemPlaceList.getPlaceDistance());

        if (Constant.USER_LATITUDE == null && Constant.USER_LONGITUDE == null) {
            text_distance.setVisibility(View.GONE);
        } else {
            text_distance.setVisibility(View.VISIBLE);
        }

        if (itemPlaceList.getPlaceRateAvg().isEmpty()) {
            text_avg_rate.setText("0");
        } else {
            text_avg_rate.setText(itemPlaceList.getPlaceRateAvg());
        }
        if (itemPlaceList.getPlaceRateAvg().isEmpty()) {
            rating_place.setRating(0);
        } else {
            rating_place.setRating(Float.parseFloat(itemPlaceList.getPlaceRateAvg()));
        }

        Picasso.get().load(itemPlaceList.getPlaceImage()).placeholder(R.drawable.place_holder_big).into(image_place);
        WebSettings webSettings = webViewDesc.getSettings();
        webSettings.setJavaScriptEnabled(true);
        boolean isRTL = Boolean.parseBoolean(getResources().getString(R.string.isRTL));
        String direction = isRTL ? "rtl" : "ltr";
        String text = "<html dir=" + direction + "><head>" + "<style type=\"text/css\">@font-face {font-family: MyFont;src: url(\"file:///android_asset/myfonts/Montserrat-Regular.ttf\")}body,* {font-family: MyFont; color:#575757; font-size: 13px;}img{max-width:100%;height:auto; border-radius: 3px;}</style></head></html>";
        webViewDesc.loadDataWithBaseURL("", text + "<div>" + itemPlaceList.getPlaceDescription() + "</div>", "text/html", "utf-8", null);

        image_phone.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                dialNumber();
            }
        });

        image_video.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                if (itemPlaceList.getPlaceVideo().isEmpty()) {
                    Toast.makeText(ActivityDetail.this, getString(R.string.no_video), Toast.LENGTH_SHORT).show();
                } else {
                    Intent i = new Intent(ActivityDetail.this, YoutubePlay.class);
                    i.putExtra("id", JsonUtils.getVideoId(itemPlaceList.getPlaceVideo()));
                    startActivity(i);
                }

            }
        });

        image_direction.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                String geoUri = "http://maps.google.com/maps?q=loc:" + itemPlaceList.getPlaceLatitude() + "," + itemPlaceList.getPlaceLongitude() + " (" + itemPlaceList.getPlaceName() + ")";
                Intent intent = new Intent(Intent.ACTION_VIEW, Uri.parse(geoUri));
                startActivity(intent);
            }
        });

        image_share.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                (new SaveTask(ActivityDetail.this)).execute(itemPlaceList.getPlaceImage());
            }
        });

        rating_tap.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Constant.LATEST_PLACE_IDD = itemPlaceList.getPlaceId();
                if (myApplication.getIsLogin()) {
                    JsonObject jsObj = (JsonObject) new Gson().toJsonTree(new API());
                    jsObj.addProperty("method_name", "get_user_rating");
                    jsObj.addProperty("post_id", itemPlaceList.getPlaceId());
                    jsObj.addProperty("user_id", myApplication.getUserId());
                    if (JsonUtils.isNetworkAvailable(ActivityDetail.this)) {
                        new getRating(API.toBase64(jsObj.toString())).execute(Constant.API_URL);
                    }
                } else {
                    final PrettyDialog dialog = new PrettyDialog(ActivityDetail.this);
                    dialog.setTitle(getString(R.string.dialog_warning))
                            .setTitleColor(R.color.dialog_text)
                            .setMessage(getString(R.string.login_require))
                            .setMessageColor(R.color.dialog_text)
                            .setAnimationEnabled(false)
                            .setIcon(R.drawable.pdlg_icon_close, R.color.dialog_color, new PrettyDialogCallback() {
                                @Override
                                public void onClick() {
                                    dialog.dismiss();
                                }
                            })
                            .addButton(getString(R.string.dialog_ok), R.color.dialog_white_text, R.color.dialog_color, new PrettyDialogCallback() {
                                @Override
                                public void onClick() {
                                    dialog.dismiss();
                                    Intent intent_login = new Intent(ActivityDetail.this, SignInActivity.class);
                                    intent_login.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
                                    intent_login.putExtra("isfromdetail", true);
                                    intent_login.putExtra("isid", Constant.LATEST_PLACE_IDD);
                                    startActivity(intent_login);
                                }
                            })
                            .addButton(getString(R.string.dialog_no), R.color.dialog_white_text, R.color.dialog_color, new PrettyDialogCallback() {
                                @Override
                                public void onClick() {
                                    dialog.dismiss();
                                }
                            });
                    dialog.setCancelable(false);
                    dialog.show();
                }
            }
        });

        text_email.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                openEmail();
            }
        });

        text_phone.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                dialNumber();
            }
        });

        text_web.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                openWebsite();
            }
        });

        galleryAdapter = new GalleryAdapter(ActivityDetail.this, mItemGalleries);
        recycler_gallery.setAdapter(galleryAdapter);

        if (galleryAdapter.getItemCount() == 0) {
            no_gallery.setVisibility(View.VISIBLE);
        } else {
            no_gallery.setVisibility(View.GONE);
        }

        recycler_gallery.addOnItemTouchListener(new RecyclerTouchListener(ActivityDetail.this, recycler_gallery, new RecyclerTouchListener.ClickListener() {
            @Override
            public void onClick(View view, final int position) {

                Constant.ConsImage = mItemGalleries;
                Intent intent_gallery = new Intent(ActivityDetail.this, ImageActivity.class);
                intent_gallery.putExtra("POSITION_ID", position);
                startActivity(intent_gallery);
            }

            @Override
            public void onLongClick(View view, int position) {

            }
        }));

        if (menu != null) {
            if (itemPlaceList.isFavourite()) {
                menu.findItem(R.id.menu_bookmark).setIcon(R.drawable.fav_hover);
            } else {
                menu.findItem(R.id.menu_bookmark).setIcon(R.drawable.fav_unfav);
            }
        }
    }

    private void showProgress(boolean show) {
        if (show) {
            mProgressBar.setVisibility(View.VISIBLE);
            scrollView.setVisibility(View.GONE);
            lyt_not_found.setVisibility(View.GONE);
        } else {
            mProgressBar.setVisibility(View.GONE);
            scrollView.setVisibility(View.VISIBLE);
        }
    }

    @SuppressLint("StaticFieldLeak")
    private class getRating extends AsyncTask<String, Void, String> {

        ProgressDialog pDialog;
        String base64;

        private getRating(String base64) {
            this.base64 = base64;
        }

        @Override
        protected void onPreExecute() {
            super.onPreExecute();

            pDialog = new ProgressDialog(ActivityDetail.this);
            pDialog.setMessage(getString(R.string.loading));
            pDialog.setCancelable(false);
            pDialog.show();
        }

        @Override
        protected String doInBackground(String... params) {
            return JsonUtils.getJSONString(params[0], base64);
        }

        @Override
        protected void onPostExecute(String result) {
            super.onPostExecute(result);

            if (null != pDialog && pDialog.isShowing()) {
                pDialog.dismiss();
            }

            if (null == result || result.length() == 0) {
                showToast(getString(R.string.no_data_found));

            } else {

                try {
                    JSONObject mainJson = new JSONObject(result);
                    JSONArray jsonArray = mainJson.getJSONArray(Constant.CATEGORY_ARRAY_NAME);
                    JSONObject objJson;
                    for (int i = 0; i < jsonArray.length(); i++) {
                        objJson = jsonArray.getJSONObject(i);
                        userRate = objJson.getString("user_rate");
                        userRateMsg = objJson.getString("user_msg");
                    }

                } catch (JSONException e) {
                    e.printStackTrace();
                }
                setgetRate();
            }

        }

        private void setgetRate() {
            showRate();
        }
    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        getMenuInflater().inflate(R.menu.menu_detail, menu);
        this.menu = menu;

        return true;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem menuItem) {
        switch (menuItem.getItemId()) {
            case android.R.id.home:
                if (!iswhichscreen) {
                    super.onBackPressed();
                } else {
                    Intent intent = new Intent(ActivityDetail.this, MainActivity.class);
                    intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
                    startActivity(intent);
                    finish();
                }
                break;
            case R.id.menu_bookmark:
                if (MyApplication.getAppInstance().getIsLogin()) {
                    if (JsonUtils.isNetworkAvailable(ActivityDetail.this)) {
                        FavClickListener saveClickListener = new FavClickListener() {
                            @Override
                            public void onItemClick(boolean isSave, String message) {
                                if (isSave) {
                                    menu.getItem(0).setIcon(R.drawable.fav_hover);
                                } else {
                                    menu.getItem(0).setIcon(R.drawable.fav_unfav);
                                }
                            }
                        };
                        new FavUnFavorite(ActivityDetail.this).userFav(itemPlaceList.getPlaceId(), saveClickListener);
                    } else {
                        Toast.makeText(ActivityDetail.this, getString(R.string.network_msg), Toast.LENGTH_SHORT).show();
                    }
                } else {
                    Toast.makeText(ActivityDetail.this, getString(R.string.need_login), Toast.LENGTH_SHORT).show();
                    Intent intentLogin = new Intent(ActivityDetail.this, SignInActivity.class);
                    intentLogin.putExtra("isfromdetail", true);
                    startActivity(intentLogin);
                }
                break;
            default:
                return super.onOptionsItemSelected(menuItem);
        }
        return true;
    }

    private void openWebsite() {
        startActivity(new Intent(
                Intent.ACTION_VIEW,
                Uri.parse(addHttp(itemPlaceList.getPlaceWebsite()))));
    }

    private void openEmail() {
        Intent emailIntent = new Intent(Intent.ACTION_SENDTO, Uri.fromParts("mailto", itemPlaceList.getPlaceEmail(), null));
        emailIntent.putExtra(Intent.EXTRA_SUBJECT, itemPlaceList.getPlaceName());
        startActivity(Intent.createChooser(emailIntent, "Send suggestion..."));
    }

    protected String addHttp(String string1) {
        // TODO Auto-generated method stub
        if (string1.startsWith("http://"))
            return String.valueOf(string1);
        else
            return "http://" + String.valueOf(string1);
    }

    private void dialNumber() {
        Intent intent = new Intent(Intent.ACTION_DIAL, Uri.fromParts("tel", itemPlaceList.getPlacePhone(), null));
        startActivity(intent);
    }

    private void showAllReview() {
        final Dialog mDialog = new Dialog(ActivityDetail.this, R.style.Theme_AppCompat_Translucent);
        mDialog.setContentView(R.layout.review_all_dialog);
        jsonUtils = new JsonUtils(this);
        jsonUtils.forceRTLIfSupported(getWindow());
        RecyclerView recyclerView = mDialog.findViewById(R.id.vertical_courses_list);
        recyclerView.setHasFixedSize(true);
        recyclerView.setLayoutManager(new GridLayoutManager(ActivityDetail.this, 1));
        recyclerView.setFocusable(false);
        recyclerView.setNestedScrollingEnabled(false);
        TextView textView_no = mDialog.findViewById(R.id.no_fav);
        TextView text_dialog_review = mDialog.findViewById(R.id.text_dialog_review);
        ImageView image_close_dialog = mDialog.findViewById(R.id.image_close_dialog);

        text_dialog_review.setText(itemPlaceList.getPlaceRateAvg());
        image_close_dialog.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                mDialog.dismiss();
            }
        });

        reviewAdapter = new ReviewAdapter(ActivityDetail.this, mListReview);
        recyclerView.setAdapter(reviewAdapter);

        if (reviewAdapter.getItemCount() == 0) {
            textView_no.setVisibility(View.VISIBLE);
        } else {
            textView_no.setVisibility(View.GONE);
        }
        mDialog.show();
    }

    @SuppressLint("HardwareIds")
    private void showRate() {
        final String deviceId;
        final Dialog mDialog = new Dialog(ActivityDetail.this, R.style.Theme_AppCompat_Translucent);
        mDialog.setContentView(R.layout.rate_dialog);
        jsonUtils = new JsonUtils(this);
        jsonUtils.forceRTLIfSupported(getWindow());
        deviceId = Settings.Secure.getString(this.getContentResolver(), Settings.Secure.ANDROID_ID);
        final RatingView ratingView = mDialog.findViewById(R.id.ratingView);
        ImageView image_rate_close = mDialog.findViewById(R.id.image_close);
        final TextInputEditText editTextReview = mDialog.findViewById(R.id.editText_name_edit);
        ratingView.setRating(Float.parseFloat(userRate));
        editTextReview.setText(userRateMsg);
        Button button = mDialog.findViewById(R.id.btn_submit);

        image_rate_close.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                mDialog.dismiss();
            }
        });

        button.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if (editTextReview.getText().length() == 0) {
                    Toast.makeText(ActivityDetail.this, getString(R.string.require_review), Toast.LENGTH_SHORT).show();
                } else {
                    JsonObject jsObj = (JsonObject) new Gson().toJsonTree(new API());
                    jsObj.addProperty("method_name", "place_ratings");
                    jsObj.addProperty("ip", deviceId);
                    jsObj.addProperty("post_id", itemPlaceList.getPlaceId());
                    jsObj.addProperty("user_id", myApplication.getUserId());
                    jsObj.addProperty("rate", ratingView.getRating());
                    jsObj.addProperty("message", editTextReview.getText().toString());
                    if (JsonUtils.isNetworkAvailable(ActivityDetail.this)) {
                        new SentRating(API.toBase64(jsObj.toString())).execute(Constant.API_URL);
                    }

                    mDialog.dismiss();
                }
            }
        });
        mDialog.show();
    }

    @SuppressLint("StaticFieldLeak")
    private class SentRating extends AsyncTask<String, Void, String> {

        ProgressDialog pDialog;
        String Rate;
        String base64;

        private SentRating(String base64) {
            this.base64 = base64;
        }

        @Override
        protected void onPreExecute() {
            super.onPreExecute();

            pDialog = new ProgressDialog(ActivityDetail.this);
            pDialog.setMessage(getString(R.string.loading));
            pDialog.setCancelable(false);
            pDialog.show();
        }

        @Override
        protected String doInBackground(String... params) {
            return JsonUtils.getJSONString(params[0], base64);
        }

        @Override
        protected void onPostExecute(String result) {
            super.onPostExecute(result);

            if (null != pDialog && pDialog.isShowing()) {
                pDialog.dismiss();
            }

            if (null == result || result.length() == 0) {
                showToast(getString(R.string.no_data_found));

            } else {

                try {
                    JSONObject mainJson = new JSONObject(result);
                    JSONArray jsonArray = mainJson.getJSONArray(Constant.CATEGORY_ARRAY_NAME);
                    JSONObject objJson;
                    for (int i = 0; i < jsonArray.length(); i++) {
                        objJson = jsonArray.getJSONObject(i);
                        rateMsg = objJson.getString("msg");
                        if (objJson.has("rate_avg")) {
                            Rate = objJson.getString("rate_avg");
                            stringRateAvg = objJson.getString("rate_avg");
                            stringTotalRate = objJson.getString("total_users");
                        } else {
                            Rate = "";
                        }
                    }

                } catch (JSONException e) {
                    e.printStackTrace();
                }
                setRate();
            }
        }

        @SuppressLint("SetTextI18n")
        private void setRate() {
            showToast(rateMsg);
            text_total_rating.setText(stringRateAvg);
            rating_place.setRating(Float.parseFloat(stringRateAvg));
            text_avg_rate.setText(stringRateAvg);
            if (stringRateAvg.equals("0")) {
                text_total_rating.setVisibility(View.GONE);
            } else {
                text_total_rating.setVisibility(View.VISIBLE);
                text_total_rating.setText(stringTotalRate + " " + getString(R.string.rate_place_title));
            }
        }
    }

    public void showToast(String msg) {
        Toast.makeText(ActivityDetail.this, msg, Toast.LENGTH_SHORT).show();
    }

    @SuppressLint("StaticFieldLeak")
    public class SaveTask extends AsyncTask<String, String, String> {
        Context context;
        URL myFileUrl;
        Bitmap bmImg = null;
        File file;

        private SaveTask(Context context) {
            this.context = context;
        }

        @Override
        protected void onPreExecute() {
            // TODO Auto-generated method stub

            super.onPreExecute();
        }

        @Override
        protected String doInBackground(String... args) {
            // TODO Auto-generated method stub

            try {

                myFileUrl = new URL(args[0]);

                HttpURLConnection conn = (HttpURLConnection) myFileUrl.openConnection();
                conn.setDoInput(true);
                conn.connect();
                InputStream is = conn.getInputStream();
                bmImg = BitmapFactory.decodeStream(is);
            } catch (IOException e) {
                e.printStackTrace();
            }
            try {

                String path = myFileUrl.getPath();
                String idStr = path.substring(path.lastIndexOf('/') + 1);
                String filepath = getExternalCacheDir().getAbsolutePath();
                File dir = new File(filepath.toString());
                dir.mkdirs();
                String fileName = idStr;
                file = new File(dir, fileName);
                FileOutputStream fos = new FileOutputStream(file);
                bmImg.compress(Bitmap.CompressFormat.JPEG, 75, fos);
                fos.flush();
                fos.close();

            } catch (Exception e) {
                e.printStackTrace();
            }
            return null;
        }


        @Override
        protected void onPostExecute(String args) {
            // TODO Auto-generated method stub

            Intent share = new Intent(Intent.ACTION_SEND);
            share.setType("image/jpeg");
            share.putExtra(Intent.EXTRA_TEXT, getString(R.string.share_place_title) + itemPlaceList.getPlaceName() + "\n" + getString(R.string.share_place_address) + itemPlaceList.getPlaceAddress() + "\n" + getString(R.string.share_place_phone) + itemPlaceList.getPlacePhone() + "\n" + getString(R.string.share_message) + "\n" + "https://play.google.com/store/apps/details?id=" + getPackageName());
            Uri U = FileProvider.getUriForFile(ActivityDetail.this, BuildConfig.APPLICATION_ID + ".fileprovider", file);
            share.putExtra(Intent.EXTRA_STREAM, U);
            if (Build.VERSION.SDK_INT <= Build.VERSION_CODES.LOLLIPOP) {
                share.setClipData(ClipData.newRawUri("", U));
                share.addFlags(Intent.FLAG_GRANT_WRITE_URI_PERMISSION | Intent.FLAG_GRANT_READ_URI_PERMISSION);
            }
            startActivity(Intent.createChooser(share, "Share Image"));
        }
    }

    @SuppressLint("StaticFieldLeak")
    private class getDetailReview extends AsyncTask<String, Void, String> {

        String base64;

        private getDetailReview(String base64) {
            this.base64 = base64;
        }

        @Override
        protected void onPreExecute() {
            super.onPreExecute();
            //showProgress(true);
        }

        @Override
        protected String doInBackground(String... params) {
            return JsonUtils.getJSONString(params[0], base64);

        }

        @Override
        protected void onPostExecute(String result) {
            super.onPostExecute(result);
            // showProgress(false);
            if (null == result || result.length() == 0) {
                lyt_not_found.setVisibility(View.VISIBLE);
            } else {

                try {
                    JSONObject mainJson = new JSONObject(result);
                    JSONArray jsonArray = mainJson.getJSONArray(Constant.CATEGORY_ARRAY_NAME);
                    JSONObject objJson;
                    for (int i = 0; i < jsonArray.length(); i++) {
                        objJson = jsonArray.getJSONObject(i);
                        if (objJson.has("status")) {
                            lyt_not_found.setVisibility(View.VISIBLE);
                        } else {
                            ItemPlaceList objItem = new ItemPlaceList();
                            objItem.setPlaceId(objJson.getString(Constant.LISTING_H_ID));
                            objItem.setPlaceCatId(objJson.getString(Constant.LISTING_H_CAT_ID));
                            objItem.setPlaceName(objJson.getString(Constant.LISTING_H_NAME));
                            objItem.setPlaceImage(objJson.getString(Constant.LISTING_H_IMAGE));
                            objItem.setPlaceVideo(objJson.getString(Constant.LISTING_H_VIDEO));
                            objItem.setPlaceDescription(objJson.getString(Constant.LISTING_H_DES));
                            objItem.setPlaceAddress(objJson.getString(Constant.LISTING_H_ADDRESS));
                            objItem.setPlaceEmail(objJson.getString(Constant.LISTING_H_EMAIL));
                            objItem.setPlacePhone(objJson.getString(Constant.LISTING_H_PHONE));
                            objItem.setPlaceWebsite(objJson.getString(Constant.LISTING_H_WEBSITE));
                            objItem.setPlaceLatitude(objJson.getString(Constant.LISTING_H_MAP_LATITUDE));
                            objItem.setPlaceLongitude(objJson.getString(Constant.LISTING_H_MAP_LONGITUDE));
                            objItem.setPlaceRateAvg(objJson.getString(Constant.LISTING_H_RATING_AVG));
                            objItem.setPlaceRateTotal(objJson.getString(Constant.LISTING_H_RATING_TOTAL));
                            objItem.setPlaceDistance(objJson.getString(Constant.LISTING_H_DISTANCE));
                            objItem.setFavourite(objJson.getBoolean(Constant.LISTING_H_FAV));
                            mCategoryList.add(objItem);

                            JSONArray jsonArrayGallery = objJson.getJSONArray(Constant.GALLERY_ARRAY_NAME);
                            if (jsonArrayGallery.length() != 0) {
                                for (int j = 0; j < jsonArrayGallery.length(); j++) {
                                    JSONObject objChild = jsonArrayGallery.getJSONObject(j);
                                    ItemCategory itemCategory = new ItemCategory();
                                    itemCategory.setCategoryImageBig(objChild.getString(Constant.LISTING_H_GALLERY));
                                    mItemGalleries.add(itemCategory);
                                }
                            }

                            JSONArray jsonArrayChild = objJson.getJSONArray(Constant.ARRAY_NAME_REVIEW);
                            if (jsonArrayChild.length() > 0 && !jsonArrayChild.get(0).equals("")) {
                                for (int j = 0; j < jsonArrayChild.length(); j++) {
                                    JSONObject objChild = jsonArrayChild.getJSONObject(j);
                                    ItemReview item = new ItemReview();
                                    item.setReviewName(objChild.getString(Constant.REVIEW_NAME));
                                    item.setReviewRate(objChild.getString(Constant.REVIEW_RATE));
                                    item.setReviewMessage(objChild.getString(Constant.REVIEW_MESSAGE));
                                    mListReview.add(item);
                                }
                            }
                        }
                    }

                } catch (JSONException e) {
                    e.printStackTrace();
                }
                setResultReview();
            }
        }
    }

    private void setResultReview() {
        showAllReview();
    }
}
