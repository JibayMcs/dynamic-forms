<?php

namespace JibayMcs\DynamicForms\Concerns;

use Closure;
use Filament\Forms\Form;

trait HasRelationship
{
    protected string|Closure|null $relationshipTitleAttribute = null;

    protected string|Closure|null $relationship = null;

    public function relationship(string|Closure|null $name = null, string|Closure|null $titleAttribute = null, Form $form): static
    {
        $this->relationship = $name ?? $this->getName();
        $this->relationshipTitleAttribute = $titleAttribute;

        if ($form->model && ! is_string($form->model)) {
            $this->fillSchema($form->model->{$this->relationship}->{$this->relationshipTitleAttribute});

            return $this;
        } else {
            if (file_exists($this->getDefaultState())) {
                $this->fillSchema($this->getDefaultState());
            } else {
                $this->fillSchema([]);
            }

            return $this;
        }

    }

    public function getRelationship(): array
    {

        return [];
    }

    public function getRelationshipName(): ?string
    {
        return $this->evaluate($this->relationship);
    }
}
