<?php

namespace ClientBundle\Controller;

use ClientBundle\Entity\Project;
use ClientBundle\Form\Type\ProjectType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @Route("/project")
 */
class ProjectController extends Controller
{
    /**
     * @Route("/")
     */
    public function listAction()
    {
        $list = $this->get('doctrine.orm.entity_manager')->getRepository('ClientBundle:Project')->findAll();

        return $this->render('ClientBundle:project:list.html.twig', [
            'projects' => $list
        ]);
    }

    /**
     * @param Request $request
     *
     * @return Response
     *
     * @Route("/create")
     */
    public function createAction(Request $request)
    {
        $project = new Project();
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $slug = $this->get('cocur_slugify')->slugify($project->getName());
            $project->setSlug($slug);
            if (!is_dir($this->getParameter('client.features_dir') . '/' . $slug)) {
                mkdir($this->getParameter('client.features_dir') . '/' . $slug, 0777, true);
            }
            if (!is_dir($this->getParameter('client.deploy_dir') . '/' . $slug)) {
                mkdir($this->getParameter('client.deploy_dir') . '/' . $slug, 0777, true);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($project);
            $em->flush();

            return $this->redirect($this->generateUrl('client_project_list'));
        }

        return $this->render('ClientBundle:project:create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param string $projectSlug
     * @param Request $request
     *
     * @return Response
     *
     * @Route("/edit/{projectSlug}")
     */
    public function editAction($projectSlug, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $project = $em->getRepository('ClientBundle:Project')->findOneBy([
            'slug' => $projectSlug
        ]);

        if (!$project) {
            throw new NotFoundHttpException();
        }

        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->persist($project);
            $em->flush();

            return $this->redirect($this->generateUrl('client_project_list'));
        }

        return $this->render('ClientBundle:project:edit.html.twig', [
            'form' => $form->createView(),
            'project' => $project
        ]);
    }
}
