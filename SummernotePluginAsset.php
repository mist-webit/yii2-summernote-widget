<?php

namespace Zelenin\yii\widgets\Summernote;

use yii\web\AssetBundle;

class SummernotePluginAsset extends AssetBundle
{
    /** @var array */
    public $plugins = [];
    /** @var string */
    public $sourcePath = '@bower/summernote/plugin';
    /** @var array */
    public $depends = [
        'Zelenin\yii\widgets\Summernote\SummernoteAsset'
    ];

    /**
     * @inheritdoc
     */
    public function registerAssetFiles($view)
    {
        foreach ($this->plugins as $plugin) {
            $this->js[] = 'summernote-ext-' . $plugin . '.js';
        }
        parent::registerAssetFiles($view);
    }
}
