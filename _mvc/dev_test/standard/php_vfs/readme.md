# php-vfs導入

itcv1800005mにてphp8.2.0にて下記unit testがvfsでdeprecateエラー
開発機では問題なく、原因不明


- HttpDownloadTest
- HttpUploadTest

##php-vfs/php-vfs

vfsをvfsStreamからphp-vfs/php-vfsに変更

- HttpUploadTestはmime判定できない


