#

## 220328

Config��Concerto�Ή�������

- Config��Container�ɓ����
	- Config��container����擾���ADotNotation�I�Ȏ擾���l������
- Hash�̂悤�ȃ��C�u���������ƁAConcerto��p�̕����𕪂���
- �e�����l����ƁA��p������Concerto\standard�ɂ���?
- 


### �f�B���N�g�����̐ݒ��ǉ�����


```php

class DirectoryConfig
{
	
	
	
	
	//�ċA�I��PATH�ȉ���ǉ�
	//regex�Ńt�B���^
	public function xxx(
		$path,	//�Ώ�PATH
		$regex,	//PATH�t�B���^�[(include��exclude�𓯎��ɂ��邽��)
	)
	{
		//
		$itelater = new RecursiveDirectoryItelater(
			new RegexIteater(
				new FilesystemItelater($path),
				$regex,
			)
		);
	}
	
	//�t�@�C��baseName+�z���arrayDotNotation���������O�Ƃ���
	
	public function get($name)
	{
		$baseName = baseName($path);
		
		
		
		
		
	
	}
}

```

### psr/container�Ƃ̘A�g

- ServiceProvider��container����擾�ł���悤�ɂ���
- ������DirectoryConfig���w��ł���悤�ɂ���
	- Concerto��_config\��common��itc_workX������̂�
	- ���݂�replace()�@�\���K�v
- containe��config�̃f�[�^�擾�́A"config.xxx.yyy...."�Ƃ�����
	- config.common.system.database.dns.symphony
	- config.seiban_kanri2.cyunyu_inf_disp.kb_nendo.color.warning



```php

class ConfigServiceProvider
{
	
	//DirectoryConfig��ǂݍ��݁AbaseName����̖��O�Ƃ���
	public function xxx(
		$baseName,	//containe�f�[�^���O��Ԃ�prefix
		$basePath,	//config dir�̃x�[�XPATH�@�����ȉ���DIR��Ώ�
		$filterRegex,	//DirectoryConfig�̃t�B���^regex
	)
	{
	}
}

```

### Concerto\standard\factory��container���p

- ���݂�getXXX()��__call�ŏȗ��\�ɂ�����
	- reflection�ň����̉������K�v
	- GetAccessetTrait���쐬
		- SetAccesserTrait�̋��ʂ�(Exclude)AccesserTrait���쐬
			- �z��őΏ�property�����O|�w��|����
	
	
	
	