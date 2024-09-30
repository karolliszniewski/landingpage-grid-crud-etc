<?php

namespace LandingPage\Form\Api\Data;

interface FormDataInterface
{
    /**
     * ID
     *
     * @return int|null
     */
    public function getId();

    /**
     * Set ID
     *
     * @param int $id
     * @return FormDataInterface
     */
    public function setId($id);

    /**
     * Customer Id
     *
     * @return int|null
     */
    public function getByCustomerId($id);

    /**
     * Set Customer Id
     *
     * @param int $customerId
     * @return FormDataInterface
     */
    public function setCustomerId($customerId);

    /**
     * Comment
     *
     * @return string|null
     */
    public function getComment();

    /**
     * Set Customer Id
     *
     * @param string $comment
     * @return FormDataInterface
     */
    public function setComment($comment);
}
