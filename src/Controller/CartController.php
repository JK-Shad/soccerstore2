<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    private $cart;
    private SessionInterface $session;
    private $em;

    public function __construct(EntityManagerInterface $em, Cart $cart, SessionInterface $session)
    {
        /* $this->cart = $cart: 

        Ce code est une instruction qui affecte la valeur de la variable $cart à la propriété $cart de l'objet en cours. 
        Cette instruction est utilisée dans une méthode d'une classe pour initialiser ou mettre à jour la valeur d'une propriété.

        Dans ce cas, cette ligne de code assigne la valeur de la variable $cart à la propriété $cart de l'objet en cours. 
        La variable $cart représente probablement le panier de l'utilisateur, qui est passé en paramètre de la méthode ou du constructeur de la classe.
        En affectant cette valeur à la propriété $cart, la classe peut accéder au panier de l'utilisateur dans d'autres méthodes,
        sans avoir à le passer en paramètre à chaque fois. 
        */
        
        $this->cart = $cart;
        $this->session = $session;
        $this->em = $em;
    }
    #[Route('/mon-panier', name: 'cart')]
    public function index(Cart $cart): Response
    {

        // On affiche les meilleurs produits
        $products = $this->em->getRepository(Product::class)->findOneBy(['isBest' => 1]);

        return $this->render('cart/index.html.twig', [
            'cart' => $cart->getFull(),
            'products' => $products,
        ]);
    }

    #[Route('/cart/add/{id}', name: 'add_to_cart')]
    public function add(Cart $cart, Request $request, $id): Response
    {
        $cart->add($id);

        return $this->redirectToRoute('cart');
    }

    #[Route('/cart/remove', name: 'remove_my_cart')]
    public function remove(Cart $cart): Response
    {
        $cart->remove();

        // On redirigera l'utilisateur vers la route nommée "products".
        return $this->redirectToRoute('products');
    }

    #[Route('/cart/delete{id}', name: 'delete_to_cart')]
    public function delete(Cart $cart, $id): Response
    {
        $cart->delete($id);

        // On redirigera l'utilisateur vers la route nommée "cart".
        return $this->redirectToRoute('cart');

    }

    #[Route('/cart/decrease{id}', name: 'decrease_to_cart')]
    public function decrease(Cart $cart, $id): Response
    {
        $cart->decrease($id);

        return $this->redirectToRoute('cart');
    }
}
