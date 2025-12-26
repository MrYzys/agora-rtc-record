<?php

namespace MrYzys\AgoraRtcRecord\Storage;
class Vendor
{
    //1：Amazon S3
    //2：阿里云
    //3：腾讯云
    //5：Microsoft Azure
    //6：谷歌云
    //7：华为云
    //8：百度智能云
    //11：其他 S3 协议云存储（如 Digital Ocean、MinIO 及部分自建云存储）。你需要在 extensionParams.endpoint 字段中指定 S3 协议云存储的域名。
    const AMAZON = 1;
    const ALIYUN = 2;
    const TENCENT = 3;
    const AZURE = 5;
    const GOOGLE = 6;
    const HUAWEI = 7;
    const BAIDU = 8;
    const OTHER = 11;
}