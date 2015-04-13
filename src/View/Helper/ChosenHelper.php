<?php

/**
 * This file is part of the Chosen CakePHP Plugin.
 *
 * Copyright (c) Paul Redmond - https://github.com/paulredmond
 *
 * @link https://github.com/paulredmond/chosen-cakephp
 * @license http://paulredmond.mit-license.org/ The MIT License
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Chosen Helper File
 *
 * @package chosen
 * @subpackage chosen.views.helpers
 */

namespace Chosen\View\Helper;

use Cake\View\Helper;
use Cake\View\View;
use Cake\Core\Configure;
use Cake\Event\Event;

class ChosenHelper extends Helper
{
    public $helpers = ['Html', 'Form'];

    /**
     * Default configuration options.
     *
     * Settings configured Configure class, ie. `Configure::write('Chosen.asset_base', '/path');`
     * take precedence over settings configured through Controller::$helpers property.
     */
    public $_defaultConfig = [
        'framework' => 'jquery',
        'class' => 'chosen-select',
        'asset_base' => '/chosen',
    ];

    /**
     * If a chosen select element was called, load up the scripts.
     *
     * @var Boolean
     */
    private $load = false;

    /**
     * If the scripts were loaded.
     *
     * @var Boolean
     */
    private $loaded = false;

    /**
     * Determine if debug is disabled/enabled
     *
     * @var Boolean
     */
    private $debug = false;

    public function __construct(View $view, $config = [])
    {
        parent::__construct($view, $config);

        // @todo - this is merged by Helper::__construct() in 2.3.
        $this->settings = $this->config();
        
        $this->debug = Configure::read('debug') ? true : false;

        if (!$this->isSupportedFramework($fw = $this->getSetting('framework'))) {
            throw new NotConfiguredException(['framework' => $fw]);
        }
    }

    public function getSettings()
    {
        return $this->settings;
    }

    public function getSetting($setting)
    {
        if (isset($this->settings[$setting])) {
            return $this->settings[$setting];
        }

        return null;
    }

    public function getDebug()
    {
        return (boolean) $this->debug;
    }

    public function getLoadStatus()
    {
        return (bool) $this->load;
    }

    /**
     * Chosen select element.
     */
    public function select($name, $options = [], $attributes = [])
    {
        if (false === $this->load) {
            $this->load = true;
        }

        $class = $this->getSetting('class');

        // Use these locally to do some checking...still pass attributes to FormHelper.
        $multiple = isset($attributes['multiple']) ? $attributes['multiple'] : false;
        $deselect = isset($attributes['deselect']) ? $attributes['deselect'] : false;

        // Chosen only supports deselect on single selects.
        // @todo write a test and configure
        if ($deselect === true && $multiple === false) {
            $class .= '-deselect';
            unset($attributes['deselect']);
        }

        if (isset($attributes['class']) === false) {
            $attributes['class'] = $class;
        }
        else if (strstr($attributes['class'], $class) === false) {
            $attributes['class'] .= " {$class}";
        }

        return $this->Form->select($name, $options, $attributes);
    }

    public function afterRender(Event $event, $viewFile)
    {
        if (false === $this->load) {
            return;
        }

        $this->loadScripts();
    }

    public function loadScripts()
    {
        if ($this->loaded) {
            return;
        }

        $this->loaded = true;
        
        $base = $this->getsetting('asset_base');

        switch ($this->getSetting('framework')) {
            case 'prototype':
                $elm = 'prototype-script';
                $script = 'chosen.proto.%s';
            break;

            case 'jquery':
            default:
                $elm = 'jquery-script';
                $script = 'chosen.jquery.%s';
            break;
        }

        // 3rd party assets.
        $script = sprintf($script, $this->debug === true ? 'js' : 'min.js');
        $this->Html->script('Chosen.'.$script, ['inline' => false, 'block' => true]);
        $this->Html->css('Chosen.chosen.css', ['inline' => false, 'block' => true]);

        // Add the script.
        $this->_View->append('script', $this->getElement($elm));
    }

    /**
     * Gets the Plugin's element file based on JS framework being used.
     *
     * @param $element string Name of the plugin element to use.
     * @return string rendered javascript block based on the JS framework element.
     */
    protected function getElement($element)
    {
        $class = $this->getSetting('class');

        return $this->_View->Element('Chosen.' . $element, ['class' => $class]);
    }

    /**
     * Test if a JS framework is supported by this helper.
     *
     * @param $val The 'framework' setting must use a supported framework.
     *
     * @return bool
     */
    public function isSupportedFramework($val)
    {
        return in_array($val, ['jquery', 'prototype']);
    }
}
