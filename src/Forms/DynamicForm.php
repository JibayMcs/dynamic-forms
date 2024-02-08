<?php

namespace JibayMcs\DynamicForms\Forms;

use Filament\Forms\Components\Field;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;

class DynamicForm extends Component
{
    public static function make(Model|string $model, ?string $column = null): array
    {
        if (is_string($model)) {
            $form_data = json_decode(file_get_contents($model), true);
        } else {
            $form_data = $model->{$column};
        }

        return static::constructForm($form_data);
    }

    public static function constructForm($data): array
    {
        $schema = [];

        foreach ($data as $key => $property) {

            match ($key) {
                "Filament\Forms\Components\Grid" => $schema[] = self::constructFromGrid($key, $property),
                "Filament\Forms\Components\Section" => $schema[] = self::constructFromSection($key, $property),
                "Filament\Forms\Components\Fieldset" => $schema[] = self::constructFromFieldset($key, $property),
                "Filament\Forms\Components\Tabs" => $schema[] = self::constructFromTabs($key, $property),
                default => $schema[] = self::constructField($key, $property),
            };

        }

        return $schema;
    }

    private static function constructField($key, $property): Field|\Filament\Forms\Components\Component
    {
        $field = $property['field']::make($key);

        unset($property['field']);

        foreach ($property as $property_key => $value) {
            if (method_exists($field, $property_key)) {
                if (is_array($value)) {
                    $field = self::mountFieldFromArray($field, $property_key, $value);
                } else {
                    $field = self::mountField($field, $property_key, $value);
                }
            }
        }

        return $field;
    }

    private static function constructFromGrid(int|string $key, $property): Grid
    {
        $columns = $property['columns'] ?? 2;
        $layout_fields = [];

        foreach ($property['schema'] as $children) {
            foreach ($children as $name => $properties) {
                $layout_fields[] = self::constructField($name, $properties);
            }
        }

        return $key::make($columns)
            ->schema($layout_fields);
    }

    private static function constructFromSection(int|string $key, $property): Section
    {
        return self::constructSimpleLayout($key, $property);
    }

    private static function constructFromFieldset(int|string $key, $property): Fieldset
    {
        return self::constructSimpleLayout($key, $property);
    }

    private static function constructFromTabs(int|string $key, $property): Tabs
    {
        $tabs_instance = $key::make($property['heading']);
        $tabs = [];

        foreach ($property['tabs'] as $tabs_list) {
            foreach ($tabs_list as $tab_key => $schema) {
                $tab = Tabs\Tab::make($tab_key);
                $tab_fields = [];

                foreach (collect($schema) as $tab_schema) {
                    foreach ($tab_schema as $name => $properties) {
                        $tab_fields[] = self::constructField($name, $properties);
                    }
                }

                $tab->schema($tab_fields);

                $tabs[] = $tab;
            }

        }

        return $tabs_instance->tabs($tabs);
    }

    private static function constructSimpleLayout(int|string $key, $property)
    {
        $layout_fields = [];

        foreach ($property['schema'] as $children) {
            foreach ($children as $name => $properties) {
                $layout_fields[] = self::constructField($name, $properties);
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

    private static function mountFieldFromArray(mixed $field, int|string $property_key, array $value): Field
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

    private static function mountField(mixed $field, int|string $property_key, $value): Field
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
