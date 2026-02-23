<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
  |--------------------------------------------------------------------------
  | Template List
  |--------------------------------------------------------------------------
  |
  | The $template['active_template'] setting lets you choose which template
  | group to make active.  By default there is only one group (the
  | "default" group).
  |
 */

$template = array(
  'active_template' => 'material_admin',

  // Admin: Material Admin 2.6
  'material_admin' => array(
    'template'       => '../theme_layouts/material_admin/main',
    'regions'        => array('title', 'content'),
    'parser'         => 'parser',
    'parser_method'  => 'parse',
    'parse_template' => TRUE
  ),

  // Admin: SB Admin Pro
  'sb_admin' => array(
    'template'       => '../theme_layouts/sb_admin/main',
    'regions'        => array('title', 'content'),
    'parser'         => 'parser',
    'parser_method'  => 'parse',
    'parse_template' => TRUE
  ),
);

/* End of file template.php */
/* Location: ./application/config/template.php */
