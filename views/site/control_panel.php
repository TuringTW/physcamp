
	<div class="table-responsive">
		<table class="table table-hover" style="text-align:center">
			
				<thead>
					<tr>
						<th>#</th>
						<th>據點名稱</th>
						<th>lati</th>
						<th>longi</th>
						<th>owner</th>
						<th>type</th>
						<th style="width:5%">詳細</th>
					</tr>
				</thead>
				<tbody id="result_table">
					<?php  ?>
					<?php foreach ($site['sites'] as $key => $value): ?>
						<tr>
							<td><?=$key?></td>
							<td><?=$value['sname']?></td>
							<td><?=$value['lati']?></td>
							<td><?=$value['longi']?></td>
							<td><?=$value['owner']?></td>
							<td><?=$value['type']?></td>
						</tr>
					<?php endforeach ?>


		
				</tbody>
		</table>
	</div>
</div>

			