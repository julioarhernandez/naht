<?php

/**
 * @file
 * Hooks provided by the Block in block module.
 */

/**
 * @addtogroup hooks
 * @{
 */

/**
 * Change what determines whether a block is supposed to be inserted.
 *
 * @param &$is_insertable
 *   Boolean indicating if the block is configured to be inserted into another.
 * @param $block
 *   Block object to test.
 *
 * @see block_in_block_is_insertable()
 *   This hook is declared inside the function.
 */
function hook_block_in_block_is_insertable_alter(&$is_insertable, $block) {
  // Prevent a block with a specific MODULE and DELTA from being inserted.
  if ($is_insertable && $block->module == 'MODULE' && $block->delta == 'DELTA') {
    $is_insertable = FALSE;
  }
}

/**
 * Act on blocks prior to processing.
 *
 * This hook allows you to add, remove, or modify blocks in the block list.
 *
 * Note: just because a block is included in the list, does not necessarily
 * mean it will be inserted during this page load.
 *
 * @param $blocks
 *   An array of block insertion settings, keyed by "block key".
 *
 * @see block_in_block_get_block_key()
 *   For more information about block keys.
 * @see _block_in_block_load_block_in_blocks()
 *   This hook is declared inside the function.
 */
function hook_block_in_block_list_alter(&$blocks) {
  // Change a block's insertion status to enabled.
  foreach ($blocks as $block_key => $insertion_settings) {
    if (!$insertion_settings['enabled'] && $block_key == 'some_block_key') {
      $blocks[$block_key]['enabled'] = TRUE;
      // Add something to the array to identify at a later point that the block
      // was enabled by some_module.
      $blocks[$block_key]['some_module_enabled'] = TRUE;
    }
  }
}

/**
 * Perform alterations to the insertion settings of a block prior to processing.
 *
 * This contains the insertion settings for a block that is enabled to be
 * inserted, and immediately prior to it being processed for inclusion in the
 * array of blocks to be examined during the block/node preprocess hooks.
 *
 * Note: Even though the block is enabled to be inserted, does not necessarily
 * mean it will be inserted during this page load.
 *
 * You can prevent a block from being processed any further by Block in Block
 * by assigning the array under the target_blocks key to an empty array:
 * $settings['target_blocks'] = array();
 *
 * @param $settings
 *   An associative array containing information of where, how, and what should
 *   be inserted.
 * @param $block
 *   The block object being inserted.
 *
 * @see block_in_block_get_block_insertion_settings()
 *   For more information about the $settings array.
 * @see _block_in_block_store_block_to_insert()
 *   This hook is declared inside the function.
 */
function hook_block_in_block_settings_alter(&$settings, $block) {
  // Change the target substring to something that is different on each page
  // load.
  if ($settings['target_substring'] == 'my_module_placeholder') {
    $settings['target_substring'] = my_module_get_dynamic_target_substring();
  }
}

/**
 * Perform alterations to a set of occurrences, or takes over their insertions.
 *
 * To stop all occurrences from being processed:
 *   $occurrence_parts = array();
 * To stop one occurrence from being processed:
 *   unset($occurrence_parts[INDEX]);
 * To prevent the placeholder from being inserted into an element of the
 * content_array:
 *   content_indexes_used[] = CONTENT_PARTS_INDEX;
 *
 * @param $occurrence_parts
 *   Array of occurrence strings resulting from exploding the value entered
 *   into the occurrences input field by a comma.
 * @param $insertion_details
 *   Associative array composed of the following:
 *   - content_parts (passed by reference)
 *       Alterable array of strings of the content split by the target
 *       substring. Note: the first occurrence of the target substring is
 *       located at index 1, not index 0.
 *   - content_indexes_used (passed by reference)
 *       Alterable array of content_part indexes that have already been inserted
 *       into, or are excluded from being inserted into.
 *   - insertion_groups_by_insertion_type
 *       Associative array containing insertion groups, keyed by how the
 *       insertion is to take place. Possible keys:
 *       - after: the content should be inserted after the target substring.
 *       - before: the content should be inserted before the target substring.
 *       - replace: the content should replace the target substring.
 *    - inserted_insertion_groups (passed by reference)
 *       Array of insertion groups: If a module that implements this hook
 *       inserts an insertion group's placeholder into the $content_parts array,
 *       then it should also add a copy of that placeholder's insertion group
 *       into the $inserted_insertion_groups array.
 *
 * @see _block_in_block_store_block_to_insert()
 *   For more information about insertion groups.
 * @see _block_in_block_get_content_with_placeholders_at_occurrences()
 *   This hook is declared inside the function.
 */
function hook_block_in_block_insert_placeholders_at_occurrences_alter(&$occurrence_parts, &$insertion_details) {
  // Handle all insertions for my custom occurrence type.
  $inserted_insertion_groups = &$insertion_details['inserted_insertion_groups'];
  $content_parts = &$insertion_details['content_parts'];
  $content_indexes_used = &$insertion_details['content_indexes_used'];
  $insertion_groups_by_insertion_type = &$insertion_details['insertion_groups_by_insertion_type'];
  foreach ($occurrence_parts as $occurrence_key => $occurrence) {
    if (my_module_is_custom_occurrence($occurrence)) {
      foreach ($insertion_groups_by_insertion_type as $insertion_type => $insertion_group) {
        $occurrence_number = my_module_get_occurrence_number($occurrence);
        if (!in_array($occurrence_number, $content_indexes_used)) {
          my_module_insert_placeholders($content_parts, $occurrence_number, $insertion_type, $insertion_group);
          $content_indexes_used[] = $occurrence_number;
          $inserted_insertion_groups[] = $insertion_group;
        }
      }
      unset($occurrence_parts[$occurrence_key]);
    }
  }
}

/**
 * Use a custom occurrence type to insert a placeholder.
 *
 * The given $occurrence expression is not recognized by the Block in Block
 * module, and no insertions have been made with it.
 *
 * @param $occurrence
 *   String containing the occurrence expression.
 * @param $insertion_details
 *   Associative array composed of the following:
 *   - content_parts (passed by reference)
 *       Alterable array of strings of the content split by the target
 *       substring. Note: the first occurrence of the target substring is
 *       located at index 1, not index 0.
 *   - content_indexes_used (passed by reference)
 *       Alterable array of content_part indexes that have already been inserted
 *       into, or are excluded from being inserted into.
 *   - insertion_groups_by_insertion_type
 *       Associative array containing insertion groups, keyed by how the
 *       insertion is to take place. Possible keys:
 *       - after: the content should be inserted after the target substring.
 *       - before: the content should be inserted before the target substring.
 *       - replace: the content should replace the target substring.
 *    - inserted_insertion_groups (passed by reference)
 *       Array of insertion groups: If a module that implements this hook
 *       inserts an insertion group's placeholder into the $content_parts array,
 *       then it should also add a copy of that placeholder's insertion group
 *       into the $inserted_insertion_groups array.
 *
 * @see hook_block_in_block_is_valid_occurrence_alter()
 *   For more information on defining custom occurrences.
 * @see hook_block_in_block_insert_placeholders_at_occurrences_alter()
 *   For information on performing alterations to a set of occurrences, or
 *   taking over their insertions.
 * @see _block_in_block_store_block_to_insert()
 *   For more information about insertion groups.
 * @see _block_in_block_insert_placeholders_at_occurrence()
 *   This hook is declared inside the function.
 */
function hook_block_in_block_insert_placeholders_at_occurrence_alter($occurrence, &$insertion_details) {
  // Insert the placeholder using the custom occurrence expression.
  $inserted_insertion_groups = &$insertion_details['inserted_insertion_groups'];
  $content_parts = &$insertion_details['content_parts'];
  $content_indexes_used = &$insertion_details['content_indexes_used'];
  if (my_module_is_custom_occurrence($occurrence)) {
    foreach ($insertion_details['insertion_groups_by_insertion_type'] as $insertion_type => $insertion_group) {
      $occurrence_number = my_module_get_occurrence_number($occurrence);
      if (!in_array($occurrence_number, $content_indexes_used)) {
        my_module_insert_placeholders($content_parts, $occurrence_number, $insertion_type, $insertion_group);
        $content_indexes_used[] = $occurrence_number;
        $inserted_insertion_groups[] = $insertion_group;
      }
    }
  }
}

/**
 * Change how an insertion group's placeholder is inserted into the content.
 *
 * @param &$content_with_placeholders
 *   String containing the content after the placeholders have been inserted
 *   by the Block in Block module. Replace with your module's changes.
 * @param $content
 *   String containing the content before this insertion group's placeholders
 *   have been inserted. It may contain placeholders from other insertion
 *   groups. Note: Any changes you make to this variable will be discarded.
 * @param $insertion_group
 *   Associative array with the insertion details and the blocks to be inserted:
 *   - 'blocks_to_insert': array of blocks keyed by block_key.
 *   - 'insertion_group_placeholder': String with the placeholder to insert.
 *   - 'insertion_group_key' = > String of a unique key for the insertion group.
 *   - 'target_block_key' = > String for the block_key being inserted into.
 *   - 'node_type' = > String with the node_type (only present inserted into the
 *      Main page content block).
 *   - 'view_mode' = > String with the view_mode (only present inserted into the
 *      Main page content block).
 *   - 'target_substring' = > String containing 'Where to insert'.
 *   - 'occurrences' = > String containing the 'Occurrences to insert at'.
 *   - 'insertion_type' = > String containing the 'How to insert' value.
 *
 * @see _block_in_block_store_block_to_insert()
 *   For more information about insertion groups.
 * @see _block_in_block_insert_placeholders();
 *   This hook is declared inside the function.
 */
function hook_block_in_block_insert_placeholder_alter(&$content_with_placeholders, $content, $insertion_group) {
  if (my_module_is_my_insertion_type($insertion_group['insertion_type'])) {
    $content_with_placeholders = my_module_get_content_with_placeholders($content, $insertion_group);
  }
}

/**
 * Change the 'replace' string to be used to insert the placeholder.
 *
 * @param &$replace
 *   String containing the 'replace' parameter to be used for a str_replace().
 * @param $insertion_group
 *   Associative array with the insertion details and the blocks to be inserted:
 *   - 'blocks_to_insert': array of blocks keyed by block_key.
 *   - 'insertion_group_placeholder': String with the placeholder to insert.
 *   - 'insertion_group_key' = > String of a unique key for the insertion group.
 *   - 'target_block_key' = > String for the block_key being inserted into.
 *   - 'node_type' = > String with the node_type (only present inserted into the
 *      Main page content block).
 *   - 'view_mode' = > String with the view_mode (only present inserted into the
 *      Main page content block).
 *   - 'target_substring' = > String containing 'Where to insert'.
 *   - 'occurrences' = > String containing the 'Occurrences to insert at'.
 *   - 'insertion_type' = > String containing the 'How to insert' value.
 *
 * @see _block_in_block_store_block_to_insert()
 *   For more information about insertion groups.
 * @see _block_in_block_get_replace_string_for_insertion_group()
 *   This hook is declared inside the function.
 */
function hook_block_in_block_replacement_string_alter(&$replace, $insertion_group) {
  if (my_module_is_my_insertion_type($insertion_group['insertion_type'])) {
    $replace = my_module_get_replace_string($insertion_group);
  }
}

/**
 * Validate a custom occurrence type.
 *
 * This hook allows other modules to define their own occurrence-type. The hook
 * is called only when the occurrence does not match one of Block in Block's
 * occurrence-types.
 *
 * A module should not change any of the variables passed by reference if the
 * occurrence does not match its occurrence-type. If the occurrence matches a
 * module's occurrence-type, it should should then indicate if the occurrence
 * is properly formatted (i.e. is valid):
 * - If the occurrence is properly formatted:
 *   Set $is_valid to TRUE.
 * - If the occurrence is not properly formatted:
 *    Set $is_valid to FALSE, add an error message to $custom_error_messages.
 *
 * @param $is_valid
 *   Boolean: TRUE if occurrence is valid. FALSE if not.
 * @param $custom_error_messages
 *   Array of strings to display to the user explaining why an occurrence is
 *   invalid.
 * @param $occurrence
 *   String containing the occurrence to act upon.
 *
 * @see hook_block_in_block_insert_placeholders_at_occurrence_alter()
 *   For information on using custom occurrences to insert placeholders.
 * @see _block_in_block_is_valid_occurrence()
 *   This hook is declared inside the function.
 */
function hook_block_in_block_is_valid_occurrence_alter(&$is_valid, &$custom_error_messages, $occurrence) {
  if (my_module_is_my_occurrence_type($occurrence)) {
    $validation_error = my_module_validate_occurrence($occurrence);
    $is_valid = TRUE;
    if ($validation_error) {
      $is_valid = FALSE;
      $custom_error_messages[] = $validation_error;
    }
  }
}

/**
 * Add custom insertion types.
 *
 * @return array
 *   Associative array of insertion types structured in the way Drupal's Field
 *   API expects for the #options array of select fields.
 *
 * @see block_in_block_form_block_admin_configure_alter()
 *   This hook is declared inside the function.
 */
function hook_block_in_block_add_insertion_types() {
  // Add custom insertion types, using the module short name within the values
  // to make sure they are unique.
  $my_insertion_types = array(
    'my_module_insertion_type_1_value' => t('My first insertion type'),
    'my_module_insertion_type_2_value' => t('My other insertion type'),
  );
  return $my_insertion_types;
}

/**
 * Perform alterations to the list of insertion types.
 *
 * @param $insertion_types
 *   Associative array of insertion types structured in the way Drupal's Field
 *   API expects for the #options array of select fields.
 *
 * @see block_in_block_form_block_admin_configure_alter()
 *   This hook is declared inside the function.
 */
function hook_block_in_block_insertion_types_alter(&$insertion_types) {
  // Add alternative insertion type for inserting after the target string,
  // and make it easy to tell which is which.
  $insertion_types['my_module_after'] = t("After string (my module's)");
  $insertion_types[BLOCK_IN_BLOCK_AFTER_TARGET] = t("After string (original)");
}
