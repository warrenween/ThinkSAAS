<?php
	/* 
	 * 分类
	 */
	defined('IN_TS') or die('Access Denied.');
	$cateid = intval($_GET['cateid']);
	
	$groupid = intval($_GET['groupid']);
	
	if($cateid == '0'){
		//小组所有分类
		$topCate = $db->findAll("select * from ".dbprefix."group_cates where catereferid='0'");
		
		if(is_array($topCate)){
			foreach($topCate as $item){
				$cates = $db->findAll("select * from ".dbprefix."group_cates where catereferid='".$item['cateid']."'");
				
				$arrCate[] = array(
					'cateid'	=> $item['cateid'],
					'catename'	=> $item['catename'],
					'count_group'	=> $item['count_group'],
					'cates'	=> $cates,
				);
				
			}
		}
		
		$title = '所有小组分类';
		
	}else{
	
		//二级分类
		$strCate = $db->find("select * from ".dbprefix."group_cates where cateid = '$cateid'");
		$strCate['caterefername'] = getcaterefername($strCate['catereferid']);
		
		$arrCate = $db->findAll("select * from ".dbprefix."group_cates where catereferid = '$cateid'");
		
		$groupcatenum = $db->findCount('group_cates_index',array(
			'cateid'=>$cateid,
		));
		
		if($groupid != ''){

			$groupnum = $db->findCount('group',array(
				'groupid'=>$groupid,
			));
			
			$groupcateindex = $db->findCount('group_cates_index',array(
				'groupid'=>$groupid,
				'cateid'=>$cateid,
			));
			
			//判断存在这个小组并且小组分类索引中没有这个小组和分类的索引存在
			if($groupnum > '0' && $groupcateindex=='0'){
				$strGroup = $db->find("select * from ".dbprefix."group where groupid='$groupid'");
				
			}
		}
		
		//循环输出小组
		if($strCate['catereferid'] > '0' && $groupcatenum > '0' || $TS_APP['options']['iscate']==1){
		
			$arrGroupCateIndex = $db->findAll("select * from ".dbprefix."group_cates_index where cateid='$cateid'");
			foreach($arrGroupCateIndex as $key=>$item){
				$strGroups = $new['group']->getOneGroup($item['groupid']);
				$arrGroup[] = $strGroups;
			}
			
		}
		
		$title = $strCate['catename'];
	}
	
	function getcaterefername($catereferid){
		global $db;
		$strCateRefer = $db->find("select catename from ".dbprefix."group_cates where cateid='$catereferid'");
		return $strCateRefer['catename'];
	}
	
	include template("cate");