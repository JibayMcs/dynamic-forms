<?php

namespace JibayMcs\DynamicForms\Concerns;

use Closure;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Support\Services\RelationshipJoiner;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneOrMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\Relation;
use JibayMcs\DynamicForms\Forms\DynamicForm;

trait HasRelationship
{
    protected string|Closure|null $relationshipTitleAttribute = null;
    protected string|Closure|null $relationship = null;

    public function relationship(string|Closure|null $name = null, string|Closure|null $titleAttribute = null, Form $form): static
    {
        $this->relationship = $name ?? $this->getName();
        $this->relationshipTitleAttribute = $titleAttribute;

        if ($form->model && !is_string($form->model)) {
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
