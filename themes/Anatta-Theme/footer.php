<?php
$request_uri = explode('/',($_SERVER['REQUEST_URI']));
if($request_uri[1] != 'videos' && $request_uri[1] != 'client-portal' ) //checking for videos custom post type
{ ?>
   <!--bottom advertisement-->
			<div class="add-content">
					<!--displaying Ads-->
					<span class ="add-space"><?php if(function_exists('drawAd')) drawAd(array('id' => 2), true);?></span>
				<?php if(function_exists('drawAd')) drawAd(array('id' => 3), true);?>
			</div>
			<!--/bottom advertisement-->
    <?php } ?>
    <!--fotter-->
			<div id="footer">
			<!--Main navigations for wp-pages - Header top navigation and footer navigation-->
					<?php wp_nav_menu( array('menu' => 'Header Top Navigation', 'container' => '','container_class' => '', 'container_id' => '','menu_class'      => '', 'items_wrap'      => '<ul class="list-1">%3$s</ul>' )); ?>
				
				
					<?php wp_nav_menu( array('menu' => 'Footer Navigation', 'container' => '','container_class' => '', 'container_id' => '','menu_class'      => '', 'items_wrap'      => '<ul>%3$s</ul>')); ?>
				
			</div>
			<!--/fotter-->
			<!--copyrights-->
			<div id="copyrights"><?php echo date("Y"); ?> Med World Live, Inc. All Rights Reserved </div>
			<!--/copyrights-->
		</div>
		<!--/Content area-->
    </div>
	<!--/wrapper-->
	<!-- analytics -->
    <script type="text/javascript" language="javascript">llactid=19482</script> 
<script type="text/javascript" language="javascript" src="http://t2.trackalyzer.com/trackalyze.js"></script>
	<?php wp_footer(); ?>
    <script src="<?php bloginfo('template_directory'); ?>/scripts/custom.js"></script>
</body>
</html>
