<?php 
$sidebardata = array(array(	'name'=>'',
							'style'=>'alert-info',
							'data'=>array(
								// array('icon'=>'','url'=>web_url("/database/dorm"),'text'=>'宿舍管理'),	
								array('icon'=>'','url'=>web_url("/item/index"),'text'=>'各小隊道具'),	
								array('icon'=>'','url'=>web_url("/item/itemlist"),'text'=>'道具清單'),	
								// array('icon'=>'','url'=>web_url("/database/room"),'text'=>'據點狀態'),	
								// array('icon'=>'','url'=>web_url("/database/electronum"),'text'=>'電表管理'),	
							)
						),
					
					);
echo sidebar($sidebardata, $active);

 ?>