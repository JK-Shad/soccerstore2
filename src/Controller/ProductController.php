<?php

namespace App\Controller;

use App\Classe\Search;
use App\Entity\Category;
use App\Entity\Product;
use App\Form\SearchType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    #[Route('/nos-produits', name: 'products')]
    public function index(Request $request): Response 
    {
        // Filtrage des produits
        $search = new Search();
        $form = $this->createForm(SearchType::class, $search);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $products = $this->em->getRepository(Product::class)->findWithSearch($search); // Affiche le produit recherché
        } else {
            $products = $this->em->getRepository(Product::class)->findAll(); // Sinon Affiche tous les produits
            $this->redirectToRoute('products');
        } 

        // Filtrage des Catégories
        $search->string = $request->get('q', '');
        $search->categories = $request->get('categories', []);
        $search->productName = $request->get('productName', '');
        $search->categoryName = $request->get('categoryName', '');
        $products = $this->em->getRepository(Product::class)->findWithSearch($search); // Rechercher des produits dans la BDD en fonction des critères définis dans l'objet de recherche $search
        $search = $request->query->get('search');

        return $this->render('product/index.html.twig', [
            'products' => $products,
            'search' => $search,
            'form' => $form->createView()
        ]);
    }

    #[Route('/produit/{slug}', name: 'product')]
    public function show($slug): Response
    {
        /* Slug :
        Le slug est une chaîne de caractères qui représente une partie d'une URL. 
        Le slug est généralement utilisé pour identifier de manière concise et explicite une ressource web spécifique, 
        telle qu'un article de blog, une page de produit, etc.
        Le slug est généralement généré automatiquement à partir du titre de la ressource, 
        en remplaçant les espaces par des tirets et en supprimant les caractères spéciaux et les majuscules.
        */

        /*
        Cette ligne de code est utilisée pour récupérer un objet Product à partir de son slug en utilisant la méthode findOneBySlug fournie par Doctrine.

        Le code utilise l'objet EntityManager ($this->em) pour accéder au référentiel (ou repository) des produits (Product::class)
        et appelle la méthode findOneBySlug sur ce référentiel en passant le slug du produit en tant que paramètre ($slug).
        Si le produit existe en base de données, la méthode findOneBySlug retourne l'objet Product correspondant. 
        Dans le cas contraire, la méthode retourne null. */

        $product = $this->em->getRepository(Product::class)->findOneBySlug($slug);
        $products = $this->em->getRepository(Product::class)->findByIsBest(1);

        if (!$product) {
            return $this->redirectToRoute('products');
        }

        return $this->render('product/show.html.twig', [
            'product' => $product,
            'products' => $products,
        ]);
    }
}
