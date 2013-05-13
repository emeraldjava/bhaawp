

-- rename the teamtype values
select * from wp_term_taxonomy where taxonomy = 'teamtype'
select * from wp_terms where term_id in (88,89,90)
update wp_terms set name='company',slug='company' where name='companyteam';
update wp_terms set name='sector',slug='sector' where name='sectorteam';
update wp_terms set name='inactive',slug='inactive' where name='inactiveteam';


-- list the runners in teams
select * from wp_users
inner join wp_usermeta company on (company.user_id=wp_users.id and company.meta_key='bhaa_runner_company')
WHERE NOT EXISTS (
	SELECT * FROM p2p_wp_usermeta standard WHERE standard.user_id=wp_users.id and standard.meta_key='bhaa_runner_standard'
)

-- runner id, company and if they are linked
select wp_users.id,wp_users.display_name,status.meta_value,dor.meta_value,company.meta_value,house.post_title,r2c.p2p_from from wp_users
left join wp_usermeta company ON (company.user_id=wp_users.id AND company.meta_key = 'bhaa_runner_company')
join wp_posts house on (house.id=company.meta_value and house.post_type='house')
left join wp_p2p r2c ON (r2c.p2p_to=wp_users.id AND r2c.p2p_type = 'house_to_runner')
left join wp_usermeta status ON (status.user_id=wp_users.id AND status.meta_key = 'bhaa_runner_status')
left join wp_usermeta dor ON (dor.user_id=wp_users.id AND dor.meta_key = 'bhaa_runner_dateofrenewal')
where company.meta_value IS NOT NULL and r2c.p2p_from IS NULL and status.meta_value='M'

insert into wp_p2p (p2p_type,p2p_from,p2p_to)
select 'house_to_runner',company.meta_value,wp_users.id from wp_users
left join wp_usermeta company ON (company.user_id=wp_users.id AND company.meta_key = 'bhaa_runner_company')
join wp_posts house on (house.id=company.meta_value and house.post_type='house')
left join wp_p2p r2c ON (r2c.p2p_to=wp_users.id AND r2c.p2p_type = 'house_to_runner')
left join wp_usermeta status ON (status.user_id=wp_users.id AND status.meta_key = 'bhaa_runner_status')
where company.meta_value IS NOT NULL and r2c.p2p_from IS NULL and status.meta_value='M'

-- where the two values are set but not the same
-- company.meta_value!=r2c.p2p_from

-- a specific runner
-- where wp_users.id=11046

-- list the runners not linked to teams
-- select non-active company teams with

-- list companies and number of active runner

-- select sector teams with more than 6  runners
select p2p_from,house.post_title,count(p2p_id) as total,
(select p2p_to from wp_p2p contact where contact.p2p_from=wp_p2p.p2p_from and contact.p2p_type='team_contact') as contact
from wp_p2p 
join wp_posts house on (house.id=wp_p2p.p2p_from and house.post_type='house')
where p2p_type='sectorteam_to_runner' 
group by p2p_from
order by total desc;

select p2p_from,house.post_title,count(p2p_id) as total,
(select p2p_to from wp_p2p contact where contact.p2p_from=wp_p2p.p2p_from and contact.p2p_type='team_contact') as contact
from wp_p2p 
join wp_posts house on (house.id=wp_p2p.p2p_from and house.post_type='house')
where p2p_type='house_to_runner' 
group by p2p_from
order by total desc;

-- select active teams without contacts