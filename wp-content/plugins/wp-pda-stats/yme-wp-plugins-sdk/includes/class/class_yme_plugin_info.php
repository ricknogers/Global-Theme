<?php
class Yme_Plugin_Info {
    public function __construct($plugin_name, $plugin_dir, $plugin_version) {
        $this->plugin_name = $plugin_name;
        $this->plugin_dir = $plugin_dir;
        $this->plugin_version = $plugin_version;
    }
}