(function ($) {
  Drupal.behaviors.mynaht = {
    attach: function (context, settings) {
		

//----------------->>
		
			
		$("body").removeAttr("style");
		
		// Graph
		var pixOfGraph = $("body.node-type-quiz #quiz_progress").width();
		var statusOfGraph = parseInt($("body.node-type-quiz #quiz_progress #quiz-question-number").text());
		var partsOfGraph = parseInt($("body.node-type-quiz #quiz_progress #quiz-num-questions").text());
		var pixelParts = statusOfGraph * Math.round(pixOfGraph/partsOfGraph);
		$("#quiz_progress").css("background-position",pixelParts+"px 0");
		
		//formating the exam page	
		if(pixOfGraph>0){
		
		$("body.node-type-quiz #postscript-bottom-wrapper").remove();
		$("body.node-type-quiz h1.title").addClass("shyTitle");
		$("body.node-type-quiz #footer-wrapper").addClass("slideDown");
		$("body.node-type-quiz #header-group-wrapper").remove();
		}
			
		$(".page-user .field-name-field-ssn .field-item").text('***-**-****');
		
		$('.page-user-myresults	.content table.sticky-enabled td:first-child').html($('.page-user-myresults	.content table.sticky-enabled td:first-child').text());	
	
	$('.page-user-myresults	.content table.sticky-enabled th:last-child').remove();
	$('.page-user-myresults	.content table.sticky-enabled td:last-child').remove();	
	$('.page-user-myresults	.content table.sticky-enabled td:last-child').css('text-align','center');

	$('.page-node-myresults .content table.sticky-enabled th:last-child').remove();
	$('.page-node-myresults .content table.sticky-enabled td:last-child').remove();	
	$('.page-node-myresults .content table.sticky-enabled td:last-child').css('text-align','center');





			$(".reserved-license").css("display","none");
			$("#block-views-grand-licenses-block td.views-field-field-license-number input").css({
																								   'font-size' : '16px',
																								    'letter-spacing' : '3px',
																									width : "140px"
																								});
			
$('.field-add-more-submit').val("Add another license");
$(".tabledrag-toggle-weight-wrapper").remove();
$(".editablefield-item .form-actions.form-wrapper").css({
																									   'position' : 'relative',
																								    'margin-top' : '-42px',
																								    'float' : 'right',
																								    'margin-bottom' : '0'
	});


					
			$(".view-grand-licenses .field-name-field-license-numbers .double-field-elements input[id*='first'], .view-quizes-taken .field-name-field-license-numbers .double-field-elements input[id*='first']").click(function(){	
				
					var value=$.trim($(this).val());

					if(value.length>0)
					{
						//do nothing
					} else {
						//create license
						$("#licensePrefix").text();	
						var num = Math.floor(Math.random()*(999-101)+101);
						$(this).val($("#licensePrefix").text()+num);	
					}
				
			});
			
			
			
			
			
var selectCertificationDate	= $('.view-certification-dates').parent().html();

$('.field-name-field-certification-date input').after(selectCertificationDate);		
$('.field-name-field-certification-date input').click(function(){
	//$(this).after(selectCertificationDate);
	
	
	});
	
$('.item-list-certification-dates ul li .date-display-single').click(function(){
	
	$('.field-name-field-certification-date input').val($(this).text());
	
	});
	
$('.item-list-certification-dates ul li').click(function(){
	
	$(this).siblings().removeClass('selected');
	
	$(this).addClass('selected');
	
	});
	
	

var pathname = window.location.pathname;
var pathname = pathname.substr(1);
$('.register-now-button').attr('href','/user/register?destination='+pathname);	

$('#content-tabs li a:contains("Edit")').text('Update Account');

//-----------expiration weeks


$('.view-my-licenses-user-dashboard .double-field-second').after( '<div class="double-field-expiration"></div>' );

//$('.view-license-manager .double-field-second').after( '<div class="double-field-expiration"></div>' );

$('.double-field-expiration').each( function(index){
	
			var week_of_year = [
			    "Jan",
			    "Jan",
			    "Jan",
			    "Jan",
			    "Jan",
			    "Feb",
			    "Feb",
			    "Feb",
			    "Feb",
			    "Mar",
			    "Mar",
			    "Mar",
			    "Mar",
			    "Mar",
			    "Apr",
			    "Apr",
			    "Apr",
			    "Apr",
			    "May",
			    "May",
			    "May",
			    "May",
			    "Jun",
			    "Jun",
			    "Jun",
			    "Jun",
			    "Jul",
			    "Jul",
			    "Jul",
			    "Jul",
			    "Aug",
			    "Aug",
			    "Aug",
			    "Aug",
			    "Aug",
			    "Sep",
			    "Sep",
			    "Sep",
			    "Sep",
			    "Oct",
			    "Oct",
			    "Oct",
			    "Oct",
			    "Nov",
			    "Nov",
			    "Nov",
			    "Nov",
			    "Dec",
			    "Dec",
			    "Dec",
			    "Dec",
			    "Dec"
			];
				

				var expiration = $(this).siblings('.double-field-first').text();
				
				var expiration_week = Number(expiration.substring(2, 4)); 
				expiration_week = week_of_year[expiration_week];
				
				
				var expiration_year = Number(expiration.substring(0, 2)); 
				expiration_year = expiration_year + 2; 
				
				/*		year_expires = new Date('1/1/'+expiration_year);
						expiration_days = expiration_week*7;
						year_expires.setDate(year_expires.getDate() + expiration_days);
				
				//expiration_week = Date.UTC(expiration_year, m , 1+ 1);*/
				
				$(this).text("Valid until "+ expiration_week + " of 20" + expiration_year );
	
});



/*
expirationDate = function(licenseNumber){

// convert the license number into formated date
// license expires last day of the month
  
  console.log('license #: ' + licenseNumber);
  var license = licenseNumber.toString();

  
  
	var findMonthByWeek = [ 
		1, 1, 1, 1, 		1, 
		2, 2, 2, 2,
		3, 3, 3, 3,			3,  
		4, 4, 4, 4, 
		5, 5, 5, 5, 
		6, 6, 6,6, 
		7, 7, 7, 7, 
		8, 8,8, 8, 			8, 
		9, 9, 9, 9,	
		10, 10, 10, 10, 
		11, 11, 11, 11, 
		12, 12, 12, 12, 	12
        ];
  
  var week = license.substring(2, 4);
  
  console.log('week #: ' + week) ;

	expirationMonh = Number( findMonthByWeek[week]);
	expirationYear = Number(20 + license.substring(0, 2)) + 2; 
  
	expirationDay = 1;

	var now = new Date(expirationYear, expirationMonh, expirationDay);

	expiration = new Date(now.getFullYear(), now.getMonth()+1, 0);

	return(expiration);


}
$('.views-field-field-license-numbers-1').each(function(index){

	//expiration = expirationDate(15211380);

	var licenseNumber = $(this).find('.double-field-first');

	console.log(licenseNumber.text());

	expiration = expirationDate(licenseNumber);


	licenseNumber.text('Expires ' + expiration);



});

*/
				function licenseExpiration(e) {

				  var expires_year = 2000+2+Number(e.substring(0, 2)); 
				  var created_days = (Number(e.substring(2, 4))*7)+6; 

				  jan01 = new Date(expires_year+'-01-01 00:00:00 GMT-0500 (EST)');
				  expiration = jan01;
				  expiration.setDate(jan01.getDate()+created_days);

				  var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];

				  var month = months[expiration.getUTCMonth()];
				  var day = expiration.getUTCDate();
				  var year = expiration.getUTCFullYear();

				  return month + ' ' + day + ', ' + year;
				}

/*

				var expiration = licenseExpiration('15211380');

				$('.double-field-first').text(expiration);

*/

				$('.views-field-field-license-numbers-1 .double-field-first').each(function(index){


				  var licenseNumber = $(this).text();

				  console.log(licenseNumber);

				  var expiration = licenseExpiration(licenseNumber);

				  console.log(expiration);

				  var this_expires = expiration;

				  $(this).after('<div class="double-field-second">Expires: ' + this_expires+'</div>');

				  //$(this).text(expiration);


				  //$(this).append(expiration);


				  //expiration = licenseExpiration(licenseNumber);


				 // licenseNumber.text('Expires: ' + expiration);



				});

// verify license


$('.expires').each(function(){

	licenseNumber = $('.double-field-first').text();
	var expiration = licenseExpiration(licenseNumber);

	$(this).text(expiration);

});

$('#edit-submit-license-verification').click(function(){

	//console.log(licenseExpiration('55245544'));

});

$(".view-license-verification .views-row-first:contains('Anonymous')").remove();
$(".view-license-verification .views-row-first div:last-child").addClass('certificate-expiration-date');
$('.view-license-verification .views-exposed-widgets #edit-submit-license-verification').text('Verify License');

$('body.node-type-quiz #edit-button').toggleClass('btn-default btn-primary btn-lg');
$('.field-name-field-time-available').addClass('well well-lg');
$('table').addClass('table');
// take my exam
$('.view-my-exams-user-dashboard ul li').each(function(index){
    var examsUserDashboard = $('.view-my-exams-user-dashboard ul li');
    var href = $('.views-field-path a').eq(index).attr('href');
	//console.log(index);
	//console.log(href);
	if (href.search('spanish') != -1) {
		examsUserDashboard.eq(index).find('a.btn').text('Comenzar Examen');
	}	
});

certificationTitle = $('body.node-type-quiz h1.page-header').text();
console.log(certificationTitle);
 	if (certificationTitle.search('panish') != -1){
		$('#quiz-start-quiz-button-form #edit-button').attr('value','Comenzar Examen').text('Comenzar Examen');
	} else {
		$('#quiz-start-quiz-button-form #edit-button').attr('value','Start Exam').text('Start Exam');	
	}


// duplicating registration button

var registerNow = $('#block-block-17').html();
$('.page-node-99 p.lead, .page-node-102 p.lead').append(registerNow);



//----------------->>
			
    }
  };
}(jQuery));