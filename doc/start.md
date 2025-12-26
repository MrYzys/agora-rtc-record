# 开始云端录制

``` 
POST  https://api.sd-rtn.com/v1/apps/{appid}/cloud_recording/resourceid/{resourceid}/mode/{mode}/start
```

`start` 方法：开始云端录制。

通过 [`acquire`](/doc/cloud-recording/restful/cloud-recording/operations/post-v1-apps-appid-cloud_recording-acquire) 方法获取云端录制资源后，调用 `start` 方法开始云端录制。

> **注意**  
> 发起 `start` 请求后，你还需要依据[保障录制成功启动](https://doc.shengwang.cn/doc/cloud-recording/restful/best-practices/recording-status#%E4%BF%9D%E9%9A%9C%E5%BD%95%E5%88%B6%E6%88%90%E5%8A%9F%E5%90%AF%E5%8A%A8)检查录制服务是否已经成功启动。

## 请求

### Basic Auth

声网云端录制 RESTful API 仅支持 HTTPS 协议。发送请求时，你需要使用声网提供的客户 ID 和客户密钥生成 Base64 编码凭证，并填入请求头部的 `Authorization` 字段中。详见[实现 HTTP 基本认证](https://doc.shengwang.cn/doc/cloud-recording/restful/user-guides/http-basic-auth)。

### 路径参数

#### appid

- 类型: `string`
- 必填: 是
- 描述: 你的项目使用的 [App ID](http://doc.shengwang.cn/doc/cloud-recording/restful/get-started/enable-service#%E8%8E%B7%E5%8F%96-app-id)。
    - 对于页面录制模式，只需输入开通了云端录制服务的 App ID。
    - 对于单流和合流录制模式，必须使用和待录制的频道相同的 App ID，且该 App ID 需要开通云端录制服务。

#### resourceid

- 类型: `string`
- 必填: 是
- 描述: 通过 [`acquire`](/doc/cloud-recording/restful/cloud-recording/operations/post-v1-apps-appid-cloud_recording-acquire) 请求获取到的 Resource ID。

#### mode

- 类型: `string`
- 必填: 是
- 描述: 录制模式：
    - `individual`：[单流录制模式](https://doc.shengwang.cn/doc/cloud-recording/restful/user-guides/individual-mode/set-individual)。
    - `mix`：[合流录制模式](https://doc.shengwang.cn/doc/cloud-recording/restful/user-guides/mix-mode/set-composite)。
    - `web`：[页面录制模式](https://doc.shengwang.cn/doc/cloud-recording/restful/user-guides/web-mode/set-webpage-recording)。

### 请求 Header

#### Content-Type

- 类型: `string`
- 必填: 是
- 描述: `application/json`。

### 请求 Body

#### cname

- 类型: `string`
- 必填: 是
- 描述: 录制服务所在频道的名称。需要和你在 [`acquire`](/doc/cloud-recording/restful/cloud-recording/operations/post-v1-apps-appid-cloud_recording-acquire) 请求中输入的 `cname` 相同。

#### uid

- 类型: `string`
- 必填: 是
- 描述: 字符串内容为录制服务在 RTC 频道内使用的 UID，用于标识该录制服务，需要和你在 [`acquire`](/doc/cloud-recording/restful/cloud-recording/operations/post-v1-apps-appid-cloud_recording-acquire) 请求中输入的 `uid` 相同。

#### clientRequest

- 类型: `object`
- 必填: 是
- 描述: 设置该字段后，可以提升可用性并优化负载均衡。
  > **注意**  
  > 如果填写该字段，则必须确保 `startParameter` object 和后续 `start` 请求中填写的 `clientRequest` object 完全一致，且取值合法，否则 `start` 请求会收到报错。

##### token

- 类型: `string`
- 必填: 否
- 描述: 用于鉴权的动态密钥（Token）。如果你的项目已启用 App 证书，则务必在该字段中传入你项目的动态密钥。详见[使用 Token 鉴权](https://doc.shengwang.cn/doc/rtc/android/basic-features/token-authentication)。
    - **注意**：
        - 仅需在**单流录制**和**合流录制**模式下设置。
        - 云端录制服务暂不支持更新 Token。为保证录制正常，请确保 Token 有效时长大于你预计的录制时长，以避免 Token 过期导致录制任务退出频道而结束录制。

##### storageConfig

- 类型: `object`
- 必填: 是
- 描述: 第三方云存储的配置项。

###### vendor

- 类型: `number`
- 必填: 是
- 描述: 第三方云存储平台。
    - `1`：[Amazon S3](https://aws.amazon.com/cn/s3/?c=s&sec=srv)
    - `2`：[阿里云](https://www.aliyun.com/product/oss)
    - `3`：[腾讯云](https://cloud.tencent.com/product/cos)
    - `5`：[Microsoft Azure](https://azure.microsoft.com/zh-cn/products/storage/blobs/)
    - `6`：[谷歌云](https://cloud.google.com/storage)
    - `7`：[华为云](https://www.huaweicloud.com/product/obs.html)
    - `8`：[百度智能云](https://cloud.baidu.com/product/bos.html)
    - `11`：其他 S3 协议云存储（如 Digital Ocean、MinIO 及部分自建云存储）。你需要在 `extensionParams.endpoint` 字段中指定 S3 协议云存储的域名。

###### region

- 类型: `number`
- 必填: 是
- 描述: 第三方云存储指定的地区信息。
  > **注意**  
  > 为确保录制文件上传的成功率和实时性，第三方云存储的 `region` 与你发起请求的应用服务器必须在同一个区域中。例如：你发起请求的 App 服务器在中国大陆地区，则第三方云存储需要设置为中国大陆区域内。详见[第三方存储地区说明](https://doc.shengwang.cn/doc/cloud-recording/restful/api/reference)。

###### bucket

- 类型: `string`
- 必填: 是
- 描述: 第三方云存储的 Bucket。Bucket 名称需要符合对应第三方云存储服务的命名规则。

###### accessKey

- 类型: `string`
- 必填: 是
- 描述: 第三方云存储的 Access Key（访问密钥）。

###### secretKey

- 类型: `string`
- 必填: 是
- 描述: 第三方云存储的 Secret Key。

###### stsToken

- 类型: `string`
- 必填: 否
- 描述: 第三方云存储的临时安全令牌。该令牌由云服务商的安全令牌服务 (Security Token Service, STS) 临时颁发，用于授予第三方云存储资源的有限访问权限。
  > **注意**  
  > 目前支持的云服务商仅包括，`1`：Amazon S3，`2`：阿里云，`3`：腾讯云。

###### stsExpiration

- 类型: `number`
- 必填: 否
- 描述: 用于标记 `stsToken` 的过期时间戳，POSIX 时间，单位为秒。
  > **注意**
  > - 为避免时间戳溢出问题，建议使用 Uint64 存储。
  > - 建议在申请 `stsToken` 时设置尽可能长的有效期，最短有效期需不少于 4 小时，并需要在过期前调用 `update` 方法更新 `stsToken` 的值
  > - 如录制任务持续时间超过 1 小时，需每 60 分钟重新申请 `stsToken`，并调用 `update` 更新 `storageConfig` 中的相关参数。

###### fileNamePrefix

- 类型: `array[string]`
- 必填: 否
- 描述: 录制文件在第三方云存储中的存储位置，与录制文件名前缀有关。如果设为 `["directory1","directory2"]`，那么录制文件名前缀为 `"directory1/directory2/"`，即录制文件名为 `directory1/directory2/xxx.m3u8`。前缀长度（包括斜杠）不得超过 128 个字符。字符串中不得出现斜杠、下划线、括号等符号字符。以下为支持的字符集范围：
    - 26 个小写英文字母 a~z
    - 26 个大写英文字母 A~Z
    - 10 个数字 0-9

###### extensionParams

- 类型: `object`
- 必填: 否
- 描述: 第三方云存储服务会按照该字段设置对已上传的录制文件进行加密和打标签。

###### sse

- 类型: `string`
- 必填: 否
- 描述: 加密模式。设置该字段后，第三方云存储服务会按照该加密模式将已上传的录制文件进行加密。该字段仅适用于 Amazon S3，详见 [Amazon S3 官方文档](https://docs.aws.amazon.com/zh_cn/AmazonS3/latest/userguide/UsingEncryption.html)。
    - `kms`：KMS 加密。
    - `aes256`：AES256 加密。

###### tag

- 类型: `string`
- 必填: 否
- 描述: 标签内容。设置该字段后，第三方云存储服务会按照该标签内容将已上传的录制文件进行打标签操作。该字段仅适用于阿里云和 Amazon S3，详见[阿里云官方文档](https://help.aliyun.com/zh/oss/developer-reference/object-tagging-10?spm=a2c4g.11186623.help-menu-31815.d_5_3_2_2_26.7732131cSR2ojD)和 [Amazon S3 官方文档](https://docs.aws.amazon.com/zh_cn/AmazonS3/latest/userguide/object-tagging.html)。

###### endpoint

- 类型: `string`
- 必填: 否
- 描述: S3 协议云存储的域名。当 `vendor` 设为 `11` 时，该字段为必填。

##### recordingConfig

- 类型: `object`
- 必填: 否
- 描述: 录制的音视频流配置项。
    - **注意**：仅需在**单流录制**和**合流录制**模式下设置。

###### channelType

- 类型: `number`
- 必填: 是
- 默认值: `0`
- 描述: 频道场景。
    - `0`：通信场景。
    - `1`：直播场景。
  > **注意**  
  > 频道场景必须与声网 RTC SDK 的设置一致，否则可能导致问题。

###### decryptionMode

- 类型: `number`
- 必填: 否
- 默认值: `0`
- 描述: 解密模式。如果你在 SDK 客户端设置了频道加密，那么你需要对云端录制服务设置与加密相同的解密模式。
    - `0`：不加密。
    - `1`：AES_128_XTS 加密模式。128 位 AES 加密，XTS 模式。
    - `2`：AES_128_ECB 加密模式。128 位 AES 加密，ECB 模式。
    - `3`：AES_256_XTS 加密模式。256 位 AES 加密，XTS 模式。
    - `4`：SM4_128_ECB 加密模式。128 位 SM4 加密，ECB 模式。
    - `5`：AES_128_GCM 加密模式。128 位 AES 加密，GCM 模式。
    - `6`：AES_256_GCM 加密模式。256 位 AES 加密，GCM 模式。
    - `7`：AES_128_GCM2 加密模式。128 位 AES 加密，GCM 模式。相比于 AES_128_GCM 加密模式，AES_128_GCM2 加密模式安全性更高且需要设置密钥和盐。
    - `8`：AES_256_GCM2 加密模式。256 位 AES 加密，GCM 模式。相比于 AES_256_GCM 加密模式，AES_256_GCM2 加密模式安全性更高且需要设置密钥和盐。

###### secret

- 类型: `string`
- 必填: 否
- 描述: 与加解密相关的密钥。仅需在 `decryptionMode` 非 `0` 时设置。

###### salt

- 类型: `string`
- 必填: 否
- 描述: 与加解密相关的盐。Base64 编码、32 位字节。仅需在 `decryptionMode` 为 `7` 或 `8` 时设置。

###### maxIdleTime

- 类型: `number`
- 必填: 否
- 默认值: `30`
- 最小值: `5`
- 最大值: `2592000`
- 描述: 最大频道空闲时间。单位为秒。最大值不超过 30 天。超出最大频道空闲时间后，录制服务会自动退出。录制服务退出后，如果你再次发起 `start` 请求，会产生新的录制文件。
    - 频道空闲：直播频道内无任何主播，或通信频道内无任何用户。

###### streamTypes

- 类型: `number`
- 必填: 否
- 默认值: `2`
- 描述: 订阅的媒体流类型。
    - `0`：仅订阅音频。适用于智能语音审核场景。
    - `1`：仅订阅视频。
    - `2`：订阅音频和视频。

###### videoStreamType

- 类型: `number`
- 必填: 否
- 默认值: `0`
- 描述: 设置订阅的视频流类型。如果你在 SDK 客户端开启了双流模式，你可以选择订阅视频大流或者小流。
    - `0`：视频大流，即高分辨率高码率的视频流
    - `1`：视频小流，即低分辨率低码率的视频流

###### subscribeAudioUids

- 类型: `array[string]`
- 必填: 否
- 描述: 指定订阅哪几个 UID 的音频流。如需订阅全部 UID 的音频流，则无需设置该字段。数组长度不得超过 32，不推荐使用空数组。该字段和 `unSubscribeAudioUids` 只能设一个。详见[设置订阅名单](https://doc.shengwang.cn/doc/cloud-recording/restful/api/reference#%E8%AE%A2%E9%98%85%E5%90%8D%E5%8D%95%E8%AE%BE%E7%BD%AE)。
    - **注意**：
        - 该字段仅适用于 `streamTypes` 设为音频，或音频和视频的情况。
        - 如果你设置了音频的订阅名单，但没有设置视频的订阅名单，云端录制服务不会订阅任何视频流。反之亦然。
        - 设为 `["#allstream#"]` 可订阅频道内所有 UID 的音频流。

###### unSubscribeAudioUids

- 类型: `array[string]`
- 必填: 否
- 描述: 指定不订阅哪几个 UID 的音频流。云端录制会订阅频道内除指定 UID 外所有 UID 的音频流。数组长度不得超过 32，不推荐使用空数组。该字段和 `subscribeAudioUids` 只能设一个。详见[设置订阅名单](https://doc.shengwang.cn/doc/cloud-recording/restful/api/reference#%E8%AE%A2%E9%98%85%E5%90%8D%E5%8D%95%E8%AE%BE%E7%BD%AE)。

###### subscribeVideoUids

- 类型: `array[string]`
- 必填: 否
- 描述: 指定订阅哪几个 UID 的视频流。如需订阅全部 UID 的视频流，则无需设置该字段。数组长度不得超过 32，不推荐使用空数组。该字段和 `unSubscribeVideoUids` 只能设一个。详见[设置订阅名单](https://doc.shengwang.cn/doc/cloud-recording/restful/api/reference#%E8%AE%A2%E9%98%85%E5%90%8D%E5%8D%95%E8%AE%BE%E7%BD%AE)。
    - **注意**：
        - 该字段仅适用于 `streamTypes` 设为视频，或音频和视频的情况。
        - 如果你设置了视频的订阅名单，但没有设置音频的订阅名单，云端录制服务不会订阅任何音频流。反之亦然。
        - 设为 `["#allstream#"]` 可订阅频道内所有 UID 的视频流。

###### unSubscribeVideoUids

- 类型: `array[string]`
- 必填: 否
- 描述: 指定不订阅哪几个 UID 的视频流。云端录制会订阅频道内除指定 UID 外所有 UID 的视频流。数组长度不得超过 32，不推荐使用空数组。该字段和 `subscribeVideoUids` 只能设一个。详见[设置订阅名单](https://doc.shengwang.cn/doc/cloud-recording/restful/api/reference#%E8%AE%A2%E9%98%85%E5%90%8D%E5%8D%95%E8%AE%BE%E7%BD%AE)。

###### subscribeUidGroup

- 类型: `number`
- 必填: 否
- 描述: 预估的订阅人数峰值。
    - `0`：1 到 2 个 UID。
    - `1`：3 到 7 个 UID。
    - `2`：8 到 12 个 UID。
    - `3`：13 到 17 个 UID。
    - `4`：18 到 32 个 UID。
    - `5`：33 到 49 个 UID。
  > **注意**
  > - 仅需在**单流录制**模式下设置，且单流录制模式下必填。
  > - 举例来说，如果 `subscribeVideoUids` 为 `["100","101","102"]`，`subscribeAudioUids` 为 `["101","102","103"]`，则订阅人数为 4 人。

###### streamMode

- 类型: `string`
- 必填: 否
- 默认值: `"default"`
- 描述: 媒体流的输出模式。详见[媒体流输出模式设置](https://doc.shengwang.cn/doc/cloud-recording/restful/api/reference#%E5%AA%92%E4%BD%93%E6%B5%81%E8%BE%93%E5%87%BA%E6%A8%A1%E5%BC%8F%E8%AE%BE%E7%BD%AE)。
    - `"default"`：默认模式。录制过程中音频转码，分别生成 M3U8 音频索引文件和视频索引文件。
    - `"standard"`：标准模式。声网推荐使用该模式。录制过程中音频转码，分别生成 M3U8 音频索引文件、视频索引文件和合并的音视频索引文件。如果在 Web 端使用 VP8 编码，则生成一个合并的 MPD 音视频索引文件。
    - `"original"`：原始编码模式。适用于[单流音频不转码录制](http://doc.shengwang.cn/doc/cloud-recording/restful/user-guides/individual-mode/set-individual-nontranscoding)。仅订阅音频时（`streamTypes` 为 0）时该字段生效，录制过程中音频不转码，生成 M3U8 音频索引文件。
  > **注意**  
  > 仅需在**单流录制**模式下设置。

###### audioProfile

- 类型: `number`
- 必填: 否
- 默认值: `0`
- 描述: 设置输出音频的采样率、码率、编码模式和声道数。
    - `0`：48 kHz 采样率，音乐编码，单声道，编码码率约 48 Kbps。
    - `1`：48 kHz 采样率，音乐编码，单声道，编码码率约 128 Kbps。
    - `2`：48 kHz 采样率，音乐编码，双声道，编码码率约 192 Kbps。
  > **注意**  
  > 仅需在**合流录制**模式下设置。

###### transcodingConfig

- 类型: `object`
- 必填: 否
- 描述: 转码输出的视频配置项。取值可参考[设置录制输出视频的分辨率](http://doc.shengwang.cn/doc/cloud-recording/restful/api/reference)。
    - **注意**：仅需在**合流录制**模式下设置。

###### width

- 类型: `number`
- 必填: 否
- 默认值: `360`
- 最大值: `1920`
- 描述: 视频的宽度，单位为像素。`width` 和 `height` 的乘积不能超过 1920 × 1080。

###### height

- 类型: `number`
- 必填: 否
- 默认值: `640`
- 最大值: `1920`
- 描述: 视频的高度，单位为像素。`width` 和 `height` 的乘积不能超过 1920 × 1080。

###### fps

- 类型: `number`
- 必填: 否
- 默认值: `15`
- 描述: 视频的帧率，单位 fps。

###### bitrate

- 类型: `number`
- 必填: 否
- 默认值: `500`
- 描述: 视频的码率，单位 Kbps。

###### maxResolutionUid

- 类型: `string`
- 必填: 否
- 描述: 仅需在**垂直布局**下设置。指定显示大视窗画面的用户 UID。字符串内容的整型取值范围 1 到 (2^32-1)，且不可设置为 0。

###### mixedVideoLayout

- 类型: `number`
- 必填: 否
- 默认值: `0`
- 描述: 视频合流布局：
    - `0`：悬浮布局。第一个加入频道的用户在屏幕上会显示为大视窗，铺满整个画布，其他用户的视频画面会显示为小视窗，从下到上水平排列，最多 4 行，每行 4 个画面，最多支持共 17 个画面。
    - `1`：自适应布局。根据用户的数量自动调整每个画面的大小，每个用户的画面大小一致，最多支持 17 个画面。
    - `2`：垂直布局。指定 `maxResolutionUid` 在屏幕左侧显示大视窗画面，其他用户的小视窗画面在右侧垂直排列，最多两列，一列 8 个画面，最多支持共 17 个画面。
    - `3`：自定义布局。由你在 `layoutConfig` 字段中自定义合流布局。

###### backgroundColor

- 类型: `string`
- 必填: 否
- 默认值: `"#000000"`
- 描述: 视频画布的背景颜色。支持 RGB 颜色表，字符串格式为 # 号和 6 个十六进制数。默认值 `"#000000"`，代表黑色。

###### backgroundImage

- 类型: `string`
- 必填: 否
- 描述: 视频画布的背景图的 URL。背景图的显示模式为裁剪模式。
    - 裁剪模式：优先保证画面被填满。背景图尺寸等比缩放，直至整个画面被背景图填满。如果背景图长宽与显示窗口不同，则背景图会按照画面设置的比例进行周边裁剪后填满画面。

###### defaultUserBackgroundImage

- 类型: `string`
- 必填: 否
- 描述: 默认的用户画面背景图的 URL。
    - 配置该字段后，当任一⽤户停止发送视频流超过 3.5 秒，画⾯将切换为该背景图；如果针对某 UID 单独设置了背景图，则该设置会被覆盖。

###### layoutConfig

- 类型: `array`
- 必填: 否
- 描述: 用户的合流画面布局。由每个用户对应的布局画面设置组成的数组，支持最多 17 个用户。
    - **注意**：仅需在**自定义布局**下设置。

###### uid

- 类型: `string`
- 必填: 否
- 描述: 字符串内容为待显示在该区域的用户的 UID，32 位无符号整数。
    - 如果不指定 UID，会按照用户加入频道的顺序自动匹配 `layoutConfig` 中的画面设置。

###### x_axis

- 类型: `number`
- 必填: 是
- 最小值: `0`
- 最大值: `1`
- 描述: 屏幕里该画面左上角的横坐标的相对值，精确到小数点后六位。从左到右布局，`0.0` 在最左端，`1.0` 在最右端。该字段也可以设置为整数 0 或 1。

###### y_axis

- 类型: `number`
- 必填: 是
- 最小值: `0`
- 最大值: `1`
- 描述: 屏幕里该画面左上角的纵坐标的相对值，精确到小数点后六位。从上到下布局，`0.0` 在最上端，`1.0` 在最下端。该字段也可以设置为整数 0 或 1。

###### width

- 类型: `number`
- 必填: 是
- 最小值: `0`
- 最大值: `1`
- 描述: 该画面宽度的相对值，精确到小数点后六位。该字段也可以设置为整数 0 或 1。

###### height

- 类型: `number`
- 必填: 是
- 最小值: `0`
- 最大值: `1`
- 描述: 该画面高度的相对值，精确到小数点后六位。该字段也可以设置为整数 0 或 1。

###### alpha

- 类型: `number`
- 必填: 否
- 默认值: `1`
- 最小值: `0`
- 最大值: `1`
- 描述: 图像的透明度。精确到小数点后六位。`0.0` 表示图像为透明的，`1.0` 表示图像为完全不透明的。

###### render_mode

- 类型: `number`
- 必填: 否
- 默认值: `0`
- 描述: 画面显示模式：
    - `0`：裁剪模式。优先保证画面被填满。视频尺寸等比缩放，直至整个画面被视频填满。如果视频长宽与显示窗口不同，则视频流会按照画面设置的比例进行周边裁剪后填满画面。
    - `1`：缩放模式。优先保证视频内容全部显示。视频尺寸等比缩放，直至视频窗口的一边与画面边框对齐。如果视频尺寸与画面尺寸不一致，在保持长宽比的前提下，将视频进行缩放后填满画面，缩放后的视频四周会有一圈黑边。

###### backgroundConfig

- 类型: `array`
- 必填: 否
- 描述: 用户的背景图设置。

###### uid

- 类型: `string`
- 必填: 是
- 描述: 字符串内容为用户 UID。

###### image_url

- 类型: `string`
- 必填: 是
- 描述: 该用户的背景图 URL。配置背景图后，当该⽤户停止发送视频流超过 3.5 秒，画⾯将切换为该背景图。
    - URL 支持 HTTP 和 HTTPS 协议，图片格式支持 JPG 和 BMP。图片大小不得超过 6 MB。录制服务成功下载图片后，设置才会生效；如果下载失败，则设置不⽣效。不同字段设置可能会互相覆盖，具体规则详见[设置背景色或背景图](https://doc.shengwang.cn/doc/cloud-recording/restful/user-guides/mix-mode/set-composite-layout#%E8%BF%9B%E9%98%B6%E8%AE%BE%E7%BD%AE%E8%83%8C%E6%99%AF%E8%89%B2%E6%88%96%E8%83%8C%E6%99%AF%E5%9B%BE)。

###### render_mode

- 类型: `number`
- 必填: 否
- 默认值: `0`
- 描述: 画面显示模式：
    - `0`：裁剪模式。优先保证画面被填满。视频尺寸等比缩放，直至整个画面被视频填满。如果视频长宽与显示窗口不同，则视频流会按照画面设置的比例进行周边裁剪后填满画面。
    - `1`：缩放模式。优先保证视频内容全部显示。视频尺寸等比缩放，直至视频窗口的一边与画面边框对齐。如果视频尺寸与画面尺寸不一致，在保持长宽比的前提下，将视频进行缩放后填满画面，缩放后的视频四周会有一圈黑边。

##### recordingFileConfig

- 类型: `object`
- 必填: 否
- 描述: 录制文件配置项。
    - **注意**：**仅截图时不可设置**该字段，其他情况都需要设置该字段。其他情况包含如下：
        - 单流录制模式下，不转码录制，转码录制，或同时录制和截图。
        - 合流录制。
        - 页面录制模式下，仅页面录制，或同时页面录制和转推到 CDN。

###### avFileType

- 类型: `array[string]`
- 必填: 否
- 描述: 录制生成的视频文件类型：
    - `"hls"`：默认值。M3U8 和 TS 文件。
    - `"mp4"`：MP4 文件。
  > **注意**
  > - **单流录制**模式下，且**非仅截图**情况，使用默认值即可。
  > - **合流录制**和**页面录制**模式下，如果你需要生成 MP4 文件，那么设为 `["hls","mp4"]`。仅设为 `["mp4"]` 会收到报错。设置后，录制文件行为如下：
      >   - 合流录制模式：录制服务会在当前 MP4 文件时长超过约 2 小时或文件大小超过约 2 GB 左右时，创建一个新的 MP4 文件。
  >   - 页面录制模式：录制服务会在当前 MP4 文件时长超过 `maxVideoDuration` 时，创建一个新的 MP4 文件。

##### snapshotConfig

- 类型: `object`
- 必填: 否
- 描述: 云端截图配置项。
    - **注意**：仅需在**单流录制**模式下，且使用截图功能时设置。
    - **截图**使用须知：
        - 截图功能仅适用于单流录制模式（`individual`）。
        - 你可以在一个单流录制进程中仅截图，或同时录制和截图，详见[云端截图](http://doc.shengwang.cn/doc/cloud-recording/restful/user-guides/snapshot)。同时录制和截图的情况需要设置 `recordingFileConfig` 字段。
        - 如果录制服务或录制上传服务异常，则截图失败。截图异常时，录制不受影响。
        - 使用截图时，`streamTypes` 必须设置为 1 或 2。如果设置了 `subscribeAudioUid`，则必须同时设置 `subscribeVideoUids`。

###### captureInterval

- 类型: `number`
- 必填: 否
- 默认值: `10`
- 最小值: `5`
- 最大值: `3600`
- 描述: 云端录制定期截图的截图周期。单位为秒。

###### fileType

- 类型: `array[string]`
- 必填: 是
- 描述: 截图的文件格式。目前只支持 `["jpg"]`，即生成 JPG 格式的截图文件。

##### extensionServiceConfig

- 类型: `object`
- 必填: 否
- 描述: 扩展服务配置项。
    - **注意**：仅需在**页面录制**模式下设置。

###### errorHandlePolicy

- 类型: `string`
- 必填: 否
- 默认值: `"error_abort"`
- 描述: 错误处理策略。默认且仅可设为 `"error_abort"`，表示当扩展服务发生错误后，订阅和云端录制的其他非扩展服务都停止。

###### extensionServices

- 类型: `array`
- 必填: 是
- 描述: 扩展服务的具体配置项。

###### serviceName

- 类型: `string`
- 必填: 是
- 描述: 扩展服务的名称：
    - `web_recorder_service`：代表扩展服务为**页面录制**。
    - `rtmp_publish_service`：代表扩展服务为**转推页面录制到 CDN**。

###### errorHandlePolicy

- 类型: `string`
- 必填: 否
- 描述: 扩展服务内的错误处理策略：
    - `"error_abort"`：**页面录制**时默认且只能为该值。表示当前扩展服务出错时，停止其他扩展服务。
    - `"error_ignore"`：**转推页面录制到 CDN** 时默认且只能为该值。表示当前扩展服务出错时，其他扩展服务不受影响。
    - 如果页面录制服务或录制上传服务异常，那么推流到 CDN 失败，因此**页面录制**服务出错会影响**转推页面录制到 CDN** 服务。
    - 转推到 CDN 的过程发生异常时，页面录制不受影响。

###### serviceParam

- 类型: `object`
- 必填: 是
- 描述: 扩展服务的具体配置项。

### 页面录制

#### url

- 类型: `string`
- 必填: 是
- 描述: 待录制页面的地址。

#### audioProfile

- 类型: `number`
- 必填: 是
- 描述: 输出音频的采样率、码率、编码模式和声道数。
    - `0`：48 kHz 采样率，音乐编码，单声道，编码码率约 48 Kbps。
    - `1`：48 kHz 采样率，音乐编码，单声道，编码码率约 128 Kbps。
    - `2`：48 kHz 采样率，音乐编码，双声道，编码码率约 192 Kbps。

#### videoWidth

- 类型: `number`
- 必填: 是
- 最小值: `240`
- 最大值: `1920`
- 描述: 输出视频的宽度，单位为 pixel。`videoWidth` 和 `videoHeight` 的乘积需小于等于 1920 × 1080。推荐值可参考[如何设置页面录制移动端网页模式的输出视频分辨率](https://doc.shengwang.cn/faq/integration-issues/mobile-video-profile)。

#### videoHeight

- 类型: `number`
- 必填: 是
- 最小值: `240`
- 最大值: `1920`
- 描述: 输出视频的高度，单位为 pixel。`videoWidth` 和 `videoHeight` 的乘积需小于等于 1920 × 1080。推荐值可参考[如何设置页面录制移动端网页模式的输出视频分辨率](https://doc.shengwang.cn/faq/integration-issues/mobile-video-profile)。

#### maxRecordingHour

- 类型: `number`
- 必填: 是
- 最小值: `1`
- 最大值: `720`
- 描述: 页面录制的最大时长，单位为小时。超出该值后页面录制会自动停止。
  > **注意**  
  > 建议取值不超过你在 `acquire` 方法中设置的 `resourceExpiredHour` 的值。
    - **计费相关**：页面录制停止前会持续计费，因此请根据实际业务情况设置合理的值或主动停止页面录制。

#### videoBitrate

- 类型: `number`
- 必填: 否
- 描述: 输出视频的码率，单位为 Kbps。针对不同的输出视频分辨率，`videoBitrate` 的默认值不同：
    - 输出视频分辨率大于或等于 1280 × 720：默认值为 2000。
    - 输出视频分辨率小于 1280 × 720：默认值为 1500。

#### videoFps

- 类型: `number`
- 必填: 否
- 默认值: `15`
- 最小值: `5`
- 最大值: `60`
- 描述: 输出视频的帧率，单位为 fps。

#### mobile

- 类型: `boolean`
- 必填: 否
- 默认值: `false`
- 描述: 是否开启移动端网页模式：
    - `true`：开启。开启后，录制服务使用移动端网页渲染模式录制当前页面。
    - `false`：（默认）不开启。

#### maxVideoDuration

- 类型: `number`
- 必填: 否
- 默认值: `120`
- 最小值: `30`
- 最大值: `240`
- 描述: 页面录制生成的 MP4 切片文件的最大时长，单位为分钟。页面录制过程中，录制服务会在当前 MP4 文件时长超过约 `maxVideoDuration` 左右时创建一个新的 MP4 切片文件。

#### onhold

- 类型: `boolean`
- 必填: 否
- 默认值: `false`
- 描述: 是否在启动页面录制任务时暂停页面录制。
    - `true`：在启动页面录制任务时暂停页面录制。开启页面录制任务后立即暂停录制，录制服务会打开并渲染待录制页面，但不生成切片文件。
    - `false`：启动页面录制任务并进行页面录制。
    - 建议你按照如下流程使用 `onhold` 字段：
        1. 调用 `start` 方法时将 `onhold` 设为 `true`，开启并暂停页面录制，自行判断页面录制开始的合适时机。
        2. 调用 `update` 并将 `onhold` 设为 `false`，继续进行页面录制。如果需要连续调用 `update` 方法暂停或继续页面录制，请在收到上一次 `update` 响应后再进行调用，否则可能导致请求结果与预期不一致。

#### readyTimeout

- 类型: `number`
- 必填: 否
- 默认值: `0`
- 最小值: `0`
- 最大值: `60`
- 描述: 设置页面加载超时时间，单位为秒。详见[页面加载超时检测](https://doc.shengwang.cn/doc/cloud-recording/restful/best-practices/webpage#%E6%A3%80%E6%B5%8B%E9%A1%B5%E9%9D%A2%E5%8A%A0%E8%BD%BD%E8%B6%85%E6%97%B6)。
    - `0` 或不设置，表示不检测页面加载状态。
    - [1,60] 之间的整数，表示页面加载超时时间。

## 响应

### 200

如果返回的 HTTP 状态码为 `200`，表示请求成功。

如需检查录制服务是否已经成功启动，请参考[监控录制状态](https://doc.shengwang.cn/doc/cloud-recording/restful/best-practices/recording-status)操作。

### 响应 Body

#### cname

- 类型: `string`
- 描述: 录制的频道名。

#### uid

- 类型: `string`
- 描述: 字符串内容为云端录制服务在 RTC 频道内使用的 UID，用于标识频道内的录制服务。

#### resourceId

- 类型: `string`
- 描述: 云端录制资源 Resource ID。使用这个 Resource ID 可以开始一段云端录制。这个 Resource ID 的有效期为 5 分钟，超时需要重新请求。

#### sid

- 类型: `string`
- 描述: 录制 ID。成功开始云端录制后，你会得到一个 Sid （录制 ID）。该 ID 是一次录制周期的唯一标识。