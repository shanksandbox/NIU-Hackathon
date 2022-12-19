package com.pride.BnB;

import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;

import androidx.appcompat.app.AppCompatActivity;
import androidx.appcompat.widget.Toolbar;
import androidx.viewpager.widget.PagerAdapter;
import androidx.viewpager.widget.ViewPager;

import com.pride.util.Constant;
import com.pride.util.JsonUtils;
import com.pride.util.TouchImageView;
import com.squareup.picasso.Picasso;

import io.github.inflationx.viewpump.ViewPumpContextWrapper;

public class ImageActivity extends AppCompatActivity {

    Toolbar toolbar;
    JsonUtils jsonUtils;
    TouchImageView imageView;
    ViewPager mViewPager;
    CustomViewPagerAdapter mAdapter;
    TextView textViewClose;
    int position;
    int TOTAL_IMAGE;

    @Override
    protected void attachBaseContext(Context newBase) {
        super.attachBaseContext(ViewPumpContextWrapper.wrap(newBase));
    }

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.image_activity);

        textViewClose=findViewById(R.id.txt_close);

        jsonUtils = new JsonUtils(this);
        jsonUtils.forceRTLIfSupported(getWindow());

        Intent i = getIntent();
        position = i.getIntExtra("POSITION_ID", 0);

        TOTAL_IMAGE = Constant.ConsImage.size() - 1;

        mViewPager = findViewById(R.id.viewPager);
        mAdapter = new CustomViewPagerAdapter();
        mViewPager.setAdapter(mAdapter);
        mViewPager.setCurrentItem(position);
        textViewClose.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                onBackPressed();
            }
        });


    }

    private class CustomViewPagerAdapter extends PagerAdapter {
        private LayoutInflater inflater;

        public CustomViewPagerAdapter() {
            // TODO Auto-generated constructor stub
            inflater = getLayoutInflater();
        }

        @Override
        public int getCount() {
            return Constant.ConsImage.size();
        }

        @Override
        public boolean isViewFromObject(View view, Object object) {
            return view.equals(object);
        }

        @Override
        public Object instantiateItem(ViewGroup container, final int position) {
            View imageLayout = inflater.inflate(R.layout.row_full_gallery_item, container, false);
            assert imageLayout != null;
            TouchImageView image = imageLayout.findViewById(R.id.iv_wall_details);
            TextView text = imageLayout.findViewById(R.id.textNumber);

            text.setText(position + 1 + "/" + Constant.ConsImage.size());

              Picasso.get().load(Constant.ConsImage.get(position).getCategoryImageBig()).placeholder(R.mipmap.app_icon).into(image);

            container.addView(imageLayout, 0);
            return imageLayout;
        }

        @Override
        public void destroyItem(ViewGroup container, int position, Object object) {
            (container).removeView((View) object);
        }
    }

    @Override
    public void onBackPressed() {
        super.onBackPressed();
    }
}
