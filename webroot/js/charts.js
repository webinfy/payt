$(document).ready( function() {
	var today = new Date().toISOString().split('T')[0];
    $('#start_date,#end_date').attr('max',today);
    if ($('#start_date').val() != '') 
    {
    	$('#end_date').attr('min',$('#start_date').val());
    	var today = new Date().toISOString().split('T')[0];
	    var nextmaxdate = $('#start_date').val().split('-');
	    nextmaxdate[0] = +nextmaxdate[0] + 1;
	    nextmaxdate = nextmaxdate.join('-');
	    if (Date.parse(nextmaxdate) <= Date.parse(today)){
	    	$('#end_date').attr('max',nextmaxdate);
	    }else{
	    	$('#end_date').attr('max',today);
	    }
    }
    if ($('#end_date').val() != '') 
    {
    	$('#start_date').attr('max',$('#end_date').val());
	    var nextmindate = $('#end_date').val().split('-'); 
	    nextmindate[0] = +nextmindate[0] - 1;
	    nextmindate = nextmindate.join('-');
		$('#start_date').attr('min',nextmindate);
    }
    $('#start_date').change(function(event){
    	$('#end_date').attr('min',$(this).val());
    	var today = new Date().toISOString().split('T')[0];
	    var nextmaxdate = $(this).val().split('-');
	    nextmaxdate[0] = +nextmaxdate[0] + 1;
	    nextmaxdate = nextmaxdate.join('-');
	    if (Date.parse(nextmaxdate) <= Date.parse(today)){
	    	$('#end_date').attr('max',nextmaxdate);
	    }else{
	    	$('#end_date').attr('max',today);
	    }
    });
    $('#end_date').change(function(event){
    	$('#start_date').attr('max',$(this).val());
	    var nextmindate = $(this).val().split('-'); 
	    nextmindate[0] = +nextmindate[0] - 1;
	    nextmindate = nextmindate.join('-');
		$('#start_date').attr('min',nextmindate);
    });
})