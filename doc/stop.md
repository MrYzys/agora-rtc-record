# 停止云端录制

POST https://api.sd-rtn.com/v1/apps/{appid}/cloud_recording/resourceid/{resourceid}/sid/{sid}/mode/{mode}/stop

`stop` 方法：停止云端录制。

开始录制后，你可以调用 `stop` 方法离开频道，停止录制。录制停止后如需再次录制，必须再调用 `acquire` 方法请求一个新的 Resource ID。

> **注意**
>
> - `stop` 请求仅在会话内有效。如果录制启动错误，或录制已结束，调用 `stop` 将返回 `404`。
> - 非页面录制模式下，当频道空闲（无用户）超过预设时间（默认为 30 秒） 后，云端录制也会自动退出频道，停止录制。
> - 录制结束后，正常情况下录制文件会在 20 分钟内上传至第三方云存储，但在网络异常等特殊情况下上传时间会达到 24 小时以上。

## 请求

### Basic Auth

声网云端录制 RESTful API 仅支持 HTTPS 协议。发送请求时，你需要使用声网提供的客户 ID 和客户密钥生成 Base64 编码凭证，并填入请求头部的 `Authorization` 字段中。详见[实现 HTTP 基本认证](https://doc.shengwang.cn/doc/cloud-recording/restful/user-guides/http-basic-auth)。

### 路径参数

| 参数名 | 类型 | 必填 | 描述 |
| --- | --- | --- | --- |
| appid | string | 是 | 你的项目使用的 [App ID](http://doc.shengwang.cn/doc/cloud-recording/restful/get-started/enable-service#%E8%8E%B7%E5%8F%96-app-id)。对于页面录制模式，只需输入开通了云端录制服务的 App ID。对于单流和合流录制模式，必须使用和待录制的频道相同的 App ID，且该 App ID 需要开通云端录制服务。 |
| resourceid | string | 是 | 通过 [`acquire`](/doc/cloud-recording/restful/cloud-recording/operations/post-v1-apps-appid-cloud_recording-acquire) 请求获取到的 Resource ID。 |
| sid | string | 是 | 通过 [`start`](/doc/cloud-recording/restful/cloud-recording/operations/post-v1-apps-appid-cloud_recording-resourceid-resourceid-mode-mode-start) 获取的录制 ID。 |
| mode | string | 是 | 录制模式：<br>- `individual`：[单流录制模式](https://doc.shengwang.cn/doc/cloud-recording/restful/user-guides/individual-mode/set-individual)。<br>- `mix`：[合流录制模式](https://doc.shengwang.cn/doc/cloud-recording/restful/user-guides/mix-mode/set-composite)。<br>- `web`：[页面录制模式](https://doc.shengwang.cn/doc/cloud-recording/restful/user-guides/web-mode/set-webpage-recording)。 |

### 请求 Header

| 参数名 | 类型 | 描述 |
| --- | --- | --- |
| Content-Type | string | `application/json`。 |

### 请求 Body

```json
{
  "cname": "<your_channel_name>",
  "uid": "<unique_user_id>",
  "clientRequest": {
    "async_stop": false
  }
}
```

| 参数名 | 类型 | 必填 | 描述 |
| --- | --- | --- | --- |
| cname | string | 是 | 录制服务所在频道的名称。需要和你在 [`acquire`](/doc/cloud-recording/restful/cloud-recording/operations/post-v1-apps-appid-cloud_recording-acquire) 请求中输入的 `cname` 相同。 |
| uid | string | 是 | 字符串内容为录制服务在 RTC 频道内使用的 UID，用于标识该录制服务，需要和你在 [`acquire`](/doc/cloud-recording/restful/cloud-recording/operations/post-v1-apps-appid-cloud_recording-acquire) 请求中输入的 `uid` 相同。 |
| clientRequest | object | 是 | 客户端请求参数对象。<br>#### async_stop<br>类型：boolean<br>可选<br>默认值: `false`<br>设置 `stop` 方法的响应机制：<br>- `true`：异步。调用 `stop` 后立即收到响应。<br>- `false`：同步。调用 `stop` 后，你需等待所有录制文件上传至第三方云存储方后会收到响应。声网预期上传时间不超过 20 秒，如果上传超时，你会收到错误码 `206`。 |

## 响应

### 状态码

- 200：请求成功。
- 非 200：失败。

### 响应 Body

```json
{
  "resourceId": "云端录制使用的 Resource ID。",
  "sid": "录制 ID。标识一次录制周期。",
  "serverResponse": {
    "extensionServiceState": [
      {
        "playload": {
          "uploadingStatus": "当前录制上传的状态：\n- `\"uploaded\"`：本次录制的文件已经全部上传至指定的第三方云存储。\n- `\"backuped\"`：本次录制的文件已经全部上传完成，但是至少有一个 TS 文件上传到了声网备份云。声网服务器会自动将这部分文件继续上传至指定的第三方云存储。\n- `\"unknown\"`：未知状态。"
        },
        "serviceName": "服务类型：\n- `\"upload_service\"`：上传服务。\n- `\"web_recorder_service\"`：页面录制服务。"
      }
    ]
  },
  "cname": "录制的频道名。",
  "uid": "字符串内容为云端录制服务在 RTC 频道内使用的 UID，用于标识频道内的录制服务。"
}
```

| 参数名 | 类型 | 描述 |
| --- | --- | --- |
| resourceId | string | 云端录制使用的 Resource ID。 |
| sid | string | 录制 ID。标识一次录制周期。 |
| serverResponse | object | 服务器响应对象。<br>#### extensionServiceState<br>类型：array<br>页面录制场景下会返回的字段。<br>##### playload<br>类型：object<br>页面录制模式下，上传服务返回的字段。<br>###### uploadingStatus<br>类型：string<br>当前录制上传的状态：<br>- `"uploaded"`：本次录制的文件已经全部上传至指定的第三方云存储。<br>- `"backuped"`：本次录制的文件已经全部上传完成，但是至少有一个 TS 文件上传到了声网备份云。声网服务器会自动将这部分文件继续上传至指定的第三方云存储。<br>- `"unknown"`：未知状态。<br>###### serviceName<br>类型：string<br>服务类型：<br>- `"upload_service"`：上传服务。<br>- `"web_recorder_service"`：页面录制服务。 |
| cname | string | 录制的频道名。 |
| uid | string | 字符串内容为云端录制服务在 RTC 频道内使用的 UID，用于标识频道内的录制服务。 |

## 请求示例

```bash
curl --request POST \
  --url https://api.sd-rtn.com/v1/apps/:appid/cloud_recording/resourceid/:resourceid/sid/:sid/mode/:mode/stop \
  --header 'Authorization: Basic <credentials>' \
  --header 'Content-Type: <string>' \
  --data '{
  "cname": "<your_channel_name>",
  "uid": "<unique_user_id>",
  "clientRequest": {
    "async_stop": false
  }
}'
```

## 响应示例

```json
{
  "resourceId": "云端录制使用的 Resource ID。",
  "sid": "录制 ID。标识一次录制周期。",
  "serverResponse": {
    "extensionServiceState": [
      {
        "playload": {
          "uploadingStatus": "当前录制上传的状态：\n- `\"uploaded\"`：本次录制的文件已经全部上传至指定的第三方云存储。\n- `\"backuped\"`：本次录制的文件已经全部上传完成，但是至少有一个 TS 文件上传到了声网备份云。声网服务器会自动将这部分文件继续上传至指定的第三方云存储。\n- `\"unknown\"`：未知状态。"
        },
        "serviceName": "服务类型：\n- `\"upload_service\"`：上传服务。\n- `\"web_recorder_service\"`：页面录制服务。"
      }
    ]
  },
  "cname": "录制的频道名。",
  "uid": "字符串内容为云端录制服务在 RTC 频道内使用的 UID，用于标识频道内的录制服务。"
}
```