<?php 
	if ( ! function_exists('dorm_list_html'))
	{

		function dorm_list_html($dormlist){
			?>
					<div class="btn-group" style="width:100%">
						<input type="hidden" id="dorm_select_value" value="0">
						<button type="button" class="btn btn-default dropdown-toggle btn-success" data-toggle="dropdown" style="width:100%">
							宿舍:<span id="lbldorm">全部</span> <span class="caret"></span>
						</button>
						<?php 	$countdorm = count($dormlist);
								$coln = floor(($countdorm+2)/10);
								$j = 0; 
						?>
						<div class="dropdown-menu" role="menu" style="margin-left: -120%;width:<?=($coln>1)?($coln-2)*100+200:200?>%">
							<div class="row" style="width:100%">
								<?php for ($i=0; $i <= $coln; $i++) { ?>
									<div class="col-md-<?=floor(12/($coln+1))?>" style="padding-left:15px;padding-right:0px;">
										
										<ul class="nav navbar" style="width:100%">
											<?php if ($i==0) { ?>
												<li style="font-size:15px;font-weight:bold;"><a href="#" style="color:#003767"  onclick="dorm_select(0,'全部')">全部</a></li>									
												<hr>
											<?php 
													$key = $j -2;
												}else{
													$key = $j;
												} ?>
											<?php while($j < $key+10 && $j < $countdorm) { ?>
												<?php $value = $dormlist[$j];
												if ($value['dorm_id']!=33&&$value['dorm_id']!=34){ ?>
													<li style="font-size:15px;font-weight:bold;"><a href="#" style="color:#003767" onclick="dorm_select(<?=$value['dorm_id']?>,'<?=$value['name']?>')"><?=$value['name']?></a></li>									
												<?php }else{ ?>
													<?php $key++; ?>
												<?php } ?>
												<?php $j++ ?>
											<?php } ?>
										</ul>
									</div>	
								<?php } ?>
							</div>
						</div>
					</div>
				

					<?php 


		}
	}
	if ( ! function_exists('dorm_button_btnlist'))
	{

		function dorm_button_btnlist($dormlist){
			?>
			<?php foreach ($dormlist as $key => $dorm): ?>
				<?php if ($dorm['dorm_id']!=33&&$dorm['dorm_id']!=34){ ?>
					<a href="#" class="btn btn-default dormbtnlist" id="dorm_select_<?=$dorm['dorm_id']?>" style="color:#003767" onclick="dorm_select(<?=$dorm['dorm_id']?>)"><?=$dorm['name']?></a>
				<?php } ?>
			<?php endforeach ?>

					

		<?php 


		}
	}
	if ( ! function_exists('type_list_html'))
	{

		function type_list_html($typelist){
			?>
					<div class="btn-group" style="width:100%">
						<input type="hidden" id="type_select_value" value="0">
						<button type="button" class="btn btn-default dropdown-toggle btn-info" data-toggle="dropdown" style="width:100%">
							類別:<span id="lbltype">全部</span> <span class="caret"></span>
						</button>
							
						<div class="dropdown-menu" role="menu" >

							<ul class="nav navbar" style="width:100%">
								<li style="font-size:15px;font-weight:bold;"><a href="#" style="color:#003767"  onclick="type_select(0,'全部')">全部</a></li>
								<hr>
								<?php foreach ($typelist as $key => $value) { ?>
									<li style="font-size:15px;font-weight:bold;"><a href="#" style="color:#003767" onclick="type_select(<?=$value['cate_id']?>,'<?=$value['cate']?>')"><?=$value['cate']?></a></li>
								<?php } ?>
								<hr>
								<li style="font-size:15px;font-weight:bold;"><a href="#" style="color:#003767"  onclick="">新增分類</a></li>
							</ul>
						</div>
					</div>
				

					<?php 


		}
	}
	if ( ! function_exists('rtype_list_html'))
	{

		function rtype_list_html(){
			?>
					<div class="btn-group" style="width:100%">
						<input type="hidden" id="rtype_select_value" value="0">
						<button type="button" class="btn btn-default dropdown-toggle btn-warning" data-toggle="dropdown" style="width:100%">
							收據類型:<span id="lblrtype">全部</span> <span class="caret"></span>
						</button>
							
						<div class="dropdown-menu" role="menu" >

							<ul class="nav navbar" style="width:100%">
								<li style="font-size:15px;font-weight:bold;"><a href="#" style="color:#003767"  onclick="rtype_select(0,'全部')">全部</a></li>
								<hr>
								<li style="font-size:15px;font-weight:bold;"><a href="#" style="color:#003767"  onclick="rtype_select(1,'發票')">發票</a></li>
								<li style="font-size:15px;font-weight:bold;"><a href="#" style="color:#003767"  onclick="rtype_select(2,'費用')">費用</a></li>
								<li style="font-size:15px;font-weight:bold;"><a href="#" style="color:#003767"  onclick="rtype_select(3,'請款單')">請款單</a></li>
							</ul>
						</div>
					</div>
				

					<?php 


		}
	}
	if ( ! function_exists('type_list_select'))
	{

		function type_list_select($typelist){
			?>
			<?php foreach ($typelist as $key => $value): ?>
				<option class="form-control" value="<?=$value['cate_id']?>"><?=$value['cate']?></option>
			<?php endforeach ?>
					

		<?php 


		}
	}
?>