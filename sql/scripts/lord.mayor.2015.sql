

CREATE TABLE IF NOT EXISTS importusers (
id varchar(5) DEFAULT NULL,
name varchar(20) DEFAULT NULL,
firstname varchar(20) DEFAULT NULL,
surname varchar(20) DEFAULT NULL,
gender varchar(1) DEFAULT "M",
dob varchar(20) DEFAULT NULL,
dob2 varchar(20) DEFAULT NULL,
email varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO wp_users(ID, user_login, user_pass, user_nicename, user_email, user_url, 
user_registered, user_activation_key, user_status, display_name)
SELECT 
u.ID,
CONCAT(LOWER(TRIM(firstname)),'.',LOWER(TRIM(surname))),
u.ID,
CONCAT(LOWER(TRIM(firstname)),'.',LOWER(TRIM(surname))),
u.email,
'',
CURRENT_TIMESTAMP,
'',
0,
CONCAT(LOWER(TRIM(firstname)),'.',LOWER(TRIM(surname)))
from importusers u
WHERE u.ID != "";


insert into wp_usermeta(user_id, meta_key, meta_value) 
SELECT u.id,'bhaa_runner_dateofbirth',dob
FROM importusers u;

insert into wp_usermeta(user_id, meta_key, meta_value) 
SELECT u.id,'bhaa_runner_gender','M'
FROM importusers u;

insert into wp_usermeta(user_id, meta_key, meta_value) 
SELECT u.id,'first_name',firstname
FROM importusers u;

insert into wp_usermeta(user_id, meta_key, meta_value) 
SELECT u.id,'last_name',surname
FROM importusers u;

insert into wp_usermeta(user_id, meta_key, meta_value) 
SELECT u.id,'bhaa_runner_status','D'
FROM importusers u;

insert into wp_usermeta(user_id, meta_key, meta_value) 
SELECT u.id,'bhaa_runner_company',4295
FROM importusers u;

insert into wp_p2p(p2p_from,p2p_to,p2p_type) 
SELECT 4295,u.id,'house_to_runner'
FROM importusers u;

SELECT ID FROM importusers
WHERE name = ''
WHERE ID

DELETE FROM wp_usermeta
WHERE user_id IN (SELECT ID FROM importusers
WHERE name = '')

DELETE FROM wp_users
WHERE ID IN (SELECT ID FROM importusers
WHERE name = '')

DELETE FROM importusers WHERE name = '';


INSERT INTO wp_em_bookings(event_id, person_id, booking_spaces, booking_comment, booking_date, booking_status, booking_price) 
SELECT 165,u.ID,1,"Lord Mayor Series",CURRENT_DATE,1,15.00
FROM importusers u;

