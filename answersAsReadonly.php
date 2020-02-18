<?php
/**
 * Allow to set answers as readonly in survey
 *
 * @author Denis Chenu <denis@sondages.pro>
 * @copyright 2018-2020 Denis Chenu <http://www.sondages.pro>
 * @license AGPL v3
 * @version 0.2.1
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
        /* For upload file (and maybe other after */
        $this->subscribe('getPluginTwigPath');
    }

    /**
     * Add some views for this and other plugin
     */
    public function getPluginTwigPath()
    {
        $viewPath = dirname(__FILE__)."/views";
        $this->getEvent()->append('add', array($viewPath));
    }

  /**
   * Search all input to set it as disabled and add css and js if needed
   */
  public function setReadonly()
  {
    $oEvent=$this->getEvent();
    //~ $this->getEvent()->set("text",$this->getEvent()->get("text")." setReadOnly");
    $aAttributes=QuestionAttribute::model()->getQuestionAttributes($oEvent->get('qid'));
    if(isset($aAttributes['readonly']) && $aAttributes['readonly'] ) {
        $currentReadonly = trim(LimeExpressionManager::ProcessStepString($aAttributes['readonly']));
        if($currentReadonly) { // then not "", 0, "0" … 
            $answer = $this->getEvent()->get("answers");
            $answer = str_replace("type=\"text\"","type=\"text\" readonly ",$answer);
            $answer = str_replace("type='text'","type='text' readonly ",$answer);
            $answer = str_replace("<textarea","<textarea readonly ",$answer);
            /* Remove script for upload */
            if($oEvent->get('type') == "|" && version_compare(Yii::app()->getConfig('versionnumber'),"3.10.0",">=")) {) {
                $answer = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $answer);
                $sgqa =  $oEvent->get('surveyId')."X".$oEvent->get('gid')."X".$oEvent->get('qid');
                $currentValue = $_SESSION['survey_'.$oEvent->get('surveyId')][$sgqa];
                if(!empty($currentValue)) {
                    $aFiles = @json_decode($currentValue,true);
                }
                if(empty($aFiles)) {
                    $aFiles = array();
                }
                foreach($aFiles as $key => $aFile) {
                    $sFileLink = null;
                    $sFileDir = null;
                    if( substr($aFile['filename'], 0, 3) == 'fu_') {
                        $sFileDir = Yii::app()->getConfig("uploaddir")."/surveys/".$oEvent->get('surveyId')."/files/";
                    }
                    if($sFileDir) {
                        if (is_file($sFileDir.$aFile['filename'])) {
                            $sFileLink = Yii::app()->createUrl("uploader/run",array('filegetcontents'=>$aFile['filename']));
                        }
                    }
                    $aFiles[$key]['link'] = $sFileLink;
                    $aFiles[$key]['name'] = CHtml::encode($aFile['name']);
                    $aFiles[$key]['comment'] = CHtml::encode($aFile['comment']);
                    $aFiles[$key]['title'] = CHtml::encode($aFile['title']);
                }
                $aQuestionsAttributes = QuestionAttribute::model()->getQuestionAttributes($oEvent->get('qid'));
                $aData = array(
                    'value' => $currentValue,
                    'aAttributes' => $aQuestionsAttributes,
                    'aSurveyInfo'=> getSurveyInfo($oEvent->get('surveyId'), App()->getLanguage()),
                    'aFiles' => $aFiles,
                );
                $htmlExtra = Yii::app()->twigRenderer->renderPartial('./survey/questions/answer/file_upload/answer_readonly_extra.twig', $aData);
                $answer .= $htmlExtra;
            }
            $oEvent->set("answers",$answer);
            $oEvent->set("class",$this->getEvent()->get("class")." answersasreadonly-attribute");
            $this->answersAsReadonlyAddScript();
        }
    }
  }

    /**
    * Adding the attribute in admin part
    */
    public function addReadonlyAttribute()
    {
        $scriptAttributes = array(
            'readonly' => array(
                'name'      => 'readonly',
                'types'     => '15ABCDEFGHIKLMNOPQSTUWYZ!:;|', /* all question types except equation and text display, remove ranking because untested */
                'category'  => gT('Display'),
                'sortorder' => 101,
                'inputtype' => 'text',
                'caption'   => $this->gT('Show as readonly'),
                'help'   => $this->gT('Add readonly attribute to all input inside answer part. You can use Expression, this expression was tested before showning the question, value was trimmed before comparing.'),
                'default'   => "",
                'expression' => 1,
            ),
        );
        if(version_compare(Yii::app()->getConfig('versionnumber'),"3.10.0","<")) {
            $scriptAttributes['readonly']['types'] = '15ABCDEFGHIKLMNOPQSTUWYZ!:;'; // Upload question type need 3.10 and up version
        }
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
                'depends'      =>array('limesurvey-public'),
            ));
        }
        /* Registering the package */
        Yii::app()->getClientScript()->registerPackage(get_class($this));
    }
}
