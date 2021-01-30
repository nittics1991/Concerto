#
#   array()をリテラル[]に一括置換   
#
#   注意
#       TABは使用できない
#       1行で処理する必要がある
#   sample
#       awk -f array_to_literal.awk source.php
#       cat source.php |tr "\n" "\t" |awk -f array_to_literal.awk |tr "\t" "\n"
#

BEGIN {

    #解析解析開始文字配列(注意:開始位置1から)
    pattern_start_length = split("a,r,r,a,y", pattern_start, ",");

    #解析一致文字の1文字前が次の場合arrayと判定
    allow_prev_chares = " ,({";

    #対象文字数
    target_char_length = 0;

    #対象文字数
    target_parse_pos = 0;

    #エラーメッセージ
    error_message = "";
    
}

#
#解析処理
#@param int loop_depth 再帰深さ
#@return string 解析結果文字
#
function source_parser(loop_depth)
{
    #一致位置記憶["array("の"("の位置]
    matched_cache[1] = null;
    
    #最大一致位置記憶連番
    max_matched_caches_length = 0;
    
    #括弧位置記憶["array("以外の"("の位置]
    curl_caches[1] = null;
    
    #最大一致位置記憶連番
    max_curl_caches_length = 0;
    
    #最大再帰深さ確認
    max_depth = 20;
    loop_depth++;

    if (loop_depth > max_depth) {
        error_message = "max depth over";
        return "";
    }
    
    #結果
    result = "";

    #解析一致文字数
    pattern_match_pos = 1;

    #解析一致文字の1文字前
    prev_match_first_char = "";

    #while無限ループ対策
    while_count = 1000;

    #最終文字位置まで
    while(target_parse_pos <= target_char_length && while_count > 0) {
        
        #対象文字抽出
        target_char= substr($0, target_parse_pos, 1);
        
        #"array"パターン一致完了
        if (pattern_match_pos > pattern_start_length) {

            #"array"の後が"("ならば"array("をリテラルに変換
            if (target_char == "(") {
            
                #"array"の前が決まった文字なら
                if (prev_match_first_char == "" || index(allow_prev_chares, prev_match_first_char)) {
                    result = substr(result, 0, length(result) - pattern_match_pos + 1) "[";
                    matched_caches[++max_matched_caches_length] = target_parse_pos;
                    pattern_match_pos = 1;
        
                } else {

                    #再帰処理
                    pattern_match_pos = 1;
                    curl_caches[++max_curl_caches_length] = target_parse_pos;
                    loop_depth++;
                    target_parse_pos++;
                    result = result "("
                    result = result source_parser(loop_depth);
                    loop_depth--;

                }
                
            #"array"の後が"　"ならば保留
            } else if (target_char == " ") {
                result = result target_char;
                pattern_match_pos++;
            } else {
                #不一致
                result = result target_char;
                pattern_match_pos = 1;
            }

        #"array"パターン一致
        } else if (target_char == pattern_start[pattern_match_pos]) {

            #"array"の最初の文字"a"に一致ならば、前の文字を記憶
            if (pattern_match_pos == 1 && target_parse_pos != 1) {
                prev_match_first_char = substr($0, target_parse_pos - 1, 1);
            }
            
            result = result target_char;
            pattern_match_pos++;

        #開き括弧
        } else if (target_char == "(") {

            #再帰処理
            curl_caches[++max_curl_caches_length] = target_parse_pos;
            loop_depth++;
            target_parse_pos++;
            result = result "("
            result = result source_parser(loop_depth);
            loop_depth--;
        
        #閉じ括弧
        } else if (target_char == ")") {

            #"array(" に対応する閉じ括弧
            if (matched_caches[max_matched_caches_length] > curl_caches[max_curl_caches_length]) {
                delete matched_caches[max_matched_caches_length];
                max_matched_caches_length--;
                
                #リテラルの閉じ括弧に変換
                result = result "]";

            } else {
                
                delete curl_caches[max_curl_caches_length];
                max_curl_caches_length--;
                
                #そのままの閉じ括弧
                return result  ")";

            }

        #その他
        } else {

            result = result target_char;
            pattern_match_pos = 1;
            prev_match_first_char = "";

        }

        target_parse_pos++;
        while_count--;

    }
    return result;
}

{
    #対象文字数
    target_char_length = length($0);
    #解析文字位置初期化
    target_parse_pos = 1;

    #解析
    result = source_parser(0);

    if (error_message != "") {
        print "ERROR=" error_message;
    } else {
        print result;
    }

}
