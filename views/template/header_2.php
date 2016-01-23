<body onload="ShowTime();" style="overflow-x:hidden;overflow-y:scroll;">
	<div class = "navbar navbar-inverse navbar-fixed-top">
		<div class = "container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navHeadercollapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="<?=web_url('/index/index')?>" title="首頁、控制面板">物理營2015</a>
			</div>			
			<div class = "collapse navbar-collapse navHeadercollapse">
				<ul class = "nav navbar-nav navbar-left" id="nav">
					<!-- <li><a href = "<?=web_url('/index/index')?>">Home</a></li> -->
					<li><a href = "<?=web_url('/site/index')?>">據點資料</a></li>
					<li><a href = "<?=web_url('/item/index')?>">道具</a></li>
					<li><a href = "<?=web_url('/mission/index')?>">任務</a></li>
					<li><a href = "<?=web_url('/system/log')?>">System</a></li>
					
					<!-- <li class="dropdown">
						<a herf="#" class="dropdown-toggle" data-toggle="dropdown" data-target="dropdown">道具</a>
						<ul class="dropdown-menu">
							<!-- <li><a href = "receipt.php">收據管理</a></li> -->
							<li><a href = "<?=web_url('/system/qna')?>">Q&A</a></li>
						</ul>
					</li> -->
					


				<ul class = "nav navbar-nav navbar-right">
					<li><a id="showbox"></a></li>
					<li class="dropdown">
						<a herf="#" class="dropdown-toggle" data-toggle="dropdown" data-target="dropdown"><?="使用者:".$user?></a>

					</li>
					<li><a  href="<?=web_url('/index/logout')?>">登出</a></li>				
				</ul>
			</div>
		</div>
	</div>
	<div class="row">