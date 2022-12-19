package com.pride.fragment;

import android.annotation.SuppressLint;
import android.content.Intent;
import android.net.Uri;
import android.os.AsyncTask;
import android.os.Bundle;
import android.util.Log;
import android.view.KeyEvent;
import android.view.LayoutInflater;
import android.view.Menu;
import android.view.MenuInflater;
import android.view.MenuItem;
import android.view.View;
import android.view.ViewGroup;
import android.view.inputmethod.EditorInfo;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.ProgressBar;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.Nullable;
import androidx.appcompat.widget.SearchView;
import androidx.core.widget.NestedScrollView;
import androidx.fragment.app.Fragment;
import androidx.fragment.app.FragmentManager;
import androidx.fragment.app.FragmentTransaction;
import androidx.recyclerview.widget.GridLayoutManager;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;
import androidx.viewpager.widget.PagerAdapter;
import androidx.viewpager.widget.ViewPager;

import com.pride.adapter.HomeCategoryAdapter;
import com.pride.adapter.HomeFeatureAdapter;
import com.pride.adapter.HomePopularAdapter;
import com.pride.item.ItemCategory;
import com.pride.item.ItemPlaceList;
import com.pride.item.ItemSlider;
import com.pride.BnB.FeaturedActivity;
import com.pride.BnB.MainActivity;
import com.pride.BnB.MyApplication;
import com.pride.BnB.PopularActivity;
import com.pride.BnB.R;
import com.pride.BnB.SearchActivity;
import com.pride.util.API;
import com.pride.util.Constant;
import com.pride.util.EnchantedViewPager;
import com.pride.util.JsonUtils;
import com.pride.util.OnClick;
import com.pride.util.PopUpAds;
import com.google.gson.Gson;
import com.google.gson.JsonObject;
import com.squareup.picasso.Picasso;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;


public class HomeFragment extends Fragment {

    NestedScrollView mScrollView;
    ProgressBar mProgressBar;
    ArrayList<ItemCategory> mCatList;
    ArrayList<ItemPlaceList> mFeatureList, mPopularList;
    RecyclerView mCatView, mFeatureView, mPopularView;
    HomeCategoryAdapter categoryAdapter;
    HomeFeatureAdapter homeFeatureAdapter;
    HomePopularAdapter homePopularAdapter;
    EditText edt_search;
    MyApplication MyApp;
    private LinearLayout lyt_not_found;
    JsonUtils jsonUtils;
    OnClick onClick;
    LinearLayout btn_most;
    LinearLayout lay_more_cat;
    ArrayList<ItemSlider> mSlider;
    EnchantedViewPager mViewPager;
    CustomViewPagerAdapter mAdapter;
    LinearLayout layFeatured;
    int currentCount = 0;

    @Nullable
    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        View rootView = inflater.inflate(R.layout.fragment_home, container, false);

        MyApp = MyApplication.getAppInstance();
        mCatList = new ArrayList<>();
        mFeatureList = new ArrayList<>();
        mPopularList = new ArrayList<>();
        mSlider = new ArrayList<>();

        mScrollView = rootView.findViewById(R.id.scrollView);
        mProgressBar = rootView.findViewById(R.id.progressBar);
        mCatView = rootView.findViewById(R.id.rv_latest_cat);
        mFeatureView = rootView.findViewById(R.id.rv_slider);
        mPopularView = rootView.findViewById(R.id.rv_latest_popular);
        lyt_not_found = rootView.findViewById(R.id.lyt_not_found);
        edt_search = rootView.findViewById(R.id.edt_search);
        mViewPager = rootView.findViewById(R.id.viewPager);
        mViewPager.useScale();
        mViewPager.removeAlpha();
        layFeatured=rootView.findViewById(R.id.lay_more_fea);

        mCatView.setHasFixedSize(true);
        RecyclerView.LayoutManager layoutManager_sl = new LinearLayoutManager(getContext(), LinearLayoutManager.HORIZONTAL, false);
        mCatView.setLayoutManager(layoutManager_sl);
        mCatView.setFocusable(false);
        mCatView.setNestedScrollingEnabled(false);

        mFeatureView.setHasFixedSize(true);
        RecyclerView.LayoutManager layoutManager_fea = new LinearLayoutManager(getContext(), LinearLayoutManager.HORIZONTAL, false);
        mFeatureView.setLayoutManager(layoutManager_fea);
        mFeatureView.setFocusable(false);
        mFeatureView.setNestedScrollingEnabled(false);

        mPopularView.setHasFixedSize(true);
        GridLayoutManager layoutManager = new GridLayoutManager(getContext(), 2);
        mPopularView.setLayoutManager(layoutManager);
        mPopularView.setFocusable(false);
        mPopularView.setNestedScrollingEnabled(false);

        onClick = new OnClick() {
            @Override
            public void position(int position, String type, String id, String title) {
                Bundle bundle = new Bundle();
                bundle.putString("name", title);
                bundle.putString("Id", id);

                FragmentManager fm = getFragmentManager();
                CategoryListFragment subCategoryFragment = new CategoryListFragment();
                subCategoryFragment.setArguments(bundle);
                assert fm != null;
                FragmentTransaction ft = fm.beginTransaction();
                ft.hide(HomeFragment.this);
                ft.add(R.id.fragment1, subCategoryFragment, title);
                ft.addToBackStack(title);
                ft.commit();
                ((MainActivity) requireActivity()).setToolbarTitle(title);

            }
        };
        jsonUtils = new JsonUtils(requireActivity(), onClick);

        edt_search.setOnEditorActionListener(new TextView.OnEditorActionListener() {
            @Override
            public boolean onEditorAction(TextView v, int actionId, KeyEvent event) {
                if (actionId == EditorInfo.IME_ACTION_SEARCH) {
                    //do something
                    String st_search = edt_search.getText().toString();
                    Intent intent = new Intent(getActivity(), SearchActivity.class);
                    intent.putExtra("search", st_search);
                    startActivity(intent);
                    edt_search.getText().clear();
                }
                return false;
            }
        });

        lay_more_cat = rootView.findViewById(R.id.lay_more_cat);
        lay_more_cat.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                ((MainActivity) requireActivity()).highLightNavigation(1);
                String categoryName = getString(R.string.home_category);
                FragmentManager fm = getFragmentManager();
                CategoryFragment f1 = new CategoryFragment();
                assert fm != null;
                FragmentTransaction ft = fm.beginTransaction();
                ft.replace(R.id.fragment1, f1, categoryName);
                ft.commit();
                ((MainActivity) requireActivity()).setToolbarTitle(categoryName);
            }
        });

        btn_most = rootView.findViewById(R.id.lay_more_pop);
        btn_most.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent intent_pop = new Intent(requireActivity(), PopularActivity.class);
                startActivity(intent_pop);
            }
        });

        layFeatured.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent intent_fea=new Intent(requireActivity(), FeaturedActivity.class);
                startActivity(intent_fea);
            }
        });

        JsonObject jsObj = (JsonObject) new Gson().toJsonTree(new API());
        jsObj.addProperty("method_name", "get_home_banner");
        if (JsonUtils.isNetworkAvailable(requireActivity())) {
            new getSlider(API.toBase64(jsObj.toString())).execute(Constant.API_URL);
        }

        setHasOptionsMenu(true);
        return rootView;
    }

    @SuppressLint("StaticFieldLeak")
    private class getSlider extends AsyncTask<String, Void, String> {

        String base64;

        private getSlider(String base64) {
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

            if (null == result || result.length() == 0) {
                showToast(getString(R.string.no_data_found));
            } else {

                try {
                    JSONObject mainJson = new JSONObject(result);
                    JSONArray jsonArray = mainJson.getJSONArray(Constant.CATEGORY_ARRAY_NAME);
                    JSONObject objJson;
                    for (int i = 0; i < jsonArray.length(); i++) {
                        objJson = jsonArray.getJSONObject(i);

                        ItemSlider objItem = new ItemSlider();
                        objItem.setSliderImage(objJson.getString("external_image"));
                        objItem.setSliderLink(objJson.getString("external_link"));
                        objItem.setSliderRId(objJson.getString("place_id"));
                        objItem.setSliderType(objJson.getString("place_type"));
                        mSlider.add(objItem);

                    }
                } catch (JSONException e) {
                    e.printStackTrace();
                }
                setResultSlider();
            }
        }
    }

    private void setResultSlider() {
        if (mSlider.size() == 0) {
            mViewPager.setVisibility(View.GONE);
        } else {
            mViewPager.setVisibility(View.VISIBLE);
            mAdapter = new CustomViewPagerAdapter();
            mViewPager.setAdapter(mAdapter);
            autoPlay(mViewPager);
        }

        JsonObject jsObj = (JsonObject) new Gson().toJsonTree(new API());
        jsObj.addProperty("method_name", "get_home");
        jsObj.addProperty("user_id", MyApp.getUserId());
        if (JsonUtils.isNetworkAvailable(requireActivity())) {
            new getHomeData(API.toBase64(jsObj.toString())).execute(Constant.API_URL);
        }

    }

    private void autoPlay(final ViewPager viewPager) {

        viewPager.postDelayed(new Runnable() {
            @Override
            public void run() {
                try {
                    if (mAdapter != null && viewPager.getAdapter().getCount() > 0) {
                        int position = currentCount % mAdapter.getCount();
                        currentCount++;
                        viewPager.setCurrentItem(position);
                        autoPlay(viewPager);
                    }
                } catch (Exception e) {
                    Log.e("TAG", "auto scroll pager error.", e);
                }
            }
        }, 4000);
    }

    private class CustomViewPagerAdapter extends PagerAdapter {
        private LayoutInflater inflater;

        private CustomViewPagerAdapter() {
            // TODO Auto-generated constructor stub
            inflater = requireActivity().getLayoutInflater();
        }

        @Override
        public int getCount() {
            return mSlider.size();
        }

        @Override
        public boolean isViewFromObject(View view, Object object) {
            return view.equals(object);
        }

        @Override
        public Object instantiateItem(ViewGroup container, final int position) {
            View imageLayout = inflater.inflate(R.layout.row_home_slider_item, container, false);
            assert imageLayout != null;
            ImageView image = imageLayout.findViewById(R.id.image);
            LinearLayout lytParent = imageLayout.findViewById(R.id.rootLayout);

            Picasso.get().load(mSlider.get(position).getSliderImage()).placeholder(R.drawable.place_holder_big).into(image);
            imageLayout.setTag(EnchantedViewPager.ENCHANTED_VIEWPAGER_POSITION + position);
            lytParent.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                    if (mSlider.get(position).getSliderType().equals("Place")) {
                        PopUpAds.ShowInterstitialAds(requireActivity(), mSlider.get(position).getSliderRId());
                    } else {
                        if (!mSlider.get(position).getSliderLink().isEmpty()) {
                            startActivity(new Intent(Intent.ACTION_VIEW, Uri.parse(mSlider.get(position).getSliderLink())));
                        }
                    }
                }
            });
            container.addView(imageLayout, 0);
            return imageLayout;
        }

        @Override
        public void destroyItem(ViewGroup container, int position, Object object) {
            (container).removeView((View) object);
        }
    }

    public void showToast(String msg) {
        Toast.makeText(getActivity(), msg, Toast.LENGTH_LONG).show();
    }

    @SuppressLint("StaticFieldLeak")
    private class getHomeData extends AsyncTask<String, Void, String> {

        String base64;

        private getHomeData(String base64) {
            this.base64 = base64;
        }


        @Override
        protected void onPreExecute() {
            super.onPreExecute();
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
                    JSONObject jsonArray = mainJson.getJSONObject(Constant.CATEGORY_ARRAY_NAME);
                    JSONArray json = jsonArray.getJSONArray("cat_list");
                    JSONObject objJson;
                    for (int i = 0; i < json.length(); i++) {
                        objJson = json.getJSONObject(i);

                        ItemCategory objItem = new ItemCategory();
                        objItem.setCategoryId(objJson.getString(Constant.CATEGORY_CID));
                        objItem.setCategoryName(objJson.getString(Constant.CATEGORY_NAME));
                        objItem.setCategoryImageBig(objJson.getString(Constant.CATEGORY_IMAGE));
                        mCatList.add(objItem);

                    }
                    JSONArray jsonLatest = jsonArray.getJSONArray("featured_property");
                    JSONObject objJsonCat;
                    for (int k = 0; k < jsonLatest.length(); k++) {
                        objJsonCat = jsonLatest.getJSONObject(k);
                        ItemPlaceList objItem = new ItemPlaceList();
                        objItem.setPlaceId(objJsonCat.getString(Constant.LISTING_H_ID));
                        objItem.setPlaceCatId(objJsonCat.getString(Constant.LISTING_H_CAT_ID));
                        objItem.setPlaceName(objJsonCat.getString(Constant.LISTING_H_NAME));
                        objItem.setPlaceImage(objJsonCat.getString(Constant.LISTING_H_IMAGE));
                        objItem.setPlaceVideo(objJsonCat.getString(Constant.LISTING_H_VIDEO));
                        objItem.setPlaceDescription(objJsonCat.getString(Constant.LISTING_H_DES));
                        objItem.setPlaceAddress(objJsonCat.getString(Constant.LISTING_H_ADDRESS));
                        objItem.setPlaceEmail(objJsonCat.getString(Constant.LISTING_H_EMAIL));
                        objItem.setPlacePhone(objJsonCat.getString(Constant.LISTING_H_PHONE));
                        objItem.setPlaceWebsite(objJsonCat.getString(Constant.LISTING_H_WEBSITE));
                        objItem.setPlaceLatitude(objJsonCat.getString(Constant.LISTING_H_MAP_LATITUDE));
                        objItem.setPlaceLongitude(objJsonCat.getString(Constant.LISTING_H_MAP_LONGITUDE));
                        objItem.setPlaceRateAvg(objJsonCat.getString(Constant.LISTING_H_RATING_AVG));
                        objItem.setPlaceRateTotal(objJsonCat.getString(Constant.LISTING_H_RATING_TOTAL));
                        objItem.setPlaceCatName(objJsonCat.getString("category_name"));
                        mFeatureList.add(objItem);
                    }

                    JSONArray jsonLatest2 = jsonArray.getJSONArray("popular_property");
                    JSONObject objJsonCat2;
                    for (int l = 0; l < jsonLatest2.length(); l++) {
                        objJsonCat2 = jsonLatest2.getJSONObject(l);
                        ItemPlaceList objItem = new ItemPlaceList();
                        objItem.setPlaceId(objJsonCat2.getString(Constant.LISTING_H_ID));
                        objItem.setPlaceCatId(objJsonCat2.getString(Constant.LISTING_H_CAT_ID));
                        objItem.setPlaceName(objJsonCat2.getString(Constant.LISTING_H_NAME));
                        objItem.setPlaceImage(objJsonCat2.getString(Constant.LISTING_H_IMAGE));
                        objItem.setPlaceVideo(objJsonCat2.getString(Constant.LISTING_H_VIDEO));
                        objItem.setPlaceDescription(objJsonCat2.getString(Constant.LISTING_H_DES));
                        objItem.setPlaceAddress(objJsonCat2.getString(Constant.LISTING_H_ADDRESS));
                        objItem.setPlaceEmail(objJsonCat2.getString(Constant.LISTING_H_EMAIL));
                        objItem.setPlacePhone(objJsonCat2.getString(Constant.LISTING_H_PHONE));
                        objItem.setPlaceWebsite(objJsonCat2.getString(Constant.LISTING_H_WEBSITE));
                        objItem.setPlaceLatitude(objJsonCat2.getString(Constant.LISTING_H_MAP_LATITUDE));
                        objItem.setPlaceLongitude(objJsonCat2.getString(Constant.LISTING_H_MAP_LONGITUDE));
                        objItem.setPlaceRateAvg(objJsonCat2.getString(Constant.LISTING_H_RATING_AVG));
                        objItem.setPlaceRateTotal(objJsonCat2.getString(Constant.LISTING_H_RATING_TOTAL));
                        objItem.setPlaceCatName(objJsonCat2.getString("category_name"));
                        mPopularList.add(objItem);


                    }
                } catch (JSONException e) {
                    e.printStackTrace();
                }
                setResult();
            }
        }
    }

    private void setResult() {
        if (getActivity() != null) {
            categoryAdapter = new HomeCategoryAdapter(getActivity(), mCatList, onClick);
            mCatView.setAdapter(categoryAdapter);

            homeFeatureAdapter = new HomeFeatureAdapter(getActivity(), mFeatureList);
            mFeatureView.setAdapter(homeFeatureAdapter);

            homePopularAdapter = new HomePopularAdapter(getActivity(), mPopularList);
            mPopularView.setAdapter(homePopularAdapter);

            if (categoryAdapter.getItemCount() == 0) {
                lyt_not_found.setVisibility(View.VISIBLE);
            } else {
                lyt_not_found.setVisibility(View.GONE);
            }
        }
    }

    private void showProgress(boolean show) {
        if (show) {
            mProgressBar.setVisibility(View.VISIBLE);
            mScrollView.setVisibility(View.GONE);
            lyt_not_found.setVisibility(View.GONE);
        } else {
            mProgressBar.setVisibility(View.GONE);
            mScrollView.setVisibility(View.VISIBLE);
        }
    }

    public void onCreateOptionsMenu(Menu menu, MenuInflater inflater) {
        super.onCreateOptionsMenu(menu, inflater);
        inflater.inflate(R.menu.menu_search, menu);

        final SearchView searchView = (SearchView) menu.findItem(R.id.search)
                .getActionView();

        final MenuItem searchMenuItem = menu.findItem(R.id.search);
        searchView.setOnQueryTextFocusChangeListener(new View.OnFocusChangeListener() {
            @Override
            public void onFocusChange(View v, boolean hasFocus) {
                // TODO Auto-generated method stub
                if (!hasFocus) {
                    searchMenuItem.collapseActionView();
                    searchView.setQuery("", false);
                }
            }
        });

        searchView.setOnQueryTextListener(new SearchView.OnQueryTextListener() {

            @Override
            public boolean onQueryTextChange(String newText) {
                // TODO Auto-generated method stub

                return false;
            }

            @Override
            public boolean onQueryTextSubmit(String query) {
                // TODO Auto-generated method stub
                Intent intent = new Intent(getActivity(), SearchActivity.class);
                intent.putExtra("search", query);
                startActivity(intent);
                searchView.clearFocus();
                return false;
            }
        });

    }
}
