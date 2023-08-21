<?php

namespace App\Controller;

use App\Classe\Search;
use App\Entity\Carousel;
use App\Entity\Category;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    } 

    #[Route('/', name: 'home')]
    public function index(Request $request): Response
    {
        //$categories = $this->em->getRepository(Category::class)->findAll();
        // Affichage des meilleurs produits

        /* Cette prémière ligne de code récupère tous les produits de la base de données qui sont marqués comme les meilleurs produits. 
        On utilise la méthode getRepository de l'EntityManager pour récupérer le référentiel des produits (Product::class) 
        et la méthode findByIsBest(1) pour filtrer les produits qui ont la valeur 1 pour la propriété isBest. */

        /* Ce deuxième ligne de code récupère tous les carrousels de la base de données. 
        On utilise la même méthode getRepository pour récupérer le référentiel des carrousels (Carousel::class) 
        et la méthode findAll() pour récupérer tous les carrousels sans aucun filtre. */

        $products = $this->em->getRepository(Product::class)->findByIsBest(1);
        $carousels = $this->em->getRepository(Carousel::class)->findAll();

        return $this->render('home/index.html.twig', [
            'products' => $products,
            'carousels' => $carousels,
        ]);
    }

    #[Route('/category/{id}', name: 'category_show')]
    public function showCategory(Request $request, Category $category): Response
    {
        $products = $this->em->getRepository(Product::class)->findBy(['category' => $category]);
        /*  Ce code récupère tous les produits de la base de données qui appartiennent à une catégorie spécifique. 
        Elle utilise la méthode getRepository() de l'EntityManager pour récupérer le référentiel des produits (Product::class),
        et la méthode findBy() pour filtrer les produits en fonction d'un tableau d'options.

        Dans ce cas, le tableau d'options spécifie que la propriété category de chaque produit doit correspondre à la variable $category. 
        $category doit être une instance de la classe Category (ou une valeur valide pour cette propriété). 
        Lorsque la méthode findBy() est appelée avec ce tableau d'options, 
        Doctrine récupère tous les produits qui correspondent aux critères de recherche et les stocke dans la variable $products. */

        return $this->render('product/index.html.twig', [
            'products' => $products,
        ]);
    }
}
