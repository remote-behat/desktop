<?php

namespace ClientBundle\Controller;

use ClientBundle\Form\Type\FeatureType;
use ClientBundle\Model\Feature;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @Route("/feature")
 */
class FeatureController extends Controller
{
    /**
     * @param string $projectSlug
     * @param Request $request
     *
     * @return Response
     *
     * @Route("/create/{projectSlug}")
     */
    public function createAction($projectSlug, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $project = $em->getRepository('ClientBundle:Project')->findOneBy([
            'slug' => $projectSlug
        ]);

        if (!$project) {
            throw new NotFoundHttpException();
        }

        $feature = new Feature();
        $form = $this->createForm(FeatureType::class, $feature);
        $form->handleRequest($request);

        if ($form->isValid() && !$request->isXmlHttpRequest()) {
            $fileName = $this->get('cocur_slugify')->slugify($feature->getName()) . '.feature';
            $dir = sprintf(
                '%s/%s',
                $this->getParameter('client.features_dir'),
                $projectSlug
            );

            $this->get('client.writer.feature')->write($feature, $dir . '/' . $fileName);

            return $this->redirect($this->generateUrl('client_feature_list', [
                'projectSlug' => $projectSlug
            ]));
        }

        return $this->render('ClientBundle:feature:create.html.twig', [
            'project' => $project,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param string $projectSlug
     * @param string $feature
     * @param Request $request
     *
     * @return Response
     *
     * @Route("/edit/{projectSlug}/{feature}")
     */
    public function editAction($projectSlug, $feature, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $project = $em->getRepository('ClientBundle:Project')->findOneBy([
            'slug' => $projectSlug
        ]);

        $finder = new Finder();
        $finder->files()->name($feature)->in($this->getParameter('client.features_dir') . '/' . $projectSlug);

        if (!$finder->count() || !$project) {
            throw new NotFoundHttpException();
        }

        foreach ($finder as $file) {
            break;
        }

        $feature = $this->get('client.parser.feature')->parse($file);
        $form = $this->createForm(FeatureType::class, $feature);
        $form->handleRequest($request);

        if (!$request->isXmlHttpRequest() && $form->isValid()) {
            $dir = sprintf(
                '%s/%s',
                $this->getParameter('client.features_dir'),
                $projectSlug
            );

            $this->get('client.writer.feature')->write($feature, $dir . '/' . $file->getRelativePathName());

            return $this->redirect($this->generateUrl('client_feature_list', [
                'projectSlug' => $projectSlug
            ]));
        }

        return $this->render('ClientBundle:feature:create.html.twig', [
            'project' => $project,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/form")
     */
    public function formAction(Request $request)
    {
        $feature = $this->get('client.parser.feature')->parse(__DIR__ . '/../../../features/test.feature');

        $form = $this->createForm(FeatureType::class, $feature);
        $form->handleRequest($request);

        if ($form->isValid() && !$request->isXmlHttpRequest()) {
            echo '<pre>' . nl2br($this->get('client.transformer.feature_to_string')->transform($feature));die;
        }

        return $this->render('ClientBundle:feature:index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param string $projectSlug
     *
     * @return Response
     *
     * @Route("/list/{projectSlug}")
     */
    public function listAction($projectSlug)
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

        $files = [];
        foreach ($finder as $file) {
            $files[] = $file->getRelativePathName();
        }

        return $this->render('ClientBundle:feature:list.html.twig', [
            'project' => $project,
            'features' => $files
        ]);
    }

    /**
     * @param string $projectSlug
     * @param string $feature
     *
     * @return Response
     *
     * @Route("/display/{projectSlug}/{feature}")
     */
    public function displayAction($projectSlug, $feature)
    {
        $em = $this->getDoctrine()->getManager();
        $project = $em->getRepository('ClientBundle:Project')->findOneBy([
            'slug' => $projectSlug
        ]);

        $finder = new Finder();
        $finder->files()->name($feature)->in($this->getParameter('client.features_dir') . '/' . $projectSlug);

        if (!$finder->count() || !$project) {
            throw new NotFoundHttpException();
        }

        foreach ($finder as $file) {
            $name = $file->getRelativePathName();
            $exists = $this->get('client.checker.feature_exists_in_project')->check($project, $name);
            $sync = $exists ? $this->get('client.checker.features_are_synchronized')->check($project, $name) : false;

            return $this->render('ClientBundle:feature:display.html.twig', [
                'feature' => $this->get('client.parser.feature')->parse($file),
                'project' => $project,
                'featureFile' => $feature,
                'exists' => $exists,
                'sync' => $sync
            ]);
        }
    }

    /**
     * @param string $projectSlug
     * @param Request $request
     *
     * @return Response
     *
     * @Route("/install/{projectSlug}")
     */
    public function installAction($projectSlug, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $project = $em->getRepository('ClientBundle:Project')->findOneBy([
            'slug' => $projectSlug
        ]);

        if (!$project) {
            throw new NotFoundHttpException();
        }

        $cmd = sprintf(
            'rm -rf %s/* && cd %s && %s',
            $this->getParameter('client.deploy_dir') . '/' . $projectSlug,
            $this->getParameter('client.deploy_dir') . '/' . $projectSlug,
            $project->getInstallationRequirements()
        );

        return $this->get('client.executer.command')->executeAndStreamResponse($cmd);
    }

    /**
     * @param string $projectSlug
     * @param string $feature
     * @param Request $request
     *
     * @return Response
     *
     * @Route("/test/{projectSlug}/{feature}")
     */
    public function testAction($projectSlug, $feature, Request $request)
    {
        if (!$request->isXmlHttpRequest()) {
            throw new NotFoundHttpException();
        }

        $em = $this->getDoctrine()->getManager();

        /* @var \ClientBundle\Entity\Project $project */
        $project = $em->getRepository('ClientBundle:Project')->findOneBy([
            'slug' => $projectSlug
        ]);

        if (!$project) {
            throw new NotFoundHttpException();
        }

        $finder = new Finder();
        $finder->files()->name($feature)->in($this->getParameter('client.features_dir') . '/' . $projectSlug);

        if (!$finder->count()) {
            throw new NotFoundHttpException();
        }

        foreach ($finder as $f) {
            $file = $f;
            break;
        }

        $cmd = sprintf(
            'cd %s && %s',
            sprintf(
                '%s/%s/%s',
                $this->getParameter('client.deploy_dir'),
                $projectSlug,
                $project->getTestingRootDir()
            ),
            sprintf(
                '%s -f progress %s/%s',
                $project->getBehatExe(),
                $project->getFeaturesRelativePath(),
                $feature
            )
        );

        $output = $this->get('client.executer.command')->execute($cmd);
        $featureEntity = $this->get('client.parser.feature')->parse($file);

        return new JsonResponse($this->get('client.parser.behat_result')->parse($featureEntity, $output));
    }
}
