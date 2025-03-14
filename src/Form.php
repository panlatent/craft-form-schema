<?php

namespace panlatent\craft\formschema;

use Craft;
use Panlatent\FormSchema\Forms;

class Form extends \Panlatent\FormSchema\Form
{
    public function getSettingsHtml(object $component): ?string
    {
        $schema = $this->getSchema($component);
        $html = '';
        foreach ($schema as $field) {
           $config = $this->getFieldConfig($field, $component);
           $html .= $this->render($config);
        }
        return $html;
    }

    private function getFieldConfig(Forms\Field $field, object $component): array
    {
        $config = ['id' => $field->name, 'name' => $field->name, 'label' => $field->label, 'required' => $field->required];
        return match (true) {
            $field instanceof Forms\TextInput => $config + ['type' => 'text', 'value' => $component->{$field->name}],
            $field instanceof Forms\Select => $config + ['options' => $field->getOptions(), 'type' => 'select', 'value' => $component->{$field->name}],
            $field instanceof Forms\KeyValue => $config + ['rows' => $component->{$field->name}, 'type' => 'table'],
            default => $config
        };
    }

    private function render(array $config): string
    {
        $view = Craft::$app->getView();
        return $view->renderString($this->getTemplateString(), ['config' => $config], templateMode: $view->getTemplateMode());
    }

    private function getTemplateString(): string
    {
        static $template = null;
        if ($template === null) {
            $template = file_get_contents(__DIR__ . '/templates/field.twig');
        }
        return $template;
    }
}