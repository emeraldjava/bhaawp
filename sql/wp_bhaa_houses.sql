

-- rename the teamtype values
select * from wp_term_taxonomy where taxonomy = 'teamtype'
select * from wp_terms where term_id in (88,89,90)
update wp_terms set name='company',slug='company' where name='companyteam';
update wp_terms set name='sector',slug='sector' where name='sectorteam';
update wp_terms set name='inactive',slug='inactive' where name='inactiveteam';


-- list the runners in teams


-- list the runners not linked to teams
