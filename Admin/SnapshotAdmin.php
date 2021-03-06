<?php

/*
 * This file is part of the Sonata package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\PageBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;

use Sonata\PageBundle\Cache\CacheElement;
use Sonata\PageBundle\CmsManager\CmsManagerInterface;

class SnapshotAdmin extends Admin
{
    protected $cmsPage;

    protected $cmsSnapshot;

    protected $parentAssociationMapping = 'page';

    public function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('url')
            ->add('enabled')
            ->add('publicationDateStart')
            ->add('publicationDateEnd')
        ;
    }

    public function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('routeName');
    }

    public function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('enabled', null, array('required' => false))
            ->add('decorate')
            ->add('url', 'text')
            ->add('publicationDateStart')
            ->add('publicationDateEnd')
//            ->add('content')
        ;
    }

    public function getBatchActions()
    {
        $actions = parent::getBatchActions();

        $actions['toggle_enabled'] = $this->trans('toggle_enabled');

        return $actions;
    }

    public function postUpdate($object)
    {
        $this->cmsSnapshot->invalidate(new CacheElement(array(
           'page_id' => $object->getPage()->getId()
        )));
    }

    public function postPersist($object)
    {
        $this->cmsSnapshot->invalidate(new CacheElement(array(
           'page_id' => $object->getPage()->getId()
        )));
    }

    public function setCmsSnapshot(CmsManagerInterface $cmsSnapshot)
    {
        $this->cmsSnapshot = $cmsSnapshot;
    }
}