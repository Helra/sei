<?php

namespace App\Controller;

use App\Entity\Joke;
use App\Form\JokeType;
use App\Repository\CategoryRepository;
use App\Repository\JokeRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route("")
 */
class JokeController extends AbstractController
{
    /**
     * @Route("/{filter?0}", name="joke_index", methods={"GET"})
     * @param JokeRepository $jokeRepository
     * @param CategoryRepository $categoryRepository
     * @param int $filter
     * @return Response
     */
    public function index(JokeRepository $jokeRepository, CategoryRepository $categoryRepository, int $filter): Response
    {
        $categories = $categoryRepository->findAll();
        if ($filter == 0) {
            $jokes = $jokeRepository->findBy([], ['funny' => 'DESC', 'lousy' => 'ASC']);
        } else {
            $jokes = $jokeRepository->findBy(['category' => $filter], ['funny' => 'DESC', 'lousy' => 'ASC']);
        }

        return $this->render('joke/index.html.twig', [
            'jokes' => $jokes,
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/joke/new", name="joke_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $joke = new Joke();
        $joke->setFunny(0);
        $joke->setLousy(0);
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

    /**
     * @Route("/{id}/incrementsfunny", name="joke_incrementsFunny", methods={"GET","POST"})
     * @param Request $request
     * @param Joke $joke
     * @return Response
     */
    public function incrementsFunny(Request $request, Joke $joke, int $id, JokeRepository $jokeRepository, EntityManagerInterface $em): Response
    {

        $joke = $jokeRepository->findOneBy(['id' => $id]);
        $funny = $joke->getFunny();
        $funny += 1;
        $joke->setFunny($funny);
        $em = $this->getDoctrine()->getManager();
        $em->persist($joke);
        $em->flush();
        return $this->redirectToRoute('joke_index', []);

    }

    /**
     * @Route("/{id}/incrementslousy", name="joke_incrementsLousy", methods={"GET","POST"})
     * @param Request $request
     * @param Joke $joke
     * @return Response
     */
    public function incrementsLousy(Request $request, Joke $joke, int $id, JokeRepository $jokeRepository, EntityManagerInterface $em): Response
    {

        $joke = $jokeRepository->findOneBy(['id' => $id]);
        $lousy = $joke->getLousy();
        $lousy += 1;
        $joke->setLousy($lousy);
        $em = $this->getDoctrine()->getManager();
        $em->persist($joke);
        $em->flush();
        return $this->redirectToRoute('joke_index', []);
    }
}
