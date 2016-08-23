<?php

namespace ClientBundle\Checker;

use ClientBundle\Entity\Project;

class FeatureExistsInProjectChecker
{
    /**
     * @var string
     */
    private $deployDir;

    /**
     * @param string $deployDir
     */
    public function __construct($deployDir)
    {
        $this->deployDir = $deployDir;
    }

    /**
     * @param Project $project
     * @param string $featureFile
     *
     * @return bool
     */
    public function check(Project $project, $featureFile)
    {
        return is_file(sprintf(
            '%s/%s/%s/%s/%s',
            $this->deployDir,
            $project->getSlug(),
            $project->getTestingRootDir(),
            $project->getFeaturesRelativePath(),
            $featureFile
        ));
    }
}
