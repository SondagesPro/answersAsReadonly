<?php
/**
 * Allow to set answers as readonly in survey
 *
 * @author Denis Chenu <denis@sondages.pro>
 * @copyright 2018 Denis Chenu <http://www.sondages.pro>
 * @license AGPL v3
 * @version 0.0.5
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 */
class answersAsReadonly extends PluginBase
{

  static protected $name = 'answersAsReadonly';
  static protected $description = 'Allow to set input as readonly in answer, warning : no PHP control is done.';

    /**
    * Add function to be used in beforeQuestionRender event and to attriubute
    */
    public function init()
    {
        $this->subscribe('beforeQuestionRender','setReadonly');
        $this->subscribe('newQuestionAttributes','addReadonlyAttribute');
    }

  /**
   * Search all input to set it as disabled and add css and js if needed
   */
  public function setReadonly()
  {
    $oEvent=$this->getEvent();
    $aAttributes=QuestionAttribute::model()->getQuestionAttributes($oEvent->get('qid'));
    if(isset($aAttributes['readonly']) && $aAttributes['readonly'] ) {
        $answer = $this->getEvent()->get("answers");
        $answer = str_replace("type=\"text\"","type=\"text\" readonly ",$answer);
        $answer = str_replace("type='text'","type='text' readonly ",$answer);
        $answer = str_replace("<textarea","<textarea readonly ",$answer);
        $this->getEvent()->set("answers",$answer);
        $this->getEvent()->set("class",$this->getEvent()->get("class")." answersasreadonly-attribute");
        $this->answersAsReadonlyAddScript();
    }
  }

    /**
    * Adding the attribute in admin part
    */
    public function addReadonlyAttribute()
    {
        $scriptAttributes = array(
            'readonly' => array(
                'types'     => '15ABCDEFGHIKLMNOPQSTUWYZ!:;|', /* all question types except equation and text display, remove ranking because untested */
                'category'  => gT('Display'),
                'sortorder' => 101,
                'inputtype' => 'switch',
                'caption'   => $this->gT('Show as readonly'),
                'help'   => $this->gT('Add readonly attribute to all input inside answer part.'),
                'default'   => 0,
            ),
        );
        $this->getEvent()->append('questionAttributes', $scriptAttributes);
    }

    /**
     * Create package if not exist, register it
     */
    public function answersAsReadonlyAddScript()
    {
        /* Quit if is done */
        if(array_key_exists(get_class($this),Yii::app()->getClientScript()->packages)) {
            return;
        }
        /* Add package if not exist (allow to use another one in config) */
        if(!Yii::app()->clientScript->hasPackage(get_class($this))) {
            Yii::setPathOfAlias(get_class($this),dirname(__FILE__));
            Yii::app()->clientScript->addPackage(get_class($this), array(
                'basePath'    => get_class($this).'.assets',
                'css'         => array(get_class($this).'.css'),
                'js'          => array(get_class($this).'.js'),
                'depends'      =>array( 'limesurvey-public','template-core'),
            ));
        }
        /* Registering the package */
        Yii::app()->getClientScript()->registerPackage(get_class($this));
    }
}
