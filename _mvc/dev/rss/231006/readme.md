# 簡易なrss/atomのライブラリ


## simpleなatom




## rss

[github](https://github.com/suin/php-rss-writer)




## atom

[RFC4287](https://datatracker.ietf.org/doc/html/rfc4287)
[日本語](https://tex2e.github.io/rfc-translater/html/rfc4287.html)


### base

<?xml version="1.0" encoding="utf-8"?>
XMLの為、先頭にxml element

<feed xmlns="http://www.w3.org/2005/Atom">
feed element がコンテンツroot
namespaceは固定


文字構造はtype attrで指定できる
text html xhtml

人の情報は下記 element を持てる
name,uri,email

日付書式はiso8601.1988に従う


### atom:feed element

MUST one
id,title,updated

MUST one or more
author 

MAY
category,contributor

MUST NOT contain more than one
generator,icon,logo

>linkについて色々記載あり


entry element は空でない
content element は空でない
summary element は空でない

### entry element


















