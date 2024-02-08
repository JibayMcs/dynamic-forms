# A filament plugin to create dynamic Form Schema from json representation

[![Latest Version on Packagist](https://img.shields.io/packagist/v/jibaymcs/dynamic-forms.svg?style=flat-square)](https://packagist.org/packages/jibaymcs/dynamic-forms)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/jibaymcs/dynamic-forms/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/jibaymcs/dynamic-forms/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/jibaymcs/dynamic-forms/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/jibaymcs/dynamic-forms/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/jibaymcs/dynamic-forms.svg?style=flat-square)](https://packagist.org/packages/jibaymcs/dynamic-forms)



This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require jibaymcs/dynamic-forms
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="dynamic-forms-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="dynamic-forms-config"
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="dynamic-forms-views"
```

This is the contents of the published config file:

```php
return [
];
```

## Load Form from JSON ! <sup><u><small>(Not ready for production)</small></u></sup>

Mainly used to load a form from a JSON file, but also from a database model or relationship.

The purpose of this plugin is to create forms with dynamic fields from a JSON file.

It is not designed to directly modify the values of a `Resource`, but rather for
a [custom `Page`](https://filamentphp.com/docs/3.x/panels/resources/custom-pages), implementing a form (*
*_[Adding a form to a Livewire component](https://filamentphp.com/docs/3.x/forms/adding-a-form-to-a-livewire-component)_
**).

## Usage

Do whatever you want with your JSON!

`TextInput`, `Select`, `RichEditor`, `Grid`, `Section`, `Tabs`, almost anything works!

But you'll find the latest list of supported elements below.

> [!WARNING]
> **Live alert !**
> Yep reactivity is a great thing with Livewire !
> But for security reasons, nothing about state updates can be done in JSON
> `afterStateUpdate` is currently not supported.
>
> But I'm working on callable method from json using a particulation PHP "Controller" for this `DynamicForm`

### Supported Fields/Layouts

#### Fields
| Field                                                                              |      Supported ?      |
|------------------------------------------------------------------------------------|:---------------------:|
| [Text input](https://filamentphp.com/docs/3.x/forms/fields/text-input)             |           ✅           |
| [Select](https://filamentphp.com/docs/3.x/forms/fields/select)                     |           ✅           |
| [Toggle](https://filamentphp.com/docs/3.x/forms/fields/toggle)                     |           ✅           |
| [Checkbox](https://filamentphp.com/docs/3.x/forms/fields/checkbox)                 |    **Not tested**     |
| [Radio](https://filamentphp.com/docs/3.x/forms/fields/radio)                       |    **Not tested**     |
| [Date-time picker](https://filamentphp.com/docs/3.x/forms/fields/date-time-picker) |    **Not tested**     |
| [File upload](https://filamentphp.com/docs/3.x/forms/fields/file-upload)           |    **Not tested**     |
| [Rich editor](https://filamentphp.com/docs/3.x/forms/fields/rich-editor)           |           ✅           |
| [Markdown editor](https://filamentphp.com/docs/3.x/forms/fields/markdown-editor)   |           ✅           |
| [Repeater](https://filamentphp.com/docs/3.x/forms/fields/repeater)                 |    **Not tested**     |
| [Builder](https://filamentphp.com/docs/3.x/forms/fields/builder)                   |    **Not tested**     |
| [Tags input](https://filamentphp.com/docs/3.x/forms/fields/tags-input)             |    **Not tested**     |
| [Textarea](https://filamentphp.com/docs/3.x/forms/fields/textarea)                 |           ✅           |
| [Key-value](https://filamentphp.com/docs/3.x/forms/fields/key-value)               |    **Not tested**     |
| [Color picker](https://filamentphp.com/docs/3.x/forms/fields/color-picker)         |    **Not tested**     |
| [Toggle buttons](https://filamentphp.com/docs/3.x/forms/fields/toggle-buttons)     |    **Not tested**     |
| [Hidden](https://filamentphp.com/docs/3.x/forms/fields/hidden)                     |           ✅           |
| [Custom fields](https://filamentphp.com/docs/3.x/forms/fields/custom)              |  **Not implemented**  |

#### Layouts
| Layout         |      Supported ?      |
|----------------|:---------------------:|
| [Grid](https://filamentphp.com/docs/3.x/forms/layout/grid)           |           ✅           |
| [Fieldset](https://filamentphp.com/docs/3.x/forms/layout/fieldset)       |           ✅           |
| [Tabs](https://filamentphp.com/docs/3.x/forms/layout/tabs)           |           ✅           |
| [Wizard](https://filamentphp.com/docs/3.x/forms/layout/wizard)         |  **Not implemented**  |
| [Section](https://filamentphp.com/docs/3.x/forms/layout/section)        |           ✅           |
| [Split](https://filamentphp.com/docs/3.x/forms/layout/split)          |  **Not implemented**  |
| [Custom layouts](https://filamentphp.com/docs/3.x/forms/layout/custom) |  **Not implemented**  |
| [Placeholder](https://filamentphp.com/docs/3.x/forms/layout/placeholder)    |  **Not implemented**  |

## Example Form:

```php
public static function form(Form $form): Form
    {
        return $form
            //   From Database/Model
            ->schema(DynamicForm::make(DummyForm::first(), 'data')->getSchema());

            //   From JSON File
            ->schema(DynamicForm::make(storage_path('forms.json'))->getSchema());

            //   Classic Form
            ->schema([
                Forms\Components\TextInput::make('test')
                ->live()
            ]);

            // Classic Form + Dynamic Form from JSON
            ->schema([
                Forms\Components\TextInput::make('test'),
                ...DynamicForm::make(storage_path('forms.json'))
                    ->getSchema()
            ]);

            /**
             * TODO W.I.P  
             */
            // Classic Form + Dynamic Form from relation
            ->schema([
                Forms\Components\TextInput::make('test'),

                ...DynamicForm::make("test_form")
                    // Used for creation context
                    ->default(storage_path('forms.json'))
                    // Mainly used for edition context
                    ->relationship('dummyForm', 'data', $form)
                    ->getSchema()
            ]);
    }
```

## Example JSON File:

```json
{
    "side": {
        "field": "Filament\\Forms\\Components\\TextInput",
        "label": "Side",
        "default": "Default text hey !",
        "required": true,
        "hint": "Le côté où ajouter les espaces (left, right, both)"
    },
    "size": {
        "field": "Filament\\Forms\\Components\\TextInput",
        "label": "Size",
        "hint": "La longueur de la chaîne",
        "integer": true,
        "default": 15,
        "minValue": 10,
        "visibleOn": [
            "create",
            "edit"
        ],
        "live": {
            "onBlur": true
        }
    },
    "Filament\\Forms\\Components\\Fieldset": {
        "heading": "I'm a fieldset from JSON !",
        "columns": 1,
        "schema": [
            {
                "checkbox": {
                    "field": "Filament\\Forms\\Components\\Checkbox",
                    "label": "Checkbox",
                    "hint": "I'm a checkbox from JSON !"
                }
            }
        ]
    },
    "Filament\\Forms\\Components\\Tabs": {
        "heading": "I'm a fieldset from JSON !",
        "columns": 1,
        "tabs": [
            {
                "Tab 1": [
                    {
                        "yayTabCheckbox": {
                            "field": "Filament\\Forms\\Components\\Checkbox",
                            "label": "Checkbox",
                            "hint": "I'm a checkbox from JSON, From a Tab !"
                        }
                    },
                    {
                        "yayTabText": {
                            "field": "Filament\\Forms\\Components\\TextInput",
                            "label": "Woaw",
                            "hint": "I'm a text input from JSON, From a Tab !"
                        }
                    }
                ]
            }
        ]
    },
    "Filament\\Forms\\Components\\Grid": {
        "columns": {
            "sm": 3,
            "xl": 6,
            "2xl": 1
        },
        "schema": [
            {
                "character": {
                    "label": "Character",
                    "field": "Filament\\Forms\\Components\\TextInput",
                    "hint": "Le caractère à ajouter",
                    "live": {
                        "onBlur": true
                    }
                }
            },
            {
                "visibleOnEdit": {
                    "field": "Filament\\Forms\\Components\\RichEditor",
                    "label": "Editor",
                    "hint": "I'm only visible on edit !",
                    "visibleOn": "edit"
                }
            }
        ]
    },
    "Filament\\Forms\\Components\\Section": {
        "heading": "I'm a section from JSON !",
        "description": "I'm a description from JSON too !",
        "icon": "heroicon-m-shopping-bag",
        "aside": false,
        "collapsible": true,
        "schema": [
            {
                "textOnSection": {
                    "field": "Filament\\Forms\\Components\\TextInput",
                    "label": "Text on section",
                    "default": "I'm a text on a section !"
                }
            }
        ]
    }
}

```

- [JibayMcs](https://github.com/JibayMcs)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
