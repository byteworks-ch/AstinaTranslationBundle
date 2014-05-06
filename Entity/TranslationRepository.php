<?php

namespace Astina\Bundle\TranslationBundle\Entity;

use Doctrine\ORM\EntityRepository;

class TranslationRepository extends EntityRepository
{
    public function set($domain, $source, $locale, $target)
    {
        $translation = $this->get($domain, $source, $locale);
        if (null == $translation) {
            $translation = new Translation();
            $translation->setDomain($domain);
            $translation->setSource($source);
            $translation->setLocale($locale);
            $this->getEntityManager()->persist($translation);
        }

        $translation->setTarget($target);
    }

    /**
     * @param $domain
     * @param $source
     * @param $locale
     * @return Translation
     */
    public function get($domain, $source, $locale)
    {
        return $this->findOneBy(array(
            'domain' => $domain,
            'source' => $source,
            'locale' => $locale,
        ));
    }
} 