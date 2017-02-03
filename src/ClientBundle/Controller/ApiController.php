<?php

namespace ClientBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @Route("/api")
 */
class ApiController extends Controller
{
    /**
     * @param string $projectSlug
     *
     * @return Response
     *
     * @Route("/project/{projectSlug}")
     */
    public function getProjectAction($projectSlug)
    {
        $em = $this->getDoctrine()->getManager();
        $project = $em->getRepository('ClientBundle:Project')->findOneBy([
            'slug' => $projectSlug
        ]);

        if (!$project) {
            throw new NotFoundHttpException();
        }

        return new Response($this->get('jms_serializer')->serialize($project, 'json'), 200, [
            'Content-type' => 'application/json'
        ]);
    }

    /**
     * @param string $projectSlug
     *
     * @return JsonResponse
     *
     * @Route("/features/{projectSlug}")
     */
    public function getFeaturesAction($projectSlug)
    {
        $em = $this->getDoctrine()->getManager();
        $project = $em->getRepository('ClientBundle:Project')->findOneBy([
            'slug' => $projectSlug
        ]);

        if (!$project) {
            throw new NotFoundHttpException();
        }

        $finder = new Finder();
        $finder->files()->in($this->getParameter('client.features_dir') . '/' . $projectSlug);

        $features = [];
        foreach ($finder as $file) {
            /* @var SplFileInfo $file */
            $features[] = $file->getRelativePathname();
        }

        return new JsonResponse($features);
    }

    /**
     * @param string $projectSlug
     * @param string $feature
     *
     * @return BinaryFileResponse
     *
     * @Route("/pull/{projectSlug}/{feature}")
     */
    public function pullAction($projectSlug, $feature)
    {
        $em = $this->getDoctrine()->getManager();
        $project = $em->getRepository('ClientBundle:Project')->findOneBy([
            'slug' => $projectSlug
        ]);

        if (!$project) {
            throw new NotFoundHttpException();
        }

        $finder = new Finder();
        $finder->files()->name($feature)->in($this->getParameter('client.features_dir') . '/' . $projectSlug);

        foreach ($finder as $file) {
            return new BinaryFileResponse($file);
        }

        throw new NotFoundHttpException;
    }
}
