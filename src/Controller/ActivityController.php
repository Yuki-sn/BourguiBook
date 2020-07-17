<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Form\ActiviteType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Comment;
use App\Form\CommentType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use \DateTime;

class ActivityController extends AbstractController
{
    /**
     * @Route("/toutes-les-activites/", name="main_activity")
     */
    public function allActivity(Request $request, PaginatorInterface $paginator)
    {

        // On récupère dans l'url la données GET page (si elle n'existe pas, la valeur retournée par défaut sera la page 1)
        $requestedPage = $request->query->getInt('page', 1);

        // Si le numéro de page demandé dans l'url est inférieur à 1, erreur 404
        if($requestedPage < 1){
            throw new NotFoundHttpException();
        }

        // Récupération du manager des entités
        $em = $this->getDoctrine()->getManager();

        // Création d'une requête qui servira au paginator pour récupérer les articles de la page courante
        $query = $em->createQuery('SELECT a FROM App\Entity\Activity a ORDER BY a.id DESC');

        $pageActivitys =$paginator->paginate(
            $query,
            $requestedPage,
            9
        );



        return $this->render('activity/allActivity.html.twig', [
            'activitys' => $pageActivitys
        ]);
    }

    /**
     * @Route("/ajouter_une_activite/", name="add_activity")
     * 
     */
    public function newActivity(Request $request)
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('main_home');
        }
        $newactivite = new Activity();

        $form = $this->createForm(ActiviteType::class , $newactivite);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            // Récupération de l'utilisateur actuellement connecté
            $userConnected = $this->getUser();

            // Extraction de l'objet de la photo envoyée dans le formulaire
            $photo = $form->get('pictur')->getData();

            // Création d'un nouveau nom aléatoire pour la photo avec son extension (récupérée via la méthode guessExtension() )
            $newFileName = md5(time() . rand() . uniqid() ) . '.' . $photo->guessExtension();

            // Déplacement de la photo dans le dossier que l'on avait paramétré dans le fichier services.yaml, avec le nouveau nom qu'on lui a généré
            $photo->move(
                $this->getParameter('app.activity.photos.directory'),     // Emplacement de sauvegarde du fichier
                $newFileName    // Nouveau nom du fichier
            );

            $newactivite
                ->setPublicationDate(new \DateTime())
                ->setPictur($newFileName)
                ->setAuthor( $userConnected )
            ;
            $em = $this->getDoctrine()->getManager();
            $em->persist($newactivite);
            $em->flush();

            $this->addFlash('success', 'Votre activité a bien été ajoutée !');

            // Redirection de l'utilisateur vers la page détaillée de l'activiter tout nouvellement créé
            return $this->redirectToRoute('activity_view', [
                'slug' => $newactivite->getSlug()
            ]);
        }

        return $this->render('add_activity/addActivity.html.twig',[
            'formActivity'=> $form->createView()
        ]);
    }

    /**
     * Page d'affichage d'une publication en détail
     *
     * @Route("/activite/{slug}/", name="activity_view")
     */
    public function publicationView(Activity $activity, Request $request)
    {
        // Si l'utilisateur n'est pas connecté, on appel la vue directement pour pas que le formulaire soit traité
        if(!$this->getUser()){
            // Appel de la vue en envoyant l'article qui sera affiché dessus
            return $this->render('activity/activityView.html.twig',[
                'activitys' => $activity
            ]);
        }

        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $connectedUser = $this->getUser();

            $comment
                ->setAuthor($connectedUser)
                ->setPublicationDate( new DateTime())
                ->setActivity($activity)
            ;

            $em = $this->getDoctrine()->getManager();

            $em->persist($comment);

            $em->flush();

            $this->addFlash('success', 'Votre commentaire a été publié avec succès !');

            // Reset du formulaire
            unset($comment);
            unset($form);

            $comment = new Comment();

            $form = $this->createForm(CommentType::class, $comment);
        }

        $commentRepo = $this->getDoctrine()->getRepository(Comment::class);

        $comments = $commentRepo->findByActivity($activity);

        // Appel de la vue en envoyant l'article qui sera affiché dessus
        return $this->render('activity/activityView.html.twig', [
            'activitys' => $activity,
            'commentForm' => $form->createView()
        ]);
    }

    /**
     * Page affichant les résultats de recherches faites par le formulaire de recherche dans la navbar
     *
     * @Route("/recherche/", name="activity_search")
     */
    public function search(Request $request, PaginatorInterface $paginator){

        // Récupération du numéro de la page demandée dans l'url (si il existe pas, 1 sera pris à la place)
        $requestedPage = $request->query->getInt('page', 1);

        // Si la page demandée est inférieur à 1, erreur 404
        if($requestedPage < 1){
            throw new NotFoundHttpException();
        }

        // Récupération du manager général des entités
        $em = $this->getDoctrine()->getManager();

        // Recherche de l'utilisateur, récupérée depuis l'url)
        $activityType = $request->query->get('q');
        $postalCode = $request->query->get('c');
        $ville = $request->query->get('v');

        // Création de plusieur requette a effectué en fonction des champ
        if (!empty($activityType) && !empty($postalCode) && !empty($ville)){
            $query = $em
                ->createQuery('SELECT a FROM App\Entity\Activity a WHERE a.postalCode LIKE :code AND a.typeActivity LIKE :type AND a.city LIKE :ville ')
                ->setParameters(['type' => '%' . $activityType . '%','code' => '%' . $postalCode . '%','ville' => '%' . $ville . '%']);
            ;
        }elseif (empty($activityType) && empty($postalCode) && empty($ville)){
            $this->addFlash('error', 'Veuillez ne pas envoyer un formulaire vide');
            return $this->redirectToRoute('main_home');
        }elseif (!empty($activityType) && !empty($postalCode)){
            $query = $em
                ->createQuery('SELECT a FROM App\Entity\Activity a WHERE a.postalCode LIKE :code AND a.typeActivity LIKE :type ')
                ->setParameters(['type' => '%' . $activityType . '%','code' => '%' . $postalCode . '%']);
            ;
        }elseif (!empty($activityType) && !empty($ville)){
            $query = $em
                ->createQuery('SELECT a FROM App\Entity\Activity a WHERE a.city LIKE :ville AND a.typeActivity LIKE :type ')
                ->setParameters(['type' => '%' . $activityType . '%','ville' => '%' . $ville . '%']);
            ;
        }elseif (!empty($postalCode) && !empty($ville)){
            $query = $em
                ->createQuery('SELECT a FROM App\Entity\Activity a WHERE a.postalCode LIKE :code AND a.city LIKE :ville ')
                ->setParameters(['ville' => '%' . $ville . '%','code' => '%' . $postalCode . '%']);
            ;
        }elseif (!empty($postalCode)){
            $query = $em
                ->createQuery('SELECT a FROM App\Entity\Activity a WHERE a.postalCode LIKE :code ORDER BY a.publicationDate DESC')
                ->setParameters(['code' => '%' . $postalCode . '%'])
            ;
        }elseif (!empty($activityType)){
            $query = $em
                ->createQuery('SELECT a FROM App\Entity\Activity a WHERE a.typeActivity LIKE :type ORDER BY a.publicationDate DESC')
                ->setParameters(['type' => '%' . $activityType . '%'])
            ;
        }elseif (!empty($ville)){
            $query = $em
                ->createQuery('SELECT a FROM App\Entity\Activity a WHERE a.city LIKE :ville ORDER BY a.publicationDate DESC')
                ->setParameters(['ville' => '%' . $ville . '%'])
            ;
        }

        // Récupération des activité
        $activity = $paginator->paginate(
            $query,
            $requestedPage,
            6
        );

        // Appel de la vue en lui envoyant les articles à afficher
        return $this->render('activity/actitivySearch.html.twig', [
            'activitys' => $activity
        ]);
    }
}
