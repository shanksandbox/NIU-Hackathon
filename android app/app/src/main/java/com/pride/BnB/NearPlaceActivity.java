package com.pride.BnB;

import android.annotation.SuppressLint;
import android.content.Context;
import android.content.Intent;
import android.os.AsyncTask;
import android.os.Bundle;
import android.view.MenuItem;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.LinearLayout;
import android.widget.ProgressBar;
import android.widget.Spinner;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.Nullable;
import androidx.appcompat.app.AppCompatActivity;
import androidx.appcompat.widget.Toolbar;
import androidx.cardview.widget.CardView;

import com.pride.item.ItemCategory;
import com.pride.util.API;
import com.pride.util.Constant;
import com.pride.util.JsonUtils;
import com.pride.util.NothingSelectedSpinnerAdapter;
import com.google.android.material.button.MaterialButton;
import com.google.gson.Gson;
import com.google.gson.JsonObject;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;

import io.github.inflationx.viewpump.ViewPumpContextWrapper;

public class NearPlaceActivity extends AppCompatActivity {


    LinearLayout adLayout;
    Spinner spinnerCategory, spinnerDistance;
    CardView card_view;
    ProgressBar mProgressBar;
    private LinearLayout lyt_not_found;
    ArrayList<ItemCategory> itemCategories;
    ArrayList<String> mCategoryName;
    String srt_distance[];
    MaterialButton button_search;

    @Override
    protected void attachBaseContext(Context newBase) {
        super.attachBaseContext(ViewPumpContextWrapper.wrap(newBase));
    }

    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_near_place);
        Toolbar toolbar = findViewById(R.id.toolbar);
        JsonUtils.setStatusBarGradiant(NearPlaceActivity.this);
        toolbar.setTitle(R.string.menu_near);
        setSupportActionBar(toolbar);
        if (getSupportActionBar() != null) {
            getSupportActionBar().setDisplayHomeAsUpEnabled(true);
            getSupportActionBar().setDisplayShowHomeEnabled(true);
        }
        itemCategories = new ArrayList<>();
        mCategoryName = new ArrayList<>();
        adLayout = findViewById(R.id.adLayout);
        if (Constant.SAVE_BANNER_TYPE.equals("admob")) {
            JsonUtils.ShowBannerAds(NearPlaceActivity.this, adLayout);
        } else {
            JsonUtils.ShowBannerAdsFb(adLayout, NearPlaceActivity.this);
        }
        srt_distance = getResources().getStringArray(R.array.dis_array);

        spinnerCategory = findViewById(R.id.spCategory);
        card_view = findViewById(R.id.card_view);
        mProgressBar = findViewById(R.id.progressBar);
        lyt_not_found = findViewById(R.id.lyt_not_found);
        spinnerDistance = findViewById(R.id.spDistance);
        button_search = findViewById(R.id.button_login_activity);

        JsonObject jsObj = (JsonObject) new Gson().toJsonTree(new API());
        jsObj.addProperty("method_name", "get_category");
        if (JsonUtils.isNetworkAvailable(NearPlaceActivity.this)) {
            new getNearData(API.toBase64(jsObj.toString())).execute(Constant.API_URL);
        }
    }


    @SuppressLint("StaticFieldLeak")
    private class getNearData extends AsyncTask<String, Void, String> {

        String base64;

        private getNearData(String base64) {
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

                            ItemCategory objItem = new ItemCategory();
                            objItem.setCategoryId(objJson.getString(Constant.CATEGORY_CID));
                            objItem.setCategoryName(objJson.getString(Constant.CATEGORY_NAME));
                            objItem.setCategoryImageBig(objJson.getString(Constant.CATEGORY_IMAGE));
                            mCategoryName.add(objJson.getString(Constant.CATEGORY_NAME));
                            itemCategories.add(objItem);
                        }
                    }
                } catch (JSONException e) {
                    e.printStackTrace();
                }
                setResult2();
            }
        }
    }

    private void setResult2() {

        ArrayAdapter<String> typeAdapter2 = new ArrayAdapter<>(NearPlaceActivity.this, R.layout.spinner_item_home, mCategoryName);
        typeAdapter2.setDropDownViewResource(R.layout.spinner_item_home);
        spinnerCategory.setAdapter(
                new NothingSelectedSpinnerAdapter(typeAdapter2,
                        R.layout.contact_spinner_row_nothing_selected_home, NearPlaceActivity.this));
        spinnerCategory.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {

            @Override
            public void onItemSelected(AdapterView<?> parent, View view,
                                       int position, long id) {
                // TODO Auto-generated method stub
                if (position == 0) {
                    ((TextView) parent.getChildAt(0)).setTextColor(getResources().getColor(R.color.green));
                    ((TextView) parent.getChildAt(0)).setTextSize(13);

                } else {
                    ((TextView) parent.getChildAt(0)).setTextColor(getResources().getColor(R.color.green));
                    ((TextView) parent.getChildAt(0)).setTextSize(13);

                }
            }

            @Override
            public void onNothingSelected(AdapterView<?> parent) {
                // TODO Auto-generated method stub

            }
        });

        ArrayAdapter<String> typeAdapter = new ArrayAdapter<>(NearPlaceActivity.this, android.R.layout.simple_list_item_1, srt_distance);
        typeAdapter.setDropDownViewResource(R.layout.spinner_item_home);
        spinnerDistance.setAdapter(typeAdapter);


        spinnerDistance.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {

            @Override
            public void onItemSelected(AdapterView<?> parent, View view,
                                       int position, long id) {
                // TODO Auto-generated method stub
                if (position == 0) {
                    ((TextView) parent.getChildAt(0)).setTextColor(getResources().getColor(R.color.green));
                    ((TextView) parent.getChildAt(0)).setTextSize(13);

                } else {
                    ((TextView) parent.getChildAt(0)).setTextColor(getResources().getColor(R.color.green));
                    ((TextView) parent.getChildAt(0)).setTextSize(13);

                }
            }

            @Override
            public void onNothingSelected(AdapterView<?> parent) {
                // TODO Auto-generated method stub

            }
        });


        button_search.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if (spinnerCategory.getSelectedItemPosition() == 0) {
                    Toast.makeText(NearPlaceActivity.this, getString(R.string.select_category), Toast.LENGTH_SHORT).show();
                } else {
                    Intent intent = new Intent(NearPlaceActivity.this, NearSearchActivity.class);
                    intent.putExtra("DisId", String.valueOf(spinnerDistance.getSelectedItem()));
                    intent.putExtra("CatId", itemCategories.get(spinnerCategory.getSelectedItemPosition() - 1).getCategoryId());
                    startActivity(intent);
                }
            }
        });
    }

    private void showProgress(boolean show) {
        if (show) {
            mProgressBar.setVisibility(View.VISIBLE);
            card_view.setVisibility(View.GONE);
            lyt_not_found.setVisibility(View.GONE);
        } else {
            mProgressBar.setVisibility(View.GONE);
            card_view.setVisibility(View.VISIBLE);
        }
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem menuItem) {
        switch (menuItem.getItemId()) {
            case android.R.id.home:
                onBackPressed();
                break;

            default:
                return super.onOptionsItemSelected(menuItem);
        }
        return true;
    }
}
