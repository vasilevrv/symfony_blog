<?php

declare(strict_types=1);

namespace App\Form\Blog\PostComment;

use App\Form\AbstractAPIType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use App\Model\Blog\DTO\PostComment as DTO;

class Create extends AbstractAPIType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('content', TextType::class, ['empty_data' => '']);
    }

    protected function getDataClass(): string
    {
        return DTO\Create::class;
    }
}