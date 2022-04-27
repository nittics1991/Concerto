<? require_once('../_template/header_top.php'); ?>
<? require_once('../_template/header_jquery.php'); ?>
<? require_once('../_template/header_jquery_ui.php'); ?>
<? require_once('../_template/header_sigmagrid_css_std.php'); ?>
<? require_once('../_template/header_sigmagrid_js.php'); ?>
<? require_once('../_template/header_buttonmenu.php'); ?>
<? require_once('../_template/header_number_format.php'); ?>
<?php require_once('../_template/header_changebutton.php'); ?>

<title>直課計画立案画面</title>

<style>
#header {
}

#main {
    display:flex;
}

#grid {
    width:840px;
}

#entry {
}

ul {
    list-style-type:none;
}

#button {
    display:flex;
    width:400px;
}

.td1 {
    width:200px;
}

.text2 {
    width:70px;
}

</style>
</head>

<body>

<div id="header">

<table class="table-button">
<form name="form2" target="_self" method="GET">

<tr>
<td id="com_button"></td>
<td id="change_button"></td>
<td>
<select name="cd_bumon" onChange="sel_exec()" title="部門を選択します">
<option value=""></option>

<? foreach ((array)$bumon_list as $list): ?>
<option value="<?= $list['cd_bumon']; ?>" <? if ($list['cd_bumon'] == $cd_bumon) {echo'selected';} ?>><?= $list['nm_bumon']; ?></option>
<? endforeach; ?>

</select>
</td>

<td>
<select name="kb_nendo" onChange="sel_exec()" title="年度を選択します">
<option value=""> </option>

<? foreach ((array)$nendo_list as $list): ?>
<option value="<?= $list['kb_nendo']; ?>" <? if ($list['kb_nendo'] == $kb_nendo) {echo 'selected';} ?>><?= $list['nm_nendo']; ?></option>
<? endforeach; ?>

</select>
</td>

<td><a class="ui-state-default" href="cyokka_rituan_disp.php" target="_top" title="指定した条件でデータ表示を実行します">検索</a></td>
<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
<td><a class="ui-state-default" href="../index.php" target="_top" title="メニュー画面に戻ります">終了</a></td>
<td><a class="ui-state-default" href="/help/cyokka_rituan_disp.pdf" target="_blank" title="操作マニュアルを表示します">？</a></td>

</tr>
</form>
</table>

</div>

<div id="main">

<div id="grid">
    <div id="taskGrid"></div>
</div>

<div id="entry">
<form name="form1" target="_self" method="POST" enctype="multipart/form-data">
<input type="hidden" name="token" value="<?= $this->csrf; ?>">
    
<ul id="button">
    <? if (!$is_created): ?>
    <input type="hidden" name="act" value="insert">
    <? else: ?>
    <li class="act"><input type="radio" class="radio1" id="act_1" name="act" value="update" checked><label for="act_1">更新</label></li>
    <li class="act"><input type="radio" class="radio1" id="act_2" name="act" value="delete"><label for="act_2">削除</label></li>
    <? endif; ?>
    
    <li><a class="ui-state-default" href="#" target="_self" onClick="act_exec()" title="データを保存します">保存/実行</a></li>
    <li><a class="ui-state-default" href="#" target="_self" onClick="cancel_exec()" title="入力データをクリアします">キャンセル</a></li>
</ul>

<table id="bumon-info">
    <tbody>
    <tr>
        <th class="th1">部門</th>
        <td class="td1"><input type="text" class="text1 input-text-readonly" name="cd_bumon" value="<?= $cd_bumon; ?>" required pattern="<?= $this->pattern->bumon; ?>" readonly tabindex="1"></td>
    </tr>
    
    <tr>
        <th class="th1">年度</th>
        <td class="td1"><input type="text" class="text1 input-text-readonly" name="kb_nendo" value="<?= $kb_nendo; ?>" required pattern="<?= $this->pattern->nendo; ?>" readonly tabindex="2"></td>
    </tr>
    
    <tr>
        <th class="th1">直課対象人数</th>
        <td class="td1"><input type="number" class="text1 input-text-readonly" name="su_cyokka" value="<?= $su_cyokka; ?>" required min="0" max="100"  readonly tabindex="3"></td>
    </tr>
    
    <tr>
        <th class="th1">直課率</th>
        <td class="td1"><input type="number" class="text1" name="ri_cyokka" value="<?= $ri_cyokka; ?>" required min="0" max="100" tabindex="4"></td>
    </tr>
    
    <tr>
        <th class="th1">直課単価</th>
        <td class="td1"><input type="number" class="text1" name="yn_tanka" value="<?= $yn_tanka; ?>" required min="0" max="50000" tabindex="5"></td>
    </tr>
    
    <tr>
        <th class="th1">残業時間</th>
        <td class="td1"><input type="number" class="text1" name="tm_zangyo" value="<?= $tm_zangyo; ?>" required min="0" max="100" tabindex="6"></td>
    </tr>
    
    <tr>
        <th class="th1">出勤率</th>
        <td class="td1"><input type="number" class="text1" name="ri_syukkin" value="<?= $ri_syukkin; ?>" required min="0" max="100" tabindex="7"></td>
    </tr>
    
    </tbody>
</table>

<table id="monthly-info">
    <tbody>
    <tr>
        <th class="th2"></th>
        <th class="th2">日数</th>
        <th class="th2">保有直課</th>
        <th class="th2">予算(k)</th>
        <th class="th2">損益(k)</th>
    </tr>
    
    <? $i=0; ?>
    <? foreach ($cyokka_mon_list as $list): ?>
    <? $i++; ?>
    <tr>
        <th><?= $list['dt_month']; ?>
        <td><input type="number" class="text2 input-text-right" name="dt_kado[]" min="0" max="31" step="0.01" value="<?= $list['dt_kado']; ?>" tabindex="<?= "1{$i}"; ?>"></td>
        <td><input type="number" class="text2 input-text-right" name="tm_hoyu_cyokka[]" min="0" max="10000" value="<?= $list['tm_hoyu_cyokka']; ?>" tabindex="<?= "2{$i}"; ?>"></td>
        <td><input type="number" class="text2 input-text-right" name="yn_yosan[]" min="0" max="1000000" value="<?= $list['yn_yosan']; ?>" tabindex="<?= "3{$i}"; ?>"></td>
        <td><input type="number" class="text2 input-text-right" name="yn_soneki[]" min="-1000000" max="1000000" value="<?= $list['yn_soneki']; ?>" tabindex="<?= "4{$i}"; ?>"></td>
    </tr>
    <? endforeach; ?>
    
    </tbody>
</table>

<div id="description">
    <dl>
        <dt>一覧表　計算式</dt>
        <dd>定時間＝日数×出勤率×7.75</dd>
        <dd>実働時間＝定時間＋残業時間</dd>
        <dd>直課時間＝実働時間×直課率</dd>
    </dl>
    
    <dl>
        <dt>一覧表　直課時間・保有直課</dt>
        <dd>負荷山の「保有」欄に表示する</dd>
        <dd>保有直課に登録があれば、「保有」欄は保有直課を表示する</dd>
        <dd>保有直課が０の場合、「保有」欄は直課時間を表示する</dd>
    </dl>
    
    <dl>
        <dt>入力欄　直課対象人数</dt>
        <dd>担当者マスタで直課率＞０の人数</dd>
    </dl>
    
    <dl>
        <dd>入力欄　日数・保有工数予算・損益について</dd>
        <dd>予実算の実行予算を参考に登録する</dd>
        <dd>保有工数は総直課時間を登録する</dd>
        <dd>予算・損益は営業と製造で異なる</dd>
        <dd>営業部門は売上高・販売粗利益を登録する</dd>
        <dd>製造部門は生産高・製番損益を登録する</dd>
    </dl>
    
    <dl>
        <dt>注意</dt>
        <dd>担当者マスタで直課率を変更したら、本画面を再保存する事</dd>
    </dl>
</div>

</form>
</div>
</body>

<script>

(function() {
    new Concerto.ButtonMenu('#com_button');
    new Concerto.ChangeButton('#change_button');
})();

(function() {
    var dsOption = {
        fields :[ 
            {name:'dt_yyyymm'},
            {name:'dt_kado', type:'float'},
            {name:'tm_hoyu_cyokka', type:'int'},
            {name:'yn_yosan', type:'int'},
            {name:'yn_soneki', type:'int'},
            {name:'tm_teizikan', type:'float'},
            {name:'tm_zangyo', type:'float'},
            {name:'tm_zitudo', type:'float'},
            {name:'tm_cyokka', type:'float'},
            {name:'dt_kado_m', type:'float'},
            {name:'tm_teizikan_m', type:'float'},
            {name:'tm_zangyo_m', type:'float'},
            {name:'tm_zitudo_m', type:'float'},
            {name:'tm_cyokka_m  ', type:'float'},
        ],
        recordType: 'object'
    };
    
    var colsOption = [
        {id:'dt_yyyymm', header:"年月", width:60, headAlign:"center", align:"center", sortable:true, frozen:true},
        {id:'dt_kado', header:"日数", width:40, headAlign:"center", align:"right", sortable:true, frozen:true},
        {id:'tm_hoyu_cyokka', header:"保有直課", width:60, headAlign:"center", align:"right", sortable:true, frozen:true,
            renderer:function(value ,record,colObj,grid,colNo,rowNo) {
                return number_format(value);
            }
        },
        {id:'yn_yosan', header:"予算", width:80, headAlign:"center", align:"right", sortable:true, frozen:true,
            renderer:function(value ,record,colObj,grid,colNo,rowNo) {
                return number_format(value);
            }
        },
        {id:'yn_soneki', header:"損益", width:80, headAlign:"center", align:"right", sortable:true, frozen:true,
            renderer:function(value ,record,colObj,grid,colNo,rowNo) {
                return number_format(value);
            }
        },
        {id:'tm_teizikan', header:"定時間合計", width:60, headAlign:"center", align:"right", sortable:true, frozen:false,
            renderer:function(value ,record,colObj,grid,colNo,rowNo) {
                return number_format(value);
            }
        },
        {id:'tm_zangyo', header:"残業合計", width:60, headAlign:"center", align:"right", sortable:true, frozen:false,
            renderer:function(value ,record,colObj,grid,colNo,rowNo) {
                return number_format(value);
            }
        },
        {id:'tm_zitudo', header:"実働合計", width:60, headAlign:"center", align:"right", sortable:true, frozen:false,
            renderer:function(value ,record,colObj,grid,colNo,rowNo) {
                return number_format(value);
            }
        },
        {id:'tm_cyokka', header:"直課合計", width:60, headAlign:"center", align:"right", sortable:true, frozen:false,
            renderer:function(value ,record,colObj,grid,colNo,rowNo) {
                return number_format(value);
            }
        },
        {id:'tm_teizikan_m', header:"定時間", width:60, headAlign:"center", align:"right", sortable:true, frozen:false,
            renderer:function(value ,record,colObj,grid,colNo,rowNo) {
                return number_format(value, 2);
            }
        },
        {id:'tm_zangyo_m', header:"残業時間", width:60, headAlign:"center", align:"right", sortable:true, frozen:false,
            renderer:function(value ,record,colObj,grid,colNo,rowNo) {
                return number_format(value, 2);
            }
        },
        {id:'tm_zitudo_m', header:"実働時間", width:60, headAlign:"center", align:"right", sortable:true, frozen:false,
            renderer:function(value ,record,colObj,grid,colNo,rowNo) {
                return number_format(value, 2);
            }
        },
        {id:'tm_cyokka_m', header:"直課時間", width:60, headAlign:"center", align:"right", sortable:true, frozen:false,
            renderer:function(value ,record,colObj,grid,colNo,rowNo) {
                return number_format(value, 2);
            }
        },
    ];
    
    <? require_once('../_template/sigmagrid_gridoption.php'); ?>
    gridOption.loadURL = 'cyokka_rituan_grid.php';

    gridOption.toolbarContent = gridOption.toolbarContent.replace('| state ', '') + '| sort | confsave confdel download upload | state';
    
    <? require_once('../_template/sigmagrid_height.php'); ?>
    <? require_once('../_template/sigmagrid_config.php'); ?>
    <? require_once('../_template/sigmagrid_render.php'); ?>
})();

function sel_exec()
{
    document.form2.submit();
}

function act_exec()
{
    if (!document.form1.checkValidity()) {
        alert("入力に不正があります");
        return;
    }
    document.form1.submit();
}

function cancel_exec()
{
    var elms = document.querySelectorAll('#entry input[type="number"]');
    
    Array.prototype.forEach.call(elms, function(elm) {
        if (!elm.hasAttribute('readonly')) {
            elm.value='';
        }
    });
}

</script>
</html>
