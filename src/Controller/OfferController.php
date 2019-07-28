<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Offer;
use App\Event\OfferApplianceEvent;
use App\Event\OfferEditionEvent;
use App\Form\OfferType;
use App\Repository\OfferCategoryRepository;
use App\Repository\OfferRepository;
use App\Repository\OfferTypeRepository;
use App\Repository\UserRepository;
use App\Service\OffersQueryService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/")
 */
class OfferController extends AbstractController
{
    /**
     * @Route("/", name="offer_index", methods={"GET"})
     *
     * @param Request            $request
     * @param PaginatorInterface $paginator
     * @param OffersQueryService $queryService
     *
     * @return Response
     */
    public function index(Request $request, PaginatorInterface $paginator, OffersQueryService $queryService): Response
    {
        $offers = $paginator->paginate(
            $queryService->getFilteredQuery()->getQuery(),
            $request->query->getInt('page', 1),
            10,
            [
                PaginatorInterface::SORT_FIELD_WHITELIST => [
                    'u.email',
                    'o.id',
                    'o.status',
                    'o.position',
                    'ot.name',
                    'oc.name',
                ],
            ]
        );

        return $this->render('offer/index.html.twig', [
            'offers' => $offers,
            'offer_alias' => OfferRepository::ALIAS,
            'offer_category_alias' => OfferCategoryRepository::ALIAS,
            'offer_type_alias' => OfferTypeRepository::ALIAS,
            'user_alias' => UserRepository::ALIAS,
        ]);
    }

    /**
     * @Route("/new", name="offer_new", methods={"GET","POST"})
     * @IsGranted("ROLE_RECRUITER")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function new(Request $request): Response
    {
        $offer = new Offer();
        $form = $this->createForm(OfferType::class, $offer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($offer);
            $entityManager->flush();

            return $this->redirectToRoute('offer_index');
        }

        return $this->render('offer/new.html.twig', [
            'offer' => $offer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="offer_show", methods={"GET"}, requirements={"id":"\d+"}))
     *
     * @param Offer $offer
     *
     * @return Response
     */
    public function show(Offer $offer): Response
    {
        $this->denyAccessUnlessGranted('view', $offer);

        return $this->render('offer/show.html.twig', [
            'offer' => $offer,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="offer_edit", methods={"GET","POST"}, requirements={"id":"\d+"}))
     *
     * @param Request                  $request
     * @param Offer                    $offer
     * @param EventDispatcherInterface $dispatcher
     *
     * @return Response
     */
    public function edit(Request $request, Offer $offer, EventDispatcherInterface $dispatcher): Response
    {
        $this->denyAccessUnlessGranted('edit', $offer);

        $dispatcher->dispatch(
            new OfferEditionEvent($offer),
            OfferEditionEvent::NAME
        );

        $form = $this->createForm(OfferType::class, $offer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('offer_index');
        }

        return $this->render('offer/edit.html.twig', [
            'offer' => $offer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="offer_delete", methods={"DELETE"}, requirements={"id":"\d+"}))
     * @IsGranted("ROLE_ADMIN")
     *
     * @param Request $request
     * @param Offer   $offer
     *
     * @return Response
     */
    public function delete(Request $request, Offer $offer): Response
    {
        if ($this->isCsrfTokenValid('delete'.$offer->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($offer);
            $entityManager->flush();
        }

        return $this->redirectToRoute('offer_index');
    }

    /**
     * @Route("/{id}/apply", name="offer_apply", methods={"GET","POST"}, requirements={"id":"\d+"})
     *
     * @param Offer                    $offer
     * @param EventDispatcherInterface $dispatcher
     *
     * @return Response
     *
     * @throws \TypeError
     */
    public function apply(Offer $offer, EventDispatcherInterface $dispatcher): Response
    {
        $this->denyAccessUnlessGranted('apply', $offer);

        $appliedCandidate = $this->getUser();
        $offer->addCandidate($appliedCandidate);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($offer);
        $entityManager->flush();

        $dispatcher->dispatch(
            new OfferApplianceEvent($offer, $appliedCandidate),
            OfferApplianceEvent::NAME
        );

        return $this->redirectToRoute('offer_show', ['id' => $offer->getId()]);
    }
}
