<?php
/**
* RecipeIngredient type.
*/

namespace App\Form;

use App\Entity\RecipeIngredient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class RecipeIngredientType
 * @package App\Form
 */
class RecipeIngredientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'amount',
            ChoiceType::class,
            [
                'label' => 'label.amount',
                'choices'  => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                ],
            ]
        );

        $builder->add(
            'Ingredient',
            IngredientType::class,
                [
                    'label' => false
                ]
        );
/**
        $builder->add(
            'Ingredient',
            CollectionType::class,
            [
                'entry_type' => IngredientType::class,
                'allow_add' => true,
                'by_reference' => false,
                'allow_delete' => true,
            ]
        );
 **/
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RecipeIngredient::class,
        ]);
    }
}