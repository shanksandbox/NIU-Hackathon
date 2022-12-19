package com.pride.util;


import com.pride.item.ItemCategory;
import com.pride.BnB.BuildConfig;

import java.io.Serializable;
import java.util.ArrayList;

public class Constant implements Serializable {

    private static final long serialVersionUID = 1L;

    public static final String SERVER_URL = BuildConfig.server_url;

     //images url
    public static final String SERVER_IMAGE_FOLDER=SERVER_URL+"images/";

    public static final String API_URL = SERVER_URL + "api.php";


    public static final String CATEGORY_ARRAY_NAME="PLACE_APP";
    public static final String GALLERY_ARRAY_NAME="gallery";
    public static final String CATEGORY_CID="cid";
    public static final String CATEGORY_NAME="category_name";
    public static final String CATEGORY_IMAGE="category_image";

    public static final String LISTING_H_ID="p_id";
    public static final String LISTING_H_NAME="place_name";
    public static final String LISTING_H_CAT_ID="p_cat_id";
    public static final String LISTING_H_IMAGE="place_image";
    public static final String LISTING_H_VIDEO="place_video";
    public static final String LISTING_H_DES="place_description";
    public static final String LISTING_H_MAP_LATITUDE="place_map_latitude";
    public static final String LISTING_H_MAP_LONGITUDE="place_map_longitude";
    public static final String LISTING_H_ADDRESS="place_address";
    public static final String LISTING_H_EMAIL="place_email";
    public static final String LISTING_H_PHONE="place_phone";
    public static final String LISTING_H_WEBSITE="place_website";
    public static final String LISTING_H_RATING_AVG="place_rate_avg";
    public static final String LISTING_H_RATING_TOTAL="place_total_rate";
    public static final String LISTING_H_FAV="is_favourite";
    public static final String LISTING_H_GALLERY="image_name";
    public static final String LISTING_H_DISTANCE="place_distance";

    public static final String ARRAY_NAME_REVIEW="Ratings";
    public static final String REVIEW_NAME="user_name";
    public static final String REVIEW_RATE="rate";
    public static final String REVIEW_MESSAGE="message";

    public static final String APP_NAME = "app_name";
    public static final String APP_IMAGE = "app_logo";
    public static final String APP_VERSION = "app_version";
    public static final String APP_AUTHOR = "app_author";
    public static final String APP_CONTACT = "app_contact";
    public static final String APP_EMAIL = "app_email";
    public static final String APP_WEBSITE = "app_website";
    public static final String APP_DESC = "app_description";
    public static final String APP_PRIVACY = "app_privacy_policy";
    public static final String APP_DEVELOP = "app_developed_by";
    public static final String ADS_BANNER_ID="banner_ad_id";
    public static final String ADS_FULL_ID="interstital_ad_id";
    public static final String ADS_BANNER_ON_OFF="banner_ad";
    public static final String ADS_FULL_ON_OFF="interstital_ad";
    public static final String ADS_PUB_ID="publisher_id";
    public static final String ADS_CLICK="interstital_ad_click";
    public static final String APP_PACKAGE_NAME="package_name";
    public static final String NATIVE_AD_ON_OFF = "native_ad";
    public static final String NATIVE_AD_ID = "native_ad_id";
    public static final String BANNER_TYPE="banner_ad_type";
    public static final String FULL_TYPE="interstital_ad_type";
    public static final String NATIVE_TYPE="native_ad_type";
    public static boolean isAppUpdate = false, isAppUpdateCancel = false;
    public static int appUpdateVersion;
    public static String  appUpdateUrl, appUpdateDesc;
    public static String SAVE_ADS_NATIVE_ON_OFF,SAVE_NATIVE_ID,SAVE_BANNER_TYPE,SAVE_FULL_TYPE,SAVE_NATIVE_TYPE,SAVE_NATIVE_CLICK_OTHER,
            SAVE_NATIVE_CLICK_CAT,SAVE_TAG_LINE,SAVE_ADS_BANNER_ID,SAVE_ADS_FULL_ID,SAVE_ADS_BANNER_ON_OFF="false",SAVE_ADS_FULL_ON_OFF="false",SAVE_ADS_PUB_ID,SAVE_ADS_CLICK;

    public static int GET_SUCCESS_MSG;
    public static final String MSG = "msg";
    public static final String SUCCESS = "success";
    public static final String USER_NAME = "name";
    public static final String USER_ID = "user_id";
    public static final String USER_EMAIL = "email";
    public static final String USER_PHONE = "phone";

     public static int AD_COUNT=0;
    public static String USER_LATITUDE ;
    public static String USER_LONGITUDE;
    public static String LATEST_PLACE_IDD;
    public static ArrayList<ItemCategory> ConsImage= new ArrayList<>();
    public static final String DOWNLOAD_FOLDER_PATH="/Places/";


}
