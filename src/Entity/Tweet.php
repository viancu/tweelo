<?php

namespace Tweelo\Entity;

class Tweet
{
    /** @var  string */
    private $text;
    /** @var  string */
    private $profileImageUrl;
    /** @var  Position */
    private $position;
    /** @var  \DateTime */
    private $createdAt;

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $text
     * @return Tweet
     */
    public function setText($text)
    {
        $this->text = $text;
        return $this;
    }

    /**
     * @return string
     */
    public function getProfileImageUrl()
    {
        return $this->profileImageUrl;
    }

    /**
     * @param string $profileImageUrl
     * @return Tweet
     */
    public function setProfileImageUrl($profileImageUrl)
    {
        $this->profileImageUrl = $profileImageUrl;
        return $this;
    }

    /**
     * @return Position
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param Position $position
     * @return Tweet
     */
    public function setPosition($position)
    {
        $this->position = $position;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     * @return Tweet
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }
}