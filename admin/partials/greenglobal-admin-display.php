<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://greenglobal.vn
 * @since      1.0.0
 *
 * @package    Greenglobal
 * @subpackage Greenglobal/admin/partials
 */
register_setting( 'pluginPage', 'gg_settings' );
?>
<div class="wrap wrap-greenglobal">
  <h2 align="center">GG Functions</h2>
  <form action='options.php' method='post'>
    <table>
      <tbody>
        <tr>
          <td valign="top" width="50%">
            <?php
            settings_fields( 'wpFrontend' );
            do_settings_sections( 'wpFrontend' );
            submit_button();
            ?>
          </td>
          <td valign="top" width="50%">
            <?php
            settings_fields( 'wpTheme' );
            do_settings_sections( 'wpTheme' );
            submit_button();
            ?>
          </td>
        </tr>
      </tbody>
    </table>
    
  </form>
</div>
