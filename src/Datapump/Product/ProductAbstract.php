<?php
/**
 * @author Martin Aarhof <martin.aarhof@gmail.com>
 * @version GIT: $Id$
 */
namespace Datapump\Product;

use Datapump\Product\Data\RequiredData;
use Datapump\Product\Data\DataInterface;
use Datapump\Exception;

/**
 * Class ProductAbstract
 * @package Datapump\Product
 */
abstract class ProductAbstract
{

    /**
     * Product type
     * @var string
     */
    protected $type = '';

    /**
     * The required fields for a product
     * @var array
     */
    protected $requiredFields = array();

    /**
     * Required data holder
     * @var RequiredData
     */
    private $requiredData;

    /**
     * Injected data holder for our product
     * @var array
     */
    private $data = array();

    abstract public function beforeImport();
    abstract public function afterImport();

    /**
     * Build our product
     *
     * @param RequiredData $data
     */
    public function __construct(RequiredData $data)
    {
        $data->setType($this->type);
        $this->injectData($data);
    }

    /**
     * Inject data into our product
     *
     * @param DataInterface $data
     *
     * @return $this
     */
    public function injectData(DataInterface $data)
    {
        if ($data instanceof RequiredData) {
            $this->requiredData = $data;

            return $this;
        }

        $this->data = array_merge($this->data, $data->getData());

        return $this;
    }

    /**
     * Gets the required data
     * @return RequiredData
     */
    public function getRequiredData()
    {
        return $this->requiredData;
    }

    /**
     * Add data to our product
     *
     * @param string $key
     * @param mixed $value
     *
     * @return $this
     */
    public function set($key, $value)
    {
        $this->getRequiredData()->set($key, $value);

        return $this;
    }

    /**
     * Add data to our product
     *
     * @param string $key
     * @param mixed $value
     *
     * @return $this
     */
    public function __set($key, $value)
    {
        // @codeCoverageIgnoreStart
        return $this->set($key, $value);
        // @codeCoverageIgnoreEnd
    }

    /**
     * Get data from our product
     *
     * @param string $key
     *
     * @return mixed|null
     */
    public function get($key)
    {
        return $this->getRequiredData()->get($key);
    }

    /**
     * Get data from our product
     *
     * @param string $key
     *
     * @return mixed|null
     */
    public function __get($key)
    {
        // @codeCoverageIgnoreStart
        return $this->get($key);
        // @codeCoverageIgnoreEnd
    }

    /**
     * Check our product for missing fields
     * @return bool
     * @throws \Datapump\Exception\MissingProductData
     */
    public function check()
    {
        $this->import();
        $missingFields = array();

        foreach ($this->requiredFields as $key => $msg) {
            $method = 'get' . ucfirst($key);

            if ($this->getRequiredData()->{$method}() === null) {
                $missingFields[] = $msg;
            }
        }

        if ($missingFields) {
            throw new Exception\MissingProductData(
                sprintf(
                    'Product with SKU: %s does not have the all the required data %s',
                    $this->getRequiredData()->getSku(),
                    "\n" . implode("\n", $missingFields)
                )
            );
        }

        return true;
    }

    /**
     * Before import runner
     * @return $this
     */
    public function import()
    {
        $this->beforeImport();
        $this->data = array_merge($this->data, $this->requiredData->getData());
        return $this;
    }

    /**
     * Do stuff after import
     */
    public function after()
    {
        $this->afterImport();
    }

    /**
     * Get data from our product
     * @return array
     */
    public function getData()
    {
        $this->import();
        return $this->data;
    }
}
