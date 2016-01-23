
	<div class="table-responsive">
		<table class="table table-hover" style="text-align:center">
			
				<thead>
					<tr>
						<th>#</th>
						<th>道具名稱</th>
						<th>類型</th>
						<th>攻擊力</th>
						<th style="width:5%">詳細</th>
					</tr>
				</thead>
				<tbody id="result_table">
					<?php foreach ($itemlist['itemlist'] as $key => $value): ?>
						<tr>
							<td><?=$key?></td>
							<td><?=$value['name']?></td>
							<td><?=($value['type']==1?'武器':($value['type']==2)?'消耗品':'任務道具')?></td>
							<td><?=$value['atk']?></td>
						</tr>
					<?php endforeach ?>


		
				</tbody>
		</table>
	</div>
</div>

			