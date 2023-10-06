#

## 221223

- 別件で従来機能でfactoryを検討
    - 呼び出し元project/Controllerで configファイルを検索
        - 存在すれば読み込み
        - 非存在なら不要
        - razy loadにするには?
            - 使用しないmethodもある?
                - 同じセッション内ならOK
                - 別セッションならContoller分ける?

############################

## 220504

- 全面再検討?
- Configの定義をsubprojectやDateConfig,NumberConfigなどファイルを分けて管理したい
- ファイルの指定を簡単にする為、基準となるPATHを定数っぽく取得できないか?

############################

#210823

- lint OK

#210822

- LogはPsr/ContainerInterfaceを持つ
- reader DIR, writer DIR 作成・移動




