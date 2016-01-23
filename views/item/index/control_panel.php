
	<div class="table-responsive">
		<table class="table table-hover" style="text-align:center">
			
				<thead>
					<tr>
						<th>#</th>
						<th>隊伍</th>
						<th>小隊名稱</th>
						<th>物品名稱</th>
						<th>類型</th>
						<th style="width:5%">詳細</th>
					</tr>
				</thead>
				<tbody id="result_table">
					<?php  ?>
					<?php foreach ($item['items'] as $key => $value): ?>
						<tr>
							<td><?=$key?></td>
							<td><?=($value['team']==1?'藍':($value['team']==2)?'紅':'')?></td>
							<td><?=$value['uname']?></td>
							<td><?=$value['iname']?></td>
							<td><?=$value['itype']?></td>
						</tr>
					<?php endforeach ?>


		
				</tbody>
		</table>
	</div>
</div>

			