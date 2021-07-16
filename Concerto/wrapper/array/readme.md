

#othes_value

別途、class化？

array_pop/array_shiftだけっぽい
==>poped/shiftedというmethodにしたい
==>ReferencedFunctionから別class化？
==>methodにpop/shiftとpoped/shifted
    ==>combineと類似








#調査
        
        //array かつ arg1がarray
                array_change_key_case
                array_chunk
                array_column
                array_count_values
                
                array_diff_assoc
                array_diff_key
                array_diff_uassoc
                array_diff_ukey
                array_diff
                array_udiff_assoc
                array_udiff_uassoc
                array_udiff
                
                array_filter
                array_flip
                
                array_intersect_assoc
                array_intersect_key
                array_intersect_uassoc
                array_intersect_ukey
                array_intersect
                array_uintersect_assoc 
                array_uintersect_uassoc
                array_uintersect
                
                array_keys
                array_merge_recursive
                array_merge
                array_pad
                array_replace_recursive
                array_replace
                array_reverse
                array_slice
                array_unique
                array_values
        
        
        
        
        
        
        //arrayだがreturnの検討必要
          array_splice  //1&  //return 置換された1&
        
        //array 引数も要注意
        compact //変数名から配列を作る  //対象外
        
        
        //array 引数は検討必要
            array_combine //conbineKeyUseKey,...
        array_fill_keys //1 //fillKeysKeyUseKey,...
          array_map //2
        
        
        
        //引数にarrayなし
          array_fill
          array
          list
          range
        
        
        //////////////////////////////////////
        
        
        ------------------------------------
        //ReferencedFunction
        //boolだがarrayのreturnにする
          array_multisort
          array_walk
          array_walk_recursive
          
          arsort
          asort
          krsort
          ksort
          natcasesort
          natsort
          rsort
          sort
          uasort
          uksort
          usort
          
          shuffle
        ------------------------------------
        
        
        //bool
          array_key_exists/key_exists //2番目 <=$arrayが
          in_array    //1番目
        
        
        
        
        //int|string|null
          array_key_first //1
          array_key_last  //1
          key //1
        
        //mixed
          array_pop   //1&  //==>popedデータを取得するmethod作る?
          array_reduce    //1
          array_shift //1&  //==>shiftedデータを...
          current/pos //1
        
        
        //mixedだがarrayのreturnにする
          end //1&
          next    //1&
          prev    //1&
          reset   //1&
        
        
        //int
          array_push  //1&  //reruen pushed array
          array_unshift   //1&  //return unshifted array
          count/seizeof   //1
        extract //1&  //returnが展開された変数の数だがどうする?
        
        //int|float
          array_product   //1
          array_sum   //1
        
        //int|string|arrray
          array_rand  //1
        
        //int|string|false
          array_search    //2
        



        ////////////////////////////////////////
        
        //bool 引数arrayなし
        is_array
        
        //array 引数arrayなし
        explode
        
        //string
        implode/join //2
        
        
        //array|false  引数arrayなし
        preg_split
        
        
        その他mbstringにも類似あり
        mb_split
        mb_str_split
        ...etc
        


        




