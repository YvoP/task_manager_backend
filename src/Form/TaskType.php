<?php

namespace App\Form;

use App\Entity\File;
use App\Entity\ProjectUser;
use App\Entity\TaskContent;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class TaskType extends AbstractType
{

    private readonly TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $project = $options['project'];

        $builder
            ->add('title')
            ->add('description')
            ->add('status', ChoiceType::class, [
                'choices' => [
                    $this->translator->trans('status.TODO', [], 'tasks') => 'TODO',
                    $this->translator->trans('status.IN_PROGRESS', [], 'tasks') => 'IN_PROGRESS',
                    $this->translator->trans('status.DONE', [], 'tasks') => 'DONE',
                ],
            ])
            ->add('priority', ChoiceType::class, [
                'choices' => [
                    $this->translator->trans('card.priority.0', [], 'tasks') => 0,
                    $this->translator->trans('card.priority.1', [], 'tasks') => 1,
                    $this->translator->trans('card.priority.2', [], 'tasks') => 2,
                    $this->translator->trans('card.priority.3', [], 'tasks') => 3,
                ],
            ])
            ->add('startDate', DateType::class, [
                'required' => false,
            ])
            ->add('deadline', DateType::class, [
                'required' => false,
            ])
            ->add('isArchived')
            ->add('attributedTo', EntityType::class, [
                'class' => ProjectUser::class,
                'choice_label' => 'user.username',
                'choices' => $project->getProjectUsers(),
            ])
            ->add('files', EntityType::class, [
                'class' => File::class,
                'choice_label' => 'name',
                'multiple' => true,
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TaskContent::class,
            'project' => null,
        ]);
    }
}
