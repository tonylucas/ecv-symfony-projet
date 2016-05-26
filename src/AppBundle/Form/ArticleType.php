<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use AppBundle\Entity\Category;
use AppBundle\Entity\Tag;

class ArticleType extends AbstractType {
    
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('title', TextType::class)
            ->add('content', TextareaType::class)
            ->add('email', EmailType::class)
            ->add('tags', EntityType::class, array(
                'class' => 'AppBundle:Tag',
                'multiple' => true,
                'choice_label' => 'name',
                'expanded' => 'true'
            ))
            ->add('category', EntityType::class, array(
                'class' => 'AppBundle:Category',
                'choice_label' => 'name',
            ))
            ;
    }
    
}