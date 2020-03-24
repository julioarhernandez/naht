(function ($) {

  'use strict';

  /**
   * Provide the summary information within the block settings vertical tabs.
   */
  Drupal.behaviors.blockInBlockSettingsSummary = {
    attach: function (context) {
      // The drupalSetSummary method required for this behavior is not available
      // on the Blocks administration page, so we need to make sure this
      // behavior is processed only if drupalSetSummary is defined.
      if (typeof jQuery.fn.drupalSetSummary == 'undefined') {
        return;
      }

      $('fieldset#edit-block-in-block', context).drupalSetSummary(function (context) {
        var is_enabled = $('input[name="block_in_block_enabled"]', context).attr('checked');
        if (is_enabled) {
          var target_blocks = [];
          $('.block-in-block-target-block option[value!=0]:selected').each(function () {
            target_blocks.push($.trim($(this).text()));
          });
          if (target_blocks.length === 0) {
            target_blocks.push(Drupal.t('Enabled, but no block chosen'));
          }
          return target_blocks.join(', ');
        }
        else {
          return Drupal.t('Not restricted');
        }
      });
    }
  };

  /**
   * Dynamically change the "target substring" within field descriptions.
  */
  Drupal.behaviors.blockInBlockUpdateFieldDescriptions = {
    attach: function (context) {
      var typingTimer;
      var doneTypingInterval = 1500;

      doneTyping('.block-in-block-where-to-insert', $('input[name="block_in_block_target_substring"]').val());
      $('input[name="block_in_block_target_substring"]', context).keyup(function (context) {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(function () {
          doneTyping('.block-in-block-where-to-insert', $('input[name="block_in_block_target_substring"]').val());
        }, doneTypingInterval);
      });
      function doneTyping(selector, value) {
        if (!value.trim()) {
          value = Drupal.t('the "Where to insert" string');
        }
        else {
          value = '"' + value + '"';
        }
        $(selector).text(value);
      }
    }
  };
})(jQuery);


