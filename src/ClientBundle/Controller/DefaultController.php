<?php

namespace ClientBundle\Controller;

use ClientBundle\Form\Type\FeatureType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction(Request $request)
    {
        $feature = $this->get('client.parser.feature')->parse(__DIR__ . '/../../../test.feature');

        $form = $this->createForm(FeatureType::class, $feature);
        $form->handleRequest($request);

        if ($form->isValid() && !$request->isXmlHttpRequest()) {
            echo '<pre>';
            var_dump($feature);die;
        }

        return $this->render('ClientBundle:Default:index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
