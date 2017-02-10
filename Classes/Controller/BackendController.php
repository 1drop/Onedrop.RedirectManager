<?php
namespace Onedrop\RedirectManager\Controller;

/*
 * This file is part of the Onedrop.RedirectManager package.
 */

use Neos\Error\Messages\Message;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Mvc\Controller\ActionController;
use Neos\RedirectHandler\DatabaseStorage\Domain\Model\Redirect;
use Neos\RedirectHandler\DatabaseStorage\Domain\Repository\RedirectRepository;

class BackendController extends ActionController
{

    /**
     * @Flow\Inject
     * @var RedirectRepository
     */
    protected $redirectRepository;

    /**
     * @param integer $hostIdx
     * @return void
     */
    public function indexAction($hostIdx = null)
    {
        $hosts = $this->redirectRepository->findDistinctHosts();
        $currentHost = null;
        if (!empty($hosts)) {
            if ($hostIdx === null) {
                $hostIdx = array_search(null, $hosts);
            }
            $currentHost = $hosts[$hostIdx];
        }
        $redirects = $this->redirectRepository->findAll($currentHost);
        $this->view->assignMultiple([
            'redirects' => $redirects,
            'hosts' => $hosts,
            'hostIdx' => $hostIdx
        ]);
    }

    /**
     * Renders a form for creating a new redirect
     *
     * @param Redirect $redirect
     * @return void
     */
    public function newAction(Redirect $redirect = null)
    {
        $this->view->assign('redirect', $redirect);
    }

    /**
     * Create a new redirect
     *
     * @param Redirect $redirect The redirect to create
     * @return void
     */
    public function createAction(Redirect $redirect)
    {
        $this->redirectRepository->add($redirect);
        $this->addFlashMessage('The redirect has been created.', 'Redirect created', Message::SEVERITY_OK, [], 1486751002);
        $this->redirect('index');
    }

    /**
     * Edit an existing redirect
     *
     * @param Redirect $redirect
     * @return void
     */
    public function editAction(Redirect $redirect)
    {
        $this->view->assign('redirect', $redirect);
    }

    /**
     * Update a given redirect
     *
     * @param Redirect $redirect The redirect to update
     * @return void
     */
    public function updateAction(Redirect $redirect)
    {
        $this->redirectRepository->update($redirect);
        $this->addFlashMessage('The redirect has been updated.', 'Redirect updated', Message::SEVERITY_OK, [], 1486751143);
        $this->redirect('index');
    }

}
