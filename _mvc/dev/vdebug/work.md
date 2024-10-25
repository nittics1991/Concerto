

# 20309

## 作業

実行成功

- xdebug インストール
    - sudo apt insall php8.1-xdebug

- vdebug 準備
    - nittics2002/vim インストール
    - bin/set_vimrc 実行
    - bin/install_vdebug 実行
    - 環境変数設定
        - XDEBUG_SESSION=1
        - XDEBUG_CONFIG="client_host=localhost client_port=9000"
        - vdebug の　ポート初期値が 9000
        - xdebug の　ポート初期値が 9003
            - 揃えるため、vdebug と php の設定 9000 を、明示して指定

- php local server 起動
    - php \
        -d xdebug.mode=debug -\
        -d xdebug.client_host=127.0.0.1 -\
        -d xdebug.client_port=9000 -\
        -S 127.0.0.1:8080 {target.php}
        
    - サーバとvimが同じホスト上での設定の場合 xdebug.mode=debug だけ
    - サーバ名 localhost ではアクセスエラー

- ブラウザアクセス
    - http://127.0.0.1:8080/{target.php}
    - Xdebug helper の アイコンを debug に　設定

- GET キーの場合
    - XDEBUG_SESSION_START={session_name} をクエリに追加する

- Xdebug helper for firefox addon インストールの場合
    - IDE key type = other
    - IDE key = {session_name}

- vim操作
    - vim {target.php} 開く
    - F5 で コネクション待機
    - ブラウザでアクセスすると、コネクションが接続され、スタート待機する




# 210407
F5, run to the next break point not working

- 参考 [issue](https://github.com/vim-vdebug/vdebug/issues/196)

