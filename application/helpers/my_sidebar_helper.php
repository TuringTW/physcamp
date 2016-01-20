<?php 
	if ( ! function_exists('sidebar'))
	{
		function sidebar($data, $active = -1, $extend = 0)
		{
			$html_string = '<div class="col-sm-1 col-md-2 sidebar" id="sidebar">';
			$num = 0;
			foreach ($data as $gnum => $group) {
				$html_string .= '<ul class="nav nav-sidebar">';
				$html_string .= '<div class="alert '.$group['style'].'">'.$group['name'].'</div>';
				foreach ($group['data'] as $dnum => $datum) {
					$html_string .= '<li '.(($num+$dnum==$active)?'class="active"':'').'><a href="'.$datum['url'].'">'.$datum['icon'].'<span class="sidebartext">&nbsp;&nbsp;'.$datum['text'].'</span></a></li>';
				}
				$num += $dnum+1;
				$html_string .= '</ul>';
				
			}
			$html_string .= '</div><div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">';
			return $html_string;
		}
		// array
			// group
				// groupname
				// group data
					// icon
					// url
					// text
	}

 ?>

	
	
	