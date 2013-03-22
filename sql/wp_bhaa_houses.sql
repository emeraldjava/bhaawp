

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

-- list the runners not linked to teams
-- select non-active company teams with

-- list companies and number of active runner

-- select sector teams with more than 6 runners

-- select active teams without contacts