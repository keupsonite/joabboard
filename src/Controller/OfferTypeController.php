<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\OfferType;
use App\Form\OfferTypeType;
use App\Repository\OfferTypeRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/offer/type")
 * @IsGranted("ROLE_ADMIN")
 */
class OfferTypeController extends AbstractController
{
    /**
     * @Route("/", name="offer_type_index", methods={"GET"})
     *
     * @param OfferTypeRepository $offerTypeRepository
     *
     * @return Response
     */
    public function index(OfferTypeRepository $offerTypeRepository): Response
    {
        return $this->render('offer_type/index.html.twig', [
            'offer_types' => $offerTypeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="offer_type_new", methods={"GET","POST"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function new(Request $request): Response
    {
        $offerType = new OfferType();
        $form = $this->createForm(OfferTypeType::class, $offerType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($offerType);
            $entityManager->flush();

            return $this->redirectToRoute('offer_type_index');
        }

        return $this->render('offer_type/new.html.twig', [
            'offer_type' => $offerType,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="offer_type_show", methods={"GET"})
     *
     * @param OfferType $offerType
     *
     * @return Response
     */
    public function show(OfferType $offerType): Response
    {
        return $this->render('offer_type/show.html.twig', [
            'offer_type' => $offerType,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="offer_type_edit", methods={"GET","POST"})
     *
     * @param Request   $request
     * @param OfferType $offerType
     *
     * @return Response
     */
    public function edit(Request $request, OfferType $offerType): Response
    {
        $form = $this->createForm(OfferTypeType::class, $offerType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('offer_type_index');
        }

        return $this->render('offer_type/edit.html.twig', [
            'offer_type' => $offerType,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="offer_type_delete", methods={"DELETE"})
     *
     * @param Request   $request
     * @param OfferType $offerType
     *
     * @return Response
     */
    public function delete(Request $request, OfferType $offerType): Response
    {
        if ($this->isCsrfTokenValid('delete'.$offerType->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($offerType);
            $entityManager->flush();
        }

        return $this->redirectToRoute('offer_type_index');
    }
}
