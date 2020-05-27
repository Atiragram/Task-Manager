<?php

declare(strict_types=1);

namespace App\Form\Type;

use App\Form\Model\TaskModel;
use DateTimeImmutable;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('title', TextType::class)
            ->add('description', TextType::class)
            ->add('dueDate', DateTimeType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-M-dd HH:mm',
                'html5' => false,
                'invalid_message' => 'Due date value is not valid. Format should be Y-m-d H:i',
                'input' => 'datetime',
                'constraints' => [
                    new Callback(static function ($dueDate, ExecutionContextInterface $context) {
                        $now = new DateTimeImmutable();

                        if ($dueDate < $now) {
                            $context->buildViolation('Task due date cannot be in the past.')->addViolation();
                        }
                    }),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
                'data_class' => TaskModel::class,
                'csrf_protection' => false,
            ]
        );
    }
}
