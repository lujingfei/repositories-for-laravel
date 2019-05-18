<?php
/**
 * Created by PhpStorm.
 * User: lujingfei
 * Date: 2019/5/17
 * Time: 2:27 PM
 */

namespace Geoff\Repositories\Eloquent;

use Geoff\Repositories\Contracts\DecoratorInterface;
use Geoff\Repositories\Contracts\RepositoryInterface;
use Geoff\Repositories\Decorator\Decorator;
use Geoff\Repositories\Exceptions\RepositoryException;
use Illuminate\Container\Container as App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

abstract class Repository implements RepositoryInterface, DecoratorInterface
{
    /**
     * @var App
     */
    private $app;
    /**
     * @var
     */
    protected $model;
    protected $newModel;
    /**
     * @var Collection
     */
    protected $decorators;
    /**
     * @var bool
     */
    protected $skipDecorator = false;
    /**
     * Prevents from overwriting same decorator in chain usage
     * @var bool
     */
    protected $preventDecoratorOverwriting = true;

    /**
     * @param App $app
     * @param Collection $collection
     * @throws \Goeff\Repositories\Exceptions\RepositoryException
     */
    public function __construct(App $app, Collection $collection)
    {
        $this->app = $app;
        $this->decorators = $collection;
        $this->resetScope();
        $this->makeModel();
    }

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public abstract function model();

    /**
     * @param array $columns
     * @return mixed
     */
    public function items($columns = array('*'))
    {
        $this->applyDecorator();
        return $this->model->get($columns);
    }

    /**
     * @param array $relations
     * @return $this
     */
    public function with(array $relations)
    {
        $this->model = $this->model->with($relations);
        return $this;
    }

    /**
     * @param int $per_page
     * @param array $columns
     * @return mixed
     */
    public function paginates($per_page = 25, $columns = array('*'))
    {
        $this->applyDecorator();
        return $this->model->paginate($per_page, $columns);
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * save a model without massive assignment
     *
     * @param array $data
     * @return bool
     */
    public function store(array $data)
    {
        foreach ($data as $k => $v) {
            $this->model->$k = $v;
        }
        return $this->model->save();
    }

    /**
     * @param array $data
     * @param $id
     * @param string $attribute
     * @return mixed
     */
    public function update(array $data)
    {
        $this->applyDecorator();
        return $this->model->update($data);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        return $this->model->destroy($id);
    }

    /**
     * @param $id
     * @param array $columns
     * @return mixed
     */
    public function find($id, $columns = array('*'))
    {
        return $this->model->find($id, $columns);
    }

    /**
     * @param $attribute
     * @param $value
     * @param array $columns
     * @return mixed
     */
    public function first($columns = array('*'))
    {
        $this->applyDecorator();
        return $this->model->first($columns);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     * @throws RepositoryException
     */
    public function makeModel()
    {
        return $this->setModel($this->model());
    }

    /**
     * Set Eloquent Model to instantiate
     *
     * @param $eloquentModel
     * @return Model
     * @throws RepositoryException
     */
    public function setModel($eloquentModel)
    {
        $this->newModel = $this->app->make($eloquentModel);
        if (!$this->newModel instanceof Model)
            throw new RepositoryException("Class {$this->newModel} must be an instance of Illuminate\\Database\\Eloquent\\Model");
        return $this->model = $this->newModel;
    }

    /**
     * @return $this
     */
    public function resetScope()
    {
        $this->skipDecorator(false);
        return $this;
    }

    /**
     * @param bool $status
     * @return $this
     */
    public function skipDecorator($status = true)
    {
        $this->skipDecorator = $status;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDecorators()
    {
        return $this->decorators;
    }

    /**
     * @param Criteria $criteria
     * @return $this
     */
    public function getByDecorator(Decorator $decorator)
    {
        $this->model = $decorator->apply($this->model, $this);
        return $this;
    }

    /**
     * @param Decorator $decorator
     * @return $this
     */
    public function pushDecorator(Decorator $decorator)
    {
        if ($this->preventDecoratorOverwriting) {
            // Find existing criteria
            $key = $this->decorator->search(function ($item) use ($decorator) {
                return (is_object($item) && (get_class($item) == get_class($decorator)));
            });
            // Remove old criteria
            if (is_int($key)) {
                $this->decorator->offsetUnset($key);
            }
        }
        $this->decorator->push($decorator);
        return $this;
    }

    /**
     * @return $this
     */
    public function applyDecorator()
    {
        if ($this->skipDecorator === true)
            return $this;
        foreach ($this->getDecorator() as $decorator) {
            if ($decorator instanceof Decorator)
                $this->model = $decorator->apply($this->model, $this);
        }
        return $this;
    }
}