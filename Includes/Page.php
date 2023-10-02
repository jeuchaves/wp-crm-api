<?php

namespace WPCRM\Includes;

defined('ABSPATH') || exit;

class Page
{
    protected function __construct() { }

    public static function WPCRM_settings_page() {
        ?>
        <div class="wrap">
            <h2>Configurações de Integração CRM</h2>
            <form method="post" action="options.php">
                <?php settings_fields('meu_plugin_opcoes'); ?>
                <?php do_settings_sections('meu_plugin_integracao_crm'); ?>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row">Token da Aplicação</th>
                        <td><input type="text" name="token_aplicacao" value="<?php echo esc_attr(get_option('token_aplicacao')); ?>" /></td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }

    public static function add_menu_page($title, $menu_name, $menu_slug)
    {
        add_menu_page($title, $menu_name, 'manage_options', $menu_slug, array(__CLASS__, 'WPCRM_settings_page'));
    }

    public static function remove_menu_page()
    {
        
    }
}
