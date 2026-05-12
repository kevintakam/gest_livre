<?php
namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use App\Security\BookVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/books', name: 'app_book_')]
class BookController extends AbstractController
{
    #[Route('', name: 'index')]
    public function index(BookRepository $repo): Response
    {
        return $this->render('book/index.html.twig', [
            'books' => $repo->findAll(),
        ]);
    }

    #[Route('/new', name: 'new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book, ['submit_label' => 'Ajouter le livre']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $book->setOwner($this->getUser());
            $em->persist($book);
            $em->flush();

            $this->addFlash('success', 'Livre ajouté avec succès !');
            return $this->redirectToRoute('app_book_index');
        }

        return $this->render('book/form.html.twig', [
            'form'  => $form->createView(),
            'title' => 'Ajouter un livre',
        ]);
    }

    #[Route('/{id}/edit', name: 'edit')]
    public function edit(Book $book, Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted(BookVoter::EDIT, $book);

        $form = $this->createForm(BookType::class, $book, ['submit_label' => 'Modifier le livre']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Livre modifié avec succès !');
            return $this->redirectToRoute('app_book_index');
        }

        return $this->render('book/form.html.twig', [
            'form'  => $form->createView(),
            'title' => 'Modifier : ' . $book->getTitle(),
        ]);
    }

    #[Route('/{id}/delete', name: 'delete', methods: ['POST'])]
    public function delete(Book $book, Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted(BookVoter::DELETE, $book);

        if ($this->isCsrfTokenValid('delete_book_' . $book->getId(), $request->request->get('_token'))) {
            $em->remove($book);
            $em->flush();
            $this->addFlash('success', 'Livre supprimé.');
        }

        return $this->redirectToRoute('app_book_index');
    }

    #[Route('/{id}', name: 'show')]
    public function show(Book $book): Response
    {
        return $this->render('book/show.html.twig', ['book' => $book]);
    }
}