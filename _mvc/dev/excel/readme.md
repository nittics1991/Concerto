#

##

##


```php

$excel = new ExcelTemplate();

$book = $excel->open($file_path);

//createSheet�͖���
$sheet = $book->createSheet($sheet_name);

$sheet = $book->sheets($sheet_name);
	$sheet = $sheet->addData($address, $data);
	$sheet = $sheet->expandData();

$sheet = $book->loadSheet($sheet_name);
	$array2d = $sheet->toArray();
	//rowData�͖���
	$array = $sheet->rowData($row_no);

//close()�ł�sheet��ۑ�����
//$book = $book->save();
$book->close();
	//�eExcelSheet��mappedData��xml�ɏ�������

$excel->download($download_file_name);
$excel->saveAs($file_name);

```

- ExcelBook�ŊeOOXML�t�@�C���𑀍삷��ExcelContents������
- fuluent pattern�̎����́AExcelTemplate��method������
	- �]����$excel�ł�method���s�̏ꍇ�A���s����book,sheet�̏���ێ�����

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

