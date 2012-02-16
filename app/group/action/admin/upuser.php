<?php 
//将用户全部绑定到群组
$groupid = $_GET['groupid'];

$arrUser = $db->findAll("select userid from ".dbprefix."user order by userid desc");

foreach($arrUser as $item){

	$groupusernum = $db->findCount('group_users',array(
		'userid'=>$item['userid'],
		'groupid'=>$groupid,
	));
	
	if($groupusernum == '0'){
		
		$db->create('group_users',array(
			'userid'=>$item['userid'],
			'groupid'=>$item['groupid'],
			'addtime'=>time(),
		));
		
	}
	
}

$userNum = $db->findCount('group_users',array(
	'groupid'=>$groupid,
));

$db->update('group',array(
	'count_user'=>$userNum,
),array(
	'groupid'=>$groupid,
));


qiMsg("会员投送成功！");