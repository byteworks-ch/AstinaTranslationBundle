<?php

namespace Astina\Bundle\TranslationBundle\Controller;

use Astina\Bundle\TranslationBundle\Entity\Translation;
use Astina\Bundle\TranslationBundle\Entity\TranslationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Request;

class TranslationController extends Controller
{

    public function indexAction(Request $request)
    {
        $domains = $this->getDomains();
        $filters = $this->container->getParameter('astina_translation.filters');
        $filter  = null;

        if ($request->get('filter')) {
            foreach ($filters as $filterItem) {
                if ($filterItem['name'] == $request->get('filter')) {
                    $filter = $filterItem;
                }
            }
        }

        $messages = $this->loadMessages($filter);

        $params = array(
            'layout'   => $this->container->getParameter('astina_translation.layout_template'),

            'locales'  => $this->getLocales(),
            'domains'  => $domains,

            'filters'  => $filters,
            'filter'   => $filter,

            'messages' => $messages,
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

    private function loadMessages($filter)
    {
        $manager = $this->getDoctrine()->getManager();

        $queryDql = 'SELECT t FROM Astina\Bundle\TranslationBundle\Entity\Translation t';

        if ($filter) {
            $queryDql .= ' WHERE t.domain = \'' . $filter['domain'] . '\' AND (';

            foreach (explode(' ', $filter['filter']) as $filterToken) {
                if (preg_match('/^(and|or|\(|\))$/i', $filterToken)) {
                    $queryDql .= ' ' . strtoupper($filterToken);
                }
                else if (substr($filterToken, 0, 1) == '!') {
                    $queryDql .= ' t.source NOT LIKE \'' . $filterToken . '\'';
                }
                else {
                    $queryDql .= ' t.source LIKE \'' . $filterToken . '\'';
                }
            }

            $queryDql .= ')';
        }

        $queryDql .= ' ORDER BY t.source ASC';

        $messages = array();

        try {
            $query = $manager->createQuery($queryDql);

            /** @var Translation $translation */
            foreach ($query->getResult() as $translation) {
                $messages[$translation->getDomain()][$translation->getSource()][$translation->getLocale()] = $translation->getTarget();
            }
        }
        catch (\Exception $e) {
            echo $queryDql . ':' . $e->getMessage();
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