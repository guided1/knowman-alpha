<?php

namespace Landreg\Bundle\KnowmanBundle\Admin;

use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\DoctrinePHPCRAdminBundle\Admin\Admin;

class ItemAdmin extends Admin
{
    private $batchActions = null;

    protected $supportsPreviewMode = true;

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('title', 'text_and_references')
            ->add('body', 'textarea', array(
                'required' => false,
                'attr' => array(
                    'rows' => 10,
                    'class' => 'ckeditor'
                ),
            ))
            ->end();
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('title', 'text',array(
                'route' => array('name' => 'show'),
            ))
            ->add('_action', 'actions', array(
                'actions' => array(
                    'edit' => array(),
                    'delete' => array(),
                ),
                'label' => 'Options'
            ))
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('title', 'doctrine_phpcr_string');
        $datagridMapper->add('body', 'doctrine_phpcr_string');
    }



    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('title')
            ->add('body')
        ;
    }

    public function getExportFormats()
    {
        return array();
    }

    public function prePersist($document)
    {
        $parent = $this->getModelManager()->find(null, '/knowman/item');
        $document->setParentDocument($parent);
    }

    public function getTemplate($name)
    {
        switch ($name) {
            case 'preview':
                return 'LandregKnowmanBundle:Admin/Item:preview.html.twig';
                break;
            case 'show':
                return 'LandregKnowmanBundle:Admin/Item:show.html.twig';
                break;
            case 'edit':
                return 'LandregKnowmanBundle:Admin/Item:edit.html.twig';
                break;
            default:
                return parent::getTemplate($name);
                break;
        }
    }

    public function setBatchActions($actions=null)
    {
        $this->batchActions = $actions;
    }

    public function getBatchActions()
    {
        if(!is_null($this->batchActions)) {
            return $this->batchActions;
        }
        return parent::getBatchActions();
    }
}
