<?php
/**
 * Recipe type.
 */

namespace App\Form;
use App\Entity\Recipe;
use App\Entity\RecipeIngredient;
use App\Entity\Tag;
use App\Form\DataTransformer\TagsDataTransformer;
use App\Form\EventListener\DefaultPhotoFileEventSubscriber;
use phpDocumentor\Reflection\DocBlock\Type\Collection;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class RecipeType.
 */
class RecipeType extends AbstractType
{

    /**
     * Tags data transformer.
     *
     * @var \App\Form\DataTransformer\TagsDataTransformer|null
     */
    private $tagsDataTransformer = null;

    /**
     * TaskType constructor.
     *
     * @param \App\Form\DataTransformer\TagsDataTransformer $tagsDataTransformer Tags data transformer
     */
    public function __construct(TagsDataTransformer $tagsDataTransformer)
    {
        $this->tagsDataTransformer = $tagsDataTransformer;
    }

    /**
     * Builds the form.
     *
     * This method is called for each type in the hierarchy starting from the
     * top most type. Type extensions can further modify the form.
     *
     * @see FormTypeExtensionInterface::buildForm()
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $photo = $builder->getData();

        $builder->add(
            'name',
            TextType::class,
            [
                'label' => 'label.name',
                'required' => true,
                'attr' => ['max_length' => 45],
            ]
        );

        $builder->add(
            'text',
            TextType::class,
            [
                'label' => 'label.text',
                'required' => true,
                'attr' => ['max_length' => 255],
            ]
        );
        $builder->add(
            'tags',
            TextType::class,
            [
                'label' => 'label.tags',
                'required' => false,
                'attr' => [
                    'max_length' => 255,
                ],
            ]
        );

        $builder->add(
            'photo',
            PhotoType::class,
            [
                'label' => false
////               'data_class' => null,
//                'mapped' => true,
            ]
        );
/**
        $builder->add(
            'ingredient',
            CollectionType::class,
            [
                'entry_type' => RecipeIngredientType::class,
                'entry_options' => ['label' => false],
            ]
        );
**/
        $builder->get('tags')->addModelTransformer(
            $this->tagsDataTransformer
        );

        $builder->addEventSubscriber(new DefaultPhotoFileEventSubscriber());



    }

    /**
     * Configures the options for this type.
     *
     * @param OptionsResolver $resolver The resolver for the options
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Recipe::class]);
    }

    /**
     * Returns the prefix of the template block name for this type.
     *
     * The block prefix defaults to the underscored short class name with
     * the "Type" suffix removed (e.g. "UserProfileType" => "user_profile").
     *
     * @return string The prefix of the template block name
     */
    public function getBlockPrefix(): string
    {
        return 'recipe';
    }
}
