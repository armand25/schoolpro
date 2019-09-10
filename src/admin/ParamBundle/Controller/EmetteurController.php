<?php

namespace admin\ParamBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use admin\ParamBundle\Entity\Emetteur;
use admin\ParamBundle\Form\EmetteurType;

/**
 * Emetteur controller.
 *
 */
class EmetteurController extends Controller
{

    /**
     * Lists all Emetteur entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('adminParamBundle:Emetteur')->findAll();

        return $this->render('adminParamBundle:Emetteur:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Emetteur entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Emetteur();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('emetteur_show', array('id' => $entity->getId())));
        }

        return $this->render('adminParamBundle:Emetteur:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Emetteur entity.
     *
     * @param Emetteur $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Emetteur $entity)
    {
        $form = $this->createForm(new EmetteurType(), $entity, array(
            'action' => $this->generateUrl('emetteur_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Emetteur entity.
     *
     */
    public function newAction()
    {
        $entity = new Emetteur();
        $form   = $this->createCreateForm($entity);

        return $this->render('adminParamBundle:Emetteur:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Emetteur entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('adminParamBundle:Emetteur')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Emetteur entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('adminParamBundle:Emetteur:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Emetteur entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('adminParamBundle:Emetteur')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Emetteur entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('adminParamBundle:Emetteur:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Emetteur entity.
    *
    * @param Emetteur $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Emetteur $entity)
    {
        $form = $this->createForm(new EmetteurType(), $entity, array(
            'action' => $this->generateUrl('emetteur_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Emetteur entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('adminParamBundle:Emetteur')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Emetteur entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('emetteur_edit', array('id' => $id)));
        }

        return $this->render('adminParamBundle:Emetteur:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Emetteur entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('adminParamBundle:Emetteur')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Emetteur entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('emetteur'));
    }

    /**
     * Creates a form to delete a Emetteur entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('emetteur_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
