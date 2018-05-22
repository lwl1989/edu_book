<?php

namespace App\Models;


trait ExtensionModelTrait
{
    //It's like key => function()
    protected $resultFormat = [
        'created_at'    =>  'datetimeOnlyDate',
        'updated_at'    =>  'datetimeOnlyDate'
    ];

    protected $enableFormat = true;

    public function enableFormat()
    {
        $this->enableFormat = true;
        return $this;
    }

    public function disableFormat()
    {
        $this->enableFormat = false;
        return $this;
    }

    public function getEnableFormat() : bool
    {
        return $this->enableFormat;
    }

    public function getFormat() : array
    {
        return $this->resultFormat;
    }
}