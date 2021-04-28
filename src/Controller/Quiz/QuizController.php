<?php

/*
 * @copyright C UAB NFQ Technologies
 *
 * This Software is the property of NFQ Technologies
 * and is protected by copyright law – it is NOT Freeware.
 *
 * Any unauthorized use of this software without a valid license key
 * is a violation of the license agreement and will be prosecuted by
 * civil and criminal law.
 *
 * Contact UAB NFQ Technologies:
 * E-mail: info@nfq.lt
 * http://www.nfq.lt
 */

declare(strict_types=1);

namespace App\Controller\Quiz;

use App\Constants\QuizResourceEvents;
use App\Entity\Quiz\Quiz;
use App\Form\Type\Quiz\ResultsType;
use App\Generator\CsvContentGenerator;
use FOS\RestBundle\View\View;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Sylius\Component\Resource\ResourceActions;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class QuizController extends ResourceController
{
    /** @var CsvContentGenerator */
    private $csvContentGenerator;

    public function generateResultsAction(Request $request): Response
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $this->isGrantedOr403($configuration, ResourceActions::UPDATE);
        /** @var Quiz|null $resource */
        $resource = $this->findOr404($configuration);

        $form = $this->createForm(ResultsType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && false === $form->isValid()) {
            $this->flashHelper->addErrorFlash($configuration, QuizResourceEvents::FORM_ERROR);

            return $this->redirectHandler->redirectToReferer($configuration);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $content = $this->csvContentGenerator->generate($resource, $form->getData());
            } catch (NotFoundHttpException $exception) {
                $this->flashHelper->addErrorFlash($configuration, QuizResourceEvents::NOT_FOUND);

                return $this->redirectHandler->redirectToReferer($configuration);
            }

            $response = new Response($content);
            $response->headers->set('Content-Type', 'text/csv');
            $response->headers->set(
                'Content-Disposition',
                'attachment; filename="quiz_results_' . date('Ymd') . '.csv"'
            );

            return $response;
        }

        $view = View::create()
            ->setData(
                [
                    'configuration' => $configuration,
                    'metadata' => $this->metadata,
                    'resource' => $resource,
                    $this->metadata->getName() => $resource,
                    'form' => $form->createView(),
                ]
            )
            ->setTemplate($configuration->getTemplate(ResourceActions::UPDATE . '.html'));

        return $this->viewHandler->handle($configuration, $view);
    }

    public function setCsvContentGenerator(CsvContentGenerator $csvContentGenerator): void
    {
        $this->csvContentGenerator = $csvContentGenerator;
    }
}
