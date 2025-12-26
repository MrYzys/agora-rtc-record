# 更新云端录制设置

POST https://api.sd-rtn.com/v1/apps/{appid}/cloud_recording/resourceid/{resourceid}/sid/{sid}/mode/{mode}/update

`update` 方法：更新云端录制设置。

开始录制后，你可以调用 `update` 方法更新如下录制配置：

- 对单流录制和合流录制，更新订阅名单。
- 对页面录制，设置暂停/恢复页面录制，或更新页面录制转推到 CDN 的推流地址（URL）。

> **注意**
> - `update` 请求仅在会话内有效。如果录制启动错误，或录制已结束，调用 `update` 将返回 `404`。
> - 如果需要连续调用 `update` 方法更新录制设置，请在收到上一次 `update` 响应后再进行调用，否则可能导致请求结果与预期不一致。

## 请求

### Basic Auth

声网云端录制 RESTful API 仅支持 HTTPS 协议。发送请求时，你需要使用声网提供的客户 ID 和客户密钥生成 Base64 编码凭证，并填入请求头部的 `Authorization` 字段中。详见[实现 HTTP 基本认证](https://doc.shengwang.cn/doc/cloud-recording/restful/user-guides/http-basic-auth)。

### 路径参数

| 参数名 | 类型 | 必填 | 说明 |
| --- | --- | --- | --- |
| appid | string | 是 | 你的项目使用的 [App ID](http://doc.shengwang.cn/doc/cloud-recording/restful/get-started/enable-service#%E8%8E%B7%E5%8F%96-app-id)。对于页面录制模式，只需输入开通了云端录制服务的 App ID。对于单流和合流录制模式，必须使用和待录制的频道相同的 App ID，且该 App ID 需要开通云端录制服务。 |
| resourceid | string | 是 | 通过 [`acquire`](/doc/cloud-recording/restful/cloud-recording/operations/post-v1-apps-appid-cloud_recording-acquire) 请求获取到的 Resource ID。 |
| sid | string | 是 | 通过 [`start`](/doc/cloud-recording/restful/cloud-recording/operations/post-v1-apps-appid-cloud_recording-resourceid-resourceid-mode-mode-start) 获取的录制 ID。 |
| mode | string | 是 | 录制模式：<br>- `individual`：[单流录制模式](https://doc.shengwang.cn/doc/cloud-recording/restful/user-guides/individual-mode/set-individual)。<br>- `mix`：[合流录制模式](https://doc.shengwang.cn/doc/cloud-recording/restful/user-guides/mix-mode/set-composite)。<br>- `web`：[页面录制模式](https://doc.shengwang.cn/doc/cloud-recording/restful/user-guides/web-mode/set-webpage-recording)。 |

### 请求 Header

| 参数名 | 类型 | 说明 |
| --- | --- | --- |
| Content-Type | string | `application/json`。 |

### 请求 Body

#### clientRequest (object)

##### streamSubscribe (object) - 仅在单流录制和合流录制模式下设置

**注意：** 仅需在**单流录制**和**合流录制**模式下设置。

###### audioUidList (object)

音频订阅名单。

**注意：** 该字段仅适用于 **streamTypes** 设为音频，或音频和视频的情况。

###### subscribeAudioUids (array[string]) - 仅在 streamTypes 为音频或音频和视频时适用

指定订阅哪几个 UID 的音频流。如需订阅全部 UID 的音频流，则无需设置该字段。数组长度不得超过 32，不推荐使用空数组。该字段和 `unSubscribeAudioUids` 只能设一个。详见[设置订阅名单](https://doc.shengwang.cn/doc/cloud-recording/restful/api/reference#%E8%AE%A2%E9%98%85%E5%90%8D%E5%8D%95%E8%AE%BE%E7%BD%AE)。

**注意：**
- 该字段仅适用于 **streamTypes** 设为音频，或音频和视频的情况。
- 如果你设置了音频的订阅名单，但没有设置视频的订阅名单，云端录制服务不会订阅任何视频流。反之亦然。
- 设为 `["#allstream#"]` 可订阅频道内所有 UID 的音频流。

###### unSubscribeAudioUids (array[string])

指定不订阅哪几个 UID 的音频流。云端录制会订阅频道内除指定 UID 外所有 UID 的音频流。数组长度不得超过 32，不推荐使用空数组。该字段和 `subscribeAudioUids` 只能设一个。详见[设置订阅名单](https://doc.shengwang.cn/doc/cloud-recording/restful/api/reference#%E8%AE%A2%E9%98%85%E5%90%8D%E5%8D%95%E8%AE%BE%E7%BD%AE)。

###### videoUidList (object)

视频订阅名单。

**注意：** 该字段仅适用于 **streamTypes** 设为视频，或音频和视频的情况。

###### subscribeVideoUids (array[string]) - 仅在 streamTypes 为视频或音频和视频时适用

指定订阅哪几个 UID 的视频流。如需订阅全部 UID 的视频流，则无需设置该字段。数组长度不得超过 32，不推荐使用空数组。该字段和 `unSubscribeVideoUids` 只能设一个。详见[设置订阅名单](https://doc.shengwang.cn/doc/cloud-recording/restful/api/reference#%E8%AE%A2%E9%98%85%E5%90%8D%E5%8D%95%E8%AE%BE%E7%BD%AE)。

**注意：**
- 该字段仅适用于 **streamTypes** 设为视频，或音频和视频的情况。
- 如果你设置了视频的订阅名单，但没有设置音频的订阅名单，云端录制服务不会订阅任何音频流。反之亦然。
- 设为 `["#allstream#"]` 可订阅频道内所有 UID 的视频流。

###### unSubscribeVideoUids (array[string])

指定不订阅哪几个 UID 的视频流。云端录制会订阅频道内除指定 UID 外所有 UID 的视频流。数组长度不得超过 32，不推荐使用空数组。该字段和 `subscribeVideoUids` 只能设一个。详见[设置订阅名单](https://doc.shengwang.cn/doc/cloud-recording/restful/api/reference#%E8%AE%A2%E9%98%85%E5%90%8D%E5%8D%95%E8%AE%BE%E7%BD%AE)。

##### webRecordingConfig (object) - 仅在页面录制模式下设置

用于更新页面录制配置项。

**注意：** 仅需在**页面录制**模式下设置。

###### onhold (boolean) - 可选

设置是否暂停页面录制：

- `true`：暂停页面录制，并暂停生成页面录制文件。
- `false`：继续页面录制，并继续生成页面录制文件。
  如果想恢复已暂停的页面录制，你可以调用 `update ` 方法并将 `onhold` 设为 `false`。

##### rtmpPublishConfig (object) - 仅在页面录制模式下设置

用于更新转推页面录制到 CDN 的配置项。

**注意：** 仅需在**页面录制**模式下，且**转推页面录制到 CDN** 时设置。

###### outputs (array) - 可选

数组项为对象，包含以下字段：

- rtmpUrl (string) - 可选

CDN 推流 URL。

**注意：**
- URL 仅支持 RTMP 和 RTMPS 协议。
- 支持的最大转推 CDN 路数为 1。

##### storageConfig (object) - 可选

更新第三方云存储的配置项。

###### vendor (number)

第三方云存储平台。

- `1`：[Amazon S3](https://aws.amazon.com/cn/s3/?c=s&sec=srv)
- `2`：[阿里云](https://www.aliyun.com/product/oss)
- `3`：[腾讯云](https://cloud.tencent.com/product/cos)
- `5`：[Microsoft Azure](https://azure.microsoft.com/zh-cn/products/storage/blobs/)
- `6`：[谷歌云](https://cloud.google.com/storage)
- `7`：[华为云](https://www.huaweicloud.com/product/obs.html)
- `8`：[百度智能云](https://cloud.baidu.com/product/bos.html)
- `11`：其他 S3 协议云存储（如 Digital Ocean、MinIO 及部分自建云存储）。你需要在 `extensionParams.endpoint` 字段中指定 S3 协议云存储的域名。

###### region (number)

第三方云存储指定的地区信息。
**注意：** 为确保录制文件上传的成功率和实时性，第三方云存储的 `region` 与你发起请求的应用服务器必须在同一个区域中。例如：你发起请求的 App 服务器在中国大陆地区，则第三方云存储需要设置为中国大陆区域内。详见[第三方存储地区说明](https://doc.shengwang.cn/doc/cloud-recording/restful/api/reference)。

###### bucket (string)

第三方云存储的 Bucket。Bucket 名称需要符合对应第三方云存储服务的命名规则。

###### accessKey (string)

第三方云存储的 Access Key（访问密钥）。

###### secretKey (string)

第三方云存储的 Secret Key。

###### stsToken (string) - 可选

第三方云存储的临时安全令牌。该令牌由云服务商的安全令牌服务 (Security Token Service, STS) 临时颁发，用于授予第三方云存储资源的有限访问权限。

> **注意**
> - 目前支持的云服务商仅包括，`1`：Amazon S3，`2`：阿里云，`3`：腾讯云。

###### stsExpiration (number) - 可选

用于标记 `stsToken` 的过期时间戳，POSIX 时间，单位为秒。

> **注意**
> - 为避免时间戳溢出问题，建议使用 Uint64 存储。
> - 建议在申请 `stsToken` 时设置尽可能长的有效期，最短有效期需不少于 4 小时，并需要在过期前调用 `update` 方法更新 `stsToken` 的值。
> - 如录制任务持续时间超过 1 小时，需每 60 分钟重新申请 `stsToken`，并调用 `update` 更新 `storageConfig` 中的相关参数。

###### fileNamePrefix (array[string]) - 可选

录制文件在第三方云存储中的存储位置，与录制文件名前缀有关。如果设为 `["directory1","directory2"]`，那么录制文件名前缀为 `"directory1/directory2/"`，即录制文件名为 `directory1/directory2/xxx.m3u8`。前缀长度（包括斜杠）不得超过 128 个字符。字符串中不得出现斜杠、下划线、括号等符号字符。以下为支持的字符集范围：
- 26 个小写英文字母 a~z
- 26 个大写英文字母 A~Z
- 10 个数字 0-9

###### extensionParams (object) - 可选

第三方云存储服务会按照该字段设置对已上传的录制文件进行加密和打标签。

####### sse (string) - 可选

加密模式。设置该字段后，第三方云存储服务会按照该加密模式将已上传的录制文件进行加密。该字段仅适用于 Amazon S3，详见 [Amazon S3 官方文档](https://docs.aws.amazon.com/zh_cn/AmazonS3/latest/userguide/UsingEncryption.html)。

- `kms`：KMS 加密。
- `aes256`：AES256 加密。

####### tag (string) - 可选

标签内容。设置该字段后，第三方云存储服务会按照该标签内容将已上传的录制文件进行打标签操作。该字段仅适用于阿里云和 Amazon S3，详见[阿里云官方文档](https://help.aliyun.com/zh/oss/developer-reference/object-tagging-10?spm=a2c4g.11186623.help-menu-31815.d_5_3_2_2_26.7732131cSR2ojD)和 [Amazon S3 官方文档](https://docs.aws.amazon.com/zh_cn/AmazonS3/latest/userguide/object-tagging.html)。

####### endpoint (string) - 可选

S3 协议云存储的域名。当 `vendor` 设为 `11` 时，该字段为必填。

## 响应

如果返回的 HTTP 状态码为 `200`，表示请求成功。

### 响应 Body

| 参数名 | 类型 | 说明 |
| --- | --- | --- |
| cname | string | 录制的频道名。 |
| uid | string | 字符串内容为云端录制服务在 RTC 频道内使用的 UID，用于标识频道内的录制服务。 |
| resourceId | string | 云端录制使用的 Resource ID。 |
| sid | string | 录制 ID。标识每次录制周期。 |

## 请求示例

```bash
curl --request POST \
  --url https://api.sd-rtn.com/v1/apps/:appid/cloud_recording/resourceid/:resourceid/sid/:sid/mode/:mode/update \
  --header 'Authorization: Basic <credentials>' \
  --header 'Content-Type: <string>' \
  --data '{
  "cname": "<your_channel_name>",
  "uid": "<unique_user_id>",
  "clientRequest": {
    "streamSubscribe": {
      "audioUidList": {
        "subscribeAudioUids": [
          "#allstream#"
        ]
      },
      "videoUidList": {
        "unSubscribeVideoUids": [
          "<uid1_to_be_unsubscribed>",
          "<uid2_to_be_unsubscribed>"
        ]
      }
    }
  }
}'
```

## 响应示例

```json
{
  "cname": "录制的频道名。",
  "uid": "字符串内容为云端录制服务在 RTC 频道内使用的 UID，用于标识频道内的录制服务。",
  "resourceId": "云端录制使用的 Resource ID。",
  "sid": "录制 ID。标识每次录制周期。"
}
```