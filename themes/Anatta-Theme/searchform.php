<form action="<?php bloginfo('siteurl'); ?>" id="searchform" method="get">
    <div id="search-video"><!-- replace w/ fieldset, destyled? -->
     
        <input type="text" id="s" name="s" size="20" value="Search Videos" onfocus="if (this.value == 'Search Videos') {this.value = '';}" onblur="if (this.value == '') {this.value = 'Search Videos';}" />
        
        <input type="submit" value="&nbsp;" id="searchsubmit" class="search-btn" />
    </div>
</form>

