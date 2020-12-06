<?php

declare(strict_types=1);

namespace App\Helper;

use Symfony\Component\Form\FormInterface;

class FormErrorFormatter
{
    private function __construct()
    {
    }

    public static function getErrors(FormInterface $form): array
    {
        $errors = [];
        $all = count($form->all());

        foreach ($form->all() as $key => $child) {
            if ($child instanceof FormInterface && $error = self::getErrors($child)) {
                $errors[$child->getName()] = $error;
            }
        }
        if ($all === 0) {
            foreach ($form->getErrors() as $error) {
                $errors[] = $error->getMessage();
            }
        } else {
            foreach ($form->getErrors() as $error) {
                $errors[$form->getName()][] = $error->getMessage();
            }
        }

        return $errors;
    }


}