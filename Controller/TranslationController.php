<?php

namespace Astina\Bundle\TranslationBundle\Controller;

use Astina\Bundle\TranslationBundle\Entity\Translation;
use Astina\Bundle\TranslationBundle\Entity\TranslationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Request;

class TranslationController extends Controller
{
    public function indexAction()
    {
        $domains = $this->getDomains();

        $params = array(
            'layout' => $this->container->getParameter('astina_translation.layout_template'),
            'domains' => $domains,
            'locales' => $this->getLocales(),
            'messages' => $this->loadMessages($domains),
        );

        return $this->render(
            'AstinaTranslationBundle:Translation:index.html.twig',
            $params
        );
    }

    public function updateAction(Request $request)
    {
        $messages = $request->get('trans', array());

        $repo = $this->getTranslationRepository();

        foreach ($messages as $domain => $domainTranslations) {
            foreach ($domainTranslations as $source => $translations) {
                foreach ($translations as $locale => $target) {
                    $repo->set($domain, $source, $locale, $target);
                }
            }
        }

        $this->getDoctrine()->getManager()->flush();

        $this->clearTranslationsCache();

        return $this->redirect($this->generateUrl('astina_translations'));
    }

    private function loadMessages($domains)
    {
        $repo = $this->getTranslationRepository();

        $messages = array();
        foreach ($domains as $domain) {
            $messages[$domain] = array();
        }

        /** @var Translation $translation */
        foreach ($repo->findBy(array('domain' => $domains), array('source' => 'asc')) as $translation) {
            $messages[$translation->getDomain()][$translation->getSource()][$translation->getLocale()] = $translation->getTarget();
        }

        return $messages;
    }

    private function getDomains()
    {
        return $this->container->getParameter('astina_translation.domains');
    }

    private function getLocales()
    {
        return $this->container->getParameter('astina_translation.locales');
    }

    /**
     * @return TranslationRepository
     */
    private function getTranslationRepository()
    {
        return $this->get('astina_translation.repository.translation');
    }

    private function clearTranslationsCache()
    {
        // XXX dirty hack

        $cacheDir = $this->container->getParameter('kernel.cache_dir');

        $finder = new Finder();

        $finder->in($cacheDir . '/translations')->files();

        foreach($finder as $file){
            unlink($file->getRealpath());
        }
    }
} 