<?php 
function gg_mce_buttons($buttons){
  $options = get_option('gg_settings');  
  if(isset($options['gg_mce_editor']) && !empty($options['gg_mce_editor'])){
    $mce_plugins = explode(',',$options['gg_mce_editor']);
    if(!empty($mce_plugins)){
      foreach ($mce_plugins as $item) {
        array_push($buttons,trim($item));
      }
      return $buttons;
    }
  }
  return $buttons;
}

function get_all_plugins() {
  return array(
    'advlist',
    'anchor',
    'code',
    'contextmenu',
    'emoticons',
    'importcss',
    'insertdatetime',
    'link',
    'nonbreaking',
    'print',
    'searchreplace',
    'table',
    'visualblocks',
    'visualchars',
    'wptadv',
  );
}

function mce_external_plugins( $mce_plugins ) {
  $plugins = array();
  $plugins = get_all_plugins();

  $plugin_url = plugins_url( 'mce/', __FILE__ );
  $mce_plugins = (array) $mce_plugins;
  $suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
  
  foreach ( $plugins as $plugin ) {
    $mce_plugins["$plugin"] = $plugin_url . $plugin . "/plugin{$suffix}.js";
  }
  return $mce_plugins;
}

function mce_options( $init, $editor_id = '' ) {
  $plugins = get_all_plugins();
  $options = get_option('gg_settings');
  $mce_plugins = explode(',',$options['gg_mce_editor']);
  if(!empty($mce_plugins)){
    foreach ($mce_plugins as $item) {
      array_push($plugins,trim($item));
    }
  }
  if ( in_array( 'table',$plugins, true ) ) {
    $init['table_toolbar'] = false;

    // Remove default table styles and attributes. They should be set by the theme.
    // Note that if the table cells are resized by dragging, an inline style with the widths will still be added.
    $init['table_default_attributes'] = '{}';
    $init['table_default_styles'] = '{}';
  }
  if ( in_array( 'fontsizeselect', $plugins, true ) ) {
    $init['fontsize_formats'] = "9px 10px 12px 13px 14px 16px 18px 21px 24px 28px 32px 36px";
  }
  return $init;
}
// mce_external_plugins();
add_filter( 'mce_external_plugins','mce_external_plugins', 999 );
add_filter( 'tiny_mce_before_init','mce_options', 10, 2 );
add_filter( "mce_buttons", "gg_mce_buttons");