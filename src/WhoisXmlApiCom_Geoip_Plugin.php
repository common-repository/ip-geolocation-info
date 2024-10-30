<?php

require_once plugin_dir_path( __FILE__ ) . '/WhoisXmlApiCom_Geoip_Settings.php';

if (! class_exists('WhoisXmlApiCom_Geoip_Plugin')) {
    class WhoisXmlApiCom_Geoip_Plugin
    {
        const TARGET_KEY = 'links_target';
        const TARGET_BLANK = '_blank';
        const TARGET_SELF = '_self';


        protected $target = true;
        protected $unique = false;
        protected $comment = false;

        protected $processedIps = array();

        public function __construct()
        {
            $this->init_options();
            add_action('the_content', array($this, 'update_post_content_ipv4'), 10);
            add_action('the_content', array($this, 'update_post_content_ipv6'), 10);
            if ($this->comment) {
                add_action('comment_text', array($this, 'update_comment_content_ipv4'), 10);
                add_action('comment_text', array($this, 'update_comment_content_ipv6'), 10);
            }
        }

        public function update_comment_content_ipv4($comment_text)
        {
            return $this->update_content_ipv4($comment_text);
        }

        public function update_comment_content_ipv6($comment_text)
        {
            return $this->update_content_ipv6($comment_text);
        }

        public function update_post_content_ipv4($post_content)
        {
            return $this->update_content_ipv4($post_content);
        }

        public function update_post_content_ipv6($post_content)
        {
            return $this->update_content_ipv6($post_content);
        }

        protected function update_content_ipv4($content)
        {
            $line = preg_replace_callback(
                '/(^|>|\s|\(|\{)+((([0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])\.){3})([0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])((<|\W|$)?(?![0-9]))/',
                array($this, 'ipv4_handler'),
                $content
            );

            return $line;
        }

        protected function update_content_ipv6($content)
        {
            $line = preg_replace_callback(
                '/(^|>|\s)+((([[0-9abcdfe]{0,4})?:){1,7}(:|[0-9abcdef]{0,4}))(($|[^:0-9a-zA-Z])?(?![0-9abcdfe]))/',
                array($this, 'ipv6_handler'),
                $content
            );

            return $line;
        }

        public function ipv4_handler($matches)
        {
            $line = preg_replace_callback(
                '/((\d+\.){3}\d+)/',
                array($this, 'wrap_ip'),
                $matches[0]
            );

            return $line;
        }

        public function ipv6_handler($matches)
        {
            $line = preg_replace_callback(
                '/[:0-9abcdef]/',
                array($this, 'wrap_ip'),
                $matches[0]
            );

            return $line;
        }

        public function wrap_ip($matches)
        {
            $ip = $matches[0];

            $targetValue = $this->target ? static::TARGET_BLANK : static::TARGET_SELF;

            if ($this->unique) {
                if (isset($this->processedIps[$ip])) {
                    return $ip;
                }
                $this->processedIps[$ip] = true;
            }

            return '<span class="whoisxmlapicom-geoip-element" data-loaded="false" data-target="' . $targetValue . '">'
                . $ip
                . '</span>';
        }


        protected function init_options()
        {
            $options = get_option(WhoisXmlApiCom_Geoip_Settings::OPTIONS_NAME);

            if (isset($options['links_target'])) {
                $this->target = boolval($options['links_target']);
            }
            if (isset($options['unique'])) {
                $this->unique = boolval($options['unique']);
            }
            if (isset($options['comment'])) {
                $this->comment = boolval($options['comment']);
            }
        }

    }
}