<?php
class AgeCategory extends BaseModel implements Table
{
	function __construct()
	{
		parent::__construct();
	}
	
	public function getName()
	{
		return $this->wpdb->prefix.'_bhaa_agecategory';
	}
	
	public function getCreateSQL()
	{
		return 'category varchar(4) DEFAULT NULL,
		  gender enum("M","W") DEFAULT "M",
		  min int(11) NOT NULL,
		  max int(11) NOT NULL,
		  PRIMARY KEY (`category`)';
	}
}
?>