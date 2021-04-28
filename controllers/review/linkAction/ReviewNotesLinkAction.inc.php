<?php

/**
 * @file controllers/review/linkAction/ReviewNotesLinkAction.inc.php
 *
 * Copyright (c) 2014-2021 Simon Fraser University
 * Copyright (c) 2003-2021 John Willinsky
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class ReviewInfoCenterLinkAction
 * @ingroup controllers_review_linkAction
 *
 * @brief An action to open up the review notes for a review assignments.
 */

use PKP\linkAction\LinkAction;
use PKP\linkAction\request\AjaxModal;

class ReviewNotesLinkAction extends LinkAction
{
    /**
     * Constructor
     *
     * @param $request Request
     * @param $reviewAssignment \PKP\submission\reviewAssignment\ReviewAssignment The review assignment
     * to show information about.
     * @param $submission Submission The reviewed submission.
     * @param $user User The user.
     * @param $handler string name of the gridhandler.
     * @param $isUnread bool Has a review been read
     */
    public function __construct($request, $reviewAssignment, $submission, $user, $handler, $isUnread = null)
    {
        // Instantiate the information center modal.
        $router = $request->getRouter();
        $actionArgs = [
            'submissionId' => $reviewAssignment->getSubmissionId(),
            'reviewAssignmentId' => $reviewAssignment->getId(),
            'stageId' => $reviewAssignment->getStageId()
        ];

        $ajaxModal = new AjaxModal(
            $router->url(
                $request,
                null,
                $handler,
                'readReview',
                null,
                $actionArgs
            ),
            __('editor.review') . ': ' . htmlspecialchars($submission->getLocalizedTitle()),
            'modal_information'
        );

        $viewsDao = DAORegistry::getDAO('ViewsDAO'); /** @var ViewsDAO $viewsDao */
        $lastViewDate = $viewsDao->getLastViewDate(ASSOC_TYPE_REVIEW_RESPONSE, $reviewAssignment->getId(), $user->getId());

        $icon = !$lastViewDate || $isUnread ? 'read_new_review' : null;

        // Configure the link action.
        parent::__construct('readReview', $ajaxModal, __('editor.review.readReview'), $icon);
    }
}
