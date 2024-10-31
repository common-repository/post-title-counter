jQuery(document).ready(function(){
	var maxcount = jQuery('#ptc-maxcount').val();
    jQuery('#title').keyup(function(){
		check_max_count(this);
	});
	function check_max_count(elemId){
		var count = jQuery(elemId).val().length;
		if(count > maxcount){
			jQuery('#ptc-count').addClass('ptc-over');
		}else{
			jQuery('#ptc-count').removeClass('ptc-over');
		}
		jQuery('#ptc-count').html(count);
	}
	jQuery('#clear-post-title').click(function(){jQuery('#title').val("").focus();jQuery('#ptc-count').html(0);});
});