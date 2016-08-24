<?php

namespace ClientBundle\Parser;

use ClientBundle\Model\Feature;
use ClientBundle\Model\Scenario;

class BehatResultParser
{
    /**
     * @var Scenario[]
     */
    private $backgrounds = [];

    /**
     * @var Scenario[]
     */
    private $scenarios = [];

    /**
     * @param Feature $feature
     * @param array $pipes
     *
     * @return array
     */
    public function parse(Feature $feature, array $pipes)
    {
        if (!empty($pipes[1])) {
            echo 'oh no !';die;
        }

        $testResult = substr($pipes[0], 0, strpos($pipes[0], "\n"));
        $this->explodeScenarios($feature);

        $return = [
            'backgrounds' => [],
            'scenarios' => []
        ];
        $i = 0;
        $first = true;
        foreach ($this->scenarios as $scenario) {
            foreach ($this->backgrounds as $background) {
                foreach ($background->getSteps() as $step) {
                    if ($first) {
                        $return['backgrounds'][] = $this->convertResult($testResult[$i]);
                    }
                    $i++;
                }
            }
            $first = false;
            foreach ($scenario->getSteps() as $step) {
                $return['scenarios'][] = $this->convertResult($testResult[$i]);
                $i++;
            }
        }

        return $return;
    }

    /**
     * @param Feature $feature
     */
    public function explodeScenarios(Feature $feature)
    {
        foreach ($feature->getScenarios() as $scenario) {
            if ($scenario->getType() === Scenario::TYPE_BACKGROUND) {
                $this->backgrounds[] = $scenario;
            } else {
                $this->scenarios[] = $scenario;
            }
        }
    }

    /**
     * @param string $char
     *
     * @return array
     */
    public function convertResult($char)
    {
        if ($char === '.') {
            return [
                'code' => 'success'
            ];
        }

        return [
            'code' => 'error',
            'message' => $char
        ];
    }
}
