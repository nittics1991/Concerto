# Excel VBA 2024


--------------------------------------------------------------------------------
### constant

```vba

Public Const DEFINITION_TABLE_SHEET = "メニュー"
Public Const DEFINITION_TABLE_BASE_CELL = "C10"

Public Const ERR_PARSE_INVALID_NUM = 601
Public Const ERR_PARSE_INVALID_DES = "マクロ定義異常.番号="


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
            Apprication.Run def.macro def.args
        End If
    Next
    
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

        If def.invalid() Then
            Err.Raise( _
                ERR_PARSE_INVALID_NUM, _
                , _
                ERR_PARSE_INVALID_DES & i _
            )
        End If

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

Private def_array As Variant
Private in_valid As Boolean

Public Function init( _
    defArray As Variant, _
)
    def_array = defArray
    invalid = True
    
End Function

Public Function create()
    
End Function

Public Function invalid() As Boolean
    invalid = in_valid
End Function





```

--------------------------------------------------------------------------------
### 










--------------------------------------------------------------------------------
