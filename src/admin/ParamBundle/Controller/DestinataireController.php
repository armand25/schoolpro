<?php

namespace admin\ParamBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use admin\ParamBundle\Entity\Destinataire;
use admin\ParamBundle\Form\DestinataireType;

/**
 * Destinataire controller.
 *
 */
class DestinataireController extends Controller
{

    /**
     * Lists all Destinataire entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('adminParamBundle:Destinataire')->findAll();

        return $this->render('adminParamBundle:Destinataire:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Destinataire entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Destinataire();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('destinataire_show', array('id' => $entity->getId())));
        }

        return $this->render('adminParamBundle:Destinataire:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Destinataire entity.
     *
     * @param Destinataire $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Destinataire $entity)
    {
        $form = $this->createForm(new DestinataireType(), $entity, array(
            'action' => $this->generateUrl('destinataire_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Destinataire entity.
     *
     */
    public function newAction()
    {
        $entity = new Destinataire();
        $form   = $this->createCreateForm($entity);

        return $this->render('adminParamBundle:Destinataire:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Destinataire entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('adminParamBundle:Destinataire')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Destinataire entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('adminParamBundle:Destinataire:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Destinataire entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('adminParamBundle:Destinataire')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Destinataire entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('adminParamBundle:Destinataire:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Destinataire entity.
    *
    * @param Destinataire $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Destinataire $entity)
    {
        $form = $this->createForm(new DestinataireType(), $entity, array(
            'action' => $this->generateUrl('destinataire_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Destinataire entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('adminParamBundle:Destinataire')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Destinataire entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('destinataire_edit', array('id' => $id)));
        }

        return $this->render('adminParamBundle:Destinataire:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Destinataire entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('adminParamBundle:Destinataire')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Destinataire entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('destinataire'));
    }

    /**
     * Creates a form to delete a Destinataire entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('destinataire_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
