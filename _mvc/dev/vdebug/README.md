# vdebug

vim-vdebugの動作テスト用

## 資料

[vim-vdebug home](https://github.com/vim-vdebug/vdebug)
[vim-vdebug docs](https://github.com/vim-vdebug/vdebug/blob/master/doc/Vdebug.txt)

[xdebug home](https://xdebug.org/)
[xdebug step_debug](https://xdebug.org/docs/step_debug)

## 使い方

### vdebugインストール

- ~/.vim/pack/{project_name}/start/以下にgit cloneする
- vim１を起動
- :helptags ~/.vim/pack/{project_name}/start/vdebug/doc でヘルプを読み込み
- :h vdebug で表示を確認

### xdebugインストール

- 以下は ver3 での使用方法

### コマンドラインデバッグ

- 環境変数を設定する

> `XDEBUG_SESSION=1`
> `XDEBUG_CONFIG="client_host=localhost client_port=9000"`
>> xdebugのportは初期値=9003
>> vdebugのportは初期値=9000
>> vdebugの変更は手間がかかるため、xdebug側を変更する

- phpコマンド実行。同時にINI設定を行う

> `php -d xdebug.mode=debug {ソースファイル}`

### vimデバッグ準備

- vim {ソースファイル} を開く
- [F5]でvdebug connectionの接続待ちにする (debian Fn+F5)

> Vdebug will wait for a connection


## デバッグ

- vimで[F5]を実行し、接続が成功していると、デバッグウィンドウが開く
- デバッグウィンドウがの分割枠はマウスで移動可能
- [F5]でスクリプトを実行開始
- [F6]で終了。2回目バッグウィンドウを閉じる
- [F9]でカーソル位置まで実行
- [F2]step_over
- [F3]step_in
- [F4]step_out
- [F10]breakpoint設定

> 他のキーはテストした環境では上手くない

## vimコマンドでの設定

- `:VdebugOpt' portなどの設定値を変更する
> 変更はvimrcに書くか、[F5]でconncet待ち時に実行

- ':Breakpoint' ブレークポイントの設定 ライン指定lineコマンドない?


## firefoxデバッグ

- addon　Xdebug Helperがxdebug docsに紹介されているが、ver2時代のもの?
> __要確認__







