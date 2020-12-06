<?php

declare(strict_types=1);

namespace App\Form\Blog\Post;

use App\Form\AbstractAPIType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use App\Model\Blog\DTO\Post as DTO;

class Update extends AbstractAPIType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, ['empty_data' => ''])
            ->add('content', TextType::class, ['empty_data' => '']);
    }

    protected function getDataClass(): string
    {
        return DTO\Update::class;
    }
}