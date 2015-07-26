<?php

namespace yiicod\base\models\behaviors;

/**
 * Class XssBehavior
 *
 * Only for all
 * 
 * Xss protected
 *
 * @package app\models\behaviors
 */
use HTMLPurifier;
use HTMLPurifier_Config;
use yii\base\Behavior;
use yii\db\ActiveRecord;

class XssBehavior extends Behavior
{

    /**
     *
     * @var type 
     */
    public $attributesExclude = array();

    /**
     *
     * @var type 
     */
    public $allowedFilter = array();

    /**
     *
     * @var type 
     */
    public $configFilter = array();

    /**
     *
     * @var type 
     */
    public $defFilter = array();

    /**
     * 
     * @return Array
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_VALIDATE => 'beforeValidate',
        ];
    }

    public function beforeValidate($event)
    {
        // EDIT: modify this to whatever you need.
        $allowedAttrs = array('id', 'class');
        $allowed = array(
            'img[src|alt|title|width|height|style|data-mce-src|data-mce-json]',
            'figure', 'figcaption', 'small[style]',
            'video[src|type|width|height|poster|preload|controls]', 'source[src|type]',
            'a[href|target]',
            'iframe[width|height|src|frameborder|allowfullscreen]',
            'strong', 'b', 'i', 'u', 'em', 'br', 'font',
            'h1[style]', 'h2[style]', 'h3[style]', 'h4[style]', 'h5[style]', 'h6[style]',
            'p[style]', 'div[style]', 'center', 'address[style]',
            'span[style]', 'pre[style]',
            'ul[style|class]', 'ol[style|class]', 'li[style|class]',
            'table[width|height|border|style]', 'th[width|height|border|style]',
            'tr[width|height|border|style]', 'td[width|height|border|style]',
            'hr[style|class]', 'section[style|class]', 'nav[style|class]', 'article[style|class]',
            'aside[style|class]', 'header[style|class]', 'footer[style|class]',
            'address', 'hgroup', 'figure', 'figcaption',
            'video[src|type|width|height|poster|preload|controls|loop|autoplay]',
            's', 'var', 'sub', 'sup', 'mark', 'wbr', 'ins', 'del', 'blockquote', 'q', '*[style|class|id|width|height|alt|title|target|src]'
        );
        foreach ($allowed as $key => $element) {
            foreach ($allowedAttrs as $attr) {
                if (strpos($element, $attr . '|') === false && strpos($element, '|' . $attr) === false) {
                    $allowed[$key] = $element = str_replace(']', '|' . $attr . ']', $element);
                }
            }
        }

        if (is_callable($this->allowedFilter)) {
            $allowed = call_user_func_array($this->allowedFilter, array('self' => $this, 'allowed' => $allowed));
        }

        $config = HTMLPurifier_Config::createDefault();
        $config->set('HTML.Doctype', 'HTML 4.01 Transitional');
        $config->set('CSS.AllowTricky', true);
        $config->set('Cache.SerializerPath', '/tmp');
        // Allow iframes from:
        // o YouTube.com
        // o Vimeo.com
        $config->set('HTML.SafeIframe', true);
        $config->set('URI.SafeIframeRegexp', '%^(https?:)?(http?:)?//(www.youtube.|player.vimeo.|maps.google.|www.slideshare.)%');
//        $config->set('URI.SafeIframeRegexp', '%^(http:|https:)?//(www.youtube(?:-nocookie)?.com/embed/|player.vimeo.com/video/)%');        
        $config->set('Attr.AllowedFrameTargets', array(
            '_blank',
            '_self',
            '_parent',
            '_top',
        ));
        $config->set('URI.AllowedSchemes', array(
            'http' => TRUE,
            'https' => TRUE,
            'mailto' => TRUE,
            'target' => TRUE,
            'ftp' => TRUE
        ));
        $config->set('Attr.EnableID', true);
        $config->set('HTML.Allowed', implode(',', $allowed));
        // Set some HTML5 properties
        if ($def = $config->getHTMLDefinition(true)) {
            // http://developers.whatwg.org/sections.html
            $def->addElement('section', 'Block', 'Flow', 'Common');
            $def->addElement('nav', 'Block', 'Flow', 'Common');
            $def->addElement('article', 'Block', 'Flow', 'Common');
            $def->addElement('aside', 'Block', 'Flow', 'Common');
            $def->addElement('header', 'Block', 'Flow', 'Common');
            $def->addElement('footer', 'Block', 'Flow', 'Common');
            // Content model actually excludes several tags, not modelled here
            $def->addElement('address', 'Block', 'Flow', 'Common');
            $def->addElement('hgroup', 'Block', 'Required: h1 | h2 | h3 | h4 | h5 | h6', 'Common');
            // http://developers.whatwg.org/grouping-content.html
            $def->addElement('figure', 'Block', 'Optional: (figcaption, Flow) | (Flow, figcaption) | Flow', 'Common');
            $def->addElement('figcaption', 'Inline', 'Flow', 'Common');
            // http://developers.whatwg.org/the-video-element.html#the-video-element
            $def->addElement('video', 'Block', 'Optional: (source, Flow) | (Flow, source) | Flow', 'Common', array(
                'src' => 'URI',
                'type' => 'Text',
                'width' => 'Length',
                'height' => 'Length',
                'poster' => 'URI',
                'preload' => 'Enum#auto,metadata,none',
                'controls' => 'Bool',
            ));
            $def->addElement('source', 'Block', 'Flow', 'Common', array(
                'src' => 'URI',
                'type' => 'Text',
            ));
            // http://developers.whatwg.org/text-level-semantics.html
            $def->addElement('s', 'Inline', 'Inline', 'Common');
            $def->addElement('var', 'Inline', 'Inline', 'Common');
            $def->addElement('sub', 'Inline', 'Inline', 'Common');
            $def->addElement('sup', 'Inline', 'Inline', 'Common');
            $def->addElement('mark', 'Inline', 'Inline', 'Common');
            $def->addElement('wbr', 'Inline', 'Empty', 'Core');
            // http://developers.whatwg.org/edits.html
            $def->addElement('ins', 'Block', 'Flow', 'Common', array('cite' => 'URI', 'datetime' => 'CDATA'));
            $def->addElement('del', 'Block', 'Flow', 'Common', array('cite' => 'URI', 'datetime' => 'CDATA'));
            // TinyMCE
            $def->addAttribute('img', 'data-mce-src', 'Text');
            $def->addAttribute('img', 'data-mce-json', 'Text');
            //video            
            $def->addAttribute('video', 'loop', 'Text');
            $def->addAttribute('video', 'autoplay', 'Text');
            // Others
            $def->addAttribute('iframe', 'allowfullscreen', 'Bool');
            $def->addAttribute('table', 'height', 'Text');
            $def->addAttribute('td', 'border', 'Text');
            $def->addAttribute('th', 'border', 'Text');
            $def->addAttribute('tr', 'width', 'Text');
            $def->addAttribute('tr', 'height', 'Text');
            $def->addAttribute('tr', 'border', 'Text');


            if (is_callable($this->defFilter)) {
                $def = call_user_func_array($this->defFilter, array('self' => $this, 'def' => $def));
            }
        }
        if (is_callable($this->configFilter)) {
            $config = call_user_func_array($this->configFilter, array('self' => $this, 'config' => $config));
        }
        $p = new HTMLPurifier($config);

        $attributes = array();

        foreach ($this->owner->getAttributes() as $key => $value) {
            if (!in_array($key, $this->attributesExclude)) {
                if (null !== $value) {
                    if (is_array($value)) {
                        $attributes[$key] = @unserialize($p->purify(serialize($value)));
                    } else {
                        $attributes[$key] = $p->purify($value);
                    }
                }
            }
        }

        $this->owner->setAttributes($attributes);
    }

}
