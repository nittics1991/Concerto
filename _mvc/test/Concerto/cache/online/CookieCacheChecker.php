<?php

/**
*   CookieCache online test
*
*   @version 190513
*/

declare(strict_types=1);

namespace test\Concerto\cache\online;

use Concerto\cache\CookieCache;

require(__DIR__ . '/../../bootstrap.php');

$result = (new CookieCacheCkecker())();

class CookieCacheCkecker
{
    private $namespace = 'test';
    private $keyName = 'counter';

    public function __invoke()
    {
        $id = $_GET['actionNo'] ?? 0;
        $method = "test{$id}";
        return $this->$method();
    }

    private function clean(): void
    {
        $obj = $this->init();
        $obj->clear();
    }

    private function init(): CookieCache
    {
        return new CookieCache($this->namespace);
    }

    private function getCookie()
    {
        return $_Cookie["{$this->namespace}_{$this->keyName}"];
    }

    /**
    *
    */
    public function test0()
    {
        $this->clean();
        $result['actionNo'] = 0;
        $result['title'] = '初期化';
        return $result;
    }

    /**
    *
    */
    public function test1()
    {
        $obj = $this->init();

        $result['actionNo'] = 1;
        $result['title'] = '初期状態確認';
        $result['has.null'] = false === $obj->has($this->keyName);
        $result['get.null'] = null === $obj->get($this->keyName);

        return $result;
    }

    /**
    *
    */
    public function test2()
    {
        $obj = $this->init();

        $result['actionNo'] = 2;
        $result['title'] = 'Cookie設定';

        $result['has.ok'] = false === $obj->has($this->keyName);
        $result['get.ok'] = null === $obj->get($this->keyName);

        $obj->set($this->keyName, 123, 60);
        $obj->set('DUMMY', 999);


        return $result;
    }

    /**
    *
    */
    public function test3()
    {
        $obj = $this->init();

        $result['actionNo'] = 3;
        $result['title'] = '削除実行';

        $result['has.ok'] = true === $obj->has($this->keyName);
        $result['get.ok'] = 123 === $obj->get($this->keyName);
        $result['get.ok.noexpire'] = 999 === $obj->get('DUMMY');

        $obj->delete($this->keyName);

        return $result;
    }
    /**
    *
    */
    public function test4()
    {
        $obj = $this->init();

        $result['actionNo'] = 4;
        $result['title'] = 'クリア';
        $result['has.ok'] = false === $obj->has($this->keyName);
        $result['get.ok'] = null === $obj->get($this->keyName);
        $result['get.ok.nodelete'] = 999 === $obj->get('DUMMY');

        $obj->clear();

        return $result;
    }

    /**
    *
    */
    public function test5()
    {
        $obj = $this->init();

        $result['actionNo'] = 5;
        $result['title'] = '複数設定';
        $result['has.ok'] = false === $obj->has($this->keyName);
        $result['get.ok'] = null === $obj->get($this->keyName);
        $result['get.ok.clear'] = 999 === $obj->get('DUMMY');

        $data = [
            'aaa' => 123,
            'bbb' => 456,
            'ccc' => 789,
        ];

        $obj->setMultiple($data, 90);

        return $result;
    }

    /**
    *
    */
    public function test6()
    {
        $obj = $this->init();

        $result['actionNo'] = 6;
        $result['title'] = '複数削除';
        $result['has.ok'] = true === $obj->has('aaa');
        $result['get.ok'] = 456 === $obj->get('bbb');
        $result['getMultiple.ok'] =  ['aaa' => 123, 'bbb' => 456, 'ccc' => 789] ===
            $obj->getMultiple(['aaa', 'bbb', 'ccc']);

        $obj->deleteMultiple(['aaa', 'ccc']);

        return $result;
    }

    /**
    *
    */
    public function test7()
    {
        $obj = $this->init();

        $result['actionNo'] = -1;
        $result['title'] = '複数削除確認';
        $result['has.ok'] = false === $obj->has('aaa');
        $result['get.ok'] = 999 === $obj->get('DUMMY');
        $result['getMultiple.ok'] =  ['aaa' => null, 'bbb' => 456, 'ccc' => null] ===
            $obj->getMultiple(['aaa', 'bbb', 'ccc']);

        $result['end'] = 'ブラウザを閉じて有効期限セッションデータを削除してください';
        return $result;
    }
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>CookieCache TEST</title>
</head>

<body>
<form name="form1" method="get" action="">
    実行番号:<input type="number" name="actionNo" value="<?= $result['actionNo'] + 1; ?>" min="0" max="9" style="width:50px;">
    <button type="submit">実行</button>

    <ul>
    <?php foreach ((array)$result as $key => $val) : ?>
    <li><?= $key; ?>=<?= $val; ?></li>
    <?php endforeach; ?>
    </ul>

</form>
</body>
</html>
