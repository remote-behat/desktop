<?php

namespace ClientBundle\Checker;

use ClientBundle\Entity\Project;

class FeaturesAreSynchronizedChecker
{
    /**
     * @var string
     */
    private $deployDir;

    /**
     * @var string
     */
    private $featuresDir;

    /**
     * @param string $deployDir
     * @param string $featuresDir
     */
    public function __construct($deployDir, $featuresDir)
    {
        $this->deployDir = $deployDir;
        $this->featuresDir = $featuresDir;
    }

    /**
     * @param Project $project
     * @param string $featureFile
     *
     * @return bool
     */
    public function check(Project $project, $featureFile)
    {
        $inProjectPath = sprintf(
            '%s/%s/%s/%s/%s',
            $this->deployDir,
            $project->getSlug(),
            $project->getTestingRootDir(),
            $project->getFeaturesRelativePath(),
            $featureFile
        );

        $inFeaturesPath = sprintf(
            '%s/%s/%s',
            $this->featuresDir,
            $project->getSlug(),
            $featureFile
        );

        return file_get_contents($inProjectPath) === file_get_contents($inFeaturesPath);
    }
}
