<?
use Concerto\standard\ArrayUtil;

require_once ('../_function/ComFunc.php');

var_dump($_POST);echo "<hr>\r\n";

?>
<!DOCTYPE html>
<html lang="ja">
<head>
<? require_once('../../../_template/header_number_format.php'); ?>
<? require_once('../../../_template/header_input_helper.php'); ?>

<meta charset="UTF-8">
<style>

:invalid {
	border-color:#ff0000;
}


</style>
</head>
<body>

<form name="form1" method="post" action="" target="">
<div><input type="text" name="A1" value="" pattern="^\d+$">text整数</div>
<div><input type="text" name="A2" value="" pattern="^(\+|\-)?\d+$">text +-整数</div>
<div><input type="text" name="A2" value="" pattern="^[+,-]?[0-9]+(\\.[0-9]+)*$">text +-小数</div>

<div><input type="number" name="A3" value=""  onChange="cng_data(this)">number +-整数helper</div>
<div><input type="number" name="A4" value=""  onChange="cng_data(this)" step="0.01">number +-小数2桁helper</div>

<hr>
<div><input type="text" name="B1" value="" pattern="^20\d{6}$">yyyymmdd</div>
<div><input type="text" name="B2" value="" pattern="^20\d{4}$">yyyymm</div>
<div><input type="text" name="B2" value="" pattern="^\d{4}$">yymm</div>
<div><input type="text" name="B2" value="" pattern="^\d{2}:\d{2}$">hh:ii</div>

<hr>
<div><input type="text" name="C5" value="" pattern="^^[ぁ-ん]*$">ひらがなのみ</div>
<div><input type="text" name="C1" value="" pattern="^[^｡-ﾟ]*$">半角かな 禁止</div>
<div><input type="text" name="C1" value="" pattern="^[^｡-ﾟ\x00-\x09\x0b\x0c\x0e-\x1f]*$">半角かな 禁止 \r\n許可 他制御記号 禁止</div>
<div><input type="text" name="C2" value="" pattern="^[^｡-ﾟ\x00-\x1f\x7f]*$">半角かな 制御記号 禁止</div>
<div><input type="text" name="C3" value="" pattern="^[^｡-ﾟ\x00-\x1f\x7f\x22\x25\x27\x3c\x3e\x5c\x5f\x60]*$">半角かな 制御記号 "'%<>\_` 禁止</div>
<div><input type="text" name="C4" value="" pattern="^[^｡-ﾟ\x00-\x1f\x7f\x20-\x2f\x3a-\x40\x5b-\x60\x7b-\x7e]*$">半角かな 制御記号 記号 禁止</div>

<div><input type="text" name="C6" value="" pattern="^[^｡-ﾟ\x00-\x09\x0b\x0c\x0e-\x2f\x3a-\x40\x5b-\x60\x7b-\x7e]*$">半角かな 禁止 \r\n許可 他制御記号 禁止 記号禁止(textarea向け)</div>





<hr>
<div><input type="text" name="D1" value="" pattern="^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.[a-zA-Z0-9._-]*$">email</div>
<div><input type="text" name="D2" value="" pattern="^[a-zA-Z0-9.]+@[a-zA-Z0-9.]*toshiba.co.jp$">東芝email</div>

<hr>
<div><input type="text" name="E1" value="" pattern="^^[0-9]{2,4}-[0-9]{2,4}-[0-9]{3,4}$">TEL</div>
<div><input type="text" name="E2" value="" pattern="^^[0-9]{3}-[0-9]{4}$">郵便番号</div>

<hr>
<div><input type="text" name="F1" value="" pattern="^[0-9]{5}ITC*$">社員番号</div>
<div><input type="text" name="F2" value="" pattern="^[0-9,A-Z,a-z]{8}$">統一ユーザID</div>

<hr>
<div><input type="text" name="G1" value="" pattern="^[A-Z,0-9]{7,8}$">注番</div>
<div><input type="text" name="G2" value="" pattern="^[A-Z,0-9]{4,5}$">項番</div>
<div><input type="text" name="G3" value="" pattern="^(K|G)[A-Z,0-9]{3}[0-9]{5}((\-)([0-9]{2}))*$">注文番号</div>

<input type="submit" name="" value="送信">

</form>

</body>
<script>

function cng_data (elm)
{
	var val = numberInputHelper(elm.value);
	
	if (val === null) {
	 	alert("数値を入力してください");
	 	elm.value = 0;
	 } else {
	 	elm.value = val;
	 }
}


</script>
</html>
