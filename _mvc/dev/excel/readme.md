#

##

##


```php

$excel = new ExcelTemplate();

$book = $excel->open($file_path);

//createSheetは無し
$sheet = $book->createSheet($sheet_name);

$sheet = $book->sheets($sheet_name);
	$sheet = $sheet->addData($address, $data);
	$sheet = $sheet->expandData();

$sheet = $book->loadSheet($sheet_name);
	$array2d = $sheet->toArray();
	//rowDataは無し
	$array = $sheet->rowData($row_no);

//close()でなsheetを保存する
//$book = $book->save();
$book->close();
	//各ExcelSheetのmappedDataをxmlに書き込む

$excel->download($download_file_name);
$excel->saveAs($file_name);

```

- ExcelBookで各OOXMLファイルを操作するExcelContentsを扱う
- fuluent patternの実装は、ExcelTemplateでmethodを持つ
	- 従って$excelでのmethod実行の場合、実行中のbook,sheetの情報を保持する

```php

(new ExcelTemplate())
	->open($file_path)
	->sheets($sheet_name)
	->addData($address, $data)
	->expandData()
	->close()
	->loadSheet($sheet_name)
	->addData($address2, $data2)
	->addData($address3, $data3)
	->expandData()
	->close()
	->download($download_file_name):

```

