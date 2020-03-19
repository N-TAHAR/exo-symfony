<?php

namespace App\Controller;

use App\Service\EtablissementPublicApi;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\GeoApi;
use Symfony\Component\HttpFoundation\Request;

class SearchController extends AbstractController
{
    /**
     * @Route("/", name="search")
     */
    public function index()
    {
        return $this->render('search/index.html.twig', [
            'controller_name' => 'SearchController',
        ]);
    }

    public function searchBar($result = null)
    {
        $form = $this->createFormBuilder(null)
            ->setAction($this->generateUrl('commune'))
            ->setMethod('GET')
            ->add('commune', TextType::class)
            ->add('code_postal', NumberType::class)
            ->add('rechercher', SubmitType::class)
            ->getForm();

        return $this->render('search/searchBar.html.twig', [
            'form' => $form->createView(),
            'result'  => $result
        ]);
    }

    /**
     * @Route("/commune", name="commune")
     */
    public function getCommune(Request $request)
    {
        $response = $request->query;
        $commune = $response->get('form')['commune'];
        $code_postal = $response->get('form')['code_postal'];
        $geoApi = new GeoApi;
        $code = $geoApi->getCode($commune, $code_postal);
        if(empty($code)){
            $result = null;
        }
        else {
            $establishmentsApi = new EtablissementPublicApi;
            $result = $establishmentsApi->getEstablishments($code);
        }
        
        return $this->searchBar($result);
    }
}
