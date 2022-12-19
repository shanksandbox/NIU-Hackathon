package com.pride.BnB;

import android.annotation.SuppressLint;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.Intent;
import android.os.AsyncTask;
import android.os.Bundle;
import android.util.Log;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.EditText;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;
import androidx.appcompat.widget.Toolbar;

import com.pride.util.API;
import com.pride.util.Constant;
import com.pride.util.JsonUtils;
import com.google.android.material.button.MaterialButton;
import com.google.android.material.textfield.TextInputEditText;
import com.google.android.material.textfield.TextInputLayout;
import com.google.gson.Gson;
import com.google.gson.JsonObject;
import com.mobsandgeeks.saripaar.ValidationError;
import com.mobsandgeeks.saripaar.Validator;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.List;

import io.github.inflationx.viewpump.ViewPumpContextWrapper;
import libs.mjn.prettydialog.PrettyDialog;
import libs.mjn.prettydialog.PrettyDialogCallback;


public class ProfileEditActivity extends AppCompatActivity implements Validator.ValidationListener {

    TextInputEditText edtFullName;
    TextInputEditText edtEmail;
    TextInputEditText edtPassword;
    TextInputEditText edtMobile;
    private Validator validator;
    MyApplication MyApp;
    String strName, strEmail, strPassword, strMobile, strMessage, saveType, saveAId;
    JsonUtils jsonUtils;
    Toolbar toolbar;
    ProgressDialog pDialog;
    Menu menu;
    TextInputLayout textInput_password_edit_profile;
    MaterialButton btn_submit;

    @Override
    protected void attachBaseContext(Context newBase) {
        super.attachBaseContext(ViewPumpContextWrapper.wrap(newBase));
    }

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_profile_edit);
        JsonUtils.setStatusBarGradiant(ProfileEditActivity.this);
        toolbar = findViewById(R.id.toolbar);
        toolbar.setTitle(getString(R.string.menu_profile));
        setSupportActionBar(toolbar);
        if (getSupportActionBar() != null) {
            getSupportActionBar().setDisplayHomeAsUpEnabled(true);
            getSupportActionBar().setDisplayShowHomeEnabled(true);
        }

        jsonUtils = new JsonUtils(this);
        jsonUtils.forceRTLIfSupported(getWindow());

        MyApp = MyApplication.getAppInstance();
        pDialog = new ProgressDialog(ProfileEditActivity.this);

        edtFullName = findViewById(R.id.editText_name_edit_profile);
        edtEmail = findViewById(R.id.editText_email_edit_profile);
        edtPassword = findViewById(R.id.editText_password_edit_profile);
        edtMobile = findViewById(R.id.editText_phone_edit_profile);
        textInput_password_edit_profile = findViewById(R.id.textInput_password_edit_profile);
        btn_submit = findViewById(R.id.btn_submit);

        switch (MyApp.getUserType()) {
            case "Google":
                edtEmail.setEnabled(false);
                edtEmail.setFocusable(false);
                edtEmail.setCursorVisible(false);
                textInput_password_edit_profile.setVisibility(View.GONE);
                break;
            case "Facebook":
                edtEmail.setEnabled(false);
                textInput_password_edit_profile.setVisibility(View.GONE);
                edtEmail.setFocusable(false);
                edtEmail.setCursorVisible(false);
                break;
            case "Normal":
                edtEmail.setEnabled(true);
                break;
        }
        validator = new Validator(this);
        validator.setValidationListener(this);

        JsonObject jsObj = (JsonObject) new Gson().toJsonTree(new API());
        jsObj.addProperty("method_name", "user_profile");
        jsObj.addProperty("user_id", MyApp.getUserId());
        if (JsonUtils.isNetworkAvailable(ProfileEditActivity.this)) {
            new getProfile(API.toBase64(jsObj.toString())).execute(Constant.API_URL);
        } else {
            showToast(getString(R.string.network_msg));
        }

        btn_submit.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                if (JsonUtils.isNetworkAvailable(ProfileEditActivity.this)) {
                    strName = edtFullName.getText().toString().replace(" ", "%20");
                    strEmail = edtEmail.getText().toString();
                    strPassword = edtPassword.getText().toString();
                    strMobile = edtMobile.getText().toString();

                    JsonObject jsObj = (JsonObject) new Gson().toJsonTree(new API());
                    jsObj.addProperty("method_name", "user_profile_update");
                    jsObj.addProperty("user_id", MyApp.getUserId());
                    jsObj.addProperty("name", strName);
                    jsObj.addProperty("email", strEmail);
                    jsObj.addProperty("password", strPassword);
                    jsObj.addProperty("phone", strMobile);
                    if (JsonUtils.isNetworkAvailable(ProfileEditActivity.this)) {
                        new getProfileUpdate(API.toBase64(jsObj.toString())).execute(Constant.API_URL);
                        Log.e("update", "" + API.toBase64(jsObj.toString()));
                    } else {
                        showToast(getString(R.string.network_msg));
                    }
                }
            }
        });
        validator = new Validator(this);
        validator.setValidationListener(this);

    }

    @Override
    public void onValidationSucceeded() {
        strPassword = edtPassword.getText().toString();

        if (JsonUtils.isNetworkAvailable(ProfileEditActivity.this)) {
            strName = edtFullName.getText().toString().replace(" ", "%20");
            strEmail = edtEmail.getText().toString();
            strPassword = edtPassword.getText().toString();
            strMobile = edtMobile.getText().toString();

            JsonObject jsObj = (JsonObject) new Gson().toJsonTree(new API());
            jsObj.addProperty("method_name", "user_profile_update");
            jsObj.addProperty("user_id", MyApp.getUserId());
            jsObj.addProperty("name", strName);
            jsObj.addProperty("email", strEmail);
            jsObj.addProperty("password", strPassword);
            jsObj.addProperty("phone", strMobile);
            if (JsonUtils.isNetworkAvailable(ProfileEditActivity.this)) {
                new getProfileUpdate(API.toBase64(jsObj.toString())).execute(Constant.API_URL);
                Log.e("update", "" + API.toBase64(jsObj.toString()));
            } else {
                showToast(getString(R.string.network_msg));
            }
        }
    }

    @Override
    public void onValidationFailed(List<ValidationError> errors) {
        for (ValidationError error : errors) {
            View view = error.getView();
            String message = error.getCollatedErrorMessage(this);
            if (view instanceof EditText) {
                ((EditText) view).setError(message);
            } else {
                Toast.makeText(this, message, Toast.LENGTH_LONG).show();
            }
        }
    }

    @SuppressLint("StaticFieldLeak")
    private class getProfile extends AsyncTask<String, Void, String> {

        String base64;

        private getProfile(String base64) {
            this.base64 = base64;
        }

        ProgressDialog pDialog;

        @Override
        protected void onPreExecute() {
            super.onPreExecute();

            pDialog = new ProgressDialog(ProfileEditActivity.this);
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
                        if (objJson.has("status")) {
                            showToast(getString(R.string.no_data_found));
                        } else {
                            edtFullName.setText(objJson.getString(Constant.USER_NAME));
                            edtEmail.setText(objJson.getString(Constant.USER_EMAIL));
                            edtMobile.setText(objJson.getString(Constant.USER_PHONE));
                        }
                    }

                } catch (JSONException e) {
                    e.printStackTrace();
                }
            }
        }
    }

    @SuppressLint("StaticFieldLeak")
    private class getProfileUpdate extends AsyncTask<String, Void, String> {

        String base64;

        private getProfileUpdate(String base64) {
            this.base64 = base64;
        }

        @Override
        protected void onPreExecute() {
            super.onPreExecute();
            showProgressDialog();
        }

        @Override
        protected String doInBackground(String... params) {
            return JsonUtils.getJSONString(params[0], base64);
        }

        @Override
        protected void onPostExecute(String result) {
            super.onPostExecute(result);
            dismissProgressDialog();

            if (null == result || result.length() == 0) {
                showToast(getString(R.string.no_data_found));

            } else {

                try {
                    JSONObject mainJson = new JSONObject(result);
                    JSONArray jsonArray = mainJson.getJSONArray(Constant.CATEGORY_ARRAY_NAME);
                    JSONObject objJson;
                    for (int i = 0; i < jsonArray.length(); i++) {
                        objJson = jsonArray.getJSONObject(i);
                        strMessage = objJson.getString(Constant.MSG);
                        Constant.GET_SUCCESS_MSG = objJson.getInt(Constant.SUCCESS);
                    }

                } catch (JSONException e) {
                    e.printStackTrace();
                }
                setResult();
            }
        }
    }

    public void setResult() {

        if (Constant.GET_SUCCESS_MSG == 0) {
            final PrettyDialog dialog = new PrettyDialog(this);
            dialog.setTitle(getString(R.string.dialog_error))
                    .setTitleColor(R.color.dialog_color)
                    .setMessage(strMessage)
                    .setMessageColor(R.color.dialog_color)
                    .setAnimationEnabled(false)
                    .setIcon(R.drawable.pdlg_icon_close, R.color.dialog_color, new PrettyDialogCallback() {
                        @Override
                        public void onClick() {
                            dialog.dismiss();
                        }
                    })
                    .addButton(getString(R.string.dialog_ok), R.color.pdlg_color_white, R.color.dialog_color, new PrettyDialogCallback() {
                        @Override
                        public void onClick() {
                            dialog.dismiss();
                        }
                    });
            dialog.setCancelable(false);
            dialog.show();
        } else {
            switch (MyApp.getUserType()) {
                case "Google":
                    saveType = "Google";
                    break;
                case "Facebook":
                    saveType = "Facebook";
                    break;
                case "Normal":
                    saveType = "Normal";
                    break;
            }
            MyApp.saveLogin(MyApp.getUserId(), strName, strEmail, saveType, saveAId);
            final PrettyDialog dialog = new PrettyDialog(this);
            dialog.setTitle(getString(R.string.dialog_success))
                    .setTitleColor(R.color.dialog_color)
                    .setMessage(strMessage)
                    .setMessageColor(R.color.dialog_color)
                    .setAnimationEnabled(false)
                    .setIcon(R.drawable.pdlg_icon_success, R.color.dialog_color, new PrettyDialogCallback() {
                        @Override
                        public void onClick() {
                            dialog.dismiss();
                        }
                    })
                    .addButton(getString(R.string.dialog_ok), R.color.pdlg_color_white, R.color.dialog_color, new PrettyDialogCallback() {
                        @Override
                        public void onClick() {
                            dialog.dismiss();
                            Intent intent = new Intent(ProfileEditActivity.this, MainActivity.class);
                            intent.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
                            startActivity(intent);
                            finish();
                        }
                    });
            dialog.setCancelable(false);
            dialog.show();
        }
    }

    public void showToast(String msg) {
        Toast.makeText(ProfileEditActivity.this, msg, Toast.LENGTH_SHORT).show();
    }

    public void showProgressDialog() {
        pDialog.setMessage(getString(R.string.loading));
        pDialog.setIndeterminate(false);
        pDialog.setCancelable(true);
        pDialog.show();
    }

    public void dismissProgressDialog() {
        pDialog.dismiss();
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem menuItem) {
        if (menuItem.getItemId() == android.R.id.home) {
            onBackPressed();
        } else {
            return super.onOptionsItemSelected(menuItem);
        }
        return true;
    }

}
