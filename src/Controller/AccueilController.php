<?php

namespace App\Controller;

use App\Entity\Membre;
use App\Entity\Produit;
use App\Entity\Commande;
use App\Form\MembreType;
use App\Form\ProduitType;
use App\Form\CommandeType;
use App\Repository\MembreRepository;
use App\Repository\ProduitRepository;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccueilController extends AbstractController
{
    /**
     * @Route("/", name="app_accueil")
     */
    public function index(ProduitRepository $repo): Response
    {
        $produits = $repo->findAll();
        return $this->render('accueil/index.html.twig', [
            'produits' => $produits,
            
            
        ]);
    }

    /**
     * @Route("/produit/form", name="produit_create")
     * @Route("/produit/edit/{id}", name="produit_edit")
     */
    public function formProduit(Request $request, Produit $produit = null, EntityManagerInterface $manager)
    {
        if($produit == null)
        {
         $produit = new Produit;
        }
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $produit->setDateEnregistrement(new \DateTime());
            $manager->persist($produit);
            $manager->flush();
            return $this->redirectToRoute('app_accueil');
            //return $this->redirectToRoute('produit_show', [
            //    'id' => $produit->getId()
            //]);
        }
        
     
     
     return $this->render('produit/form.html.twig', [
        'formProduit' => $form->createView(),
        'editMode' => $produit->getId() !== null
     ]); 
    }

    /**
     * @Route("/membre/form", name="membre_create")
     * @Route("/membre/edit/{id}", name="membre_edit")
     */
    public function formMembre(MembreRepository $repo, Request $globals, EntityManagerInterface $manager, Membre $membre = null)
    {
        $membres = $repo->findAll();

        if($membre == null){
            $membre = new Membre;
            $membre->setDateEnregistrement(new \DateTime());
        }

        $form = $this->createForm(MembreType::class, $membre);

        $form->handleRequest($globals);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($membre);
            $manager->flush();
            $this->addFlash('success', "vous etes bien editer");

            return $this->redirectToRoute('app_accueil');
        }
        
     
     return $this->renderForm('membre/formMembre.html.twig', [
        'formMembre' => $form,
        'membres' => $membres,
        'editMode' => $membre->getId() !== null
     ]) ;
    }

    /**
     * @Route("/membre/delete/{id}", name="membre_delete")
     */
    public function deleteMembre(Membre $membre, EntityManagerInterface $manager)
    {
        $manager->remove($membre);
        $manager->flush();
     
     
     return $this->redirectToRoute('membre') ;
    }

    /**
     * @Route("/commande/form", name="commande_create")
     * @Route("/commande/edit/{id}", name="commande_edit")
     */
    public function formCommande(CommandeRepository $repo, Request $globals, EntityManagerInterface $manager, Commande $commande = null)
    {
        $commandes = $repo->findAll();

        if($commande == null){
            $commande = new Commande;
            $commande->setDateEnregistrement(new \DateTime());
            //$commande->setMembre($this->getUser());
            //$commande->setProduit();
        }

        $form = $this->createForm(CommandeType::class, $commande);

        $form->handleRequest($globals);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($commande);
            $manager->flush();
            $this->addFlash('success', "vous etes bien editer");

            return $this->redirectToRoute('app_accueil');
        }
     
     
        return $this->renderForm('commande/form.html.twig', [
            'formCommande' => $form,
            'commandes' => $commandes,
            'editMode' => $commande->getId() !== null
         ]) ;
    }

    

    

       











}

