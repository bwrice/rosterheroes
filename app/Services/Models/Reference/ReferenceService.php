<?php


namespace App\Services\Models\Reference;


use App\Exceptions\UnknownBehaviorException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class ReferenceService
{
    protected ?Collection $referenceModels = null;
    protected array $behaviors = [];
    protected string $behaviorClass = '';

    /**
     * @param $identifier
     * @return mixed
     */
    public function getBehavior($identifier)
    {
        if (is_numeric($identifier)) {
            return $this->getBehaviorByID((int) $identifier);
        }
        return $this->getBehaviorByName($identifier);
    }

    /**
     * @param string $name
     * @return mixed
     */
    protected function getBehaviorByName(string $name)
    {
        if (array_key_exists($name, $this->behaviors)) {
            return $this->behaviors[$name];
        }

        throw new UnknownBehaviorException($name, $this->behaviorClass);
    }

    protected function getBehaviorByID(int $id)
    {
        $referenceModel = $this->getReferenceModelByID($id);
        if (is_null($referenceModel)) {
            throw new \InvalidArgumentException("Cannot find reference model with ID: " . $id);
        }
        return $this->getBehaviorByName($referenceModel->name);
    }

    /**
     * @param int $id
     * @return Model|null
     */
    public function getReferenceModelByID(int $id)
    {
        return $this->getReferenceModels()->first(function (Model $referenceModel) use ($id) {
            return $referenceModel->id === $id;
        });
    }

    public function getReferenceModels()
    {
        if (is_null($this->referenceModels)) {
            $this->referenceModels = $this->all();
        }
        return $this->referenceModels;
    }

    protected function mapIDsToModels(array $referenceIDs)
    {
        return collect($referenceIDs)->map(function ($ID) {
            return $this->getReferenceModelByID($ID);
        });
    }

    abstract protected function all(): Collection;
}
