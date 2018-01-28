<?php

namespace App\Model\Github;

use JMS\Serializer\Annotation as JMS;

class User
{
    /**
     * @var string
     *
     * @JMS\Type("string")
     * @JMS\SerializedName("login")
     */
    private $login;

    /**
     * @var string
     *
     * @JMS\Type("string")
     * @JMS\SerializedName("avatar_url")
     */
    private $avatarUrl;

    /**
     * @return string
     */
    public function getLogin(): string
    {
        return $this->login;
    }

    /**
     * @return string
     */
    public function getAvatarUrl(): string
    {
        return $this->avatarUrl;
    }
}
