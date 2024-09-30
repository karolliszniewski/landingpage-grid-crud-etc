<?php
namespace LandingPage\Form\Api;

use LandingPage\Form\Api\Data\FormDataInterface;

interface FormDataRepositoryInterface
{
    /**
     * Save form data.
     *
     * @param FormDataInterface $formData
     * @return FormDataInterface
     */
    public function save(FormDataInterface $formData);

    /**
     * Get form data by ID.
     *
     * @param int $id
     * @return FormDataInterface
     */
    public function getById($id);

    /**
     * Delete form data.
     *
     * @param FormDataInterface $formData
     * @return bool
     */
    public function delete(FormDataInterface $formData);
}