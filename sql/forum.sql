
-- main forum id=2
select * from gl_forum_forums

-- general:0,bhaa:4
select * from gl_forum_categories

-- topics
select * from gl_forum_topic

-- parent
select * from gl_forum_topic where pid=0;
-- replies (uid=1 anon, uid>1 real user).
select * from gl_forum_topic where pid!=0;


select distinct(pid),subject from gl_forum_topic

-- mingle tables
select * from wp_forum_forums

select * from wp_forum_posts

-- each parent post is a thread
select * from wp_forum_threads
delete from wp_forum_threads
select * from gl_forum_topic where pid!=0;

-- insert topics
insert into wp_forum_threads
select id,1,views,subject,FROM_UNIXTIME(date),'open',0,-1,1,FROM_UNIXTIME(lastupdated) 
from gl_forum_topic where pid=0;
-- insert first message for each topic
insert into wp_forum_posts
select id,comment,id,FROM_UNIXTIME(lastupdated),uid,subject,views 
from gl_forum_topic where pid=0;

-- insert the replies
insert into wp_forum_posts
select 0,comment,pid,FROM_UNIXTIME(date),uid,subject,views 
from gl_forum_topic where pid!=0;

-- move the replies
select * from wp_forum_posts
delete from wp_forum_posts