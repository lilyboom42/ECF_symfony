<?php

namespace App\Controller;


use App\Form\BookFormType;
use App\Entity\Book;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    #[Route('/books/new', name: 'book_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $book = new Book();
        $form = $this->createForm(BookFormType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $book->setCreatedAt(new \DateTimeImmutable());
            $book->setUpdatedAt(new \DateTimeImmutable());
            $entityManager->persist($book);
            $entityManager->flush();
            $this->addFlash('success', 'Le livre a été créé avec succès!');
            return $this->redirectToRoute('books_list'); // Assurez-vous que cette route est correctement définie
        }

        return $this->render('books/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/books/update/{id}', name: 'book_update')]
    public function update(Book $book, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BookFormType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $book->setUpdatedAt(new \DateTimeImmutable());
            $entityManager->persist($book);
            $entityManager->flush();
            $this->addFlash('success', 'Le livre a été mis à jour avec succès!');
            return $this->redirectToRoute('books_list'); // Assurez-vous que cette route est correctement définie
        }

        return $this->render('books/edit.html.twig', [
            'form' => $form->createView(),
            'book' => $book,
        ]);
    }

    #[Route('/books/{id}', name: 'book_show')]
    public function show(Book $book): Response
    {
        return $this->render('books/show.html.twig', [
            'book' => $book,
        ]);
    }

    #[Route('/books/{id}/delete', name: 'book_delete', methods: ['POST'])]
    public function delete(Book $book, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($book);
        $entityManager->flush();

        $this->addFlash('success', 'Le livre a été supprimé avec succès!');
        return $this->redirectToRoute('books_list'); // Assurez-vous que cette route est correctement définie
    }

    #[Route('/books', name: 'books_list')]
    public function list(EntityManagerInterface $entityManager): Response
    {
        $books = $entityManager->getRepository(Book::class)->findAll();

        return $this->render('books/list.html.twig', [
            'books' => $books,
        ]);
    }
}
