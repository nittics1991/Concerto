
## 220319

- FilePath　を　test で利用したい
- StandardFilesystemObject test より先に、FilePath　を作る
- StandardFilesystemObject test
    - 1.protected function
    - 2. pwd とか、順番を考えて test を作っていく
    

## 20225

- filesystem
    - static method =>instance
    - Domein操作用のFilesystemObject に injection
    - date と異なり、色々なシステムがある為
    - 内部で path などを　decode() して実行　必要があれば　encode()
    - chXXX()のrecursive 削除
    
- FilesPathInterface　
    - 各methodは内部でcanonicalizeする



