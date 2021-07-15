<?php
require '../vendor/autoload.php';

use Aws\S3\S3Client;
// use Aws\Exception\AwsException;
use Aws\S3\MultipartUploader;
use Aws\Exception\MultipartUploadException;


define('AWS_S3_KEY', 'AKIAZF27QLIWD4X4GEDA');
define('AWS_S3_SECRET', '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');
define('AWS_S3_REGION', 'us-east-2');
define('AWS_S3_BUCKET', 'bucket');
define('AWS_S3_URL', 'http://s3.' . AWS_S3_REGION . '.amazonaws.com/' . AWS_S3_BUCKET . '/');

$tmpfile = $_FILES['file']['tmp_name'];
$file = $_FILES['file']['name'];
// Instantiate an Amazon S3 client.
$s3Client = new S3Client([
    'profile' => 'default',
    'region' => 'us-east-2',
    'version' => '2006-03-01'
]);

$bucket = AWS_S3_BUCKET;
$key = $file;

// Using stream instead of file path
$source = fopen('../img/awsUpload/'.$key, 'rb');

$uploader = new MultipartUploader($s3Client, $source, [
    'bucket' => 'your-bucket',
    'key' => 'my-file.zip',
    'before_initiate' => function (\Aws\Command $command) {
        // $command is a CreateMultipartUpload operation
        $command['CacheControl'] = 'max-age=3600';
    },
    'before_upload' => function (\Aws\Command $command) {
        // $command is an UploadPart operation
        $command['RequestPayer'] = 'requester';
    },
    'before_complete' => function (\Aws\Command $command) {
        // $command is a CompleteMultipartUpload operation
        $command['RequestPayer'] = 'requester';
    },
]);
 