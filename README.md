# PKCE for PHP 

## How to use
```php
use Wilkques\PKCE\Generator;

$pkce = Generator::force(); // output ['codeVerifier' => '123', 'codeChallenge' => '456']

// or

$codeVerifier = Generator::codeVerifier();

$codeChallenge = Generator::codeChallenge($codeVerifier);

// or

$pkce = Generator::generate();

$codeVerifier = $pkce->getCodeVerifier();

$codeChallenge = $pkce->getCodeChallenge();
```

## REFERENCE

1. [PKCE実装で使うcode_verifierとcode_challengeをPHPで実装する。](https://qiita.com/sugamaan/items/50699432a65ad9e5829e)
1. [PKCE support for LINE Login](https://developers.line.biz/en/docs/line-login/integrate-pkce/)
1. [OAuth2.0拡張仕様のPKCE実装紹介 〜 Yahoo! ID連携に導入しました](https://techblog.yahoo.co.jp/entry/20191219790463)