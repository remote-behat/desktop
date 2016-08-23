<?php

namespace ClientBundle\Transformer;

use ClientBundle\Model\Feature;
use ClientBundle\Model\Scenario;
use ClientBundle\Model\Step;
use ClientBundle\Model\StepParameter;

class FeatureToStringTransformer
{
    /**
     * @param Feature $feature
     *
     * @return string
     */
    public function transform(Feature $feature)
    {
        $asArray = ['Feature: ' . $feature->getName()];

        foreach ($feature->getScenarios() as $scenario) {
            $asArray[] = sprintf(
                '  %s: %s',
                $scenario->getType() === Scenario::TYPE_BACKGROUND ? 'Background' : 'Scenario',
                $scenario->getName()
            );
            foreach ($scenario->getSteps() as $step) {
                $asArray[] = sprintf('    %s %s', $this->transformType($step->getType()), $step->getContent());
                foreach ($step->getParameters() as $parameter) {
                    if ($parameter->getType() === StepParameter::TYPE_MULTILINE) {
                        $asArray[] = '    """';
                        foreach (explode("\n", $parameter->getContent()) as $line) {
                            $asArray[] = sprintf('    %s', $line);
                        }
                        $asArray[] = '    """';
                    } elseif ($parameter->getType() === StepParameter::TYPE_TABLE) {
                        $asArray[] = sprintf('      | %s |', implode(' | ', $parameter->getKeys()));
                        foreach ($parameter->getValues() as $value) {
                            $asArray[] = sprintf('      | %s |', implode(' | ', $value));
                        }
                    }
                }
            }
        }

        return implode("\n", $asArray);
    }

    /**
     * @param int $type
     *
     * @return string
     */
    private function transformType($type)
    {
        $types = [
            Step::TYPE_GIVEN => 'Given',
            Step::TYPE_WHEN => 'When',
            Step::TYPE_THEN => 'Then',
            Step::TYPE_AND => 'And'
        ];

        return $types[$type];
    }
}
