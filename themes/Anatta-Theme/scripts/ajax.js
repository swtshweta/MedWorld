$(document).ready(function(){
	
	// As soon as any of the selector is changed, make the AJAX call to fetch data
	$("#portal-listing select").change(function(){
											
		var data = {
			action: 'portal_listing',
			physician: $("select#filterbyp").val(),
			country: $("select#filterbyc").val(),
			speciality: $("select#filterbys").val(),
			medical: $("select#filterbym").val(),
			hospital: $("select#filterbyh").val(),
			page: '1'
		};
		//alert('test');
		
		// Make sure you pass the correct url in the first argument below
		$.post( ajax_data.ajax_url, data, function( data ){
						
			$("#main_listing_div").html(data); // assuming you are echoing the markup from the AJAX call
		});
	});
	
	// When clicking on a link in pagination
	$("#main_listing_div .wp-pagenavi a").live( 'click', function(){
		
		// Collect data to be send on AJAX call
		var data = {
			action: 'portal_listing',
			physician: $("select#filterbyp").val(),
			country: $("select#filterbyc").val(),
			speciality: $("select#filterbys").val(),
			medical: $("select#filterbym").val(),
			hospital: $("select#filterbyh").val()
			
		};
		
		// check if the click is on a page number or next page listing link
		if ( $(this).hasClass('nextpostslink') || $(this).hasClass('larger')) {
			// fetches the current page
			data.page = $("#main_listing_div .wp-pagenavi .current").text();
		 
			// increment it to get the desired page number
			data.page++;
			//alert(data.page);
			
			$.post( ajax_data.ajax_url, data, function( data ){
						
			$("#main_listing_div").html(data); // assuming you are echoing the markup from the AJAX call
		});
		} else if ( $(this).hasClass('previouspostslink') || $(this).hasClass('smaller')) {
			// fetches the current page
			data.page = $("#main_listing_div .wp-pagenavi .current").text();
			// decrement it to get the desired page number
			data.page--;
			
			//alert(data.page);
			
			$.post( ajax_data.ajax_url, data, function( data ){
						
			$("#main_listing_div").html(data); // assuming you are echoing the markup from the AJAX call
		});
		} else {
			// get the page number from the anchor text
			data.page = $(this).text();
		}
		// Make sure you pass the correct url in the first argument below
		
		
		//cancel the default action of anchor tag
		return false;
	});
});

