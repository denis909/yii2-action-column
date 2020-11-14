<?php

namespace denis909\yii;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;

class ActionColumn extends \yii\grid\ActionColumn
{

    public $contentOptions = ['style' => 'width: 1%; white-space: nowrap;'];

    public $options = ['style' => 'width: 1%'];

    public $baseUrl;

    public $enableIcons = true;

    protected function initDefaultButton($name, $iconName, $additionalOptions = [])
    {
        if (!$this->enableIcons)
        {
            if (!isset($this->buttons[$name]) && strpos($this->template, '{' . $name . '}') !== false)
            {
                $this->buttons[$name] = function ($url, $model, $key) use ($name, $iconName, $additionalOptions)
                {
                    switch ($name) {
                        case 'view':
                            $title = Yii::t('yii', 'View');
                            break;
                        case 'update':
                            $title = Yii::t('yii', 'Update');
                            break;
                        case 'delete':
                            $title = Yii::t('yii', 'Delete');
                            break;
                        default:
                            $title = ucfirst($name);
                    }
                
                    $options = array_merge([
                        'title' => $title,
                        'aria-label' => $title,
                        'data-pjax' => '0',
                    ], $additionalOptions, $this->buttonOptions);
                
                    return Html::a($title, $url, $options);
                };
            }
        }

        return parent::initDefaultButton($name, $iconName, $additionalOptions);
    }
    
    public function createUrl($action, $model, $key, $index)
    {
        if (is_callable($this->urlCreator)) 
        {
            return call_user_func($this->urlCreator, $action, $model, $key, $index, $this);
        }

        $params = is_array($key) ? $key : ['id' => (string) $key];
       
        if ($this->baseUrl)
        {
            $params[0] = $this->baseUrl . '/' . $action;
        }
        else
        {
            $params[0] = $this->controller ? $this->controller . '/' . $action : $action;   
        }
        
        $params['backUrl'] = Url::current();
       
        return Url::toRoute($params); 
    }

}