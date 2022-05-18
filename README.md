# PKCE for PHP

[![Latest Stable Version](https://poser.pugx.org/wilkques/pkce-php/v/stable)](https://packagist.org/packages/wilkques/pkce-php)
[![License](https://poser.pugx.org/wilkques/pkce-php/license)](https://packagist.org/packages/wilkques/pkce-php)

````
composer require wilkques/pkce-php
````

## How to use
```php
use Wilkques\PKCE\Generator;

$codeVerifier = Generator::codeVerifier();

$codeChallenge = Generator::codeChallenge($codeVerifier);

// or

$pkce = Generator::generate();

$codeVerifier = $pkce->getCodeVerifier();

$codeChallenge = $pkce->getCodeChallenge();

// or

$codeVerifier = $pkce->codeVerifier;

$codeChallenge = $pkce->codeChallenge;

// or

$pkce->toArray() // output ['codeVerifier' => '123', 'codeChallenge' => '456']

$pkce->toJson() // output {'codeVerifier' : '123', 'codeChallenge' : '456'}

```

## REFERENCE

1. [PKCE実装で使うcode_verifierとcode_challengeをPHPで実装する。](https://qiita.com/sugamaan/items/50699432a65ad9e5829e)
1. [PKCE support for LINE Login](https://developers.line.biz/en/docs/line-login/integrate-pkce/)
1. [OAuth2.0拡張仕様のPKCE実装紹介 〜 Yahoo! ID連携に導入しました](https://techblog.yahoo.co.jp/entry/20191219790463)