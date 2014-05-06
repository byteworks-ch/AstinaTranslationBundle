<?php

namespace Astina\Bundle\TranslationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Astina\Bundle\TranslationBundle\Entity\TranslationRepository")
 * @ORM\Table(uniqueConstraints = {
 *     @ORM\UniqueConstraint(name="target", columns={"source", "locale", "domain"})
 * })
 */
class Translation
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $source;

    /**
     * @ORM\Column(type="string", length=4095)
     */
    private $target;

    /**
     * @ORM\Column(type="string", length=5)
     */
    private $locale;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $domain;


    public function getId()
    {
        return $this->id;
    }

    public function setSource($source)
    {
        $this->source = $source;
    }

    public function getSource()
    {
        return $this->source;
    }

    public function setTarget($target)
    {
        $this->target = $target;
    }

    public function getTarget()
    {
        return $this->target;
    }

    public function setLocale($locale)
    {
        $this->locale = $locale;
    }

    public function getLocale()
    {
        return $this->locale;
    }

    public function setDomain($domain)
    {
        $this->domain = $domain;
    }

    public function getDomain()
    {
        return $this->domain;
    }
}