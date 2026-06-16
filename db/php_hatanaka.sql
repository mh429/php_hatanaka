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