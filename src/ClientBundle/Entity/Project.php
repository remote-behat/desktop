<?php

namespace ClientBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity
 */
class Project
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @Serializer\Type("integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     *
     * @Serializer\Type("string")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     *
     * @Serializer\Type("string")
     */
    private $installationRequirements;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     *
     * @Serializer\Type("string")
     */
    private $testingRootDir;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     *
     * @Serializer\Type("string")
     */
    private $featuresRelativePath;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     *
     * @Serializer\Type("string")
     */
    private $behatExe;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     *
     * @Serializer\Type("string")
     */
    private $slug;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getInstallationRequirements()
    {
        return $this->installationRequirements;
    }

    /**
     * @param string $installationRequirements
     */
    public function setInstallationRequirements($installationRequirements)
    {
        $this->installationRequirements = $installationRequirements;
    }

    /**
     * @return string
     */
    public function getTestingRootDir()
    {
        return $this->testingRootDir;
    }

    /**
     * @param string $testingRootDir
     */
    public function setTestingRootDir($testingRootDir)
    {
        $this->testingRootDir = $testingRootDir;
    }

    /**
     * @return string
     */
    public function getFeaturesRelativePath()
    {
        return $this->featuresRelativePath;
    }

    /**
     * @param string $featuresRelativePath
     */
    public function setFeaturesRelativePath($featuresRelativePath)
    {
        $this->featuresRelativePath = $featuresRelativePath;
    }

    /**
     * @return string
     */
    public function getBehatExe()
    {
        return $this->behatExe;
    }

    /**
     * @param string $behatExe
     */
    public function setBehatExe($behatExe)
    {
        $this->behatExe = $behatExe;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }
}
