# Laravel＋Vue.jsの掲示板風SPA
## 概要
サーバーサイドをLaravel、フロントサイドをVue.jsで作成した掲示板風SPAです。
ユーザ登録(Googleアカウントも可)、ログイン/ログアウト、テキスト/画像の投稿・削除などができます。
Heroku上で稼働しています。(勉強のために少しずつ機能を増やしていってます)
https://laravel-board.herokuapp.com/

## 作成した経緯・理由
- このアプリケーションの前に、素のPHPだけで同じような掲示板アプリケーションを作成しましたが、実際の現場ではフレームワークを用いることがほとんどだということをメンターから聞きました。そこで、より実践に近い形でのアプリケーションを作成したいと思ったためです。
- 前職で保守していたWebアプリケーションは10年近く保守が続いており、誰も全貌を把握できていないような巨大なシステムでした。サーバーサイドの改修作業しか発生しなかったため、小さくてもアプリケーションを全て作成することで最低限必要な流れや仕組みを学びたかったためです。
- テストも手動でスクリーンショットを撮るということが当たり前だったため、少しでもテストコードを書くことに慣れたかったというもあります。

#### なぜ掲示板風アプリなのか
- LaravelとVue.jsでのシングルページアプリケーションというモダンな技術を優先して学びたかったためです。（アイデア出しや仕様にリソースを割きたくなかった）
- 掲示板でもユーザの登録・ログイン機能・テキスト、画像の投稿など基本的な要素は揃っているためと考えたためです。


## 意識した点・工夫した点
- APIを新しく作る際は、先にテストを書くようにしました。そうすることによってコントローラやバリデーションに必要な処理が具体的に意識できるようになり開発がしやすくなりました。
- コントローラーに記載するコード量を少なくするようにして、メンテナンスしやすいようにしました。初めはバリデーションなどもコントローラーで行っていましたが、コントローラーが肥大化してきたため、バリデーションはフォームリクエストで行うようにするなどし、コントローラーに持たせる機能を必要最低限にするようにしました。
- 画像は画像用モデルを作成し、ファイル名を持たせることで実ファイルを参照できるようにしています。投稿が削除される場合、添付されている画像も削除しますが、画像モデルを削除する度に実ファイルも削除するのは手間ですし、漏れが発生すると考えました。そこで、オブザーバーの機能を用いて画像モデルが削除されたら画像の実ファイルも削除するようにしました。
- フロントでは、投稿用フォームやページネーション、ヘッダーなどをコンポーネントとして作成し、複数ページで使いまわしができるよう実装しました。

## 苦労した点
- Laravelのデフォルトの仕様がSPA向けになっておらず、フォームからの送信時にCSRFトークンの検証エラーが発生していました。公式のドキュメントでは検証の詳細な仕様がわからなかったため、実際にCSRFトークンの検証を行っているメソッドのソースを読むことで、仕様を理解し解決しました。
- シングルページアプリケーションの実装が初めてだったため、Laravelのみの場合とフロントの実装方法が異なり、慣れるまで戸惑いました。ページを作りながら手順や仕組みを別途まとめることで学習していきました。

## ローカル開発環境
- Cent OS 7.6 (ローカルの仮想マシン)
- Laravel 5.8
- PHP 7.3
- Vue.js 2.6

