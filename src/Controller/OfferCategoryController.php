<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\OfferCategory;
use App\Form\OfferCategoryType;
use App\Repository\OfferCategoryRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/category")
 * @IsGranted("ROLE_ADMIN")
 */
class OfferCategoryController extends AbstractController
{
    /**
     * @Route("/", name="offer_category_index", methods={"GET"})
     *
     * @param OfferCategoryRepository $offerCategoryRepository
     *
     * @return Response
     */
    public function index(OfferCategoryRepository $offerCategoryRepository): Response
    {
        return $this->render('offer_category/index.html.twig', [
            'offer_categories' => $offerCategoryRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="offer_category_new", methods={"GET","POST"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function new(Request $request): Response
    {
        $offerCategory = new OfferCategory();
        $form = $this->createForm(OfferCategoryType::class, $offerCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($offerCategory);
            $entityManager->flush();

            return $this->redirectToRoute('offer_category_index');
        }

        return $this->render('offer_category/new.html.twig', [
            'offer_category' => $offerCategory,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="offer_category_show", methods={"GET"})
     *
     * @param OfferCategory $offerCategory
     *
     * @return Response
     */
    public function show(OfferCategory $offerCategory): Response
    {
        return $this->render('offer_category/show.html.twig', [
            'offer_category' => $offerCategory,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="offer_category_edit", methods={"GET","POST"})
     *
     * @param Request       $request
     * @param OfferCategory $offerCategory
     *
     * @return Response
     */
    public function edit(Request $request, OfferCategory $offerCategory): Response
    {
        $form = $this->createForm(OfferCategoryType::class, $offerCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('offer_category_index');
        }

        return $this->render('offer_category/edit.html.twig', [
            'offer_category' => $offerCategory,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="offer_category_delete", methods={"DELETE"})
     *
     * @param Request       $request
     * @param OfferCategory $offerCategory
     *
     * @return Response
     */
    public function delete(Request $request, OfferCategory $offerCategory): Response
    {
        if ($this->isCsrfTokenValid('delete'.$offerCategory->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($offerCategory);
            $entityManager->flush();
        }

        return $this->redirectToRoute('offer_category_index');
    }
}
