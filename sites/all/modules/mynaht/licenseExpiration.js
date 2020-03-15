(function ($) {
  Drupal.behaviors.mynaht = {
    attach: function (context, settings) {
//----------------->>



function licenseExpiration(e) {

  var expires_year = 2000+2+Number(e.substring(0, 2)); 
  var created_days = (Number(e.substring(2, 4))*7)+6; 

  jan01 = new Date(expires_year+'-01-01 00:00:00 GMT-0500 (EST)');
  expiration = jan01;
  expiration.setDate(jan01.getDate()+created_days);

  var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dic'];

  var month = months[expiration.getUTCMonth()];
  var day = expiration.getUTCDate();
  var year = expiration.getUTCFullYear();

  return month + ' ' + day + ', ' + year;
}


$('.double-field-first').remove();

$('.views-field-field-license-numbers-1').each(function(index){

  //expiration = expirationDate(15211380);

  var licenseNumber = $(this).find('.double-field-first');

  console.log(licenseNumber);

  expiration = licenseExpiration(licenseNumber);


  licenseNumber.text('Expires: ' + expiration);



});



//----------------->>
    }
  };
}(jQuery));