<?php

namespace App\Controller;

use App\Entity\Property;
use App\Form\PropertyType;
use App\Repository\PropertyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class PropertyController extends AbstractController
{
    /**
     * @Route("/", name="admin_property_index", methods={"GET"})
     */
    public function index(PropertyRepository $propertyRepository): Response
    {
        return $this->render('admin/index.html.twig', [
            'properties' => $propertyRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="admin_property_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $property = new Property();
        $form = $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($property);
            $entityManager->flush();

            return $this->redirectToRoute('admin_property_index');
        }

        return $this->render('admin/new.html.twig', [
            'property' => $property,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_property_show", methods={"GET"})
     */
    public function show(Property $property): Response
    {
        return $this->render('admin/show.html.twig', [
            'property' => $property,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_property_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Property $property): Response
    {
        $form = $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_property_index', [
                'id' => $property->getId(),
            ]);
        }

        return $this->render('admin/edit.html.twig', [
            'property' => $property,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_property_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Property $property): Response
    {
        if ($this->isCsrfTokenValid('delete'.$property->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($property);
            $entityManager->flush();
            $this->addFlash('success', 'Bien créé avec succès');
        }

        return $this->redirectToRoute('admin_property_index');
    }
}
