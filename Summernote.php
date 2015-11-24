<?php

namespace Zelenin\yii\widgets\Summernote;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\InputWidget;

class Summernote extends InputWidget
{
    /** @var array */
    private $defaultOptions = ['class' => 'form-control'];

    /** @var array */
    private $defaultClientOptions = [
        'height' => 200,
        'codemirror' => [
            'theme' => 'monokai'
        ]
    ];

    /** @var array */
    public $options = [];

    /** @var array */
    public $clientOptions = [];

    /** @var array */
    public $plugins = [];

    /** @var boolean */
    public $useTextarea = true;

    /** @var boolean */
    public $loadSummernote = true;

    /** @var string */
    public $saveUrl = null;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->options = array_merge($this->defaultOptions, $this->options);
        $this->clientOptions = array_merge($this->defaultClientOptions, $this->clientOptions);
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $this->registerAssets();
        
        /* @var $this yii\web\View */
        $view = $this->getView();
        

        if ($this->hasModel()) {
            $tag = Html::activeTextarea($this->model, $this->attribute, $this->options);
        } else {
            $tag = $this->useTextarea ? Html::textarea($this->name, $this->value, $this->options) : Html::tag('div', $this->value, $this->options);
        }
        echo $tag;

        $clientOptions = empty($this->clientOptions) ? null : Json::encode($this->clientOptions);

        if ($this->loadSummernote) {
            $view->registerJs('jQuery( "#' . $this->options['id'] . '" ).summernote(' . $clientOptions . ');');
        } else {
            $jsFuncName = ucfirst(str_replace(['-', '_', ' '], '', $this->options['id']));
$scriptEdit = <<< JS
    var editSummerNote{$jsFuncName} = function(){{
        $('#{$this->options['id']}').summernote({$clientOptions});
    };
JS;
$scriptSave = <<< JS
    var editSummerNote{$jsFuncName} = function(){{
        $('#{$this->options['id']}').summernote({$clientOptions});
    };
JS;
            $view->registerJs($scriptEdit, \yii\web\View::POS_END);

            $ajaxSave = isset($this->saveUrl) ? '$.ajax({'
                . 'url: "' . $this->saveUrl . '",'
                . 'data: {id: "", data: $("#' . $this->options['id'] . '").code()},'
                . 'success: function(data){'
                . '$("#' . $this->options['id'] . '" ).summernote().destroy()'
                . '},'
                . 'error: function(xhr){'
                . 'alert("Error: " + xhr.status + " " + xhr.statusText);'
                . '}'
                . '});' : '';

$scriptEdit = <<< JS
    var saveSummerNote{$jsFuncName} = function(){{
        {$ajaxSave}
        $('#{$this->options['id']}').summernote().destroy();
    };
JS;


            $view->registerJs($scriptSave, \yii\web\View::POS_END);
        }
    }

    private function registerAssets()
    {
        $view = $this->getView();

        if (ArrayHelper::getValue($this->clientOptions, 'codemirror')) {
            CodemirrorAsset::register($view);
        }

        SummernoteAsset::register($view);

        if ($language = ArrayHelper::getValue($this->clientOptions, 'lang', null)) {
            SummernoteLanguageAsset::register($view)->language = $language;
        }

        if (!empty($this->plugins) && is_array($this->plugins)) {
            SummernotePluginAsset::register($view)->plugins = $this->plugins;
        }
    }

}
