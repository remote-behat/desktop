<?php

namespace ClientBundle\Model;

class Step
{
    /**
     * @var Scenario
     */
    private $scenario;

    /**
     * @var string
     */
    private $content;

    /**
     * @var StepParameter[]
     */
    private $parameters;

    public function __construct()
    {
        $this->parameters = [];
    }

    /**
     * @return Scenario
     */
    public function getScenario()
    {
        return $this->scenario;
    }

    /**
     * @param Scenario $scenario
     */
    public function setScenario($scenario)
    {
        $this->scenario = $scenario;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return StepParameter[]
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @param StepParameter[] $parameters
     */
    public function setParameters(array $parameters)
    {
        $this->parameters = $parameters;

        foreach ($parameters as $parameter) {
            $parameter->setStep($this);
        }
    }

    /**
     * @param StepParameter $parameter
     */
    public function addParameter(StepParameter $parameter)
    {
        $this->parameters[] = $parameter;
        $parameter->setStep($this);
    }
}
