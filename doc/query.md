# 查询云端录制状态

POST https://api.sd-rtn.com/v1/apps/{appid}/cloud_recording/resourceid/{resourceid}/sid/{sid}/mode/{mode}/query

## 请求

### Basic Auth

声网云端录制 RESTful API 仅支持 HTTPS 协议。发送请求时，你需要使用声网提供的客户 ID 和客户密钥生成 Base64 编码凭证，并填入请求头部的 `Authorization` 字段中。详见[实现 HTTP 基本认证](https://doc.shengwang.cn/doc/cloud-recording/restful/user-guides/http-basic-auth)。

### 路径参数

| 参数名 | 类型 | 必填 | 说明 |
|--------|------|------|------|
| `appid` | string | 是 | 你的项目使用的 [App ID](http://doc.shengwang.cn/doc/cloud-recording/restful/get-started/enable-service#%E8%8E%B7%E5%8F%96-app-id)。对于页面录制模式，只需输入开通了云端录制服务的 App ID；对于单流和合流录制模式，必须使用和待录制的频道相同的 App ID，且该 App ID 需要开通云端录制服务。 |
| `resourceid` | string | 是 | 通过 [`acquire`](/doc/cloud-recording/restful/cloud-recording/operations/post-v1-apps-appid-cloud_recording-acquire) 请求获取到的 Resource ID。 |
| `sid` | string | 是 | 通过 [`start`](/doc/cloud-recording/restful/cloud-recording/operations/post-v1-apps-appid-cloud_recording-resourceid-resourceid-mode-mode-start) 获取的录制 ID。 |
| `mode` | string | 是 | 录制模式：\n- `individual`：[单流录制模式](https://doc.shengwang.cn/doc/cloud-recording/restful/user-guides/individual-mode/set-individual)。\n- `mix`：[合流录制模式](https://doc.shengwang.cn/doc/cloud-recording/restful/user-guides/mix-mode/set-composite)。\n- `web`：[页面录制模式](https://doc.shengwang.cn/doc/cloud-recording/restful/user-guides/web-mode/set-webpage-recording)。 |

### 请求 Header

| 参数名 | 类型 | 说明 |
|--------|------|------|
| `Content-Type` | string | `application/json`。 |

## 响应

如果返回的 HTTP 状态码为 `200`，表示请求成功。

### 响应 Body

响应内容类型：`application/json`

| 参数名 | 类型 | 说明 |
|--------|------|------|
| `resourceId` | string | 云端录制使用的 Resource ID。 |
| `sid` | string | 录制 ID。 |
| `serverResponse` | object | 服务器响应数据。\n\n- **页面录制**模式下会返回的字段。\n\n  - `status` (number)：当前云服务的状态：\n    - `0`：没有开始云服务。\n    - `1`：云服务初始化完成。\n    - `2`：云服务组件开始启动。\n    - `3`：云服务部分组件启动完成。\n    - `4`：云服务所有组件启动完成。\n    - `5`：云服务正在进行中。\n    - `6`：云服务收到停止请求。\n    - `7`：云服务所有组件均停止。\n    - `8`：云服务已退出。\n    - `20`：云服务异常退出。\n\n  - `extensionServiceState` (array)：扩展服务状态信息。\n    - `payload` (object)：页面录制时会返回如下字段。\n      - `fileList` (array)：录制文件列表。\n        - `filename` (string)：录制产生的 M3U8 文件和 MP4 文件的文件名。\n        - `sliceStartTime` (number)：该文件的录制开始时间，Unix 时间戳，单位为毫秒。\n      - `onhold` (boolean)：页面录制是否处于暂停状态：\n        - `true`：处于暂停状态。\n        - `false`：处于运行状态。\n      - `state` (string)：将订阅内容上传至扩展服务的状态：\n        - `"init"`：服务正在初始化。\n        - `"inProgress"`：服务启动完成，正在进行中。\n        - `"exit"`：服务退出。\n    - `serviceName` (string)：扩展服务的名称：\n      - `web_recorder_service`：代表扩展服务为**页面录制**。\n      - `rtmp_publish_service`：代表扩展服务为**转推页面录制到 CDN**。\n\n- `cname` (string)：录制的频道名。\n- `uid` (string)：字符串内容为云端录制服务在 RTC 频道内使用的 UID，用于标识频道内的录制服务。

## 请求示例

```bash
curl --request GET \
  --url https://api.sd-rtn.com/v1/apps/:appid/cloud_recording/resourceid/:resourceid/sid/:sid/mode/:mode/query \
  --header 'Authorization: Basic <credentials>' \
  --header 'Content-Type: <string>'
```

## 响应示例

```json
{
  "resourceId": "云端录制使用的 Resource ID。",
  "sid": "录制 ID。",
  "serverResponse": {
    "status": 0,
    "extensionServiceState": [
      {
        "payload": {
          "fileList": [
            {
              "filename": "录制产生的 M3U8 文件和 MP4 文件的文件名。",
              "sliceStartTime": 0
            }
          ],
          "onhold": true,
          "state": "将订阅内容上传至扩展服务的状态：\n- `\"init\"`：服务正在初始化。\n- `\"inProgress\"`：服务启动完成，正在进行中。\n- `\"exit\"`：服务退出。"
        },
        "serviceName": "扩展服务的名称：\n- `web_recorder_service`：代表扩展服务为**页面录制**。\n- `rtmp_publish_service`：代表扩展服务为**转推页面录制到 CDN**。"
      }
    ]
  }
}
```

## 注意

`query` 请求仅在会话内有效。如果录制启动错误，或录制已结束，调用 `query` 将返回 `404`。