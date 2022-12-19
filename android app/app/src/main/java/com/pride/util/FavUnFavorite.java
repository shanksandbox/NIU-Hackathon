package com.pride.util;

import android.app.ProgressDialog;
import android.content.Context;
import android.widget.Toast;
import com.pride.BnB.MyApplication;
import com.google.gson.Gson;
import com.google.gson.JsonObject;
import com.loopj.android.http.AsyncHttpClient;
import com.loopj.android.http.AsyncHttpResponseHandler;
import com.loopj.android.http.RequestParams;
import com.pride.BnB.R;
import org.json.JSONException;
import org.json.JSONObject;
import cz.msebera.android.httpclient.Header;

public class FavUnFavorite {

    private ProgressDialog pDialog;
    private Context mContext;

    public FavUnFavorite(Context context) {
        this.mContext = context;
        pDialog = new ProgressDialog(mContext);
    }

    public void userFav(final String recId, final FavClickListener saveClickListener) {
        AsyncHttpClient client = new AsyncHttpClient();
        RequestParams params = new RequestParams();
        JsonObject jsObj = (JsonObject) new Gson().toJsonTree(new API());
        jsObj.addProperty("method_name", "add_favourite");
        jsObj.addProperty("place_id", recId);
        jsObj.addProperty("user_id", MyApplication.getAppInstance().getUserId());
        params.put("data", API.toBase64(jsObj.toString()));
        client.post(Constant.API_URL, params, new AsyncHttpResponseHandler() {
            @Override
            public void onStart() {
                super.onStart();
                showProgressDialog();
            }

            @Override
            public void onSuccess(int statusCode, Header[] headers, byte[] responseBody) {
                dismissProgressDialog();

                String result = new String(responseBody);
                try {
                    JSONObject mainJson = new JSONObject(result);

                    Toast.makeText(mContext, mainJson.getString(Constant.MSG), Toast.LENGTH_SHORT).show();
                    saveClickListener.onItemClick(mainJson.getBoolean(Constant.LISTING_H_FAV), mainJson.getString(Constant.MSG));
                    Events.Fav fav = new Events.Fav();
                    fav.setReId(recId);
                    fav.setFav(mainJson.getBoolean(Constant.LISTING_H_FAV));
                    GlobalBus.getBus().post(fav);

                } catch (JSONException e) {
                    e.printStackTrace();
                }
            }

            @Override
            public void onFailure(int statusCode, Header[] headers, byte[] responseBody, Throwable error) {
                dismissProgressDialog();
            }

        });
    }

    private void showProgressDialog() {
        pDialog.setMessage(mContext.getString(R.string.loading));
        pDialog.setIndeterminate(false);
        pDialog.setCancelable(false);
        pDialog.show();
    }

    private void dismissProgressDialog() {
        if (pDialog != null && pDialog.isShowing())
            pDialog.dismiss();
    }
}
