<?php

namespace App\Controller;

use App\Entity\Produits;
use App\Form\ProduitsType;
use App\Repository\ProduitsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/produits')]
class ProduitsController extends AbstractController
{

    private function generateSlug(string $title, SluggerInterface $slugger): string
    {
        return $slugger->slug($title)->lower();
    }

    #[Route('/', name: 'app_produits_index', methods: ['GET'])]
    public function index(ProduitsRepository $produitsRepository): Response
    {
        return $this->render('produits/index.html.twig', [
            'produits' => $produitsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_produits_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $produit = new Produits();
        $form = $this->createForm(ProduitsType::class, $produit);
        $form->handleRequest($request);
        

        if ($form->isSubmitted() && $form->isValid()) {

            $produit->setSlug($this->generateSlug($produit->getNom(), $slugger));

            $entityManager->persist($produit);
            $entityManager->flush();

            return $this->redirectToRoute('app_produits_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('produits/new.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/{slug}', name: 'app_produits_show', methods: ['GET'])]
    public function show(ProduitsRepository $produitsRepository, string $slug): Response
    {

        $produit = $produitsRepository->findOneBy(['nom' => $slug]);
        
        if (!$produit) {
            throw $this->createNotFoundException('The post does not exist');
        }
        
        return $this->render('produits/show.html.twig', [
            'produit' => $produit,
        ]);
    }

    #[Route('/{slug}/edit', name: 'app_produits_edit', methods: ['GET', 'POST'])]
    public function edit(ProduitsRepository $produitsRepository, Request $request, Produits $produit, EntityManagerInterface $entityManager, string $slug): Response
    {

        $produit = $produitsRepository->findOneBy(['nom' => $slug]);
    
        if (!$produit) {
            throw $this->createNotFoundException('The post does not exist');
        }

        $form = $this->createForm(ProduitsType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_produits_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('produits/edit.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/{slug}', name: 'app_produits_delete', methods: ['POST'])]
    public function delete(ProduitsRepository $produitsRepository, Request $request, Produits $produit, EntityManagerInterface $entityManager, string $slug): Response
    {
        $produit = $produitsRepository->findOneBy(['nom' => $slug]);

        if (!$produit) {
            throw $this->createNotFoundException('The post does not exist');
        }

        if ($this->isCsrfTokenValid('delete'.$produit->getId(), $request->request->get('_token'))) {
            $entityManager->remove($produit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_produits_index', [], Response::HTTP_SEE_OTHER);
    }
}
