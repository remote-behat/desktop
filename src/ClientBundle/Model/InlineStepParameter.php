<?php

namespace ClientBundle\Model;

class InlineStepParameter extends StepParameter
{
    /**
     * @var string
     */
    protected $identifier;

    public function getType()
    {
        return StepParameter::TYPE_INLINE;
    }

    /**
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }
}
