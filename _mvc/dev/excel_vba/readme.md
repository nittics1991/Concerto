# Excel VBA 2024

## 検討

- 文字列から関数を実行する方法
    - 呼び出す時、引数長が不定



## src

--------------------------------------------------------------------------------
### constant

```vba

Public Const DEFINITION_TABLE_SHEET = "メニュー"
Public Const DEFINITION_TABLE_BASE_CELL = "C10"

'Public Const ERR_DATATYPE_NUM = 601
'Public Const ERR_DATATYPE_DES = "データ型異常."

'Public Const ERR_DATADOMAIN_NUM = 602
'Public Const ERR_DATADOMAIN_DES = "データ定義不一致."

```
--------------------------------------------------------------------------------
### main

```vba

Dim report As Report
Set report = New Report
With report
    .init(DEFINITION_TABLE_SHEET, DEFINITION_TABLE_BASE_CELL)
    .render()
End With

```
--------------------------------------------------------------------------------
### Report.cls

```vba

Private def_sheet As String
Private base_cell As String

Public Function init( _
    defSheet As String, _
    baseCell As String, _
)
    def_sheet = defSheet
    base_cell = baseCell

End Function

Public Function render()

    set defTables = New DefTables
    With defTable
        .init(base_cell)
        .parse()
        Set iterator = defTables.getIterator()
    End With

    For Each def In iterator
        If def.isExec Then
            callMacro def.macro def.args
        End If
    Next
    
End Function

Private Function callMacro( _
    name As String, _
    args As Variant, _
)

        Set defs = args.getIterator()
        

        For Each key As defs


        Next
        
        Apprication.Run def.macro def.args

    
End Function

```

--------------------------------------------------------------------------------
### DefTables.cls

```vba

Private def_sheet As String
Private base_cell As String
Private defs As Collection

Public Function init( _
    defSheet As String, _
    baseCell As String, _
)
    def_sheet = defSheet
    base_cell = baseCell

    Set defs = New Collection

End Function
    
Public Function parse()
    rangeData = readDefRange()

    for i = LBound(rangeData) To UBound(rangeData)
        Set def = New DefTable
        With def.init(rangeData(i))
            .create()
        End With 

        defs.Add def
        
    Next i
    
End Function

Public Function getIterator() As Collection
    Set getIterator = defs
    
End Function

Private Function readDefRange() As Variant
    readDefRange = WoorkSheets(def_sheet) _
        .Range(base_cell) _
        .CurrentRegion

End Function

```
--------------------------------------------------------------------------------
### DefTable

```vba

Private Const DEF_COUNT = 4

Public name As Srring
Public macro As Srring
Public args As Variant
Public isExec As Boolean

Private def_array As Variant

Public Function init( _
    defArray As Variant, _
)
    def_array = defArray
    
End Function

Public Function create()
    call validate
    name = def_array(1)
    macro = def_array(2)
    args = createArgs(def_array(3))
    isExec = determineExec(def_array(4))
    
End Function

Private Function validate()

    If Not IsArray(def_array) Then
        Err.Raise ERR_DATATYPE_NUM, _
            , _
            , ERR_DATATYPE_DES & "配列データではない"
    End If

    If UBound(def_array) - LBound(def_array) + 1 <> Me.DEF_COUNT Then
        Err.Raise ERR_DATADOMAIN_NUM, _
            , _
            , ERR_DATADOMAIN_DES & "配列数が一致しない"
    
    End If
    
End Function

Private Function createArgs( _
    rangeStr As String _
) As Varinat 'Array





    
End Function

Private Function determineExec(
    execString As String
) As Boolean
    Dim str As String
    str = Trim(execString)

    If str = "" Then
        determineExec = False
    Else
        determineExec = True
    End If
    
End Function

```

--------------------------------------------------------------------------------
## Definitions










--------------------------------------------------------------------------------
