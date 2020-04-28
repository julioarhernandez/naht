<?php
/**
 * @file
 * The primary PHP file for this theme.
 */
function naht_preprocess_page(&$variables) {
	if (isset($variables['node']->type)) {
		// var_dump($variables['node']);
		if (isset($variables['node']->field_header_image[LANGUAGE_NONE][0])){
			$img = $variables['node']->field_header_image[LANGUAGE_NONE][0];
			$variables['hero']['path'] = $img['filename'];
		}elseif (isset($variables['node']->field_certification_image[LANGUAGE_NONE][0])){
				$img = $variables['node']->field_certification_image[LANGUAGE_NONE][0];
				$variables['hero']['path'] = $img['filename'];
		}else{
			if (!$variables['is_front']) {
				$variables['hero']['path'] = 'nurse-bg.jpg';
			  }
		}
		
	 // If the content type's machine name is "my_machine_name" the file
	 // name will be "page--my-machine-name.tpl.php".
	 $variables['theme_hook_suggestions'][] = 'page__' . $variables['node']->type;
	}
  }
  
  function naht_preprocess_node(&$variables) {
	$node = $variables['node'];
	if ($node->nid == 5 && node_is_page($node)){
		$variables['theme_hook_suggestions'][] = 'node__5_form';
	}
  }