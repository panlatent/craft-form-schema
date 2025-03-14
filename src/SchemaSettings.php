<?php

namespace panlatent\craft\formschema;

trait SchemaSettings
{
    public function getSettingsHtml(): ?string
    {
        return (new Form())->getSettingsHtml($this);
    }
}