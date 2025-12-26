# 获取云端录制资源

## 获取方法

**post**  
https://api.sd-rtn.com/v1/apps/{appid}/cloud_recording/acquire

**acquire** 方法：获取云端录制资源。

在开始云端录制之前，你需要调用 **acquire** 方法获取一个 Resource ID。一个 Resource ID 只能用于一次云端录制服务。

> **注意**：`acquire` 和 `start` 的请求需配对调用。
> - 在每次 `acquire` 请求获取到 Resource ID 后的 2 秒内立即发起对应的 `start` 请求。
> - 批量获取 Resource ID 后进行批量 `start` 请求可能导致请求失败。如请求失败，请再次调用 `acquire` 获得一个新的 Resource ID，并用该 Resource ID 再次调用 `start` 方法。

## 请求

### Basic Auth

声网云端录制 RESTful API 仅支持 HTTPS 协议。发送请求时，你需要使用声网提供的客户 ID 和客户密钥生成 Base64 编码凭证，并填入请求头部的 `Authorization` 字段中。详见[实现 HTTP 基本认证](https://doc.shengwang.cn/doc/cloud-recording/restful/user-guides/http-basic-auth)。

### 路径参数

| 参数 | 类型 | 必填 | 说明 |
|------|------|------|------|
| `appid` | string | 是 | 你的项目使用的 App ID |

> **注意**：对于页面录制模式，只需输入开通了云端录制服务的 App ID。<br>
> 对于单流和合流录制模式，必须使用和待录制的频道相同的 App ID，且该 App ID 需要开通云端录制服务。

### 请求 Header

| 参数 | 类型 | 说明 |
|------|------|------|
| `Content-Type` | string | `application/json` |

### 请求 Body

| 参数 | 类型 | 必填 | 说明 |
|------|------|------|------|
| `cname` | string | 是 | 频道名：<br>- 对于单流录制和合流录制模式，该字段用于设置待录制的频道名。<br>- 对于页面录制模式，该字段用于区分录制进程。字符串长度不得超过 128 字节。<br>**注意**：通过 `appid`、`cname` 和 `uid` 可以定位一个唯一的录制实例。因此，如果你想针对同一个频道进行多次录制，可以使用相同的 `appId` 和 `cname`，以及不同的 `uid` 来进行管理和区分。 |
| `uid` | string | 是 | 字符串内容为云端录制服务在频道内使用的 UID，用于标识频道内的录制服务，例如 `"527841"`。字符串内容需满足以下条件：<br>- 取值范围 1 到 (2^32-1)，不可设置为 `0`。<br>- 不能与当前频道内的任何 UID 重复。<br>- 字段引号内为整型 UID，且频道内所有用户均使用整型 UID。 |
| `clientRequest` | object | 是 | 用于设置录制参数的配置项，详见下表 |

#### clientRequest 配置项

| 参数 | 类型 | 默认 | 说明 |
|------|------|------|------|
| `scene` | number | 0 | 云端录制资源使用场景：<br>- `0`：单流录制、合流录制<br>- `1`：页面录制 |
| `resourceExpiredHour` | number | 72 | 云端录制 RESTful API 的调用时效。从成功开启云端录制并获得 `sid`（录制 ID）后开始计算。单位为小时。<br>**注意**：超时后，你将无法调用 `query`、`update`、`updateLayout` 和 `stop` 方法。 |
| `excludeResourceIds` | array[string] | - | 另一路或几路录制任务的 `resourceId`。该字段用于排除指定的录制资源，以便新发起的录制任务可以使用新区域的资源，实现跨区域多路录制。详见[多路任务保障](https://doc.shengwang.cn/doc/cloud-recording/restful/best-practices/rest-availability#%E5%A4%9A%E8%B7%AF%E4%BB%BB%E5%8A%A1%E4%BF%9D%E9%9A%9C) |
| `regionAffinity` | number | 0 | 指定使用某个区域的资源进行录制。支持取值如下：<br>- `0`：根据发起请求的区域就近调用资源。<br>- `1`：中国。<br>- `2`：东南亚。<br>- `3`：欧洲。<br>- `4`：北美<br>**注意**：为加速录制文件上传，当你使用的云存储区域和你发起请求的区域不同时，建议你将该字段设为云存储区域。 |
| `startParameter` | object | - | 用于设置录制参数的配置项，详见下表 |

#### startParameter 配置项

| 参数 | 类型 | 说明 |
|------|------|------|
| `token` | string | 用于鉴权的动态密钥（Token）。如果你的项目已启用 App 证书，则务必在该字段中传入你项目的动态密钥。详见[使用 Token 鉴权](https://doc.shengwang.cn/doc/rtc/android/basic-features/token-authentication)。<br>**注意**：<br>- 仅需在**单流录制**和**合流录制**模式下设置。<br>- 云端录制服务暂不支持更新 Token。为保证录制正常，请确保 Token 有效时长大于你预计的录制时长，以避免 Token 过期导致录制任务退出频道而结束录制。 |
| `storageConfig` | object | - | 第三方云存储的配置项，详见下表 |

#### storageConfig 配置项

| 参数 | 类型 | 必填 | 说明 |
|------|------|------|------|
| `vendor` | number | 是 | 第三方云存储平台：<br>- `1`：Amazon S3<br>- `2`：阿里云<br>- `3`：腾讯云<br>- `5`：Microsoft Azure<br>- `6`：Google Cloud<br>- `7`：华为云<br>- `8`：百度智能云<br>- `11`：其他 S3 协议云存储（如 Digital Ocean、MinIO 及部分自建云存储） |
| `region` | number | 是 | 第三方云存储指定的地区信息。<br>**注意**：为确保录制文件上传的成功率和实时性，第三方云存储的 `region` 与你发起请求的应用服务器必须在同一个区域中。例如：你发起请求的 App 服务器在中国大陆地区，则第三方云存储需要设置为中国大陆区域内。详见[第三方存储地区说明](https://doc.shengwang.cn/doc/cloud-recording/restful/api/reference) |
| `bucket` | string | 是 | 第三方云存储的 Bucket。Bucket 名称需要符合对应第三方云存储服务的命名规则。 |
| `accessKey` | string | 是 | 第三方云存储的 Access Key（访问密钥） |
| `secretKey` | string | 是 | 第三方云存储的 Secret Key |
| `stsToken` | string | - | 第三方云存储的临时安全令牌。该令牌由云服务商的安全令牌服务 (Security Token Service, STS) 临时颁发，用于授予第三方云存储资源的有限访问权限。<br>**注意**：目前支持的云服务商仅包括，`1`：Amazon S3，`2`：阿里云，`3`：腾讯云。 |
| `stsExpiration` | number | - | 用于标记 `stsToken` 的过期时间戳，POSIX 时间，单位为秒。<br>**注意**：为避免时间戳溢出问题，建议使用 Uint64 存储。建议在申请 `stsToken` 时设置尽可能长的有效期，最短有效期需不少于 4 小时，并需要在过期前调用 `update` 方法更新 `stsToken` 的值 |
| `fileNamePrefix` | array[string] | - | 录制文件在第三方云存储中的存储位置，与录制文件名前缀有关。如果设为 `["directory1","directory2"]`，那么录制文件名前缀为 `directory1/directory2/`，即录制文件名为 `directory1/directory2/xxx.m3u8`。前缀长度（包括斜杠）不得超过 128 个字符。字符串中不得出现斜杠、下划线、括号等符号字符。以下为支持的字符集范围：<br>- 26 个小写英文字母 a~z<br>- 26 个大写英文字母 A~Z<br>- 10 个数字 0-9 |
| `extensionParams` | object | - | 第三方云存储服务会按照该字段设置对已上传的录制文件进行加密和打标签。 |

#### extensionParams 配置项

| 参数 | 类型 | 说明 |
|------|------|------|
| `sse` | string | - | 加密模式。设置该字段后，第三方云存储服务会按照该加密模式将已上传的录制文件进行加密。该字段仅适用于 Amazon S3：<br>- `kms`：KMS 加密<br>- `aes256`：AES256 加密 |
| `tag` | string | - | 标签内容。设置该字段后，第三方云存储服务会按照该标签内容将已上传的录制文件进行打标签操作。该字段仅适用于阿里云和 Amazon S3 |

### 附加参数

#### `recordingConfig` 配置项（仅单流/合流录制模式）

| 参数 | 类型 | 说明 |
|------|------|------|
| `channelType` | number | - | 频道场景：<br>- `0`：通信场景<br>- `1`：直播场景<br>**注意**：频道场景必须与声网 RTC SDK 的设置一致，否则可能导致问题。 |
| `decryptionMode` | number | - | 解密模式：<br>- `0`：不加密<br>- `1`：AES_128_XTS 加密模式<br>- `2`：AES_128_ECB 加密模式<br>- `3`：AES_256_XTS 加密模式<br>- `4`：SM4_128_ECB 加密模式<br>- `5`：AES_128_GCM 加密模式<br>- `6`：AES_256_GCM 加密模式<br>- `7`：AES_128_GCM2 加密模式<br>- `8`：AES_256_GCM2 加密模式 |
| `secret` | string | - | 与加解密相关的密钥。仅需在 `decryptionMode` 非 `0` 时设置。 |
| `salt` | string | - | 与加解密相关的盐。Base64 编码、32 位字节。仅需在 `decryptionMode` 为 `7` 或 `8` 时设置。 |
| `maxIdleTime` | number | 30 | 最大频道空闲时间。单位为秒。最大值不超过 30 天。超出最大频道空闲时间后，录制服务会自动退出。录制服务退出后，如果你再次发起 `start` 请求，会产生新的录制文件。<br>**注意**：频道空闲：直播频道内无任何主播，或通信频道内无任何用户。 |
| `streamTypes` | number | 2 | 订阅的媒体流类型：<br>- `0`：仅订阅音频<br>- `1`：仅订阅视频<br>- `2`：订阅音频和视频 |
| `videoStreamType` | number | 0 | 设置订阅的视频流类型。如果你在 SDK 客户端开启了双流模式，你可以选择订阅视频大流或者小流：<br>- `0`：视频大流，即高分辨率高码率的视频流<br>- `1`：视频小流，即低分辨率低码率的视频流 |
| `subscribeAudioUids` | array[string] | - | 指定订阅哪几个 UID 的音频流。如需订阅全部 UID 的音频流，则无需设置该字段。数组长度不得超过 32，不推荐使用空数组。该字段和 `unSubscribeAudioUids` 只能设一个。 |
| `unSubscribeAudioUids` | array[string] | - | 指定不订阅哪几个 UID 的音频流。云端录制会订阅频道内除指定 UID 外所有 UID 的音频流。数组长度不得超过 32，不推荐使用空数组。该字段和 `subscribeAudioUids` 只能设一个。 |
| `subscribeVideoUids` | array[string] | - | 指定订阅哪几个 UID 的视频流。如需订阅全部 UID 的视频流，则无需设置该字段。数组长度不得超过 32，不推荐使用空数组。该字段和 `unSubscribeVideoUids` 只能设一个。 |
| `unSubscribeVideoUids` | array[string] | - | 指定不订阅哪几个 UID 的视频流。云端录制会订阅频道内除指定 UID 外所有 UID 的视频流。数组长度不得超过 32，不推荐使用空数组。该字段和 `subscribeVideoUids` 只能设一个。 |
| `subscribeUidGroup` | number | 0 | 预估的订阅人数峰值：<br>- `0`：1 到 2 个 UID<br>- `1`：3 到 7 个 UID<br>- `2`：8 到 12 个 UID<br>- `3`：13 到 17 个 UID<br>- `4`：18 到 32 个 UID<br>- `5`：33 到 49 个 UID |
| `streamMode` | string | `default` | 媒体流的输出模式：<br>- `"default"`：默认模式。录制过程中音频转码，分别生成 M3U8 音频索引文件和视频索引文件。<br>- `"standard"`：标准模式。声网推荐使用该模式。录制过程中音频转码，分别生成 M3U8 音频索引文件、视频索引文件和合并的音视频索引文件。如果在 Web 端使用 VP8 编码，则生成一个合并的 MPD 音视频索引文件。<br>- `"original"`：原始编码模式。适用于[单流音频不转码录制](https://doc.shengwang.cn/doc/cloud-recording/restful/user-guides/individual-mode/set-individual-nontranscoding)。仅订阅音频时（`streamTypes` 为 0）时该字段生效，录制过程中音频不转码，生成 M3U8 音频索引文件。 |
| `audioProfile` | number | 0 | 设置输出音频的采样率、码率、编码模式和声道数：<br>- `0`：48 kHz 采样率，音乐编码，单声道，编码码率约 48 Kbps。<br>- `1`：48 kHz 采样率，音乐编码，单声道，编码码率约 128 Kbps。<br>- `2`：48 kHz 采样率，音乐编码，双声道，编码码率约 192 Kbps。 |
| `transcodingConfig` | object | - | 转码输出的视频配置项。取值可参考[设置录制输出视频的分辨率](https://doc.shengwang.cn/doc/cloud-recording/restful/api/reference) |

## 请求示例

```bash
curl --request POST \
  --url https://api.sd-rtn.com/v1/apps/:appid/cloud_recording/acquire \
  --header 'Authorization: Basic <credentials>' \
  --header 'Content-Type: <string>' \
  --data '{
    "cname": "<your_channel_name>",
    "uid": "<unique_user_id>",
    "clientRequest": {
      "scene": 0,
      "resourceExpiredHour": 24
    }
  }'
```

## 响应

### 响应 Body

```json
{
  "cname": "录制的频道名。",
  "uid": "字符串内容为云端录制服务在 RTC 频道内使用的 UID，用于标识频道内的录制服务。",
  "resourceId": "云端录制资源 Resource ID。使用这个 Resource ID 可以开始一段云端录制。这个 Resource ID 的有效期为 5 分钟，超时需要重新请求。"
}
```