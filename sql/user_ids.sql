SELECT MAX(ID) FROM wp_users;

-- gaps in the BHAA member range of 10000 to 9999
select l.id + 1 as start
from wp_users as l
  left outer join wp_users as r on l.id + 1 = r.id
where r.id is null
      and l.id>10000 and l.id<50000
limit 1;

-- gaps in the BHAA member range of 1000 to 9999
select l.id + 1 as start
from wp_users as l
  left outer join wp_users as r on l.id + 1 = r.id
where r.id is null
      and l.id>1000 and l.id<9999;

SELECT COUNT(ID) FROM wp_users WHERE ID>30000; -- 209 rows

SELECT MAX(ID) FROM wp_users WHERE ID<30000; -- 29963 is the max is sub 30000

SHOW TABLE STATUS FROM `bhaaie_wp` WHERE `name` LIKE 'wp_users'; -- max 990108

SELECT `AUTO_INCREMENT`
FROM INFORMATION_SCHEMA.TABLES
WHERE TABLE_SCHEMA = 'bhaaie_wp'
      AND TABLE_NAME = 'wp_users';

-- ALTER TABLE `users` AUTO_INCREMENT = 1;

SELECT ID,
          (SELECT COUNT(ID) FROM wp_bhaa_raceresult WHERE runner=ID and class='RAN') as runcount,
  (SELECT meta_value FROM wp_usermeta WHERE meta_key='bhaa_runner_status' and user_id=ID)
FROM wp_users WHERE ID>30000
ORDER BY ID DESC LIMIT 1000;
-- 990107

990102
SELECT * FROM wp_p2p
WHERE p2p_from=990102

UPDATE wp_users
SET ID=10143
WHERE ID=989899;

Auto Increment 990108
Max Runner 29963
Next Runner ID 10950

ALTER TABLE wp_users AUTO_INCREMENT = 30000;