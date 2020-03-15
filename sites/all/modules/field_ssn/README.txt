-- SUMMARY --
Module supply your content types with a field to store Social Security Number, optionally encrypted.


-- REQUIREMENTS --
In case you need an encryption for SSN, you may install module like https://drupal.org/project/aes or
https://drupal.org/project/encrypt. First one supported out of the box. In case of using custom mechanisms please
set encryption/descryption function on a settings page.
Default settings are
  aes_encrypt:aes_decrypt
  to switch to the Encrypt module use
  encrypt:decrypt

Default setting means on saving module will do $saved_value = aes_encrypt($form_value) and on loading
$form_value = aes_decrypt($saved_value). You may clear this field if you do not need encryption, to speed up saving.

In case you change encryption mechanism, saved data will not be converted unless edited, so please keep both decryptors
available for a while.


-- INSTALLATION --
Install just as a regular module, then you should have SSN fields type.


-- MAINTAINERS --
Author and current maintainer:
* Dennis Povshedny (dpovshed) - http://drupal.org/user/117896


-- TODO/FEATURE REQUESTS --
- check availability of encryption functions only once and cache this;
- apply masked input by default;
- validation function for USA SSN;
- selector/textfield in settings to choose/set validation function;
- add status page where do check of availability and validity of encryption/decryption functions;
