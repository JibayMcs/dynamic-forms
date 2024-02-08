<?php

namespace JibayMcs\DynamicForms\Forms;

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

            $field = $property['field']::make($key);

            unset($property['field']);

            foreach ($property as $property_key => $value) {
                if (method_exists($field, $property_key)) {
                    /*if($property_key == 'afterStateUpdate'){
                    }*/
                    if (is_array($value)) {

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
                                    dd($property_key, $value);
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
                    } else {

                        switch ($property_key) {
                            case 'visibleOn':
                                $field->{$property_key}([$value]);
                                break;
                            case 'afterStateUpdated':
                                break;
                                $field->afterStateUpdated(function (Set $set, Get $get, $state, $old) use ($value) {

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
                                });
                                break;
                            default:
                                $field->{$property_key}($value);
                                break;
                        }
                    }
                }
            }

            $schema[] = $field;
        }

        //        dd($schema[array_key_last($schema)]);

        return $schema;
    }
}
