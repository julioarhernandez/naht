(function ($) {
  Drupal.behaviors.mynaht = {
    attach: function (context, settings) {
//----------------->>

// week of the year




// verify licenses
function verifyLicense(){

// display the name of the owner of this license and the expiration date

}


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

	expiration = new Date(now.getFullYear(), now.getMonth()+1, 1);

	return(expiration);


}
//expirationDate(15243344);



/*
$('.view-license-manager .double-field-first').each( function(index){

	$(this).after( '<span class="license-expires">expires</div>');

}

*/



$('.double-field-first').remove();

$('.view-license-manager .container-inline').after( '<div class="double-field-expiration"></div>' );

$('.double-field-expiration').each( function(index){
	
			var week_of_year = [
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























//----------------->>
    }
  };
}(jQuery));