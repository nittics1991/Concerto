#

-------------------------------------------------------
## 240927

### ExcelSheetDirectWriter

PHPDDocに書いた注意事項があるが、
ExcelTemplateWriterより大きいデータを扱える

- 下記の問題がある
	- 空シートでは</row>は無く、<sheetData/>となる
	- ブックに文字列が無い場合SharedStrings.xmlが無い

- 従ってテンプレートシートには、データのタイトル行を必ず準備する
