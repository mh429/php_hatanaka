-- ****************************************
-- DB作成
-- ****************************************
drop database if exists php_hatanaka;
create database php_hatanaka default character set utf8 collate utf8_general_ci;
use php_hatanaka;


-- ****************************************
-- membersテーブル
-- ****************************************
CREATE TABLE members (
  id INT NOT NULL AUTO_INCREMENT COMMENT '会員ID',
  name_sei VARCHAR(255) NOT NULL COMMENT '氏名（姓）',
  name_mei VARCHAR(255) NOT NULL COMMENT '氏名（名）',
  gender INT NOT NULL COMMENT '性別',
  pref_name VARCHAR(255) NOT NULL COMMENT '都道府県',
  address VARCHAR(255) DEFAULT NULL COMMENT 'それ以降の住所',
  password VARCHAR(255) NOT NULL COMMENT 'パスワード',
  email VARCHAR(255) NOT NULL COMMENT 'メールアドレス',
  created_at TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '登録日時',
  updated_at TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP COMMENT '編集日時',
  deleted_at TIMESTAMP NULL DEFAULT NULL COMMENT '削除日時',
  PRIMARY KEY (id)
);

ALTER TABLE members
MODIFY created_at TIMESTAMP NOT NULL
DEFAULT '0000-00-00 00:00:00'
COMMENT '登録日時';

ALTER TABLE members
MODIFY updated_at TIMESTAMP NOT NULL
DEFAULT '0000-00-00 00:00:00'
COMMENT '編集日時';

INSERT INTO members
(name_sei, name_mei, gender, pref_name, address, password, email, created_at, updated_at, deleted_at)
VALUES
('山田','太郎',1,'東京都','新宿区西新宿1-1-1','pass0001','yamada@example.com',NOW(),NOW(),NULL),
('佐藤','花子',2,'大阪府','大阪市北区梅田1-2-3','pass0002','sato@example.com',NOW(),NOW(),NULL),
('鈴木','一郎',1,'愛知県','名古屋市中区栄2-1-1','pass0003','suzuki@example.com',NOW(),NOW(),NULL),
('高橋','美咲',2,'福岡県',NULL,'pass0004','takahashi@example.com',NOW(),NOW(),NULL),
('田中','健',1,'北海道','札幌市中央区北1条','pass0005','tanaka@example.com',NOW(),NOW(),NULL);

('伊藤','優子',2,'京都府','京都市中京区河原町','pass0006','ito@example.com',NOW(),NOW(),NULL),
('渡辺','大輔',1,'兵庫県','神戸市中央区三宮町','pass0007','watanabe@example.com',NOW(),NOW(),NULL),
('中村','彩',2,'広島県',NULL,'pass0008','nakamura@example.com',NOW(),NOW(),NULL),
('小林','直人',1,'宮城県','仙台市青葉区一番町','pass0009','kobayashi@example.com',NOW(),NOW(),NULL),
('加藤','由美',2,'静岡県','静岡市葵区呉服町','pass0010','kato@example.com',NOW(),NOW(),NULL),
('吉田','誠',1,'千葉県','千葉市中央区新町','pass0011','yoshida@example.com',NOW(),NOW(),NULL),
('山本','真由美',2,'埼玉県','さいたま市大宮区桜木町','pass0012','yamamoto@example.com',NOW(),NOW(),NULL),
('松本','翔',1,'奈良県','奈良市大宮町','pass0013','matsumoto@example.com',NOW(),NOW(),NULL),
('井上','恵',2,'滋賀県','大津市京町','pass0014','inoue@example.com',NOW(),NOW(),NULL),
('木村','達也',1,'岡山県','岡山市北区駅前町','pass0015','kimura@example.com',NOW(),NOW(),NULL),
('林','裕子',2,'熊本県','熊本市中央区下通','pass0016','hayashi@example.com',NOW(),NOW(),NULL),
('清水','隆',1,'長野県','長野市南千歳','pass0017','shimizu@example.com',NOW(),NOW(),NULL),
('山崎','愛',2,'新潟県',NULL,'pass0018','yamazaki@example.com',NOW(),NOW(),NULL),
('森','浩二',1,'鹿児島県','鹿児島市中央町','pass0019','mori@example.com',NOW(),NOW(),NULL),
('池田','真理',2,'沖縄県','那覇市久茂地','pass0020','ikeda@example.com',NOW(),NOW(),NULL),
('橋本','亮',1,'石川県','金沢市香林坊','pass0021','hashimoto@example.com',NOW(),NOW(),NULL),
('阿部','奈々',2,'富山県','富山市総曲輪','pass0022','abe@example.com',NOW(),NOW(),NULL),
('石川','和也',1,'福井県','福井市中央','pass0023','ishikawa@example.com',NOW(),NOW(),NULL),
('前田','麻衣',2,'香川県','高松市丸亀町','pass0024','maeda@example.com',NOW(),NOW(),NULL),
('藤田','徹',1,'徳島県','徳島市寺島本町','pass0025','fujita@example.com',NOW(),NOW(),NULL),
('後藤','陽子',2,'愛媛県','松山市大街道','pass0026','goto@example.com',NOW(),NOW(),NULL),
('岡田','修',1,'高知県','高知市帯屋町','pass0027','okada@example.com',NOW(),NOW(),NULL),
('長谷川','由佳',2,'山形県','山形市香澄町','pass0028','hasegawa@example.com',NOW(),NOW(),NULL),
('村上','悠人',1,'秋田県','秋田市中通','pass0029','murakami@example.com',NOW(),NOW(),NULL),
('近藤','里奈',2,'青森県','青森市新町','pass0030','kondo@example.com',NOW(),NOW(),NULL);


-- ****************************************
-- threadsテーブル
-- ****************************************
CREATE TABLE threads (
  id INT NOT NULL AUTO_INCREMENT COMMENT 'スレッドID',
  member_id INT NOT NULL COMMENT '会員ID',
  title VARCHAR(255) NOT NULL COMMENT 'タイトル',
  content TEXT NOT NULL COMMENT 'コメント',
  created_at TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '登録日時',
  updated_at TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP COMMENT '編集日時',
  deleted_at TIMESTAMP NULL DEFAULT NULL COMMENT '削除日時',
  PRIMARY KEY (id)
);

INSERT INTO threads
(member_id, title, content, created_at, updated_at, deleted_at)
VALUES
(1,
'スレッド作成方法',
'スレッドを作成すると、議題ごとにコミュニケーションの場を分けることができるので、自然と情報が整理されます。
スレッドは、そのスペースにアクセスできるすべてのユーザーが作成できます。',
NOW(), NOW(), NULL),
(2,
'MySQLの正規化について',
'データベース設計を勉強している初心者です。
第一正規形、第二正規形、第三正規形までは本で学んだのですが、実際にどこまで厳密に行えばよいのか分かりません。
実務ではどのように考えているのか知りたいです。',
NOW(), NOW(), NULL),
(3,
'ポートフォリオ制作で悩んでいます',
'未経験から転職を目指しているため、ポートフォリオを作成しています。
皆さんが作ってよかったものや、採用担当者の反応がよかった機能などがあれば教えてください。',
NOW(), NOW(), NULL);

(1,
'Gitのブランチ運用について',
'現在は個人開発なので main ブランチだけで作業しています。
機能追加のたびにブランチを切った方がよいと聞きますが、個人開発でもそのような運用をした方がよいのでしょうか。
おすすめの運用方法があれば知りたいです。',
NOW(), NOW(), NULL),
(2,
'エラーメッセージの読み方',
'PHPやJavaScriptでエラーが出たとき、どこを見ればよいのか分からず苦戦しています。
英語のメッセージを翻訳しながら調べていますが、時間がかかってしまいます。
皆さんはエラーが出たとき、どのような順番で原因を調査していますか。
効率よくデバッグするコツがあれば教えてください。',
NOW(), NOW(), NULL);
(2,'HTMLとCSSのおすすめ学習サイト','初心者向けで分かりやすいサイトや動画を探しています。',NOW(),NOW(),NULL),
(2,'JavaScriptの非同期処理が難しい','Promiseやasync awaitの違いがよく分かりません。理解のコツを知りたいです。',NOW(),NOW(),NULL),
(2,'ポートフォリオ制作のアイデア','就職活動用にポートフォリオを作りたいです。どんな機能を入れるべきでしょうか。',NOW(),NOW(),NULL),
(2,'SQLのJOINについて','INNER JOINとLEFT JOINの使い分けがよく分かりません。具体例があれば教えてください。',NOW(),NOW(),NULL),
(10,'おすすめのエディタ設定','皆さんはVSCodeをどのようにカスタマイズしていますか。',NOW(),NOW(),NULL),
(11,'Reactの勉強を始めました','useStateやuseEffectの使い方を覚えるのに苦戦しています。',NOW(),NOW(),NULL),
(12,'パスワードの保存方法','平文保存は危険と聞きました。password_hashについて知りたいです。',NOW(),NOW(),NULL),
(13,'フォームのバリデーションについて','サーバー側とクライアント側の両方でチェックする必要がありますか。',NOW(),NOW(),NULL),
(14,'Dockerを学ぶタイミング','PHPとMySQLの基礎が終わった後に学ぶべきでしょうか。',NOW(),NOW(),NULL),
(15,'Web制作とWebアプリ開発の違い','仕事内容や必要なスキルの違いについて知りたいです。',NOW(),NOW(),NULL),
(16,'CSSのFlexboxについて','justify-contentやalign-itemsが混乱します。覚えるコツはありますか。',NOW(),NOW(),NULL),
(17,'PHPの配列操作','array_mapやarray_filterを使いこなしたいです。おすすめの練習方法はありますか。',NOW(),NOW(),NULL),
(18,'MAMPの使い方について','ローカル環境で開発していますが、設定で迷うことがあります。',NOW(),NOW(),NULL),
(19,'APIを使った開発をしたい','初心者でも扱いやすいAPIがあれば教えてください。',NOW(),NOW(),NULL),
(20,'おすすめの技術書','PHPやSQLを学ぶのに役立った本を知りたいです。',NOW(),NOW(),NULL),
(21,'プログラミングのモチベーション維持','勉強が続かない時に皆さんはどうしていますか。',NOW(),NOW(),NULL),
(22,'エラーメッセージの読み方','英語のエラー文を理解するのに時間がかかります。コツが知りたいです。',NOW(),NOW(),NULL),
(23,'MVCモデルについて','Laravelを学ぶ前にMVCの考え方を理解したいです。',NOW(),NOW(),NULL),
(24,'Webエンジニア転職について','未経験から転職する際に重要なポイントは何でしょうか。',NOW(),NOW(),NULL),
(25,'JavaScriptとTypeScriptの違い','TypeScriptを学ぶメリットについて教えてください。',NOW(),NOW(),NULL),
(26,'PHPのセッション管理','ログイン機能を実装しています。セッションの扱い方で注意点はありますか。',NOW(),NOW(),NULL),
(27,'おすすめの個人開発テーマ','学習のために作るならどんなアプリがよいでしょうか。',NOW(),NOW(),NULL),
(28,'デバッグの進め方','バグが見つからない時に皆さんが意識していることを知りたいです。',NOW(),NOW(),NULL),
(29,'正規化はどこまで必要か','第三正規形までやるべきなのか迷っています。実務での考え方を知りたいです。',NOW(),NOW(),NULL),
(30,'PHPのforeachについて','foreachのキーと値の使い分けについて詳しく知りたいです。',NOW(),NOW(),NULL);