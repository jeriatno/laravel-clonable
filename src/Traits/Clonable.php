<?php

namespace App\Traits;

use App\Http\Responses\ResponseBase;
use App\Interfaces\WithClonable;
use Backpack\CRUD\Exception\AccessDeniedException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

trait Clonable
{
    /**
     * Create a duplicate of the current entry in the database.
     * @param $id
     * @return Model
     * @throws AccessDeniedException
     */
    public function clones($id): Model
    {
        DB::beginTransaction();
        try {
            $model = $this->crud->model->findOrFail($id);
            $model->load($this->getModelRelations($model));

            $clonedModel = $this->cloneModel($model);

            DB::commit();
            return ResponseBase::json($clonedModel, 'cloned');
        } catch (\Exception $exception) {
            DB::rollBack();
            return ResponseBase::json($exception, 'cloned');
        }

    }

    /**
     * Get the model relationships to be eager loaded for cloning.
     * @param  Model  $model
     * @return array
     */
    protected function getModelRelations(Model $model): array
    {
        if ($model instanceof WithClonable) {
            return $model->getCloneableRelations();
        }

        return [];
    }

    /**
     * Clone a model instance and its relationships.
     * @param  Model  $model
     * @return Model
     */
    protected function cloneModel(Model $model): Model
    {
        // Clone the model
        $clonedModel = $model->replicate();

        // Get custom model
        $customData = $model->getCustomCloneData();
        $this->applyCustomData($clonedModel, $customData);

        $clonedModel->save();

        // Clone relationships if any
        $this->cloneRelationships($model, $clonedModel);

        return $clonedModel;
    }

    /**
     * Apply custom data to the cloned model.
     * @param  Model  $clonedModel
     * @param  array  $customData
     */
    protected function applyCustomData(Model $clonedModel, array $customData)
    {
        foreach ($customData as $key => $value) {
            $clonedModel->$key = $value;
        }
    }

    /**
     * Clone the relationships of a model.
     * @param  Model  $original
     * @param  Model  $clone
     * @return void
     */
    protected function cloneRelationships(Model $original, Model $clone)
    {
        foreach ($original->getRelations() as $relation => $relatedModels) {
            if (method_exists($original, $relation)) {
                $relatedModels = $original->$relation;
                if ($relatedModels instanceof \Illuminate\Database\Eloquent\Collection) {
                    foreach ($relatedModels as $relatedModel) {
                        $clonedRelatedModel = $relatedModel->replicate();

                        // Set foreign key in relationships
                        $foreignKey = $this->getForeignKey($original, $relation);
                        $clonedRelatedModel->$foreignKey = $clone->id;

                        // Apply custom data in relationships if any
                        if (is_callable($relation)) {
                            $relation($clonedRelatedModel);
                        }

                        $clonedRelatedModel->save();
                    }
                }
            }
        }
    }

    /**
     * Get the foreign key for a given relation.
     * @param  Model  $model
     * @param  string  $relation
     * @return string
     */
    protected function getForeignKey(Model $model, string $relation): string
    {
        $relationInstance = $model->$relation();
        if ($relationInstance instanceof HasMany) {
            return $relationInstance->getForeignKeyName();
        }

        return $relationInstance->getForeignKey();
    }

    /**
     * Create duplicates of multiple entries in the database.
     * @return JsonResponse
     * @throws AccessDeniedException
     */
    public function bulkClones(): \Illuminate\Http\JsonResponse
    {
        DB::beginTransaction();
        try {
            $entries = $this->request->input('entries');
            $clonedEntries = [];

            foreach ($entries as $id) {
                $model = $this->crud->model->find($id);
                if ($model) {
                    $model->load($this->getModelRelations($model));
                    $clonedEntries[] = $this->cloneModel($model);
                }
            }

            DB::commit();
            return ResponseBase::json($clonedEntries, 'cloned');
        } catch (\Exception $exception) {
            DB::rollBack();
            return ResponseBase::json($exception, 'cloned');
        }
    }
}
