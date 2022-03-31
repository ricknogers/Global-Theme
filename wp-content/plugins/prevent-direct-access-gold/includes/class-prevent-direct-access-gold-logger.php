<?php 
    
if (! defined('ABSPATH')) {
    exit;
}

class PDA_Gold_Logger_V3
{
    public function getRemoteLogger($nameSettings)
    {
        $settings = get_option(PDA_v3_Constants::OPTION_NAME);
        if ($settings) {
            $options = (!$settings) ? array() : unserialize($settings);
            $pdav3_Settings = array_key_exists($nameSettings, $options) ? $options[$nameSettings] : false;
            if ($pdav3_Settings === "true") {
                return true;
            } else {
                return false;
            }
        }
        return false;
    }

    public function remote_log($message, $force = false)
    {
        $remote_log = $this->getRemoteLogger(PDA_v3_Constants::REMOTE_LOG);
        $pda_license_key = get_option(PDA_v3_Constants::LICENSE_KEY);

        if ($force || ($remote_log && !empty($pda_license_key) && !is_null($pda_license_key))) {
            $configs = include plugin_dir_path(dirname(__FILE__)) . 'includes/class-prevent-direct-access-gold-configs.php';
            $serviceUrl = $configs->pda_lg_api;
            $bodyInput = array(
                "key" => $pda_license_key,
                "message" => $message
            );
            $args = array(
                'body' => json_encode($bodyInput),
                'timeout' => '100',
                'redirection' => '5',
                'httpversion' => '1.0',
                'blocking' => true,
                'headers' => array(
                    'x-api-key' => $configs->lc_key,
                    'Content-Type' => 'application/json'
                ),
                'cookies' => array()
            );
            $response = wp_remote_post($serviceUrl, $args);
        }
    }
}
