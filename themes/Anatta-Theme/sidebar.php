<!--left column-->
	      <div class="left-column">
		<!--Left Sidebar Navigation custom Menu-->
          <?php wp_nav_menu( array('menu' => 'Left Sidebar Navigation', 'container' => '','container_class' => '', 'container_id' => '','menu_class'      => '', 'items_wrap'      => '<ul class="left-navigation">%3$s</ul>' )); ?>
		<!--displaying Ad-->
          <div class="add"><?php if(function_exists('drawAd')) drawAd(array('id' => 1), true);?></div>
				
				
			</div>
			<!--/left column-->