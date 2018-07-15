<?php

namespace App\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Cabinet;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;

class CabinetController extends FOSRestController
{
    /**
     * @Rest\Get(
     *      path = "/cabinets/{idCabinet}",
     *      name = "cabinet_show",
     *      requirements = {"idCabinet" = "\d+"}
     * )
     * 
     * @SWG\Response(
     *      response = 200,
     *      description = "Afficher les details d'un cabinet",
     *      @Model(type = Cabinet::class)
     * )
     * 
     * @SWG\Tag(name = "Cabinet")
     */
    public function getCabinetAction(Request $request, $idCabinet)
    {
        $cabinet = $this->getDoctrine()->getManager()
                        ->getRepository(Cabinet::class)
                        ->find($idCabinet);
        if ($cabinet === null) {
            return new JsonResponse(['erreur' => 400, 'message' => 'Aucun cabinet trouve'], 400);
        }
        return $this->view(['data' => $cabinet], Response::HTTP_OK);
    }

    /**
     * @Rest\Get(
     *      path = "/cabinets",
     *      name = "cabinet_list"
     * )
     * 
     * @SWG\Response(
     *      response = 200,
     *      description = "Afficher tous les cabinets",
     *      @Model(type = Cabinet::class)
     * )
     * 
     * @SWG\Tag(name = "Cabinet")
     */
    public function getCabinetsAction(Request $request)
    {
        $cabinet = $this->getDoctrine()->getManager()
                        ->getRepository(Cabinet::class)
                        ->findAll();
        if (!sizeof($cabinet) > 0) {
            return new JsonResponse(['erreur' => 400, 'message' => 'Aucun cabinet trouve'], 400);
        }
        return $this->view(['data' => $cabinet], Response::HTTP_OK);
    }

    /**
     * @Rest\Post(
     *      path = "/cabinets",
     *      name = "cabinet_add"
     * )
     * 
     * @SWG\Response(
     *      response = 201,
     *      description = "Creer un nouveau cabinet",
     *      @Model(type = Cabinet::class)
     * )
     * 
     * @SWG\Parameter(
     *      in = "body",
     *      name = "data",
     *      @Model(type = Cabinet::class)
     * )
     * 
     * @SWG\Tag(name = "Cabinet")
     * 
     * @return view
     */
    public function postCabinetsAction(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        if ($data === null) {
            return new JsonResponse(['erreur' => 400, 'message' => 'Format de donnees non valid'], 400);
        }
        $cabinet = new Cabinet();
        $form = $this->createForm('App\Form\CabinetType', $cabinet);
        $form->submit($data);

        $validator = $this->get('validator');
        $errors = $validator->validate($cabinet);

        if (sizeof($errors) > 0) {
            return $this->view(array('errors' => $errors, 'status' => Response::HTTP_BAD_REQUEST));
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($cabinet);
        $em->flush();

        return $this->view(['data' => $cabinet], Response::HTTP_OK);
    }

    /**
     * @Rest\Put(
     *      path = "/cabinets/{$idCabinet}",
     *      name = "cabinet_update",
     *      requirements = {"idCabinet" = "\d+"}
     * )
     * 
     * @SWG\Response(
     *      response = 200,
     *      description = "Mettre à jour un cabinet",
     *      @Model(type = Cabinet::class)
     * )
     * 
     * @SWG\Parameter(
     *      in = "body",
     *      name = "data",
     *      @Model(type = Cabinet::class)
     * )
     * 
     * @SWG\Tag(name = "Cabinet")
     * 
     * @return view
     */
    public function putCabinetsAction(Request $request, $idCabinet)
    {
        $data = json_decode($request->getContent(), true);
        if ($data === null) {
            return new JsonResponse(['erreur' => 400, 'message' => 'Format de donnees non valid'], 400);
        }

        $cabinet = $this->getDoctrine()->getManager()
                        ->getRepository(Cabinet::class)
                        ->find($idCabinet);
        if ($cabinet === null) {
            return new JsonResponse(['erreur' => 400, 'message' => 'Cabinet non trouve'], 400);
        }
        $form = $this->get('validtor');
        $errors = $validator->validate($cabinet);

        if (sizeof($errors) > 0) {
            return $this->view(array('errors' => $errors, 'status' => Response::HTTP_OK));
        }
    }

    /**
     * @Rest\Delete(
     *      path = "/cabinets/{$idCabinet}",
     *      name = "cabinet_delete"
     * )
     * 
     * @SWG\Response(
     *      response = 200,
     *      description = "Supprimer un cabinet",
     *      @Model(type = Cabinet::class)
     * )
     * 
     * @SWG\Parameter(
     *      in = "body",
     *      name = "data",
     *      @Model(type = Cabinet::class)
     * )
     * 
     * @SWG\Tag(name = "Cabinet")
     * 
     * @return view
     */
    public function deleteCabinetsAction(Request $request, $idCabinet)
    {
        $data = json_decode($request->getContent(), true);
        if ($data === null) {
            return new JsonResponse(['erreur' => 400, 'message' => 'Format de donnees non valid'], 400);
        }

        $em = $this->getDoctrine()->getManager();
        $cabinet = $em->getRepository(Cabinet::class)->find($idCabinet);
        $em->remove($cabinet);
        $em->flush();

        return $this->view(['data' => $cabinet], Response::HTTP_OK);
    }

}
