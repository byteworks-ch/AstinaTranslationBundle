<?php

namespace Astina\Bundle\TranslationBundle\Loader;

use Astina\Bundle\TranslationBundle\Entity\Translation;
use Doctrine\Common\Persistence\ObjectRepository;
use Symfony\Component\Translation\Loader\LoaderInterface;
use Symfony\Component\Translation\MessageCatalogue;

class DoctrineLoader implements LoaderInterface
{
    private $repository;

    public function __construct(ObjectRepository $repository)
    {
        $this->repository = $repository;
    }

    function load($resource, $locale, $domain = 'messages')
    {
        $catalogue = new MessageCatalogue($locale);

        /** @var Translation $translation */
        foreach ($this->repository->findBy(array('locale' => $locale)) as $translation) {
            $catalogue->set($translation->getSource(), $translation->getTarget(), $translation->getDomain());
        }

        return $catalogue;
    }
}