jQuery(document).ready(function(){
	jQuery('body').on('click','.ocscw_open',function(){

		jQuery('body').addClass("body_sizechart");
		jQuery('body').append('<div class="ocscw_loading"><img src="'+ ocscw_object_name +'/includes/images/loader.gif" class="ocscw_loader"></div>');
		var loading = jQuery('.ocscw_loading');
		loading.show();


        var product_id = jQuery(this).data("id");
        var chart_id = jQuery(this).data("cid");
        var current = jQuery(this);
        jQuery.ajax({
	        url:ajax_url,
            type:'POST',
	        data:'action=ocscw_sizechart&product_id='+product_id+'&chart_id='+chart_id,
	        success : function(response) {
	        	var loading = jQuery('.ocscw_loading');
				loading.remove(); 

	            jQuery("#ocscw_sizechart_popup").css("display","block");
	            jQuery("#ocscw_sizechart_popup").html(response);
				
	        },
	        error: function() {
	            alert('Error occured');
	        }
	    });
       return false;
    });

    var modal = document.getElementById("ocscw_sizechart_popup");
	var span = document.getElementsByClassName("ocscw_popup_close")[0];

	jQuery(document).on('click','.ocscw_popup_close',function(){
		jQuery("#ocscw_sizechart_popup").css("display","none");
		jQuery('body').removeClass("body_sizechart");
	});
	
	window.onclick = function(event) {
	  if (event.target == modal) {
	    modal.style.display = "none";
	    jQuery('body').removeClass("body_sizechart");
	  }
	}

	jQuery('body').on('click','ul.ocscw_front_tabs li',function(){
		var closesta = jQuery(this).closest(".ocscw_tableclass");
        var tab_id = jQuery(this).attr('data-tab');
        closesta.find('ul.ocscw_front_tabs li').removeClass('current');
        closesta.find('.ocscw_front_tab_content').removeClass('current');
        jQuery(this).addClass('current');
        ///console.log(closesta.find("#"+tab_id).html());
        closesta.find("#"+tab_id).addClass('current');
    })


})
