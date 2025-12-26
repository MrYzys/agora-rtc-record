# 更新云端录制合流布局

POST https://api.sd-rtn.com/v1/apps/{appid}/cloud_recording/resourceid/{resourceid}/sid/{sid}/mode/{mode}/updateLayout

`updateLayout` 方法：更新云端录制合流布局。

开始合流录制后，你可以调用该方法（`updateLayout`）更新合流布局。

每次调用该方法都会覆盖原来的布局设置。例如，在开始录制时设置了 `backgroundColor` 为 `"#FF0000"`（红色），如果调用 `updateLayout` 方法更新合流布局时如果不再设置 `backgroundColor` 字段，背景色就会变为黑色（默认值）。

> **注意**
> - `updateLayout` 请求仅在会话内有效。如果录制启动错误，或录制已结束，调用 `updateLayout` 将返回 `404`。
> - 如果需要连续调用 `updateLayout` 方法更新合流布局，请在收到上一次 `updateLayout` 响应后再进行调用，否则可能导致请求结果与预期不一致。

## 请求

### Basic Auth

声网云端录制 RESTful API 仅支持 HTTPS 协议。发送请求时，你需要使用声网提供的客户 ID 和客户密钥生成 Base64 编码凭证，并填入请求头部的 `Authorization` 字段中。详见[实现 HTTP 基本认证](https://doc.shengwang.cn/doc/cloud-recording/restful/user-guides/http-basic-auth)。

### 路径参数

| 参数名 | 类型 | 必填 | 说明 |
| --- | --- | --- | --- |
| `appid` | string | 是 | 你的项目使用的 [App ID](http://doc.shengwang.cn/doc/cloud-recording/restful/get-started/enable-service#%E8%8E%B7%E5%8F%96-app-id)。对于页面录制模式，只需输入开通了云端录制服务的 App ID。对于单流和合流录制模式，必须使用和待录制的频道相同的 App ID，且该 App ID 需要开通云端录制服务。 |
| `resourceid` | string | 是 | 通过 [`acquire`](/doc/cloud-recording/restful/cloud-recording/operations/post-v1-apps-appid-cloud_recording-acquire) 请求获取到的 Resource ID。 |
| `sid` | string | 是 | 通过 [`start`](/doc/cloud-recording/restful/cloud-recording/operations/post-v1-apps-appid-cloud_recording-resourceid-resourceid-mode-mode-start) 获取的录制 ID。 |
| `mode` | string | 是 | 录制模式。只支持 `mix`，代表[合流录制模式](https://doc.shengwang.cn/doc/cloud-recording/restful/user-guides/mix-mode/set-composite)。 |

### 请求 Header

| Header 名称 | 值 |
| --- | --- |
| `Content-Type` | `application/json` |

### 请求 Body

```json
{
  "cname": "<your_channel_name>",
  "uid": "<unique_user_id>",
  "clientRequest": {
    "maxResolutionUid": "<uid_of_large_window>",
    "mixedVideoLayout": 3,
    "backgroundColor": "#FF0000",
    "backgroundImage": "<url_of_background_image>",
    "defaultUserBackgroundImage": "<url_of_default_background_image>",
    "layoutConfig": [
      {
        "uid": "<uid1_to_be_mixed>",
        "x_axis": 0.1,
        "y_axis": 0.1,
        "width": 0.1,
        "height": 0.1,
        "alpha": 1,
        "render_mode": 1
      }
    ],
    "backgroundConfig": [
      {
        "uid": "<uid_of_user>",
        "image_url": "<url_of_background_image>",
        "render_mode": 0
      }
    ]
  }
}
```

#### clientRequest 参数说明

| 参数名 | 类型 | 必填 | 默认值 | 说明 |
| --- | --- | --- | --- | --- |
| `maxResolutionUid` | string | 否 | - | 仅需在**垂直布局**下设置。指定显示大视窗画面的用户 UID。字符串内容的整型取值范围 1 到 (2^32-1)，且不可设置为 0。 |
| `mixedVideoLayout` | number | 否 | `0` | 视频合流布局：<br>- `0`：悬浮布局。第一个加入频道的用户在屏幕上会显示为大视窗，铺满整个画布，其他用户的视频画面会显示为小视窗，从下到上水平排列，最多 4 行，每行 4 个画面，最多支持共 17 个画面。<br>- `1`：自适应布局。根据用户的数量自动调整每个画面的大小，每个用户的画面大小一致，最多支持 17 个画面。<br>- `2`：垂直布局。指定 `maxResolutionUid` 在屏幕左侧显示大视窗画面，其他用户的小视窗画面在右侧垂直排列，最多两列，一列 8 个画面，最多支持共 17 个画面。<br>- `3`：自定义布局。由你在 `layoutConfig` 字段中自定义合流布局。 |
| `backgroundColor` | string | 否 | `"#000000"` | 视频画布的背景颜色。支持 RGB 颜色表，字符串格式为 # 号和 6 个十六进制数。默认值 `"#000000"`，代表黑色。 |
| `backgroundImage` | string | 否 | - | 视频画布的背景图的 URL。背景图的显示模式为裁剪模式。<br>裁剪模式：优先保证画面被填满。背景图尺寸等比缩放，直至整个画面被背景图填满。如果背景图长宽与显示窗口不同，则背景图会按照画面设置的比例进行周边裁剪后填满画面。 |
| `defaultUserBackgroundImage` | string | 否 | - | 默认的用户画面背景图的 URL。<br>配置该字段后，当任一用户停止发送视频流超过 3.5 秒，画面将切换为该背景图；如果针对某 UID 单独设置了背景图，则该设置会被覆盖。 |
| `layoutConfig` | array | 否 | - | 用户的合流画面布局。由每个用户对应的布局画面设置组成的数组，支持最多 17 个用户。<br>注意：仅需在**自定义布局**下设置。 |
| `backgroundConfig` | array | 否 | - | 用户的背景图设置。 |

##### layoutConfig 数组元素说明

| 参数名 | 类型 | 必填 | 最小值 | 最大值 | 说明 |
| --- | --- | --- | --- | --- | --- |
| `uid` | string | 否 | - | - | 字符串内容为待显示在该区域的用户的 UID，32 位无符号整数。<br>如果不指定 UID，会按照用户加入频道的顺序自动匹配 `layoutConfig` 中的画面设置。 |
| `x_axis` | number | 是 | `0` | `1` | 屏幕里该画面左上角的横坐标的相对值，精确到小数点后六位。从左到右布局，`0.0` 在最左端，`1.0` 在最右端。该字段也可以设置为整数 0 或 1。 |
| `y_axis` | number | 是 | `0` | `1` | 屏幕里该画面左上角的纵坐标的相对值，精确到小数点后六位。从上到下布局，`0.0` 在最上端，`1.0` 在最下端。该字段也可以设置为整数 0 或 1。 |
| `width` | number | 是 | `0` | `1` | 该画面宽度的相对值，精确到小数点后六位。该字段也可以设置为整数 0 或 1。 |
| `height` | number | 是 | `0` | `1` | 该画面高度的相对值，精确到小数点后六位。该字段也可以设置为整数 0 或 1。 |
| `alpha` | number | 否 | `0` | `1` | 图像的透明度。精确到小数点后六位。`0.0` 表示图像为透明的，`1.0` 表示图像为完全不透明的。 |
| `render_mode` | number | 否 | - | - | 画面显示模式：<br>- `0`：裁剪模式。优先保证画面被填满。视频尺寸等比缩放，直至整个画面被视频填满。如果视频长宽与显示窗口不同，则视频流会按照画面设置的比例进行周边裁剪后填满画面。<br>- `1`：缩放模式。优先保证视频内容全部显示。视频尺寸等比缩放，直至视频窗口的一边与画面边框对齐。如果视频尺寸与画面尺寸不一致，在保持长宽比的前提下，将视频进行缩放后填满画面，缩放后的视频四周会有一圈黑边。 |

##### backgroundConfig 数组元素说明

| 参数名 | 类型 | 必填 | 说明 |
| --- | --- | --- | --- |
| `uid` | string | 是 | 字符串内容为用户 UID。 |
| `image_url` | string | 是 | 该用户的背景图 URL。配置背景图后，当该用户停止发送视频流超过 3.5 秒，画面将切换为该背景图。<br>URL 支持 HTTP 和 HTTPS 协议，图片格式支持 JPG 和 BMP。图片大小不得超过 6 MB。录制服务成功下载图片后，设置才会生效；如果下载失败，则设置不生效。不同字段设置可能会互相覆盖，具体规则详见[设置背景色或背景图](https://doc.shengwang.cn/doc/cloud-recording/restful/user-guides/mix-mode/set-composite-layout#%E8%BF%9B%E9%98%B6%E8%AE%BE%E7%BD%AE%E8%83%8C%E6%99%AF%E8%89%B2%E6%88%96%E8%83%8C%E6%99%AF%E5%9B%BE)。 |
| `render_mode` | number | 否 | 画面显示模式：<br>- `0`：裁剪模式。优先保证画面被填满。视频尺寸等比缩放，直至整个画面被视频填满。如果视频长宽与显示窗口不同，则视频流会按照画面设置的比例进行周边裁剪后填满画面。<br>- `1`：缩放模式。优先保证视频内容全部显示。视频尺寸等比缩放，直至视频窗口的一边与画面边框对齐。如果视频尺寸与画面尺寸不一致，在保持长宽比的前提下，将视频进行缩放后填满画面，缩放后的视频四周会有一圈黑边。 |

## 响应

如果返回的 HTTP 状态码为 `200`，表示请求成功。

### 响应 Body

```json
{
  "cname": "录制的频道名。",
  "uid": "字符串内容为云端录制服务在 RTC 频道内使用的 UID，用于标识频道内的录制服务。",
  "resourceId": "云端录制使用的 Resource ID。",
  "sid": "录制 ID。标识一次录制周期。"
}
```

## 请求示例

```bash
curl --request POST \
  --url https://api.sd-rtn.com/v1/apps/:appid/cloud_recording/resourceid/:resourceid/sid/:sid/mode/:mode/updateLayout \
  --header 'Authorization: Basic <credentials>' \
  --header 'Content-Type: <string>' \
  --data '{
  "cname": "<your_channel_name>",
  "uid": "<unique_user_id>",
  "clientRequest": {
    "mixedVideoLayout": 3,
    "backgroundColor": "#FF0000",
    "layoutConfig": [
      {
        "uid": "<uid1_to_be_mixed>",
        "x_axis": 0.1,
        "y_axis": 0.1,
        "width": 0.1,
        "height": 0.1,
        "alpha": 1,
        "render_mode": 1
      }
    ]
  }
}'
```

## 响应示例

```json
{
  "cname": "录制的频道名。",
  "uid": "字符串内容为云端录制服务在 RTC 频道内使用的 UID，用于标识频道内的录制服务。",
  "resourceId": "云端录制使用的 Resource ID。",
  "sid": "录制 ID。标识一次录制周期。"
}
```