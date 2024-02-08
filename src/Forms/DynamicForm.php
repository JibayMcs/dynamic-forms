<?php

namespace JibayMcs\DynamicForms\Forms;

use Closure;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\Concerns;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use JibayMcs\DynamicForms\Concerns\HasRelationship;

class DynamicForm extends Field
{
    use Concerns\HasName;
    use HasRelationship;

    protected ?array $schema = null;

    protected string $view = 'dynamic-forms::dynamic-form';

    public static function make(string $name): static
    {
        $static = app(static::class, ['name' => $name]);
        $static->configure();

        return $static;
    }

    public function setUp(): void
    {
        $this->fillSchema($this->getName());
    }

    public function fillSchema(string|array $data = null)
    {
        if(is_array($data)) {
            $form_data = $data;
        }
        else if (is_string($data)) {
            if (file_exists($data)) {
                $form_data = json_decode(file_get_contents($data), true);
            } else {
                $form_data = null;
            }
        } else {
            $form_data = $this->getModel()->{$data};
        }

        if (!$form_data) {
            $this->schema = [];
        } else
            $this->schema = $this->constructForm($form_data);
    }

    public function getSchema(): ?array
    {
        return $this->schema;
    }


    public function constructForm($data): array
    {
        $schema = [];

        foreach ($data as $key => $property) {

            match ($key) {
                "Filament\Forms\Components\Grid" => $schema[] = $this->constructFromGrid($key, $property),
                "Filament\Forms\Components\Section" => $schema[] = $this->constructFromSection($key, $property),
                "Filament\Forms\Components\Fieldset" => $schema[] = $this->constructFromFieldset($key, $property),
                "Filament\Forms\Components\Tabs" => $schema[] = $this->constructFromTabs($key, $property),
                default => $schema[] = $this->constructField($key, $property),
            };

        }

        return $schema;
    }

    private function constructField($key, $property): Field|\Filament\Forms\Components\Component
    {
        $field = $property['field']::make($key);

        unset($property['field']);

        foreach ($property as $property_key => $value) {
            if (method_exists($field, $property_key)) {
                if (is_array($value)) {
                    $field = $this->mountFieldFromArray($field, $property_key, $value);
                } else {
                    $field = $this->mountField($field, $property_key, $value);
                }
            }
        }

        return $field;
    }

    private function constructFromGrid(int|string $key, $property): Grid
    {
        $columns = $property['columns'] ?? 2;
        $layout_fields = [];

        foreach ($property['schema'] as $children) {
            foreach ($children as $name => $properties) {
                $layout_fields[] = $this->constructField($name, $properties);
            }
        }

        return $key::make($columns)
            ->schema($layout_fields);
    }

    private function constructFromSection(int|string $key, $property): Section
    {
        return $this->constructSimpleLayout($key, $property);
    }

    private function constructFromFieldset(int|string $key, $property): Fieldset
    {
        return $this->constructSimpleLayout($key, $property);
    }

    private function constructFromTabs(int|string $key, $property): Tabs
    {
        $tabs_instance = $key::make($property['heading']);
        $tabs = [];

        foreach ($property['tabs'] as $tabs_list) {
            foreach ($tabs_list as $tab_key => $schema) {
                $tab = Tabs\Tab::make($tab_key);
                $tab_fields = [];

                foreach (collect($schema) as $tab_schema) {
                    foreach ($tab_schema as $name => $properties) {
                        $tab_fields[] = $this->constructField($name, $properties);
                    }
                }

                $tab->schema($tab_fields);

                $tabs[] = $tab;
            }

        }

        return $tabs_instance->tabs($tabs);
    }

    private function constructSimpleLayout(int|string $key, $property)
    {
        $layout_fields = [];

        foreach ($property['schema'] as $children) {
            foreach ($children as $name => $properties) {
                $layout_fields[] = $this->constructField($name, $properties);
            }
        }

        unset($property['schema']);

        $section = $key::make($property['heading']);

        unset($property['heading']);

        foreach ($property as $property_key => $value) {
            if (method_exists($section, $property_key)) {
                $section->{$property_key}($value);
            }
        }

        return $section->schema($layout_fields);
    }

    private function mountFieldFromArray(mixed $field, int|string $property_key, array $value): Field
    {
        foreach ($value as $prop_key => $prop_value) {

            switch ($property_key) {
                case 'visibleOn':
                    if (is_array($value)) {
                        $field->{$property_key}($value);
                    } else {
                        $field->{$property_key}([$prop_value]);
                    }
                    break;
                case 'afterStateUpdated':
                    break;
                default:
                    if (is_array($value)) {
                        $field->{$property_key}($prop_key = $prop_value);
                    } else {
                        $field->{$property_key}($prop_key = $prop_value);
                    }
                    break;
            }
        }

        return $field;
    }

    private function mountField(mixed $field, int|string $property_key, $value): Field
    {
        switch ($property_key) {
            case 'visibleOn':
                $field->{$property_key}([$value]);
                break;
            case 'afterStateUpdated':
                break;
                /*$field->afterStateUpdated(function(Set $set, Get $get, $state, $old) use ($value) {

                    $pattern = "/\((.*?)\)/";

                    if (preg_match($pattern, $value, $matches)) {
                        // $matches[1] contiendra la chaîne capturée entre les parenthèses
                        $variables = $matches[1]; // '$get, $set'

                        // Pour obtenir chaque variable comme élément d'un tableau
                        $variablesArray = explode(',', $variables);
                        $variablesArray = array_map('trim', $variablesArray); // Nettoie les espaces

                        // Affiche les variables
                        return $value($set, $get, $state, $old);
                    }

                    return $value($set, $get, $state, $old);
                });*/
                break;
            default:
                $field->{$property_key}($value);
                break;
        }

        return $field;
    }

}
