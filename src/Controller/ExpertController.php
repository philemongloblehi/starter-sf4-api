<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\Expert;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;

class ExpertController extends FOSRestController
{
    /**
     * @Rest\Get(
     *      path = "/experts/{idExpert}",
     *      name = "expert_show",
     *      requirements = {"idExpert" = "\d"}
     * )
     * 
     * @SWG\Response(
     *      response = 200,
     *      description = "Afficher les details d'un expert",
     *      @Model(type = Expert::class)
     * )
     * 
     * @SWG\Tag(name = "Expert")
     * 
     */
    public function getExpertAction(Request $request, $idExpert)
    {
        $expert = $this->getDoctrine()->getManager()
                       ->getRepository(Expert::class)
                       ->find($idExpert);
        if ($expert === null) {
            return new JsonResponse(['erreur' => 400, 'message' => 'Aucun expert trouve'], 400);
        }
        return $this->view(['data' => $expert], Response::HTTP_OK);
    }

    /**
     * @Rest\Get(
     *      path = "/experts",
     *      name = "expert_list"
     * )
     * 
     * @SWG\Response(
     *      response = 200,
     *      description = "Afficher tous les experts",
     *      @Model(type = Expert::class)
     * )
     * 
     * @SWG\Tag(name = "Expert")
     */
    public function getExpertsAction(Request $request)
    {
        $expert = $this->getDoctrine()->getManager()
                       ->getRepository(Expert::class)
                       ->findAll();
        if ($expert === null) {
            return new JsonResponse(['erreur' => 400, 'message' => 'Aucun expert dans la base de donnes'], 400);
        }
        return $this->view(['data' => $expert], Response::HTTP_OK);
    }

    /**
     * @Rest\Post(
     *      path = "/experts",
     *      name = "expert_add"
     * )
     * 
     * @SWG\Response(
     *      response = 201,
     *      description = "Creer un expert",
     *      @Model(type = Expert::class)
     * )
     * 
     * @SWG\Parameter(
     *      in = "body",
     *      name = "data",
     *      @Model(type = Expert::class)
     * )
     * 
     * @SWG\Tag(name = "Expert")
     * 
     * @return View
     */
    public function postExpertsAction(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        if ($data === null) {
            return new JsonResponse(['erreur' => 400, 'message' => 'Format de donnees non valid'], 400);
        }
        $expert = new Expert();
        $form = $this->createForm('App\Form\ExpertType', $expert);
        $form->submit($data);

        $validator = $this->get('validator');
        $errors = $validator->validate($expert);

        if (sizeof($errors) > 0) {
            return $this->view(array('errors' => $errors, 'status' => Response::HTTP_BAD_REQUEST));
        }
        $em = $this->getDoctrine()->getManager();
        $em->persist($expert);
        $em->flush();

        return $this->view(['data' => $expert], Response::HTTP_OK);
    }

    /**
     * @Rest\Put(
     *      path = "/experts/{idExpert}",
     *      name = "expert_update",
     *      requirements = {"idExpert" = "\d+"}
     * )
     * 
     * @SWG\Response(
     *      response = 200,
     *      description = "Mettre à jour un expert",
     *      @Model(type = Expert::class)
     * )
     * 
     * @SWG\Parameter(
     *      in = "body",
     *      name = "data",
     *      @Model(type = Expert::class)
     * )
     * 
     * @SWG\Tag(name = "Expert")
     * 
     * @return view
     */
    public function putExpertsAction(Request $request, $idExpert)
    {
        $data = json_decode($request->getContent(), true);
        if ($data === null) {
            return new JsonResponse(['erreur' =>  400, 'message' => 'Format de donnees non valid'], 400);
        }
        $expert = $this->getDoctrine()->getManager()
                       ->getRepository(Expert::class)
                       ->find($idExpert);
        if ($expert === null) {
            return new JsonResponse(['erreur' => 400, 'message' => 'Expert non trouve'], 400);
        }
        $form = $this->createForm('App\Form\ExpertType', $expert);
        $form->submit($data);

        $validator = $this->get('validator');
        $errors = $validator->validate($expert);

        if (sizeof($errors) > 0) {
            return $this->view(array('errors' => $errors, 'status' => Response::HTTP_BAD_REQUEST));
        }

        $em = $this->getDoctrine()->getManager();
        $em->flush();

        return $this->view(['data' => $expert], Response::HTTP_OK);
    }

    /**
     * Rest\Delete(
     *      path = "/experts/{$idExpert}",
     *      name = "expert_delete"
     * )
     * 
     * @SWG\Response(
     *      response = 200,
     *      description = "Supprimer un expert",
     *      @Model(type = Expert::class)
     * )
     * 
     * @SWG\Parameter(
     *      in = "body",
     *      name = "data",
     *      @Model(type = Expert::class)
     * )
     * 
     * @SWG\Tag(name = "Expert")
     * 
     * @return view
     */
    public function deleteExpertsAction(Request $request, $idExpert)
    {
        $data = json_decode($request->getContent(), true);
        if ($data === null) {
            return new JsonResponse(['erreur' => 400, 'message' => 'Format de donnees non valid'], 400);
        }
        $expert = $this->getDoctrine()->getManager()
                       ->getRepository(Expert::class)
                       ->find($idExpert);
        if ($expert === null) {
            return new JsonResponse(['erreur' => 400, 'message' => 'Expert non trouve'], 400);
        }

        $em = $this->getDoctrine()->getManager();
        $expert = $em->getRepository(Expert::class)->find($idExpert);
        $em->remove($expert);
        $em->flush();

        return $this->view(['data' => $expert], Response::HTTP_OK);
    }

}
