# Agora RTC Cloud Recording Client

 Simple Composer package that wraps the Shengwang (Agora) cloud recording REST APIs for `acquire`, `start`, `update`, `query`, and `stop`.

## Installation

```bash
composer require MrYzys/agora-rtc-record
```

## Usage

```php
use MrYzys\AgoraRtcRecord\CloudRecordingClient;
use MrYzys\AgoraRtcRecord\Requests\Acquire\AcquireClientRequest;
use MrYzys\AgoraRtcRecord\Requests\Acquire\AcquireRequest;
use MrYzys\AgoraRtcRecord\Requests\Acquire\RecordingConfig;
use MrYzys\AgoraRtcRecord\Requests\Acquire\StartParameter;
use MrYzys\AgoraRtcRecord\Requests\Acquire\StorageConfig;
use MrYzys\AgoraRtcRecord\Requests\Acquire\Transcoding\TranscodingConfig;
use MrYzys\AgoraRtcRecord\Requests\Query\QueryRequest;
use MrYzys\AgoraRtcRecord\Requests\Start\RecordingFileConfig;
use MrYzys\AgoraRtcRecord\Requests\Start\StartClientRequest;
use MrYzys\AgoraRtcRecord\Requests\Start\StartRequest;
use MrYzys\AgoraRtcRecord\Requests\Stop\StopClientRequest;
use MrYzys\AgoraRtcRecord\Requests\Stop\StopRequest;
use MrYzys\AgoraRtcRecord\Requests\Update\StreamSubscribe;
use MrYzys\AgoraRtcRecord\Requests\Update\UidList;
use MrYzys\AgoraRtcRecord\Requests\Update\UpdateClientRequest;
use MrYzys\AgoraRtcRecord\Requests\Update\UpdateRequest;
use MrYzys\AgoraRtcRecord\Requests\Update\WebRecordingConfig;
use MrYzys\AgoraRtcRecord\Requests\UpdateLayout\UpdateLayoutClientRequest;
use MrYzys\AgoraRtcRecord\Requests\UpdateLayout\UpdateLayoutRequest;
use MrYzys\AgoraRtcRecord\Requests\Acquire\Transcoding\BackgroundConfigEntry;
use MrYzys\AgoraRtcRecord\Requests\Layout\LayoutConfigEntry;
use MrYzys\AgoraRtcRecord\Requests\Layout\BackgroundConfigEntry;

$client = new CloudRecordingClient(
    appId: 'your-agora-app-id',
    customerId: 'your-customer-id',
    customerCertificate: 'your-customer-certificate'
);

$storageConfig = (new StorageConfig(
    vendor: 2,
    region: 14,
    bucket: 'bucket-name',
    accessKey: 'ak',
    secretKey: 'sk'
))->setFileNamePrefix(['recordings']);

$startParameter = (new StartParameter())
    ->setToken('channel-token')
    ->setStorageConfig($storageConfig);

$recordingConfig = (new RecordingConfig())
    ->setChannelType(1)
    ->setStreamTypes(2)
    ->setMaxIdleTime(60);

$clientRequest = (new AcquireClientRequest())
    ->setScene(0)
    ->setResourceExpiredHour(24)
    ->setRegionAffinity(1)
    ->setStartParameter($startParameter)
    ->setRecordingConfig($recordingConfig);

// 1. Acquire
$acquireResponse = $client->acquire(new AcquireRequest('demoChannel', '110', $clientRequest));
$resourceId = $acquireResponse['resourceId'];

// 2. Start
$transcodingConfig = (new TranscodingConfig())
    ->setWidth(1280)
    ->setHeight(720)
    ->setBitrate(2000)
    ->setMixedVideoLayout(1);

$recordingConfig = (new RecordingConfig())
    ->setChannelType(1)
    ->setStreamTypes(2)
    ->setMaxIdleTime(60)
    ->setTranscodingConfig($transcodingConfig);

$recordingFileConfig = (new RecordingFileConfig())->setAvFileTypes(['hls', 'mp4']);

$startClientRequest = (new StartClientRequest($storageConfig))
    ->setToken('channel-token')
    ->setRecordingConfig($recordingConfig)
    ->setRecordingFileConfig($recordingFileConfig);

$startResponse = $client->start(new StartRequest(
    resourceId: $resourceId,
    mode: 'mix',
    channelName: 'demoChannel',
    uid: '110',
    clientRequest: $startClientRequest
));
$sid = $startResponse['sid'];

// 3. Query status
$queryResponse = $client->query(new QueryRequest(
    resourceId: $resourceId,
    sid: $sid,
    mode: 'mix'
));

// 4. Update (e.g. adjust subscription list or pause/resume web recording)
$audioUidList = (new UidList('audio'))->setSubscribeUids(['#allstream#']);
$streamSubscribe = (new StreamSubscribe())->setAudioUidList($audioUidList);
$updateClientRequest = (new UpdateClientRequest())
    ->setStreamSubscribe($streamSubscribe)
    ->setWebRecordingConfig((new WebRecordingConfig())->setOnhold(false));

$client->update(new UpdateRequest(
    resourceId: $resourceId,
    sid: $sid,
    mode: 'mix',
    channelName: 'demoChannel',
    uid: '110',
    clientRequest: $updateClientRequest
));

// 5. Update mix layout (mix mode only)
$layoutClientRequest = (new UpdateLayoutClientRequest())
    ->setMixedVideoLayout(3)
    ->setBackgroundColor('#0055FF')
    ->addLayoutConfig(new LayoutConfigEntry('110', 0.0, 0.0, 0.5, 0.5))
    ->addBackgroundConfig(new BackgroundConfigEntry('111', 'https://example.com/bg.png'));

$client->updateLayout(new UpdateLayoutRequest(
    resourceId: $resourceId,
    sid: $sid,
    mode: 'mix',
    channelName: 'demoChannel',
    uid: '110',
    clientRequest: $layoutClientRequest
));

// 6. Stop
$stopClientRequest = (new StopClientRequest())->setAsyncStop(false);

$client->stop(new StopRequest(
    resourceId: $resourceId,
    sid: $sid,
    mode: 'mix',
    channelName: 'demoChannel',
    uid: '110',
    clientRequest: $stopClientRequest
));
```

The `MrYzys\AgoraRtcRecord\Requests\Acquire` namespace mirrors the settings documented in `acquire.md`, giving you typed helpers for `clientRequest`, `startParameter`, `storageConfig`, `extensionParams`, and `recordingConfig`.

- Refer to the official documentation for the expected `clientRequest` payload:
  - Acquire: https://doc.shengwang.cn/doc/cloud-recording/restful/cloud-recording/operations/post-v1-apps-appid-cloud_recording-acquire and the included `acquire.md`
  - Start: https://doc.shengwang.cn/doc/cloud-recording/restful/cloud-recording/operations/post-v1-apps-appid-cloud_recording-resourceid-resourceid-mode-mode-start and the included `start.md`
  - Update: https://doc.shengwang.cn/doc/cloud-recording/restful/cloud-recording/operations/post-v1-apps-appid-cloud_recording-resourceid-resourceid-sid-sid-mode-mode-update and the included `update-setting.md`
  - Update layout: https://doc.shengwang.cn/doc/cloud-recording/restful/cloud-recording/operations/post-v1-apps-appid-cloud_recording-resourceid-resourceid-sid-sid-mode-mode-updateLayout and the included `update-layout.md`
  - Query: https://doc.shengwang.cn/doc/cloud-recording/restful/cloud-recording/operations/get-v1-apps-appid-cloud_recording-resourceid-resourceid-sid-sid-mode-mode-query and the included `query.md`
  - Stop: https://doc.shengwang.cn/doc/cloud-recording/restful/cloud-recording/operations/post-v1-apps-appid-cloud_recording-resourceid-resourceid-sid-sid-mode-mode-stop

The `MrYzys\AgoraRtcRecord\Requests\Start` namespace mirrors the whole `clientRequest` schema in `start.md`: `StartRequest` carries path/body values, `StartClientRequest` exposes setters for the `token`, `storageConfig`, `recordingConfig`, `recordingFileConfig`, `snapshotConfig`, and `extensionServiceConfig`, and dedicated helpers such as `RecordingFileConfig`, `ExtensionServiceConfig`, and `WebRecorderServiceParam` map the nested structures for mix/web/page recording scenarios.

The `MrYzys\AgoraRtcRecord\Requests\Stop` namespace mirrors `stop.md`: `StopRequest` wraps the path/body data, while `StopClientRequest` exposes the `async_stop` flag so you can choose between synchronous and asynchronous stop semantics.

The `MrYzys\AgoraRtcRecord\Requests\Update` namespace follows `update-setting.md`: build `StreamSubscribe` with audio/video UID lists, toggle page-recorder pause via `WebRecordingConfig`, refresh RTMP publish targets, or rotate third-party storage keys before posting an `UpdateRequest`.

The `MrYzys\AgoraRtcRecord\Requests\UpdateLayout` namespace mirrors `update-layout.md`: create `LayoutConfigEntry`/`BackgroundConfigEntry` arrays, set `mixedVideoLayout`, set custom colors/images, and send them through `UpdateLayoutRequest` to adjust mix compositions on the fly.

## Error handling

Every method throws `MrYzys\AgoraRtcRecord\Exceptions\CloudRecordingException` when the underlying HTTP call fails or the API responds with a non-2xx status code. Inspect `$exception->getResponsePayload()` for the raw API error payload.
