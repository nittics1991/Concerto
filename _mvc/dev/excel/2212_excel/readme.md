#

## 230224

### exceldatatableライブラリを使用する方針に見直す

### 利用できそうな別機能

- excelシートの任意の位置にarrayをマップするRangeObj
    - toArray()でcontainerに格納された$x,$xの位置にマッピング
    - 存在しなきキーの値はnullで埋める
        -


##221224

- ExcelSheetWriter 作成
- ExcelBookで使用する事
- 別途ExcelSheetAddManagerをfacadeで準備?


##資料

MS SpreadsheetML ドキュメントの構造 (Open XML SDK)
https://learn.microsoft.com/ja-jp/office/open-xml/structure-of-a-spreadsheetml-document

Office Open XML Formats入門 第2版 SpreadsheetML
https://www.antenna.co.jp/office/publication/part_spreadsheetml/part_spreadsheetml.html

numfmtid
https://social.msdn.microsoft.com/Forums/office/en-US/e27aaf16-b900-4654-8210-83c5774a179c/xlsx-numfmtid-predefined-id-14-doesnt-match?forum=oxmlsdk
