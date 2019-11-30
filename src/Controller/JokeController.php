<?php

namespace App\Controller;

use App\Entity\Joke;
use App\Form\JokeType;
use App\Repository\JokeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/joke")
 */
class JokeController extends AbstractController
{
    /**
     * @Route("/", name="joke_index", methods={"GET"})
     * @param JokeRepository $jokeRepository
     * @return Response
     */
    public function index(JokeRepository $jokeRepository): Response
    {
        return $this->render('joke/index.html.twig', [
            'jokes' => $jokeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="joke_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $joke = new Joke();
        $form = $this->createForm(JokeType::class, $joke);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($joke);
            $entityManager->flush();

            return $this->redirectToRoute('joke_index');
        }

        return $this->render('joke/new.html.twig', [
            'joke' => $joke,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="joke_show", methods={"GET"})
     * @param Joke $joke
     * @return Response
     */
    public function show(Joke $joke): Response
    {
        return $this->render('joke/show.html.twig', [
            'joke' => $joke,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="joke_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Joke $joke): Response
    {
        $form = $this->createForm(JokeType::class, $joke);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('joke_index');
        }

        return $this->render('joke/edit.html.twig', [
            'joke' => $joke,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="joke_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Joke $joke): Response
    {
        if ($this->isCsrfTokenValid('delete'.$joke->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($joke);
            $entityManager->flush();
        }

        return $this->redirectToRoute('joke_index');
    }
}
