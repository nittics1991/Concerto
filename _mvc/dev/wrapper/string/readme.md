#

## 220507

- method nameは判定できる最小単語で

- BytesFunctionTrait
    - mb_strcut





##

//bool-none

mb_ereg_search_setpos(int $offset): bool

mb_ereg_search(?string $pattern = null, ?string $options = null): bool


//bool-none-related

mb_ereg(string $pattern, string $string, array &$matches = null): bool


//bool-first

    mb_check_encoding(array|string|null $value = null, ?string $encoding =
       null): bool

    mb_send_mail(
           string $to,
           string $subject,
           string $message,
           array|string $additional_headers = [],
           ?string $additional_params = null
       ): bool

mb_ereg_search_init(string $string, ?string $pattern = null, ?string
   $options = null): bool


//bool-first-related

    mb_parse_str(string $string, array &$result): bool


//bool-second-related

mb_eregi(string $pattern, string $string, array &$matches = null): bool

mb_ereg(string $pattern, string $string, array &$matches = null): bool


//bool|array-none

    mb_detect_order(array|string|null $encoding = null): array|bool

//bool|string-none

    mb_http_output(?string $encoding = null): string|bool

    mb_internal_encoding(?string $encoding = null): string|bool

    mb_language(?string $language = null): string|bool

    mb_regex_encoding(?string $encoding = null): string|bool


//string-first

mb_convert_case(string $string, int $mode, ?string $encoding = null):
   string

mb_convert_kana(string $string, string $mode = "KV", ?string $encoding
   = null): string

    mb_decode_mimeheader(string $string): string

    mb_decode_numericentity(string $string, array $map, ?string $encoding =
       null): string

    mb_encode_mimeheader(
           string $string,
           ?string $charset = null,
           ?string $transfer_encoding = null,
           string $newline = "\r\n",
           int $indent = 0
       ): string

    mb_encode_numericentity(
           string $string,
           array $map,
           ?string $encoding = null,
           bool $hex = false
       ): string

    mb_output_handler(string $string, int $status): string

    mb_regex_set_options(?string $options = null): string

mb_strcut(
       string $string,
       int $start,
       ?int $length = null,
       ?string $encoding = null
   ): string

mb_strimwidth(
       string $string,
       int $start,
       int $width,
       string $trim_marker = "",
       ?string $encoding = null
   ): string

mb_strtolower(string $string, ?string $encoding = null): string

mb_strtoupper(string $string, ?string $encoding = null): string

mb_substr(
       string $string,
       int $start,
       ?int $length = null,
       ?string $encoding = null
   ): string


//string|false-first

mb_convert_encoding(array|string $string, string $to_encoding,
   array|string|null $from_encoding = null): array|string|false

mb_preferred_mime_name(string $encoding): string|false

    mb_scrub(string $string, ?string $encoding = null): string

mb_stristr(
       string $haystack,
       string $needle,
       bool $before_needle = false,
       ?string $encoding = null
   ): string|false

mb_strrchr(
       string $haystack,
       string $needle,
       bool $before_needle = false,
       ?string $encoding = null
   ): string|false

mb_strrichr(
       string $haystack,
       string $needle,
       bool $before_needle = false,
       ?string $encoding = null
   ): string|false

mb_strstr(
       string $haystack,
       string $needle,
       bool $before_needle = false,
       ?string $encoding = null
   ): string|false


//string|false-second

mb_chr(int $codepoint, ?string $encoding = null): string|false

    mb_detect_encoding(string $string, array|string|null $encodings = null,
       bool $strict = false): string|false


//string|false-therd&

mb_convert_variables(
       string $to_encoding,
       array|string $from_encoding,
       mixed &$var,
       mixed &...$vars
   ): string|false


//string|false|null-therd

mb_ereg_replace_callback(
       string $pattern,
       callable $callback,
       string $string,
       ?string $options = null
   ): string|false|null

mb_ereg_replace(
       string $pattern,
       string $replacement,
       string $string,
       ?string $options = null
   ): string|false|null

mb_eregi_replace(
       string $pattern,
       string $replacement,
       string $string,
       ?string $options = null
   ): string|false|null


//string|int|bool

mb_substitute_character(string|int|null $substitute_character = null):
   string|int|bool


//int-none

mb_ereg_search_getpos(): int


//int-first

mb_strlen(string $string, ?string $encoding = null): int

mb_strwidth(string $string, ?string $encoding = null): int


//int|false-first

mb_ord(string $string, ?string $encoding = null): int|false

mb_stripos(
       string $haystack,
       string $needle,
       int $offset = 0,
       ?string $encoding = null
   ): int|false

mb_strpos(
       string $haystack,
       string $needle,
       int $offset = 0,
       ?string $encoding = null
   ): int|false

mb_strripos(
       string $haystack,
       string $needle,
       int $offset = 0,
       ?string $encoding = null
   ): int|false

mb_strrpos(
       string $haystack,
       string $needle,
       int $offset = 0,
、     ?string $encoding = null
   ): int|false

mb_substr_count(string $haystack, string $needle, ?string $encoding =
   null): int


//array|false-none

mb_ereg_search_getregs(): array|false

mb_ereg_search_pos(?string $pattern = null, ?string $options = null):
    array|false

mb_ereg_search_regs(?string $pattern = null, ?string $options = null):
   array|false


//array-first

    mb_encoding_aliases(string $encoding): array

    mb_list_encodings(): array

mb_str_split(string $string, int $length = 1, ?string $encoding =
   null): array


//array|false-second

mb_split(string $pattern, string $string, int $limit = -1): array|false


//array|string|int|false-first

    mb_get_info(string $type = "all"): array|string|int|false





/////////////////////////////////////////////////

    mb_check_encoding — 文字列が、指定したエンコーディングで有効なものかどうか  

mb_chr — Unicode のコードポイントに対応する文字を返す

mb_convert_case — 文字列に対してケースフォールディングを行う
mb_convert_encoding — 文字エンコーディングを変換する
mb_convert_kana — カナを("全角かな"、"半角かな"等に)変換する
mb_convert_variables — 変数の文字コードを変換する

    mb_decode_mimeheader — MIME ヘッダフィールドの文字列をデコードする
    mb_decode_numericentity — HTML 数値エンティティを文字にデコードする

    mb_detect_encoding — 文字エンコーディングを検出する
    mb_detect_order — 文字エンコーディング検出順序を設定あるいは取得する

    mb_encode_mimeheader — MIMEヘッダの文字列をエンコードする
    mb_encode_numericentity — 文字を HTML 数値エンティティにエンコードする
    mb_encoding_aliases — 既知のエンコーディング・タイプのエイリアスを取得

mb_ereg_match — マルチバイト文字列が正規表現に一致するか調べる
mb_ereg_replace_callback — マルチバイト文字列にコールバック関数を用いた正規表現による置換を行う
mb_ereg_replace — マルチバイト文字列に正規表現による置換を行う
mb_ereg_search_getpos — 次の正規表現検索を開始する位置を取得する
mb_ereg_search_getregs — マルチバイト文字列が正規表現に一致する部分があるか調べる
mb_ereg_search_init — マルチバイト正規表現検索用の文字列と正規表現を設定する
mb_ereg_search_pos — 指定したマルチバイト文字列が正規表現に一致する部分の位置と長さを返す
mb_ereg_search_regs — 指定したマルチバイト文字列が正規表現に一致する部分を取得する
mb_ereg_search_setpos — 次の正規表現検索を開始する位置を設定する
mb_ereg_search — 指定したマルチバイト文字列が正規表現に一致するか調べる
mb_ereg — マルチバイト対応の正規表現マッチ
mb_eregi_replace — マルチバイト文字列に大文字小文字を区別せずに正規表現による置換を行う
mb_eregi — マルチバイトをサポートし、大文字小文字を無視した正規表現マッチ

    mb_get_info — mbstring の内部設定値を取得する
    mb_http_input — HTTP 入力文字エンコーディングを検出する
    mb_http_output — HTTP 出力文字エンコーディングを設定あるいは取得する
    mb_internal_encoding — 内部文字エンコーディングを設定あるいは取得する
    mb_language — 現在の言語を設定あるいは取得する
    mb_list_encodings — サポートするすべてのエンコーディングの配列を返す

mb_ord — 文字の Unicode コードポイントを取得する

    mb_output_handler — 出力バッファ内で文字エンコーディングを変換するコールバック関数
    mb_parse_str — GET/POST/COOKIE データをパースし、グローバル変数を設定する
    mb_preferred_mime_name — MIME 文字設定を文字列で得る

    mb_regex_encoding — 現在のマルチバイト正規表現用のエンコーディングを取得または設定する
    mb_regex_set_options — マルチバイト正規表現関数のデフォルトオプションを取得または設定する

    mb_scrub — 文字列に含まれる不正なバイト列を代替文字に置き換える
    mb_send_mail — エンコード変換を行ってメールを送信する

mb_split — マルチバイト文字列を正規表現により分割する
mb_str_split — マルチバイト文字列を受取り、文字の配列を返す
mb_strcut — 文字列の一部を得る
mb_strimwidth — 指定した幅で文字列を丸める
mb_stripos — 大文字小文字を区別せず、 文字列の中で指定した文字列が最初に現れる位置を探す
mb_stristr — 大文字小文字を区別せず、 文字列の中で指定した文字列が最初に現れる位置を探す
mb_strlen — 文字列の長さを得る
mb_strpos — 文字列の中に指定した文字列が最初に現れる位置を見つける
mb_strrchr — 別の文字列の中で、ある文字が最後に現れる場所を見つける
mb_strrichr — 大文字小文字を区別せず、 別の文字列の中である文字が最後に現れる場所を探す
mb_strripos — 大文字小文字を区別せず、 文字列の中で指定した文字列が最後に現れる位置を探す
mb_strrpos — 文字列の中に指定した文字列が最後に現れる位置を見つける
mb_strstr — 文字列の中で、指定した文字列が最初に現れる位置を見つける
mb_strtolower — 文字列を小文字にする
mb_strtoupper — 文字列を大文字にする
mb_strwidth — 文字列の幅を返す
    mb_substitute_character — 置換文字を設定あるいは取得する
    mb_substr_count — 部分文字列の出現回数を数える
mb_substr — 文字列の一部を得る


/////////////////////////////////////////////////

    addcslashes — C 言語と同様にスラッシュで文字列をクォートする
    addslashes — 文字列をスラッシュでクォートする
    bin2hex — バイナリのデータを16進表現に変換する
    chop — rtrim のエイリアス
    chr — 数値から、1バイトの文字列を生成する
chunk_split — 文字列をより小さな部分に分割する
    convert_cyr_string — キリル文字セットを他のものに変換する
    convert_uudecode — uuencode された文字列をデコードする
    convert_uuencode — 文字列を uuencode する
    count_chars — 文字列で使用されている文字に関する情報を返す
    crc32 — 文字列の crc32 多項式計算を行う
    crypt — 文字列の一方向のハッシュ化を行う
    echo — 1 つ以上の文字列を出力する
explode — 文字列を文字列により分割する
    fprintf — フォーマットされた文字列をストリームに書き込む
    get_html_translation_table — htmlspecialchars および htmlentities
        される変換テーブルを返す
    hebrev — 論理表記のヘブライ語を物理表記に変換する
    hebrevc — 論理表記のヘブライ語を、改行の変換も含めて物理表記に変換する
    hex2bin — 16進エンコードされたバイナリ文字列をデコードする
    html_entity_decode — HTML エンティティを対応する文字に変換する
    htmlentities — 適用可能な文字を全て HTML エンティティに変換する
    htmlspecialchars_decode — 特殊な HTML エンティティを文字に戻す
    htmlspecialchars — 特殊文字を HTML エンティティに変換する
implode — 配列要素を文字列により連結する
join — implode のエイリアス
lcfirst — 文字列の最初の文字を小文字にする
    levenshtein — 二つの文字列のレーベンシュタイン距離を計算する
    localeconv — 数値に関するフォーマット情報を得る
ltrim — 文字列の最初から空白 (もしくはその他の文字) を取り除く
    md5_file — 指定したファイルのMD5ハッシュ値を計算する
    md5 — 文字列のmd5ハッシュ値を計算する
    metaphone — 文字列の metaphone キーを計算する
    money_format — 数値を金額文字列にフォーマットする
    nl_langinfo — 言語およびロケール情報を検索する
    nl2br — 改行文字の前に HTML の改行タグを挿入する
    number_format — 数字を千の位毎にグループ化してフォーマットする
    ord — 文字列の先頭バイトを、0 から 255 までの値に変換する
    parse_str — 文字列を処理し、変数に代入する
    print — 文字列を出力する
    printf — フォーマット済みの文字列を出力する
    quoted_printable_decode — quoted-printable 文字列を 8 ビット文字列に変換する
    quoted_printable_encode — 8 ビット文字列を quoted-printable 文字列に変換する
    quotemeta — メタ文字をクォートする
rtrim — 文字列の最後から空白 (もしくはその他の文字) を取り除く
    setlocale — ロケール情報を設定する
    sha1_file — ファイルの sha1 ハッシュを計算する
    sha1 — 文字列の sha1 ハッシュを計算する
    similar_text — 二つの文字列の間の類似性を計算する
    soundex — 文字列の soundex キーを計算する
    sprintf — フォーマットされた文字列を返す
    sscanf — フォーマット文字列に基づき入力を処理する
str_contains — 指定された部分文字列が、文字列に含まれるかを調べる
str_ends_with — 文字列が、指定された文字列で終わるかを調べる。
    str_getcsv — CSV 文字列をパースして配列に格納する
str_ireplace — 大文字小文字を区別しない str_replace
str_pad — 文字列を固定長の他の文字列で埋める
str_repeat — 文字列を反復する
str_replace — 検索文字列に一致したすべての文字列を置換する
    str_rot13 — 文字列に rot13 変換を行う
str_shuffle — 文字列をランダムにシャッフルする
str_split — 文字列を配列に変換する
str_starts_with — 文字列が指定された部分文字列で始まるかを調べる
str_word_count — 文字列に使用されている単語についての情報を返す
strcasecmp — 大文字小文字を区別しないバイナリセーフな文字列比較を行う
strchr — strstr のエイリアス
strcmp — バイナリセーフな文字列比較
strcoll — ロケールに基づく文字列比較
strcspn — マスクにマッチしない最初のセグメントの長さを返す
    strip_tags — 文字列から HTML および PHP タグを取り除く
    stripcslashes — addcslashes でクォートされた文字列をアンクォートする
stripos — 大文字小文字を区別せずに文字列が最初に現れる位置を探す
stripslashes — クォートされた文字列のクォート部分を取り除く
stristr — 大文字小文字を区別しない strstr
strlen — 文字列の長さを得る
strnatcasecmp — "自然順"アルゴリズムにより大文字小文字を区別しない文字列比較を行う
strnatcmp — "自然順"アルゴリズムにより文字列比較を行う
strncasecmp — バイナリセーフで大文字小文字を区別しない文字列比較を、最初の n 文字について行う
strncmp — 最初の n 文字についてバイナリセーフな文字列比較を行う
strpbrk — 文字列の中から任意の文字を探す
strpos — 文字列内の部分文字列が最初に現れる場所を見つける
strrchr — 文字列中に文字が最後に現れる場所を取得する
strrev — 文字列を逆順にする
strripos — 文字列中で、特定の(大文字小文字を区別しない)文字列が最後に現れた位置を探す
strrpos — 文字列中に、ある部分文字列が最後に現れる場所を探す
strspn — 指定したマスク内に含まれる文字からなる文字列の最初のセグメントの長さを探す
strstr — 文字列が最初に現れる位置を見つける
strtok — 文字列をトークンに分割する
strtolower — 文字列を小文字にする
strtoupper — 文字列を大文字にする
strtr — 文字の変換あるいは部分文字列の置換を行う
substr_compare — 指定した位置から指定した長さの 2 つの文字列について、バイナリ対応で比較する
substr_count — 副文字列の出現回数を数える
substr_replace — 文字列の一部を置換する
substr — 文字列の一部分を返す
trim — 文字列の先頭および末尾にあるホワイトスペースを取り除く
ucfirst — 文字列の最初の文字を大文字にする
ucwords — 文字列の各単語の最初の文字を大文字にする
    vfprintf — フォーマットされた文字列をストリームに書き込む
    vprintf — フォーマットされた文字列を出力する
    vsprintf — フォーマットされた文字列を返す
    wordwrap — 指定した文字数で文字列を分割する

/////////////////////////////////////////////////


String.prototype.length
  文字列の length を反映します。読み取り専用です。


String.prototype.charAt(index)
  index で指定された位置の文字 (UTF-16 コード 1 つから成ります) を返します。

String.prototype.charCodeAt(index)
  index で与えられた位置の文字の UTF-16 の値を示す数を返します。

String.prototype.codePointAt(pos)
  pos で指定された位置から始まる UTF-16
  エンコードされた際のコードポイントの、コードポイントの値である正の整数を返します。

String.prototype.concat(str [, ...strN ])
  2 つ (以上) の文字列を連結し、新しい文字列を返します。

String.prototype.includes(searchString [, position])
  文字列中に searchString が含まれているかを返します。

String.prototype.endsWith(searchString [, length])
  文字列の末尾に指定された文字列 searchString が含まれているかを返します。

String.prototype.indexOf(searchValue [, fromIndex])
  呼び出す [94]String オブジェクト中で、 searchValue が最初に現れる位置を返します。見つからなかった場合は
  -1 を返します。

String.prototype.lastIndexOf(searchValue [, fromIndex])
  呼び出す [96]String オブジェクト中で、 searchValue が最後に現れる位置を返します。見つからない場合は
  -1 を返します。

String.prototype.localeCompare(compareString [, locales [,
  options]])
  参照文字列 compareString
  が、並べ替え順において、与えられた文字列の前になるか後になるか、あるいは、同じかどうかを示す数値を返します。

String.prototype.match(regexp)
  文字列に対して正規表現 regexp を一致させるために使用されます。

String.prototype.matchAll(regexp)
  regexp が一致するものすべてのイテレーターを返します。

String.prototype.normalize([form])
 呼び出された文字列の値の Unicode 正規化形式を返します。

String.prototype.padEnd(targetLength [, padString])
 現在の文字列の末尾から指定された文字列で埋めた、長さ targetLength 文字の新たな文字列を返します。

String.prototype.padStart(targetLength [, padString])
 現在の文字列の先頭から指定した文字列で埋めた、長さ targetLength 文字の新たな文字列を作成します。

String.prototype.repeat(count)
 オブジェクトの要素を count 回繰り返した文字列を返します。

String.prototype.replace(searchFor, replaceWith)
 searchFor が現れたところを replaceWith で置換するために使用します。 searchFor
 は文字列または正規表現であり、 replaceWith は文字列または関数です。

String.prototype.replaceAll(searchFor, replaceWith)
 searchFor が現れたところすべてを replaceWith で置換するために使用します。 searchFor
 は文字列または正規表現であり、 replaceWith は文字列または関数です。

String.prototype.search(regexp)
 正規表現 regexp と呼び出された文字列が一致するところを検索します。

String.prototype.slice(beginIndex[, endIndex])
 文字列の一区間を取り出し、新しい文字列を返します。

String.prototype.split([sep [, limit] ])
 呼び出した文字列を、部分文字列 sep が現れるところで分割し、文字列の配列を生成して返します。

String.prototype.startsWith(searchString [, length])
 呼び出した文字列が文字列 searchString で開始されているかを判断します。

String.prototype.substr()
 文字列において、指定された位置から指定された文字数の文字を返します。

String.prototype.substring(indexStart [, indexEnd])
 呼び出した文字列の指定された位置以降 (または区間) にある文字が入った新しい文字列を返します。

String.prototype.toLocaleLowerCase( [locale, ...locales])
 文字列内の文字が、現在のロケールに沿って小文字に変換されます。

 ほとんどの言語では、これは [113]toLowerCase() と同じものを返します。

String.prototype.toLocaleUpperCase( [locale, ...locales])
 文字列内の文字が、現在のロケールに沿って大文字に変換されます。

 ほとんどの言語では、これは [115]toUpperCase() と同じものを返します。

String.prototype.toLowerCase()
 小文字に変換された文字列の値を呼び出して返します。

String.prototype.toString()
 指定されたオブジェクトの文字列を返します。[118]Object.prototype.toString()
 メソッドを上書きします。

String.prototype.toUpperCase()
 大文字に変換された文字列の値を呼び出して返します。

String.prototype.trim()
 文字列の先頭と末尾にある空白を削除します。 ECMAScript 5 標準の一部です。

String.prototype.trimStart()
 文字列の先頭にある空白を削除します。

String.prototype.trimEnd()
 文字列の末尾にある空白を削除します。

String.prototype.valueOf()
 指定されたオブジェクトのプリミティブ値を返します。 [124]Object.prototype.valueOf()
 メソッドを上書きします。

String.prototype.@@iterator()
 文字列値のコードポイントを反復処理し、文字列値として各コードポイントを返す、新しい Iterator オブジェクトを返します。


/////////////////////////////////////////////////

文字列
__
class_basename
e
preg_replace_array
Str::after
Str::afterLast
Str::ascii
Str::before
Str::beforeLast
Str::between
Str::camel
Str::contains
Str::containsAll
Str::endsWith
Str::finish
Str::is
Str::isAscii
Str::isUuid
Str::kebab
Str::length
Str::limit
Str::lower
Str::orderedUuid
Str::padBoth
Str::padLeft
Str::padRight
Str::plural
Str::random
Str::replaceArray
Str::replaceFirst
Str::replaceLast
Str::singular
Str::slug
Str::snake
Str::start
Str::startsWith
Str::studly
Str::substr
Str::title
Str::ucfirst
Str::upper
Str::uuid
Str::words
trans
trans_choice



FluentStrings

after
afterLast
append
ascii
basename
before
beforeLast
camel
contains
containsAll
dirname
endsWith
exactly
explode
finish
is
isAscii
isEmpty
isNotEmpty
kebab
length
limit
lower
ltrim
match
matchAll
padBoth
padLeft
padRight
plural
prepend
replace
replaceArray
replaceFirst
replaceLast
replaceMatches
rtrim
singular
slug
snake
split
start
startsWith
studly
substr
title
trim
ucfirst
upper
when
whenEmpty
words


/////////////////////////////////////////////////

