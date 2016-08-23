<?php

namespace ClientBundle\Writer;

use ClientBundle\Model\Feature;
use ClientBundle\Transformer\FeatureToStringTransformer;

class FeatureWriter
{
    /**
     * @var FeatureToStringTransformer
     */
    private $transformer;

    /**
     * @param FeatureToStringTransformer $transformer
     */
    public function __construct(FeatureToStringTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    /**
     * @param Feature $feature
     * @param string $filename
     */
    public function write(Feature $feature, $filename)
    {
        file_put_contents($filename, $this->transformer->transform($feature));
    }
}
