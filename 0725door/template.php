?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8" />
</head>
<body>
    <form method="POST" enctype="multipart/form-data">
        <select name="aaa">
            <option value=""></option>
            <?php foreach($user_list as $list): ?>
            <option value="<?= $list['cd_tanto']; ?>"><?= $list['nm_tanto']; ?></option>
            <?php endforeach; ?>
        </select>
        
        <input type="password" name="bbb">
        <input type="password" name="ccc">
        <input type="password" name="ddd">
        
        <button>実行</button>
    </form>
</body>
</html>
