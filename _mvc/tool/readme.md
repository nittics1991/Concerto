# tool

�ȈՓI�ȃc�[���̃G���g���[�|�C���g

-----------------------------------------------------
## Usage

```cmd
php app.php toolname [...options]
```

|Name|Description|
|:--|:--|
|DbgCmd|CLI�f�o�b�O�w���p|
|DumpFunctionList|�֐��ꗗ�\��|
|TypeHintChecker|�^�C�v�q���g�`�F�b�N|

-----------------------------------------------------
## DbgCmd 

- CLI�f�o�b�O�w���p
- �w�肵���N���X���\�b�h�����b�v���s����w���p�R�}���h
-

### Usage

```cmd

#php cli appcmd
php app.php dbgCmd -- -cMyClass -mMyMethod1

#phpdbg
phpdbg -e app.php dbgCmd -- -cMyClass -mMyMethod1

````

### src

- DbgCmd.php
- DbgCmdRunner.php
- DbgAssertion.php
- bootstrap.php

## Notice

- OPTIONS��-a��-A�͕�����܂��͐��l�̂ݑΉ�

-----------------------------------------------------
## DumpFunctionList 

### Description

- php�t�@�C����ǂݍ��݁A�g�p����Ă���֐����_���v����
- �f�B���N�g���w��\
- PhpTokenizer�I�u�W�F�N�g��var_dump�Ń_���v����

### Usage

```cmd
php app.php DumpFunctionListRunner PATH
```

### src

- DumpFunctionList.php
- DumpFunctionListRunner.php

-----------------------------------------------------
## TypeHintChecker

### Description

- php�t�@�C����ǂݍ��݁A�^�C�v�q���g���`�F�b�N����
- �v���p�e�B�A���\�b�h�Ƀ^�C�v�q���g�������ꍇ���b�Z�[�W�o�͂���
- �\�[�X�t�@�C����PSR1,4�����ł��鎖
- 1file 1class/interface/trait/enum�ɐ���

### Usage

```cmd
php app.php TypeHintCheckerRunner PHPFILE
```

### src

- TypeHintChecker.php
- TypeHintCheckerRunner.php

### Notice

- __construct()�̂悤�ɂ�return type�������ƃ`�F�b�N�Ɉ���������
- WmiPrvSE.exe���d���Ȃ�

-----------------------------------------------------
