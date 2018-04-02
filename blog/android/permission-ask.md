# 权限控制

github地址：https://github.com/cowthan/AyoCompoment

## 2.1 动态权限

摘自：http://www.jianshu.com/p/a51593817825

动态权限的问题只在app的targetSdk和手机的系统版本都大于等于23时生效
- target=22的apk在6.0上不用申请权限
- target=23的apk在5.0上不用申请权限
- target=23的apk在6.0上需要申请权限
- 如果你调用的是第三方的功能，则不需要申请权限

```
以下是需要单独申请的权限，共分为9组，每组只要有一个权限申请成功了，就默认整组权限都可以使用了
- 需要现在manifest里声明
- 代码里用到时，总是需要check，若没有，则弹出弹框请求用户给权限

  group:android.permission-group.CONTACTS
    permission:android.permission.WRITE_CONTACTS
    permission:android.permission.GET_ACCOUNTS
    permission:android.permission.READ_CONTACTS

  group:android.permission-group.PHONE
    permission:android.permission.READ_CALL_LOG
    permission:android.permission.READ_PHONE_STATE
    permission:android.permission.CALL_PHONE
    permission:android.permission.WRITE_CALL_LOG
    permission:android.permission.USE_SIP
    permission:android.permission.PROCESS_OUTGOING_CALLS
    permission:com.android.voicemail.permission.ADD_VOICEMAIL

  group:android.permission-group.CALENDAR
    permission:android.permission.READ_CALENDAR
    permission:android.permission.WRITE_CALENDAR

  group:android.permission-group.CAMERA
    permission:android.permission.CAMERA

  group:android.permission-group.SENSORS
    permission:android.permission.BODY_SENSORS

  group:android.permission-group.LOCATION
    permission:android.permission.ACCESS_FINE_LOCATION
    permission:android.permission.ACCESS_COARSE_LOCATION

  group:android.permission-group.STORAGE
    permission:android.permission.READ_EXTERNAL_STORAGE
    permission:android.permission.WRITE_EXTERNAL_STORAGE

  group:android.permission-group.MICROPHONE
    permission:android.permission.RECORD_AUDIO

  group:android.permission-group.SMS
    permission:android.permission.READ_SMS
    permission:android.permission.RECEIVE_WAP_PUSH
    permission:android.permission.RECEIVE_MMS
    permission:android.permission.RECEIVE_SMS
    permission:android.permission.SEND_SMS
    permission:android.permission.READ_CELL_BROADCASTS
```

```
以下是普通权限，直接在manifest里申请

android.permission.ACCESS_LOCATION_EXTRA_COMMANDS
  android.permission.ACCESS_NETWORK_STATE
  android.permission.ACCESS_NOTIFICATION_POLICY
  android.permission.ACCESS_WIFI_STATE
  android.permission.ACCESS_WIMAX_STATE
  android.permission.BLUETOOTH
  android.permission.BLUETOOTH_ADMIN
  android.permission.BROADCAST_STICKY
  android.permission.CHANGE_NETWORK_STATE
  android.permission.CHANGE_WIFI_MULTICAST_STATE
  android.permission.CHANGE_WIFI_STATE
  android.permission.CHANGE_WIMAX_STATE
  android.permission.DISABLE_KEYGUARD
  android.permission.EXPAND_STATUS_BAR
  android.permission.FLASHLIGHT
  android.permission.GET_ACCOUNTS
  android.permission.GET_PACKAGE_SIZE
  android.permission.INTERNET
  android.permission.KILL_BACKGROUND_PROCESSES
  android.permission.MODIFY_AUDIO_SETTINGS
  android.permission.NFC
  android.permission.READ_SYNC_SETTINGS
  android.permission.READ_SYNC_STATS
  android.permission.RECEIVE_BOOT_COMPLETED
  android.permission.REORDER_TASKS
  android.permission.REQUEST_INSTALL_PACKAGES
  android.permission.SET_TIME_ZONE
  android.permission.SET_WALLPAPER
  android.permission.SET_WALLPAPER_HINTS
  android.permission.SUBSCRIBED_FEEDS_READ
  android.permission.TRANSMIT_IR
  android.permission.USE_FINGERPRINT
  android.permission.VIBRATE
  android.permission.WAKE_LOCK
  android.permission.WRITE_SYNC_SETTINGS
  com.android.alarm.permission.SET_ALARM
  com.android.launcher.permission.INSTALL_SHORTCUT
  com.android.launcher.permission.UNINSTALL_SHORTCUT

```

怎么申请

先看manifest里：
```
<!-- uses-permission-sdk-m标示的权限只在6.0及其以上的机器上被申请，在低于6.0的设备里不会申请，也不会生效 -->
自动生效，不需要再怎么申请了
<uses-permission-sdk-23 android:name="android.permission.READ_CONTACTS" />
<uses-permission-sdk-23 android:name="android.permission.WRITE_CONTACTS" />

<!-- 普通权限申请，敏感的必须在运行时动态申请 -->
<uses-permission android:name="android.permission.CAMERA"/>
```

代码里判断权限：
```
///----干活之前判断是否有权限
if (ActivityCompat.checkSelfPermission(this, Manifest.permission.CAMERA) != PackageManager.PERMISSION_GRANTED) {
    // Camera permission has not been granted.
    requestCameraPermission();
} else {
    // Camera permissions is already available, show the camera preview.
    Log.i(TAG,"CAMERA permission has already been granted. Displaying camera preview.");
    showCameraPreview();
}


///----请求用户授权，并判断是第一次请求，还是已经被拒绝过，或用户主动取消过
/**
 * Requests the Camera permission.
 * If the permission has been denied previously, a SnackBar will prompt the user to grant the
 * permission, otherwise it is requested directly.
 */
private void requestCameraPermission() {
    Log.i(TAG, "CAMERA permission has NOT been granted. Requesting permission.");

    // BEGIN_INCLUDE(camera_permission_request)
    if (ActivityCompat.shouldShowRequestPermissionRationale(this, Manifest.permission.CAMERA)) {
        // Provide an additional rationale to the user if the permission was not granted
        // and the user would benefit from additional context for the use of the permission.
        // For example if the user has previously denied the permission.
        Log.i(TAG, "Displaying camera permission rationale to provide additional context.");

        ///这一层是一个用户友好的做法，既然用户主动拒绝过或者取消过该权限，那就应该在再次申请时多说点什么
        Snackbar.make(mLayout, "之前申请过被拒，或者用户去设置里关闭了该权限，在这里提示",
                Snackbar.LENGTH_INDEFINITE)
                .setAction(R.string.ok, new View.OnClickListener() {
                    @Override
                    public void onClick(View view) {
                        ActivityCompat.requestPermissions(PermissionMainActivity.this,
                                new String[]{Manifest.permission.CAMERA},
                                REQUEST_CAMERA);
                    }
                })
                .show();
    } else {
        // Camera permission has not been granted yet. Request it directly.
        ActivityCompat.requestPermissions(this, new String[]{Manifest.permission.CAMERA}, REQUEST_CAMERA);
    }
    // END_INCLUDE(camera_permission_request)
}


///----获得用户授权结果
/**
 * Callback received when a permissions request has been completed.
 */
@Override
public void onRequestPermissionsResult(int requestCode, @NonNull String[] permissions,
        @NonNull int[] grantResults) {

    if (requestCode == REQUEST_CAMERA) {
        // BEGIN_INCLUDE(permission_result)
        // Received permission result for camera permission.
        Log.i(TAG, "Received response for Camera permission request.");

        // Check if the only required permission has been granted
        if (grantResults.length == 1 && grantResults[0] == PackageManager.PERMISSION_GRANTED) {
            // Camera permission has been granted, preview can be displayed
            Log.i(TAG, "CAMERA permission has now been granted. Showing preview.");
            Snackbar.make(mLayout, R.string.permision_available_camera,
                    Snackbar.LENGTH_SHORT).show();
        } else {
            Log.i(TAG, "CAMERA permission was NOT granted.");
            Snackbar.make(mLayout, R.string.permissions_not_granted,
                    Snackbar.LENGTH_SHORT).show();

        }
        // END_INCLUDE(permission_result)

    } else if (requestCode == REQUEST_CONTACTS) {
        Log.i(TAG, "Received response for contact permissions request.");

        // We have requested multiple permissions for contacts, so all of them need to be
        // checked.
        if (PermissionUtil.verifyPermissions(grantResults)) {
            // All required permissions have been granted, display contacts fragment.
            Snackbar.make(mLayout, R.string.permision_available_contacts,
                    Snackbar.LENGTH_SHORT)
                    .show();
        } else {
            Log.i(TAG, "Contacts permissions were NOT granted.");
            Snackbar.make(mLayout, R.string.permissions_not_granted,
                    Snackbar.LENGTH_SHORT)
                    .show();
        }

    } else {
        super.onRequestPermissionsResult(requestCode, permissions, grantResults);
    }
}

```

注意：
- requestPermission一般是个系统对话框
- 但第二次弹出，可能会带个提示：以后不再弹出，如果用户选了，那shouldShowRequestPermissionRationale会返回true
    - 表示你需要给用户一个解释
    - 但定制系统里，这个可能总是返回false，如小米某版本，会导致用户选择不再弹出，对话框真就不再弹出了
        - 你request，但对话框不弹出，意思就是直接回调授权失败
        - 解决方案是在onRequestPermissionResult里，结果不为PackageManager.PERMISSION_GRANTED时，弹出应用信息界面，让用户设置

上面还有遗漏，就是同时申请多个permission时，弹出框的样子，onResult里的回调，用到时再研究吧，知道了套路就简单了


上面的代很明显很繁琐，所以我们可以封装一下