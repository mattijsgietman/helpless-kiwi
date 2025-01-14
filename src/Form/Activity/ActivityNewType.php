<?php

namespace App\Form\Activity;

use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use App\Form\Activity\PriceOptionType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class ActivityNewType extends ActivityEditType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('imageFile', VichImageType::class, [
                'label' => 'Image file',
                'required' => true,
                'allow_delete' => false,
            ])
            ->add('options', CollectionType::class, [
                'entry_type' => PriceOptionType::class,
                'label' => false,
            ])

        ;
    }
}
